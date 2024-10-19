// components/navigation/navigation.js
const hamburgerMenu = document.getElementById('hamburger-menu');
const fullScreenMenu = document.getElementById('full-screen-menu');

hamburgerMenu.addEventListener('click', () => {
  hamburgerMenu.classList.toggle('active');
  fullScreenMenu.classList.toggle('active');
  document.body.classList.toggle('menu-open');
});

// Close menu when a link is clicked
const menuLinks = document.querySelectorAll('.full-screen-menu a');
menuLinks.forEach(link => {
  link.addEventListener('click', () => {
    hamburgerMenu.classList.remove('active');
    fullScreenMenu.classList.remove('active');
    document.body.classList.remove('menu-open');
  });
});

// Stagger animation for menu items
function animateMenuItems() {
  const menuItems = document.querySelectorAll('.full-screen-menu li');
  menuItems.forEach((item, index) => {
    item.style.transitionDelay = `${index * 0.1}s`;
  });
}

animateMenuItems();

// Change navbar background on scroll
window.addEventListener('scroll', () => {
  const navbar = document.querySelector('.navbar');
  if (window.scrollY > 50) {
    navbar.style.backgroundColor = 'rgba(255, 255, 255, 0.95)';
  } else {
    navbar.style.backgroundColor = 'rgba(255, 255, 255, 0.9)';
  }
});