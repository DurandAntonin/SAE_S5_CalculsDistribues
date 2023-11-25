const profil = document.querySelector('#popUpProfil');
const showProfile = document.querySelector('#showProfil');
var showed = false;

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

function handleClickOutside(event) {

  if (!profil.contains(event.target)) {
    profil.classList.add("hidden");
    showed = false;

    document.removeEventListener('click', handleClickOutside);
  }
}


showProfile.addEventListener('click', function(event) {
  event.stopPropagation();
});