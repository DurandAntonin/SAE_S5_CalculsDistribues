window.onload = init


var changes = 0;
var intervalId = setInterval(changeModule,5000);


//éléments contenant le nombre d'utilisations d'un module
let divNbUsersModule1
let divNbUsersModule2
let divNbUsersModule3

function init(){
    //on récupère les différents éléments html
    divNbUsersModule1 = document.getElementById("div-nb-ut-m1")
    divNbUsersModule2 = document.getElementById("div-nb-ut-m2")
    divNbUsersModule3 = document.getElementById("div-nb-ut-m3")

    setTimeout(function(){
        let sectionModules = document.getElementById("sectionModules")
    sectionModules.scrollIntoView({ behavior: 'smooth' })
    },1000)

    //on appelle une fonction qui exécute une requete ajax pour récupérer le nombre d'utilisations de chaque module
    requestGetNbOfUsesPerModule()

}

function requestGetNbOfUsesPerModule(){
    let requestGetStats = new XMLHttpRequest()
    requestGetStats.open("POST","script_get_utilisation_modules.php");
    requestGetStats.setRequestHeader("Content-Type","application/json-charset=utf-8");

    //on envoi le mode d'exécution du script voulu
    requestGetStats.send()

    requestGetStats.onreadystatechange = resultRequestGetNbOfUsesPerModule
}

function resultRequestGetNbOfUsesPerModule(){
    if (this.readyState === 4 && this.status === 200) {
        //on récupère le résultat du script
        let resultScript = this.response
        //console.log(resultScript)

        let resultScriptParsed = JSON.parse(resultScript)
        //console.log(resultScriptParsed)

        //on vérifie qu'il n'y a pas eu d'erreur
        if (resultScriptParsed.error === 0){
            //on met à jour le nombre d'utilisations de chaque module
            updateUsesOfModule(divNbUsersModule1, resultScriptParsed.result.module1)
            updateUsesOfModule(divNbUsersModule2, resultScriptParsed.result.module2)
            updateUsesOfModule(divNbUsersModule3, resultScriptParsed.result.module3)
        }
        else{
            displayMessage(document.getElementById("p-message-erreur"), resultScriptParsed.errorMessage)
        }
    }
}

function displayMessage(elementToStockMessage, message){
    //on clear l'élément html
    deleteChildNodes(elementToStockMessage)

    //on ajoute le message dans l'élément
    elementToStockMessage.appendChild(document.createTextNode(message))

    //on efface le message dans n secondes
    setTimeout(function () {
        deleteChildNodes(elementToStockMessage)
    }, durationTimeOfMessage)
}

function deleteChildNodes(fatherNode){
    while (fatherNode.hasChildNodes()){
        fatherNode.removeChild(fatherNode.firstChild)
    }
}

function updateUsesOfModule(elemStatModule, nbOfUses){
    //on modifie le nombre d'utilisations de ce module
    elemStatModule.childNodes[0].nodeValue = "\n" + nbOfUses + "\n"

    //on met un s à "utilisation" si le chiffre est > 0 et inversement
    if (nbOfUses < 1)
        elemStatModule.childNodes[1].innerHTML = "utilisation"
    else
        elemStatModule.childNodes[1].innerHTML = "utilisations"
}

function goToModulePage(e){
    console.log(e)
    //on récupère l'id du button qui a été cliqué
    let buttonId = e.target.id

    console.log(buttonId)
    console.log(window.location)

    //on récupère l'url de la page courante
    let urlSource = window.location.href
    let urlDest = ""

    //on enlève le nom de la page courante dans l'url qu'on va remplacer avec le nom de la page d'un module
    let urlSourceSplit = urlSource.split("/")

    //on redirige le user vers la page de module associée au bouton cliqué
    if (buttonId === "button-module1"){
        urlSourceSplit[urlSourceSplit.length-1] = "page_module_nombres_premiers.php"
    }
    else if (buttonId === "button-module2"){
        urlSourceSplit[urlSourceSplit.length-1] = "page_module_pi_monte_carlo.php"
    }
    else if (buttonId === "button-module3"){
        urlSourceSplit[urlSourceSplit.length-1] = "page_accueil_user.php"
    }

    urlDest = urlSourceSplit.join("/")
    console.log(urlDest)
    document.location.assign(urlDest)
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
            //console.log("click");


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
            //console.log("click");


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
            //console.log("click");


            current = 2;
            resetAllProgressBars();
            //startTimer();


            changes = 1;
            changeModule();
            clearInterval(intervalId);
        });
    }
});

function changeModule(){
    changes ++;
    var mod = changes % 3
    var modS = mod.toString();

    //log(modS, mod);

    var modules = document.getElementsByClassName('wrapper');
    var module = document.getElementById(modS);

    Array.prototype.forEach.call(modules,function(elem){
        elem.classList.add("hidden");
    });

    module.classList.remove('hidden');
}