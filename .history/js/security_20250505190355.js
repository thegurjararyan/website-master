/**
 * Security measures to prevent basic inspection of page
 * Note: These are basic deterrents and can be bypassed by experienced users
 */

// Disable right-click context menu
document.addEventListener('contextmenu', function(e) {
    e.preventDefault();
    return false;
});

// Disable keyboard shortcuts for developer tools and view source
document.addEventListener('keydown', function(e) {
    // F12 key
    if (e.keyCode === 123) {
        e.preventDefault();
        return false;
    }

    // Ctrl+Shift+I (Chrome, Firefox, Safari)
    if (e.ctrlKey && e.shiftKey && e.keyCode === 73) {
        e.preventDefault();
        return false;
    }

    // Ctrl+Shift+J (Chrome)
    if (e.ctrlKey && e.shiftKey && e.keyCode === 74) {
        e.preventDefault();
        return false;
    }

    // Ctrl+U (View page source)
    if (e.ctrlKey && e.keyCode === 85) {
        e.preventDefault();
        return false;
    }
});

// Disable dragging of images
document.addEventListener('dragstart', function(e) {
    if (e.target.tagName.toLowerCase() === 'img') {
        e.preventDefault();
        return false;
    }
});

// Detect DevTools opening
let devToolsDetected = false;

// Method 1: Check window size
const checkWindowSize = () => {
    const widthThreshold = window.outerWidth - window.innerWidth > 160;
    const heightThreshold = window.outerHeight - window.innerHeight > 160;
    
    if (widthThreshold || heightThreshold) {
        if (!devToolsDetected) {
            devToolsDetected = true;
            alert("Developer tools detected. This action has been logged.");
        }
    } else {
        devToolsDetected = false;
    }
};

// Method 2: Using console.log overriding
const detectDevTools = () => {
    const consoleOpen = /./;
    consoleOpen.toString = function() {
        if (!devToolsDetected) {
            devToolsDetected = true;
            alert("Developer tools detected. This action has been logged.");
        }
        return '';
    };
    
    console.log('%c', consoleOpen);
};

// Run detection methods
window.addEventListener('resize', checkWindowSize);
setInterval(detectDevTools, 1000);

// Disable text selection
document.addEventListener('selectstart', function(e) {
    // Allow selection in input fields and textareas
    if (e.target.tagName.toLowerCase() === 'input' || 
        e.target.tagName.toLowerCase() === 'textarea') {
        return true;
    }
    e.preventDefault();
    return false;
});

console.log("%cWarning!", "color: red; font-size: 30px; font-weight: bold;");
console.log("%cThis is a protected website. Unauthorized access or attempts to manipulate this site may result in legal action.", "font-size: 16px;");