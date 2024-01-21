window.onload = init

const popUpPasCo = document.querySelector('#popUpPasCo');

//éléments html pour le formulaire
let nbLancers
let executionTime
let result
let error
let buttonCompute
let toggleB

//element html pour afficher un message d'erreur a l'utilisateur
let errorMessage
//temps d'affichage du message
let durationTimeOfMessage = 5000

//valeur maximale pour le nombre de lancers
let maxValueOfThrows = 5000000

let resultFile //fichier stockant les résultats du programme de calcul des nombres premiers

let timeoutCheckComputeFinished = 1000
let intervalCheckComputeFinished //intervalle de temps pour vérifier si le programme de calcul de pi s'est terminé

//variable pour indiquer que le programme de calcul des nombres premiers s'est terminé
let computeFinished = false

function init(){
    //on importe les éléments html du formulaire ainsi que les éléments pour stocker les réponses, messages
    nbLancers = document.getElementById("nb-lancers")
    executionTime = document.getElementById("p-execution-time")
    result = document.getElementById("p-result")
    error = document.getElementById("p-error")
    buttonCompute = document.getElementById("compute")
    toggleB = document.getElementById("toggleB")
    errorMessage = document.getElementById("erreur_message")

    //on associe un événement onclick au boutton pour lancer le programme de calcul des nombres premiers
    buttonCompute.onclick = requestComputePi

    //on lance aussi le calcul quand on appuie sur une touche
    nbLancers.addEventListener("keydown", (launchCalculWhenKeyPressed))
}

function launchCalculWhenKeyPressed(event){
    //on lance l'action si la touche est "enter"
    if (event.keyCode === 13){
        let target = event.target

        //on clique sur un bouton en fonction de l'id du target
        if (target.id === nbLancers.id){
            buttonCompute.click()
        }
        else{
            //on affiche un message d'erreur
            displayMessage(errorMessage, "Bouton inconnu")
        }
    }
}

function requestComputePi(){
    //on récupère le nombre de throws
    let nbThrows = parseInt(nbLancers.value, 10)
    let execMode = toggleB.checked

    //console.log(nbThrows)

    //on vérifie que c'est bien un int
    if (isNaN(nbThrows) || nbThrows != nbLancers.value){
        displayMessage(errorMessage, "Le nombre de lancers doit être un entier")
        resetButtonCalculate()
    }

    //on vérifie que les valeurs sont cohérentes
    else if (nbThrows <= 0){
        //console.log("Valeurs des bornes non cohérantes !!!")
        displayMessage(errorMessage, "Le nombre de lancers doit être supérieure à 0")
        resetButtonCalculate()
    }
    else if (nbThrows > maxValueOfThrows){
        //console.log("Borne max doit être supérieure ou égale à" + maxValueOfThrows)
        displayMessage(errorMessage, maxValueOfThrows + " lancers au maximum")
        resetButtonCalculate()
    }

    else{
        //on clear les éléments html contenant les résultats du script
        result.innerHTML = "Approximation de Pi : "
        error.innerHTML = "Erreur : "
        executionTime.innerHTML = "Temps d'exécution : "

        //on indique au user que le calcul est en cours
        initialiseButtonForWaitingResult()

        //console.log("Requete ajax pour lancer le calcul de pi")

        //on lance une requete ajax vers un script php qui s'occupe d'exécuter le programme de calcul des nombres premiers
        let requestStartCalcul = new XMLHttpRequest()
        requestStartCalcul.open("POST","script_calcul_module.php");
        requestStartCalcul.setRequestHeader("Content-Type","application/json-charset=utf-8");
        requestStartCalcul.send(JSON.stringify({"nbLancers": nbThrows, "numModule" : 2,  "execMode" : execMode, "mode" : 0}))

        requestStartCalcul.onreadystatechange = resultRequestComputePi
    }
}

function resultRequestComputePi(){
    if (this.readyState === 4 && this.status === 200) {
        //on récupère le résultat du script
        let resultScript = this.response
        //console.log(resultScript)

        //on essaie de parser le résultat en format JSON
        let resultScriptParsed
        try {
            resultScriptParsed = JSON.parse(resultScript)
            //console.log(resultScriptParsed)
        }
        catch (e){
            resetButtonCalculate()

            //on affiche un message d'erreur
            displayMessage(errorMessage, "Erreur lors de la tentative de récupération de la réponse du serveur")
        }

        if (resultScriptParsed != null){
            //on regarde si une erreur a été renvoyée
            if (resultScriptParsed.error === 0){
                //on récupère le nom du fichier contenant les résultats du programme
                resultFile = resultScriptParsed.result

                //on lance une requete ajax toutes les n secondes pour vérifier si l'exécution du programme est terminée
                intervalCheckComputeFinished = setInterval(requestCheckComputeFinished, timeoutCheckComputeFinished)
            }
            else{
                resetButtonCalculate()

                //console.log("Erreur : " + resultScriptParsed.errorMessage)
                displayMessage(errorMessage, resultScriptParsed.errorMessage)
            }
        }
    }
}

