// components/navigation/navigation.js
function toggleMenu() {
    const menu = document.getElementById('fullScreenMenu');
    const menuButton = document.getElementById('menuButton');
    menu.classList.toggle('active');
    menuButton.classList.toggle('active');
}

// Add animation delay to menu items
document.querySelectorAll('.menu-item').forEach((item, index) => {
    item.style.transitionDelay = `${index * 0.1}s`;
});