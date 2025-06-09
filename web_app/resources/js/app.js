import './bootstrap';

document.querySelectorAll(".setMode").forEach(item =>
    item.addEventListener("click", () => {
        if (localStorage.dark == 1) {
            localStorage.dark = 0;
            document.documentElement.setAttribute('data-bs-theme', 'light');

            var darkSwitchElements = document.getElementsByClassName("dark-switch")
            for (var i = 0; i < darkSwitchElements.length; i++) {
                darkSwitchElements[i].style.display = "block";
            }

            var lightSwitchElements = document.getElementsByClassName("light-switch")
            for (var i = 0; i < lightSwitchElements.length; i++) {
                lightSwitchElements[i].style.display = "none";
            }
        } else {
            localStorage.dark = 1;
            document.documentElement.setAttribute('data-bs-theme', 'dark');

            var darkSwitchElements = document.getElementsByClassName("dark-switch")
            for (var i = 0; i < darkSwitchElements.length; i++) {
                darkSwitchElements[i].style.display = "none";
            }

            var lightSwitchElements = document.getElementsByClassName("light-switch")
            for (var i = 0; i < lightSwitchElements.length; i++) {
                lightSwitchElements[i].style.display = "block";
            }
        }
    })
)

if (localStorage.dark == 1 || (!('dark' in localStorage) &&
    window.matchMedia('(prefers-color-scheme: dark)').matches)) {
    localStorage.dark = 1;
    document.documentElement.classList.add('dark');
} else {
    localStorage.dark = 0;
    document.documentElement.classList.remove('dark');
}


// Get elements
const scrollableWrapper = document.getElementById('scrollableWrapper');
const scrollLeftBtn = document.getElementById('scrollLeft');
const scrollRightBtn = document.getElementById('scrollRight');

// Scroll amount
const scrollAmount = 300;

// Function to update button visibility based on scroll position
function updateButtonVisibility() {
    const scrollLeft = scrollableWrapper.scrollLeft;
    const scrollWidth = scrollableWrapper.scrollWidth;
    const clientWidth = scrollableWrapper.clientWidth;

    // Show or hide the left button
    if (scrollLeft > 0) {
        scrollLeftBtn.style.display = 'block';
    } else {
        scrollLeftBtn.style.display = 'none';
    }

    // Show or hide the right button
    if (scrollLeft + clientWidth < scrollWidth) {
        scrollRightBtn.style.display = 'block';
    } else {
        scrollRightBtn.style.display = 'none';
    }
}

// Scroll left
scrollLeftBtn.addEventListener('click', () => {
    scrollableWrapper.scrollBy({
        left: -scrollAmount,
        behavior: 'smooth'
    });
});

// Scroll right
scrollRightBtn.addEventListener('click', () => {
    scrollableWrapper.scrollBy({
        left: scrollAmount,
        behavior: 'smooth'
    });
});


// Update visibility on page load
updateButtonVisibility();
// Update visibility on scroll
scrollableWrapper.addEventListener('scroll', updateButtonVisibility);
window.addEventListener('resize', updateButtonVisibility); // ← this is the resize part

let currentZoom = 1;

function zoomIn() {
    currentZoom += 0.1;
    document.body.style.zoom = currentZoom;
}

function zoomOut() {
    currentZoom = Math.max(0.5, currentZoom - 0.1);
    document.body.style.zoom = currentZoom;
}