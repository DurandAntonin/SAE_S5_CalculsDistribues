window.onload = init

const profil = document.querySelector('#popUpProfil');
const showProfile = document.querySelector('#showProfil');
const formProfil = document.querySelector('#popUpFormProfil');
const linkShowProfil = document.querySelector('#linkShowProfil');
const popUpPasCo = document.querySelector('#popUpPasCo');

var showed = false;
var showedF = false;

const navLinks = document.querySelector('.nav-links')
showProfile.addEventListener('click', function(event) {
    event.stopPropagation();
});
if(linkShowProfil){
    linkShowProfil.addEventListener('click', function(event) {
        event.stopPropagation();
    });
}


//éléments html pour le formulaire du calcul de nombres premiers
//et l'affichage du temps d'exécution du programme ainsi que la liste des nombres premiers
let minBoundary
let maxBoundary
let executionTime
let result
let buttonCompute
let toggleB

//valeur maximale de la borne max
let maxValueForBoundary = 100000

let resultFile //fichier stockant les résultats du programme de calcul des nombres premiers
let indicatorFile //fichier indiquant si le programme s'est terminé

let timeoutCheckComputeFinished = 1000
let intervalCheckComputeFinished //intervalle de temps pour vérifier si le programme de calcul des nombres premiers s'est terminé

//variable pour indiquer que le programme de calcul des nombres premiers s'est terminé
let computeFinished = false


function init(){
    //on récupère les différents éléments html
    minBoundary = document.getElementById("debut")
    maxBoundary = document.getElementById("fin")
    executionTime = document.getElementById("p-execution-time")
    result = document.getElementById("result")
    buttonCompute = document.getElementById("compute")
    toggleB = document.getElementById("toggleB")

    //on associe un événement onclick au boutton pour lancer le programme de calcul des nombres premiers
    buttonCompute.onclick = requestComputePrimeNumbers
}

function requestComputePrimeNumbers(){
    //on récupère les bornes de calcul
    let borneMin = minBoundary.value
    let borneMax = maxBoundary.value
    let execMode = toggleB.checked

    console.log(borneMin)
    console.log(borneMax)

    //on vérifie que les valeurs sont cohérentes
    if (borneMin < 0 || borneMax <= borneMin){
        console.log("Valeurs des bornes non cohérantes !!!")
    }

    //on vérifie que la valeur des bornes et inférieure ou égale à la valeur max autorisée
    if (borneMax > maxValueForBoundary){
        console.log("Borne max doit être inférieure ou égale à" + maxValueForBoundary)
    }

    //on clear l'élément contenant la liste des nombres premiers
    result.innerHTML = ""

    //on clear et cache l'élément contenant le temps d'exécution du calcul
    executionTime.innerHTML = ""
    if (executionTime.classList.contains("flex")){
        executionTime.classList.replace("flex", "hidden")
    }

    //on lance une requete ajax vers un script php qui s'occupe d'exécuter le programme de calcul des nombres premiers
    let requestGetStatsSite = new XMLHttpRequest()
    requestGetStatsSite.open("POST","script_calcul_nombres_premiers.php");
    requestGetStatsSite.setRequestHeader("Content-Type","application/json-charset=utf-8");
    requestGetStatsSite.send(JSON.stringify({"bornes": [borneMin, borneMax], "execMode" : execMode, "mode" : 0}))

    requestGetStatsSite.onreadystatechange = resultRequestComputePrimeNumbers
}

function resultRequestComputePrimeNumbers(){
    if (this.readyState === 4 && this.status === 200) {
        //on récupère le résultat du script
        let resultScript = this.response
        //console.log(resultScript)

        let resultScriptParsed = JSON.parse(resultScript)
        //console.log(resultScriptParsed)

        //on regarde si une erreur a été renvoyée
        if (resultScriptParsed.error === 0){
            //on récupère le nom de chaque fichier retourné
            resultFile = resultScriptParsed.result[0]
            indicatorFile = resultScriptParsed.result[1]

            //on lance une requete ajax toutes les n secondes pour vérifier si l'exécution du programme est terminée
            intervalCheckComputeFinished = setTimeout(requestCheckComputeFinished, timeoutCheckComputeFinished)
        }
        else{
            console.log("Erreur : " + resultScriptParsed.errorMessage)
        }
    }
}

