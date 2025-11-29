export function http() {
    if (window.axios) {
        return window.axios;
    }
    return {
        post: (url, data = {}) => fetch(url, { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content }, body: JSON.stringify(data) }).then(r => r.json()),
        get: (url) => fetch(url).then(r => r.json()),
    };
}

export function showToast(message, type = 'success') {
    if (window.toastr) {
        window.toastr[type](message);
    } else {
        alert(message);
    }
}
