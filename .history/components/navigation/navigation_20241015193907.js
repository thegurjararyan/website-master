// components/navigation/navigation.js
const navbar = document.querySelector('.navbar');
const hamburgerMenu = document.getElementById('hamburger-menu');
const fullScreenMenu = document.getElementById('full-screen-menu');
const menuItems = document.querySelectorAll('.menu-items li');

// Change navbar background on scroll
window.addEventListener('scroll', () => {
  if (window.scrollY > 50) {
    navbar.style.backgroundColor = 'rgba(0, 0, 0, 0.8)';
  } else {
    navbar.style.backgroundColor = 'transparent';
  }
});

hamburgerMenu.addEventListener('click', () => {
  hamburgerMenu.classList.toggle('active');
  fullScreenMenu.classList.toggle('active');
  document.body.classList.toggle('menu-open');
  
  if (fullScreenMenu.classList.contains('active')) {
    menuItems.forEach((item, index) => {
      setTimeout(() => {
        item.style.opacity = '1';
        item.style.transform = 'translateY(0)';
      }, index * 100);
    });
  } else {
    menuItems.forEach(item => {
      item.style.opacity = '0';
      item.style.transform = 'translateY(20px)';
    });
  }
});

// Close menu when a link is clicked
const menuLinks = document.querySelectorAll('.menu-items a, .nav-button');
menuLinks.forEach(link => {
  link.addEventListener('click', () => {
    hamburgerMenu.classList.remove('active');
    fullScreenMenu.classList.remove('active');
    document.body.classList.remove('menu-open');
  });
});

// Logo click event
const logo = document.querySelector('.navbar-logo');
logo.addEventListener('click', (e) => {
  e.preventDefault();
  window.scrollTo({
    top: 0,
    behavior: 'smooth'
  });
});