const bodyField = document.querySelector('#cover-letter-body');
const titleField = document.querySelector('#cover-letter-title');
const saveButton = document.querySelector('#save-cover-letter');
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

const insertTag = (tag) => {
    if (!bodyField) return;
    const { selectionStart, selectionEnd, value } = bodyField;
    const nextValue = `${value.slice(0, selectionStart)}${tag}${value.slice(selectionEnd)}`;
    bodyField.value = nextValue;
    bodyField.focus();
    bodyField.selectionStart = bodyField.selectionEnd = selectionStart + tag.length;
};

const initTags = () => {
    document.querySelectorAll('.insert-tag').forEach((button) => {
        button.addEventListener('click', (e) => {
            e.preventDefault();
            insertTag(button.dataset.tag || '');
        });
    });
};

const saveLetter = async () => {
    const payload = {
        title: titleField?.value,
        body: bodyField?.value,
        is_template: document.querySelector('#save-as-template')?.checked,
    };
    try {
        await fetch(window.location.href, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken || '',
            },
            body: JSON.stringify(payload),
        });
        alert('Cover letter saved');
    } catch (error) {
        alert('Unable to save cover letter');
    }
};

const initCoverLetterEditor = () => {
    initTags();
    saveButton?.addEventListener('click', (e) => {
        e.preventDefault();
        saveLetter();
    });
};

initCoverLetterEditor();
