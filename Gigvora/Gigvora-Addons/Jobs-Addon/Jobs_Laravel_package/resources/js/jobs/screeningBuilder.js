const questionsList = document.querySelector('#questions-list');
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

const questionTemplate = (index) => `
<div class="border rounded p-3 mb-2 question-block" data-index="${index}">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <strong>Question ${index + 1}</strong>
        <button type="button" class="btn-close remove-question"></button>
    </div>
    <input class="form-control mb-2" name="questions[${index}][prompt]" placeholder="Question text">
    <div class="row g-2">
        <div class="col-md-6">
            <label class="form-label">Type</label>
            <select class="form-select" name="questions[${index}][type]">
                <option value="short_answer">Short answer</option>
                <option value="yes_no">Yes / No</option>
                <option value="multiple_choice">Multiple choice</option>
            </select>
        </div>
        <div class="col-md-6">
            <label class="form-label">Knockout</label>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="questions[${index}][knockout]">
                <label class="form-check-label">Auto-reject on failure</label>
            </div>
        </div>
    </div>
    <textarea class="form-control mt-2" rows="2" name="questions[${index}][options]" placeholder="Options (comma separated)"></textarea>
</div>`;

const addQuestion = () => {
    if (!questionsList) return;
    const index = questionsList.children.length;
    questionsList.insertAdjacentHTML('beforeend', questionTemplate(index));
    bindActions();
};

const bindActions = () => {
    document.querySelectorAll('.remove-question').forEach((btn) => {
        btn.addEventListener('click', () => btn.closest('.question-block')?.remove());
    });
};

const saveTemplate = async () => {
    const payload = new FormData();
    payload.append('name', document.querySelector('#template-name')?.value || '');
    questionsList?.querySelectorAll('.question-block').forEach((block, index) => {
        payload.append(`questions[${index}][prompt]`, block.querySelector('[name$="[prompt]"]').value);
        payload.append(`questions[${index}][type]`, block.querySelector('select').value);
        payload.append(`questions[${index}][knockout]`, block.querySelector('input[type="checkbox"]').checked ? '1' : '0');
        payload.append(`questions[${index}][options]`, block.querySelector('textarea').value);
    });
    try {
        await fetch(window.location.href, { method: 'POST', body: payload, headers: { 'X-CSRF-TOKEN': csrfToken || '' } });
        alert('Template saved');
    } catch (error) {
        alert('Unable to save template');
    }
};

const initScreeningBuilder = () => {
    document.querySelector('#add-question')?.addEventListener('click', addQuestion);
    document.querySelector('#save-template')?.addEventListener('click', (e) => { e.preventDefault(); saveTemplate(); });
    document.querySelector('#new-template')?.addEventListener('click', (e) => {
        e.preventDefault();
        questionsList.innerHTML = '';
        document.querySelector('#template-name').value = '';
    });
    addQuestion();
};

initScreeningBuilder();
