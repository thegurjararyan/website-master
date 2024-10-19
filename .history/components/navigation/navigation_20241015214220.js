// components/navigation/navigation.js
const navbar = document.querySelector('.navbar');
const hamburgerMenu = document.getElementById('hamburger-menu');
const fullScreenMenu = document.getElementById('full-screen-menu');
const menuItems = document.querySelectorAll('.menu-items li');
const logo = document.querySelector('.navbar-logo');

// Logo animation
logo.addEventListener('mouseenter', () => {
  gsap.to('.logo-img', {
    scale: 1.1,
    rotation: 5,
    duration: 0.3,
    ease: 'power2.out'
  });
});

logo.addEventListener('mouseleave', () => {
  gsap.to('.logo-img', {
    scale: 1,
    rotation: 0,
    duration: 0.3,
    ease: 'power2.out'
  });
});

// Navbar shrink on scroll
window.addEventListener('scroll', () => {
  if (window.scrollY > 100) {
    navbar.style.padding = '0.3rem 2rem';
    gsap.to('.logo-img', { height: 50, duration: 0.3 });
  } else {
    navbar.style.padding = '0.5rem 2rem';
    gsap.to('.logo-img', { height: 60, duration: 0.3 });
  }
});

hamburgerMenu.addEventListener('click', () => {
  hamburgerMenu.classList.toggle('active');
  fullScreenMenu.classList.toggle('active');
  document.body.classList.toggle('menu-open');
  
  if (fullScreenMenu.classList.contains('active')) {
    menuItems.forEach((item, index) => {
      gsap.to(item, {
        opacity: 1,
        y: 0,
        duration: 0.5,
        delay: index * 0.1
      });
    });
  } else {
    menuItems.forEach(item => {
      gsap.to(item, {
        opacity: 0,
        y: 20,
        duration: 0.3
      });
    });
  }
});

// Navbar shrink on scroll
window.addEventListener('scroll', () => {
    if (window.scrollY > 100) {
      navbar.style.padding = '0.3rem 2rem';
      gsap.to('.logo-img', { height: 60, duration: 0.3 }); // Changed from 50 to 60
    } else {
      navbar.style.padding = '0.5rem 2rem';
      gsap.to('.logo-img', { height: 80, duration: 0.3 }); // Changed from 60 to 80
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
logo.addEventListener('click', (e) => {
  e.preventDefault();
  window.scrollTo({
    top: 0,
    behavior: 'smooth'
  });
});