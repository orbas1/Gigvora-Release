const experienceList = document.querySelector('#experience-list');
const educationList = document.querySelector('#education-list');
const skillsInput = document.querySelector('#skills-input');
const skillsChips = document.querySelector('#skills-chips');
const summary = document.querySelector('textarea[name="summary"]');
const summaryCount = document.querySelector('#summary-count');
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

const createBlock = (type) => {
    const wrapper = document.createElement('div');
    wrapper.className = 'border rounded p-3 mb-2 bg-light position-relative draggable';
    wrapper.innerHTML = `
        <button type="button" class="btn-close position-absolute top-0 end-0 remove-block"></button>
        <div class="row g-2">
            <div class="col-md-6"><input class="form-control" name="${type}[title][]" placeholder="${type === 'experience' ? 'Role' : 'School'}"></div>
            <div class="col-md-6"><input class="form-control" name="${type}[subtitle][]" placeholder="${type === 'experience' ? 'Company' : 'Degree'}"></div>
            <div class="col-12"><textarea class="form-control" name="${type}[description][]" rows="2" placeholder="Description"></textarea></div>
        </div>`;
    return wrapper;
};

const addBlock = (type) => {
    const target = type === 'experience' ? experienceList : educationList;
    if (!target) return;
    const block = createBlock(type);
    target.appendChild(block);
};

const bindRemoval = () => {
    document.querySelectorAll('.remove-block').forEach((btn) => {
        btn.addEventListener('click', () => btn.closest('.draggable')?.remove());
    });
};

const initDragReorder = () => {
    [experienceList, educationList].forEach((list) => {
        if (!list) return;
        list.addEventListener('dragstart', (e) => {
            if (e.target.classList.contains('draggable')) {
                e.dataTransfer.setData('text/plain', null);
                e.target.classList.add('opacity-50');
            }
        });
        list.addEventListener('dragend', (e) => e.target.classList.remove('opacity-50'));
        list.addEventListener('dragover', (e) => {
            e.preventDefault();
            const active = list.querySelector('.opacity-50');
            const current = e.target.closest('.draggable');
            if (active && current && active !== current) {
                const rect = current.getBoundingClientRect();
                const next = (e.clientY - rect.top) / (rect.bottom - rect.top) > 0.5;
                list.insertBefore(active, next ? current.nextSibling : current);
            }
        });
    });
};

const initSkills = () => {
    skillsInput?.addEventListener('keydown', (e) => {
        if (e.key === 'Enter') {
            e.preventDefault();
            if (!skillsInput.value) return;
            const chip = document.createElement('span');
            chip.className = 'badge bg-primary-subtle text-primary d-inline-flex align-items-center gap-1';
            chip.textContent = skillsInput.value;
            chip.innerHTML += '<button type="button" class="btn-close btn-close-sm ms-1"></button>';
            skillsChips?.appendChild(chip);
            skillsInput.value = '';
            chip.querySelector('button')?.addEventListener('click', () => chip.remove());
        }
    });
};

const initSummaryCount = () => {
    if (!summary || !summaryCount) return;
    const update = () => {
        summaryCount.textContent = `${summary.value.length}/500`;
    };
    summary.addEventListener('input', update);
    update();
};

const initSave = () => {
    document.querySelector('#save-cv')?.addEventListener('click', async (e) => {
        e.preventDefault();
        const formData = new FormData(document.querySelector('#cv-builder'));
        try {
            await fetch(window.location.href, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrfToken || '' },
                body: formData,
            });
            alert('CV saved');
        } catch (error) {
            alert('Unable to save CV');
        }
    });
};

const initCvBuilder = () => {
    document.querySelector('#add-experience')?.addEventListener('click', () => {
        addBlock('experience');
        bindRemoval();
    });
    document.querySelector('#add-education')?.addEventListener('click', () => {
        addBlock('education');
        bindRemoval();
    });
    bindRemoval();
    initDragReorder();
    initSkills();
    initSummaryCount();
    initSave();
};

initCvBuilder();
