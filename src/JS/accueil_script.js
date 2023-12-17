const profil = document.querySelector('#popUpProfil');
const showProfile = document.querySelector('#showProfil');
const formProfil = document.querySelector('#popUpFormProfil');
const linkShowProfil = document.querySelector('#linkShowProfil');




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
    showedF = true;
    showProfil();
    document.addEventListener('click', handleClickOutsideF);

  } else {
    formProfil.classList.add("hidden");
    formProfil.classList.remove("flex");
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

linkShowProfil.addEventListener('click', function(event) {
  event.stopPropagation();
});

function confirmDelete(){
  Swal.fire({
    title: "Êtes vous sûrs?",
    text: "Cette action est irréversible!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Oui, supprimer!",
    cancelButtonText: "Annuler"
  }).then((result) => {
    if (result.isConfirmed){
      document.getElementById("submit_supprimer_compte").value = "Supprimer";
      var form = document.getElementById("formProfil");
      form.submit();
    }
  });
}
document.addEventListener('DOMContentLoaded', function () {
  // Récupérer l'élément de la barre de progression
  var progressBar = document.querySelector('.bg-blue-800');

  // Intervalle de mise à jour du timer en millisecondes (par exemple, toutes les secondes)
  var interval = 50;

  // Fonction de mise à jour du timer
  function updateProgressBar() {
    // Augmenter la valeur de width (ajustez selon vos besoins)
    var newWidth = parseInt(progressBar.style.width) + 1;

    // Limiter la valeur de width à 100
    if (newWidth > 100) {
        newWidth = 100;

        // Réinitialiser la barre de progression à 0% après un délai
        setTimeout(resetProgressBar, 1);
    }

      // Mettre à jour la largeur de la barre de progression
      progressBar.style.width = newWidth + '%';
  }

  // Fonction de réinitialisation de la barre de progression
  function resetProgressBar() {
      progressBar.style.width = '0%';
      // Relancer le timer après la réinitialisation
      //startTimer();
      clearInterval(window.timer);
  }

  // Fonction pour démarrer le timer
  function startTimer() {
    var timer = setInterval(updateProgressBar, interval);

    // Stocker le timer dans une propriété de fenêtre pour pouvoir le réinitialiser plus tard
    window.timer = timer;
  }

  // Démarrer le timer initialement
  startTimer();
  });

var changes = 0;

function changeModule(){
  changes ++;
  var mod = changes % 3
  var modS = mod.toString();

  console.log(modS, mod);

  var modules = document.getElementsByClassName('wrapper');
  var module = document.getElementById(modS);

  Array.prototype.forEach.call(modules,function(elem){
    elem.classList.add("hidden");
  });
  
  module.classList.remove('hidden');
}

setInterval(changeModule,5000);