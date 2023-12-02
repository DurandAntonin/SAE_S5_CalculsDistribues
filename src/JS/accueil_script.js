const profil = document.querySelector('#popUpProfil');
const showProfile = document.querySelector('#showProfil');
const formProfil = document.querySelector('#popUpFormProfil');
const linkShowProfil = document.querySelector('#linkShowProfil');
var showed = false;
var showedF = false;



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
    title: "Are you sure?",
    text: "You won't be able to revert this!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, delete it!"
  }).then((result) => {
    if (result.isConfirmed) {
      Swal.fire({
        title: "Deleted!",
        text: "Your file has been deleted.",
        icon: "success"
      });
      var form = document.getElementById("formProfil");
      form.submit();

    }
  });
}