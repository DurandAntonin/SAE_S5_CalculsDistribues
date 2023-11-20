

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


const observer = new IntersectionObserver((entries)=>{
    entries.forEach((entry,index)=>{
        console.log(entry)
        if (entry.isIntersecting){
            console.log(titleElements[index]);
            titleElements[index].classList.add('animate__fadeInUp');
            console.log(entry.target);
            console.log("observe");
        } else{
            entry.target.classList.remove('animate__fadeInUp');
        }
    });
},{ threshold: 0.5 });

const sectionElements = document.querySelectorAll('section');
const titleElements = document.querySelectorAll('h1');
console.log(titleElements);
sectionElements.forEach((el)=> observer.observe(el));