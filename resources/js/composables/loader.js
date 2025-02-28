export function userLoader() {
    return {
        show() {
            const loader = document.getElementById('loader');
            if (loader) {
                loader.style.display = 'block';
            }
            return true
        },
        hide() {
            const loader = document.getElementById('loader');
            if (loader) {
                loader.style.display = 'none';
            }
            return false
        }
    };
}
