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
  var pg0 = document.getElementById('pgbar0');
  var pg1 = document.getElementById('pgbar1');
  var pg2 = document.getElementById('pgbar2');

  let pgbars = [pg0,pg1,pg2];

  var interval = 50;

  var timers = [];

  var current = 0;


  
  function updateProgressBar() {

    var progressBar = pgbars[current];

    progressBar.style.width = 'O%';
    
    var newWidth = 1;

    var timer = setInterval(function(){
      newWidth ++;

      if (newWidth > 100) {
        newWidth = 100;

        
        clearInterval(timer);
        setTimeout(resetProgressBar, 1);
    }

      
      progressBar.style.width = newWidth + '%';
    },interval);

    timers.push(timer);
  }

  
  function resetProgressBar() {
    var currentProgressBar = pgbars[current];
    currentProgressBar.style.width = '0%';
    
    current++;

    if(current>=pgbars.length){
      current = 0;
    }

    startTimer();
    //clearInterval(window.timer);
  }

  function resetAllProgressBars() {
    pgbars.forEach(function (bar) {
        bar.style.width = '0%';
    });

    timers.forEach(function (timer) {
        clearInterval(timer);
    });

    timers = [];
}

  
  function startTimer() {

    setTimeout(updateProgressBar, 0);

  }

  
  startTimer();

  var mod0 = document.getElementById('blockMod0');
  if (mod0) {
      mod0.addEventListener('click', function () {
        console.log("click");
        

        current = 0;
        resetAllProgressBars();
        //startTimer();

        changes = -1;
        changeModule();

        clearInterval(intervalId);
      });
  }

  var mod1 = document.getElementById('blockMod1');
  if (mod1) {
      mod1.addEventListener('click', function () {
        console.log("click");
        

        current = 1;
        resetAllProgressBars();
        //startTimer();

        changes = 0;
        changeModule();

        clearInterval(intervalId);
      });
  }

  var mod2 = document.getElementById('blockMod2');
  if (mod2) {
      mod2.addEventListener('click', function () {
        console.log("click");
        

        current = 2;
        resetAllProgressBars();
        //startTimer();


        changes = 1;
        changeModule();
        clearInterval(intervalId);
      });
  }
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


var intervalId = setInterval(changeModule,5000);