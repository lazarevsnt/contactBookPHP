
document.addEventListener('DOMContentLoaded', function() {
    const alert = document.getElementById('system-alert');
    if (alert) {
        setTimeout(() => {
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        }, 5000);
        

        alert.querySelector('.alert-close').addEventListener('click', () => {
            alert.remove();
        });
    }
    
    const fileInput = document.getElementById('photo');
    if (fileInput) {
        fileInput.addEventListener('change', function() {
            if (this.files.length) {
                const fileName = this.files[0].name;
                document.querySelector('.file-upload-text').textContent = fileName;
                document.querySelector('.file-upload-hint').textContent = 'Файл выбран';
            }
        });
    }
});
