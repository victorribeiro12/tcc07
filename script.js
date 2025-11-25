
function toggleActive(element) {
    const buttons = document.querySelectorAll('.header-btn');
    buttons.forEach(btn => {
        btn.classList.remove('active');
    });
    element.classList.add('active');
    console.log('Header button ativado');
}

function toggleNavActive(element) {
    const buttons = document.querySelectorAll('.nav-btn');
    buttons.forEach(btn => {
        btn.classList.remove('active');
    });
    element.classList.add('active');
    console.log('Nav button ativado');
}


document.addEventListener('DOMContentLoaded', function() {
});