function requestCheckComputeFinished(){
    //console.log("On check si le calcule est terminé")
    //on lance une requete ajax vers un script php qui s'occupe de vérifier si le programme de calcul de pi est terminé
    let requestGetStatsSite = new XMLHttpRequest()
    requestGetStatsSite.open("POST","script_calcul_module.php");
    requestGetStatsSite.setRequestHeader("Content-Type","application/json-charset=utf-8");
    requestGetStatsSite.send(JSON.stringify({"outputFileName" : resultFile,"mode" : 1}))

    requestGetStatsSite.onreadystatechange = resultRequestCheckComputeFinished
}

function resultRequestCheckComputeFinished(){
    if (this.readyState === 4 && this.status === 200) {
        //on récupère le résultat du script
        let resultScript = this.response
        //console.log(resultScript)

        //on essaie de parser le résultat en format JSON
        let resultScriptParsed
        try {
            resultScriptParsed = JSON.parse(resultScript)
            //console.log(resultScriptParsed)
        }
        catch (e){
            //on reset le bouton calculer
            resetButtonCalculate()

            //on affiche un message d'erreur
            displayMessage(errorMessage, "Erreur lors de la tentative de récupération de la réponse du serveur")
        }

        if (resultScriptParsed != null){

            //on regarde si une erreur a été renvoyée
            if (resultScriptParsed.error === 0){
                computeFinished = resultScriptParsed.result

                //console.log("Check calcul terminé : " + computeFinished)
                //on arrete l'interval si le resultat vaut true
                if (computeFinished){
                    clearInterval(intervalCheckComputeFinished)

                    //on reset le bouton calculer
                    resetButtonCalculate()

                    //on exécute une requete ajax pour récupérer le résultat de l'exécution du programme de calcul de pi
                    requestGetResult()
                }
            }
            else{
                //on reset le bouton calculer
                resetButtonCalculate()

                //console.log("Erreur : " + resultScriptParsed.errorMessage)
                displayMessage(errorMessage, resultScriptParsed.errorMessage)
            }
        }
    }
}

function requestGetResult(){
    //console.log("Calcul terminé, on récupère le résultat")
    //on lance une requete ajax vers un script php qui s'occupe de vérifier si le programme de calcul de pi est terminé
    let requestGetStatsSite = new XMLHttpRequest()
    requestGetStatsSite.open("POST","script_calcul_module.php");
    requestGetStatsSite.setRequestHeader("Content-Type","application/json-charset=utf-8");
    requestGetStatsSite.send(JSON.stringify({"fileName" : resultFile, "mode" : 2}))

    requestGetStatsSite.onreadystatechange = resultRequestGetResult
}

function resultRequestGetResult(){
    if (this.readyState === 4 && this.status === 200) {
        //on récupère le résultat du script
        let resultScript = this.response
        //console.log(resultScript)

        let resultScriptParsed
        try {
            resultScriptParsed = JSON.parse(resultScript)
            //console.log(resultScriptParsed)
        }
        catch (e){
            //on affiche un message d'erreur
            displayMessage(errorMessage, "Erreur lors de la tentative de récupération de la réponse du serveur")
        }

        if (resultScriptParsed != null){
            //on regarde si une erreur a été renvoyée
            if (resultScriptParsed.error === 0){
                //on insère dans les bons éléments, l'approximation de pi, l'erreur et le temps d'exécution du calcul
                result.innerHTML = "Approximation de Pi : " + resultScriptParsed.result.pi
                error.innerHTML = "Erreur : " + resultScriptParsed.result.error
                executionTime.innerHTML = "Temps d'exécution : " + resultScriptParsed.result.executionTime + "s"
            }
            else{
                //console.log("Erreur : " + resultScriptParsed.errorMessage)
                displayMessage(errorMessage, resultScriptParsed.errorMessage)
            }
        }
    }
}

function initialiseButtonForWaitingResult(){
    //on supprime les enfants du boutton
    deleteChildNodes(buttonCompute)

    //on ajoute un spinner dans le bouton
    let spinner = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
    let path1 = document.createElementNS(
        'http://www.w3.org/2000/svg',
        'path'
    );
    let path2 = document.createElementNS(
        'http://www.w3.org/2000/svg',
        'path'
    );

    spinner.setAttribute("id", "spinner")
    spinner.setAttribute("aria-hidden", "true");
    spinner.setAttribute("role", "status");
    spinner.setAttribute("fill", "none");
    spinner.setAttribute("viewBox", "0 0 100 101");
    spinner.classList.add("inline", "w-6", "h-6", "me-3", "text-white", "animate-spin", "fill-blue-600");

    path1.setAttribute(
        "d",
        "M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
    );
    path1.setAttribute("fill", "currentColor");
    path2.setAttribute(
        "d",
        "M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
    );
    path2.setAttribute("fill", 'currentFill');

    spinner.append(path1, path2)
    //console.log(spinner)

    buttonCompute.append(spinner, document.createTextNode("Calcul en cours ..."))
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

function resetButtonCalculate(){
    //on remet le boutton à l'état initial
    deleteChildNodes(buttonCompute)
    buttonCompute.appendChild(document.createTextNode("Calculer"))
}

function deleteChildNodes(fatherNode){
    while (fatherNode.hasChildNodes()){
        fatherNode.removeChild(fatherNode.firstChild)
    }
}

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