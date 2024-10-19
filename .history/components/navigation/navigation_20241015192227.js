// components/navigation/navigation.js
const hamburgerMenu = document.querySelector('.hamburger-menu');
const fullScreenMenu = document.querySelector('.full-screen-menu');

hamburgerMenu.addEventListener('click', () => {
  hamburgerMenu.classList.toggle('active');
  fullScreenMenu.classList.toggle('active');
});

// Close menu when a link is clicked
const menuLinks = document.querySelectorAll('.full-screen-menu a');
menuLinks.forEach(link => {
  link.addEventListener('click', () => {
    hamburgerMenu.classList.remove('active');
    fullScreenMenu.classList.remove('active');
  });
});

// Change navbar background on scroll
window.addEventListener('scroll', () => {
  const navbar = document.querySelector('.navbar');
  if (window.scrollY > 50) {
    navbar.style.backgroundColor = 'rgba(255, 255, 255, 0.95)';
  } else {
    navbar.style.backgroundColor = 'rgba(255, 255, 255, 0.9)';
  }
});