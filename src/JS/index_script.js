

const navLinks = document.querySelector('.nav-links')
        function onToggleMenu(e){
            e.name = e.name === 'menu' ? 'close' : 'menu'
            navLinks.classList.toggle('top-[9%]')
        }

var toTopButton = document.getElementById("to-top-button");

window.onscroll = function () {
    if (document.body.scrollTop > 200 || document.documentElement.scrollTop > 200) {
        toTopButton.classList.remove("hidden");
    } else {
        toTopButton.classList.add("hidden");
    }
}

function goToTop() {
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

const observer1 = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
        if (entry.isIntersecting) {
            arrow.classList.remove('text-deepblue');
            arrow.classList.add('text-white');
        } else {
            arrow.classList.remove('text-white');
            arrow.classList.add('text-deepblue');
        }
    });
}, { threshold: 0.3 });

const observer2 = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
        if (entry.isIntersecting) {
            arrow.classList.add('hidden');
        } else {
            arrow.classList.remove('hidden');
        }
    });
}, { threshold: 0.3 });



// Sélectionnez l'élément à animer
const sectionBlue = document.getElementById('sectionBlue');
const sectionLast = document.getElementById('sectionLast');
const arrow = document.getElementById('arrowDown');

// Observez l'élément
observer1.observe(sectionBlue);
observer2.observe(sectionLast);