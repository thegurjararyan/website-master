document.addEventListener('DOMContentLoaded', function() {
  const navbar = document.getElementById('navbar');
  const hamburgerMenu = document.getElementById('hamburger-menu');
  const fullScreenMenu = document.getElementById('full-screen-menu');
  const navLinks = document.querySelectorAll('.nav-link');
  const body = document.body;

  // Function to toggle full screen menu
  function toggleFullScreenMenu() {
      fullScreenMenu.style.display = fullScreenMenu.style.display === 'block' ? 'none' : 'block';
      body.style.overflow = fullScreenMenu.style.display === 'block' ? 'hidden' : 'auto';
  }

  // Add event listener to hamburger menu
  hamburgerMenu.addEventListener('click', toggleFullScreenMenu);

  // Close full screen menu when clicking outside
  window.addEventListener('click', function(event) {
      if (!event.target.closest('#hamburger-menu') && !event.target.closest('#full-screen-menu')) {
          fullScreenMenu.style.display = 'none';
          body.style.overflow = 'auto';
      }
  });

  // Handle scroll effects
  function handleScroll() {
      const scrollPosition = window.pageYOffset;
      const navbarHeight = navbar.offsetHeight;

      if (scrollPosition > navbarHeight) {
          navbar.style.backgroundColor = '#ffffff';
          navLinks.forEach(link => link.style.color = '#000000');
          hamburgerMenu.style.color = '#000000';
      } else {
          navbar.style.backgroundColor = 'transparent';
          navLinks.forEach(link => link.style.color = '#ffffff');
          hamburgerMenu.style.color = '#ffffff';
      }
  }

  // Add event listener for scroll
  window.addEventListener('scroll', handleScroll);

  // Smooth scrolling for links
  navLinks.forEach(link => {
      link.addEventListener('click', function(e) {
          e.preventDefault();
          const targetId = this.getAttribute('href').split('#')[1];
          const targetElement = document.getElementById(targetId);
          if (targetElement) {
              targetElement.scrollIntoView({ behavior: 'smooth' });
              if (window.innerWidth <= 768) {
                  toggleFullScreenMenu();
              }
          }
      });
  });
});