function requestCheckComputeFinished(){
    //on lance une requete ajax vers un script php qui s'occupe de vérifier si le programme de calcul des nombres premiers est terminé
    let requestGetStatsSite = new XMLHttpRequest()
    requestGetStatsSite.open("POST","script_calcul_nombres_premiers.php");
    requestGetStatsSite.setRequestHeader("Content-Type","application/json-charset=utf-8");
    requestGetStatsSite.send(JSON.stringify({"indicatorFileName" : indicatorFile,"mode" : 1}))

    requestGetStatsSite.onreadystatechange = resultRequestCheckComputeFinished
}

function resultRequestCheckComputeFinished(){
    if (this.readyState === 4 && this.status === 200) {
        //on récupère le résultat du script
        let resultScript = this.response
        //console.log(resultScript)

        let resultScriptParsed = JSON.parse(resultScript)
        //console.log(resultScriptParsed)

        //on regarde si une erreur a été renvoyée
        if (resultScriptParsed.error === 0){
            computeFinished = resultScriptParsed.result

            //on arrete le timeout si le resultat vaut true
            if (computeFinished){
                clearTimeout(intervalCheckComputeFinished)

                //on exécute une requete ajax pour récupérer le résultat de l'exécution du programme de calcul des nombres premiers
                requestGetResult()
            }
        }
        else{
            console.log("Erreur : " + resultScriptParsed.errorMessage)
        }
    }
}

function requestGetResult(){
    //on lance une requete ajax vers un script php qui s'occupe de vérifier si le programme de calcul des nombres premiers est terminé
    let requestGetStatsSite = new XMLHttpRequest()
    requestGetStatsSite.open("POST","script_calcul_nombres_premiers.php");
    requestGetStatsSite.setRequestHeader("Content-Type","application/json-charset=utf-8");
    requestGetStatsSite.send(JSON.stringify({"fileName" : resultFile, "mode" : 2}))

    requestGetStatsSite.onreadystatechange = resultRequestGetResult
}

function resultRequestGetResult(){
    if (this.readyState === 4 && this.status === 200) {
        //on récupère le résultat du script
        let resultScript = this.response
        //console.log(resultScript)

        let resultScriptParsed = JSON.parse(resultScript)
        //console.log(resultScriptParsed)

        //on regarde si une erreur a été renvoyée
        if (resultScriptParsed.error === 0){
            //on affiche la liste des nombres premiers
            result.innerHTML = resultScriptParsed.result.primeNumbersList

            //on affiche le temps d'exécution du calcul
            executionTime.classList
            executionTime.innerHTML = "Temps d'exécution : " + executionTime
        }
        else{
            console.log("Erreur : " + resultScriptParsed.errorMessage)
        }
    }
}

function goToPage(e){
    //console.log(e)
    //on récupère l'id du button qui a été cliqué
    let buttonId = e.target.id

    //console.log(buttonId)
    //console.log(window.location)

    //on récupère l'url de la page courante
    let urlSource = window.location.href
    let urlDest = ""

    //on enlève le nom de la page courante dans l'url qu'on va remplacer avec le nom de la page d'un module
    let urlSourceSplit = urlSource.split("/")

    //on redirige le user vers la page de module associée au bouton cliqué
    if (buttonId === "button-connection"){
        urlSourceSplit[urlSourceSplit.length-1] = "page_connexion.php"
    }
    else if (buttonId === "button-registration"){
        urlSourceSplit[urlSourceSplit.length-1] = "page_inscription.php"
    }

    urlDest = urlSourceSplit.join("/")
    //console.log(urlDest)
    document.location.assign(urlDest)
}

function onToggleMenu(e){
    e.name = e.name === 'menu' ? 'close' : 'menu'
    navLinks.classList.toggle('top-[9%]')
}

function showProfil() {
    //console.log("click");
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
    //console.log("click2");
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