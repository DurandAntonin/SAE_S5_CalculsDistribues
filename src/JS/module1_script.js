const profil = document.querySelector('#popUpProfil');
const showProfile = document.querySelector('#showProfil');
const formProfil = document.querySelector('#popUpFormProfil');
const linkShowProfil = document.querySelector('#linkShowProfil');
const popUpPasCo = document.querySelector('#popUpPasCo');

var showed = false;
var showedF = false;

const navLinks = document.querySelector('.nav-links')
function onToggleMenu(e){
    e.name = e.name === 'menu' ? 'close' : 'menu'
    navLinks.classList.toggle('top-[9%]')
}



function showProfil() {
    console.log("click");
    if (!showed) {
        profil.classList.remove("hidden");
        showed = true;

        document.addEventListener('click', handleClickOutside);
    } else {
        profil.classList.add("hidden");
        showed = false;

        document.removeEventListener('click', handleClickOutside);
    }
}

function showFormProfile() {
    console.log("click2");
    if (!showedF) {
        formProfil.classList.remove("hidden");
        formProfil.classList.add("flex");
        //sectionModules.classList.remove("flex");
        //sectionModules.classList.add("hidden");
        showedF = true;
        showProfil();
        document.addEventListener('click', handleClickOutsideF);

    } else {
        formProfil.classList.add("hidden");
        formProfil.classList.remove("flex");
        //sectionModules.classList.add("flex");
        //sectionModules.classList.remove("hidden");
        showedF = false;
        document.removeEventListener('click', handleClickOutsideF);

    }
}

function handleClickOutside(event) {

    if (!profil.contains(event.target)) {
        profil.classList.add("hidden");
        showed = false;

        document.removeEventListener('click', handleClickOutside);
    }
}

function handleClickOutsideF(event) {

    if (!formProfil.contains(event.target)) {
        formProfil.classList.add("hidden");
        showedF = false;

        document.removeEventListener('click', handleClickOutsideF);
    }
}


showProfile.addEventListener('click', function(event) {
    event.stopPropagation();
});

if(linkShowProfil){
    linkShowProfil.addEventListener('click', function(event) {
        event.stopPropagation();
    });
}


function toggleCheck() {
    if(document.getElementById("toggleB").checked === true){
        if(document.getElementById("infoToggle").innerHTML == "user"){
            document.getElementById("textCalcul").innerHTML = "ACTIF";
        }
        else{
            popUpPasCo.classList.remove("hidden");
            popUpPasCo.classList.add("flex");
        }
    } else {
        popUpPasCo.classList.remove("flex");
        popUpPasCo.classList.add("hidden");
        document.getElementById("textCalcul").innerHTML = "INACTIF";
    }
}

function closePopUp(){
    var toggle = document.getElementById("toggleB");
    toggle.checked = !toggle.checked;
    popUpPasCo.classList.remove("flex");
    popUpPasCo.classList.add("hidden");
}