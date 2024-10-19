// components/navigation/navigation.js
// Add this at the beginning of the file
const logo = document.querySelector('.navbar-logo');

// Add this with the other event listeners
logo.addEventListener('click', (e) => {
  e.preventDefault();
  window.scrollTo({
    top: 0,
    behavior: 'smooth'
  });
});