

const popUpPasCo = document.querySelector('#popUpPasCo');

function toggleCheck() {
    let textCalcul = document.getElementById("textCalcul")
    if(document.getElementById("toggleB").checked === true){
        if(document.getElementById("infoToggle").innerHTML === "user"){
            textCalcul.innerHTML = "ACTIF";
            textCalcul.classList.replace("text-red-700", "text-green-700")
            //console.log(textCalcul)
        }
        else{
            popUpPasCo.classList.remove("hidden");
            popUpPasCo.classList.add("flex");
        }
    } else {
        popUpPasCo.classList.remove("flex");
        popUpPasCo.classList.add("hidden");
        textCalcul.innerHTML = "INACTIF";
        textCalcul.classList.replace("text-green-700", "text-red-700")
    }
}

function closePopUp(){
    var toggle = document.getElementById("toggleB");
    toggle.checked = !toggle.checked;
    popUpPasCo.classList.remove("flex");
    popUpPasCo.classList.add("hidden");
}