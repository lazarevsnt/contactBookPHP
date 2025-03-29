
document.addEventListener('DOMContentLoaded', function() {
    const alert = document.getElementById('system-alert');
    if (alert) {
        // Автоматическое скрытие через 5 секунд
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s ease';
            alert.style.opacity = '0';
            
            // Полное удаление после анимации
            setTimeout(() => {
                alert.remove();
            }, 500);
        }, 5000);
        
        // Возможность закрыть вручную
        const closeBtn = document.createElement('button');
        closeBtn.innerHTML = '&times;';
        closeBtn.className = 'alert-close';
        closeBtn.addEventListener('click', () => {
            alert.remove();
        });
        alert.appendChild(closeBtn);
    }
});

