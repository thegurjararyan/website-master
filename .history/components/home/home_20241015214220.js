// components/home/home.js
const introSection = document.getElementById('intro-section');
introSection.addEventListener('mousemove', function(e) {
    const ripple = document.createElement('div');
    ripple.className = 'ripple';
    ripple.style.left = `${e.clientX - introSection.offsetLeft}px`;
    ripple.style.top = `${e.clientY - introSection.offsetTop}px`;
    introSection.appendChild(ripple);
    setTimeout(() => {
        ripple.remove();
    }, 600);
});

// components/home/home.js
document.addEventListener('DOMContentLoaded', () => {
    const buttons = document.querySelectorAll('.btn');

    buttons.forEach(button => {
        button.addEventListener('click', function(e) {
            let x = e.clientX - e.target.offsetLeft;
            let y = e.clientY - e.target.offsetTop;

            let ripple = document.createElement('span');
            ripple.className = 'ripple';
            ripple.style.left = `${x}px`;
            ripple.style.top = `${y}px`;

            this.appendChild(ripple);

            setTimeout(() => {
                ripple.remove();
            }, 600);
        });
    });
});