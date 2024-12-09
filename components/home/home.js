document.addEventListener('DOMContentLoaded', () => {
    const buttons = document.querySelectorAll('.new-btn');

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

    const desktopVideo = document.getElementById('desktopVideo');
    const mobileVideo = document.getElementById('mobileVideo');

    function handleVideoLoad(video) {
        // Initially hide video until it's ready
        video.style.opacity = '0';
        
        video.addEventListener('canplay', () => {
            // Fade in video once it can play
            video.style.opacity = '1';
        });

        // Add error handling
        video.addEventListener('error', () => {
            console.log('Video failed to load, keeping thumbnail visible');
            video.style.display = 'none';
        });
    }

    // Initialize video loading handlers based on screen size
    if (window.innerWidth > 768) {
        handleVideoLoad(desktopVideo);
    } else {
        handleVideoLoad(mobileVideo);
    }
});


var daysElement = document.getElementById('days');
var hoursElement = document.getElementById('hours');
var minutesElement = document.getElementById('minutes');
var secondsElement = document.getElementById('seconds');

function countdowntimer(){

    const countdownDate = new Date('Dec 23, 2024 00:00:00').getTime();
    
    const seconds = 1000;
    const minutes = seconds * 60;
    const hours = minutes * 60;
    const days = hours * 24;

    const interval = setInterval(() => {
    
    const now = new Date().getTime();
    const distance = countdownDate - now;
    
    daysElement.innerText = formatnumber(Math.floor(distance / days));
    hoursElement.innerText = formatnumber(Math.floor((distance % days) / hours));
    minutesElement.innerText = formatnumber(Math.floor((distance % hours) / minutes));
    secondsElement.innerText = formatnumber(Math.floor((distance % minutes) / seconds));
},1000);
}
function formatnumber(number){
    return number < 10 ? `0${number}` : number;

}

countdowntimer();


