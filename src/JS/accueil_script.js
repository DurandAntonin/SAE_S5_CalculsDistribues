const profil = document.getElementById('popUpProfil');
var showed = false;

function showProfil(){
    console.log("click")
    if (!showed){
        profil.classList.remove("hidden");
        showed = true;
    }
    else{
        profil.classList.add("hidden");
        showed = false;
    }

}