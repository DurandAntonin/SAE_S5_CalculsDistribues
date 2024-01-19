window.onload = init

const enumTimeFilters = {
    jour: 0,
    semaine:1,
    mois:2,
    tout:3
}

const popUplistLogs = document.querySelector('#popUpLogs');
const btnShowLogs = document.querySelector('#showLogs');
const popUplistUsers = document.querySelector('#popUpUsers');
const btnShowUsers = document.querySelector('#showUsers');
const contentUsers = document.querySelector('#contentUsers');
const contentLogs = document.querySelector('#contentLogs');

var showedL = false;
var showed = false;
let chartBar

//elements html contenant les stats deu site
let elemNbUsers
let elemNbVisits
let elemNbModuleUsers

//liste des stats pour chaque rpi
let listStatsClusterHat

//liste d'éléments html pour chaque time filter
let listTimeFilters
let currentTimeFilterValue = 1 //0=jour 1=semaine 2=mois 3=tout

//intervalle de temps en ms pour actualiser les stats du site
let intertalTimeGetStatsSite = 10000

//intervalle de temps qui exécute une fonction pour checker les stats du cluster hat
let intervalCheckGetStatsClusterHat
//intervalle de temps en ms pour rafraichir les stats du cluster hat
let intertalTimeGetStatsClusterHat = 60000
//temps en ms pour checker si les stats du cluster hat sont dans un fichier
let timeCheckGetStatsClusterHat = 1000
//nom du dernier fichier contenant les stats du cluster hat
let fileNameStatsClusterHat

//nom de la dernière classe recherchée
let lastClassSearched

//éléments pour la recherche et l'affichage des utilisateurs selon un attribut
let researchBarUsers
let attributeSelectedUserResearch
let buttonSubmitResearchUsers
let listUsers

//éléments pour la recherche et l'affichage des logs selon un attribut
let researchBarLogging
let attributeSelectedLoggingResearch
let buttonSubmitResearchLogging
let listLogging

//timers pour récupérer les stats
let timerRequestGetStatsClusterHat = null
let timerRequestSetStatsSite = null

//temps d'affichage du message
let durationTimeOfMessage = 5000

function init(){
    btnShowUsers.addEventListener('click', function(event) {
        event.stopPropagation();
    });

    btnShowLogs.addEventListener('click', function(event) {
        event.stopPropagation();
    });

    //on récupère les différents éléments html stockant les stats du site, du cluster hat et champs select
    elemNbUsers = document.getElementById("nb-users")
    elemNbVisits = document.getElementById("nb-visits")
    elemNbModuleUsers = document.getElementById("nb-module-uses")
    listStatsClusterHat = Array.from(document.getElementById("tbody-table-stats").children)
    listTimeFilters = Array.from(document.getElementsByClassName("time-filter"))
    researchBarUsers = document.getElementById("research-bar-user")
    attributeSelectedUserResearch = document.getElementById("select-user-attribute")
    buttonSubmitResearchUsers = document.getElementById("button-submit-research-users")
    listUsers = document.getElementById("div-liste-users")
    researchBarLogging = document.getElementById("research-bar-logging")
    attributeSelectedLoggingResearch = document.getElementById("select-logging-attribute")
    buttonSubmitResearchLogging = document.getElementById("button-submit-research-logging")
    listLogging = document.getElementById("div-list-logging")

    //par défaut on met le time filter value a 0 (== défaut)
    currentTimeFilterValue = enumTimeFilters.jour
    //console.log(listTimeFilters)

    //on associé des événements onclick pour les time filters des stats
    listTimeFilters.forEach((timeFilter) => timeFilter.onclick = changeStatsBasedTimeFilter)

    //on associe un événement on click pour lancer la recherche sur les boutons de recherche logging et users
    buttonSubmitResearchUsers.onclick = requestResearchUsersOrLogging
    buttonSubmitResearchLogging.onclick = requestResearchUsersOrLogging

    //on lance aussi la recherche quand on appuie sur une touche
    researchBarUsers.addEventListener("keydown", (launchResearchWhenKeyPressed))
    researchBarLogging.addEventListener("keydown", (launchResearchWhenKeyPressed))

    //on load les stats du site au chargement de la page
    requestSetStatsSite()
    //console.log(listStatsClusterHat)

    //on load les stats du cluster hat
    requestSetStatsClusterHat()
}

function launchResearchWhenKeyPressed(event){
    //on lance l'action si la touche est "enter"
    if (event.keyCode === 13){
        let target = event.target

        //on clique sur un bouton en fonction de l'id du target
        switch (target.id) {
            case researchBarUsers.id:
                buttonSubmitResearchUsers.click()
                break
            case researchBarLogging.id:
                buttonSubmitResearchLogging.click()
                break
            default:
                //on affiche un message d'erreur
                displayMessage(document.getElementById("p-message"), "Bouton de recherche inconnu")
                break
        }
    }
}

function changeStatsBasedTimeFilter(){
    //on récupère l'id du time filter sélectionné, et on change le current time filter
    let timeFilterSelected = this.control.id
    currentTimeFilterValue = getTimeFilterValueFromKey(timeFilterSelected)

    //on appelle une autre méthode pour changer les stats en fonction du time filter
    requestSetStatsSite()
}

function deleteUser(){
    let userIdToDelete = this.id
    //console.log(userIdToDelete)

    //on crée et exécute une requête js vers un script php pour supprimer l'utilisateur
    let requestGetStatsSite = new XMLHttpRequest()
    requestGetStatsSite.open("POST","script_supprimer_compte_utilisateur.php");
    requestGetStatsSite.setRequestHeader("Content-Type","application/json-charset=utf-8");
    requestGetStatsSite.send(JSON.stringify({"userIdToDelete": userIdToDelete}))

    requestGetStatsSite.onreadystatechange = resultRequestDeleteUser
}

function requestSetStatsSite(){
    //on crée la date de début et la date de fin en fonction du time filter pour filtrer les recherches
    let startDate = ""
    let currentDate = new Date()
    let endDate = currentDate.toJSON().slice(0, 10)
    let currentYear = currentDate.getFullYear()
    let currentMonth = currentDate.getMonth() + 1

    switch (currentTimeFilterValue){
        case 0:
            startDate = endDate;
            break;
        case 1:
            let dayOfCurrentWeek = currentDate.getDate() - currentDate.getDay() + (currentDate.getDay() === 0 ? -6 : 1)
            startDate = new Date(currentDate.setDate(dayOfCurrentWeek)).toJSON().slice(0,10)
            break;
        case 2:
            startDate = new Date(`${currentYear}-${currentMonth}-01`).toJSON().slice(0,10)
            break;
        case 3:
            startDate = new Date("1970-01-01").toJSON().slice(0,10)
            break;
    }

    //console.log("Date de début : " + startDate)
    //console.log("Date de fin : " + endDate)

    //on crée et exécute une requête js vers un script php pour récupérer les stats du site en fonction du filtre de temps
    let requestGetStatsSite = new XMLHttpRequest()
    requestGetStatsSite.open("POST","script_get_stats_site.php");
    requestGetStatsSite.setRequestHeader("Content-Type","application/json-charset=utf-8");
    requestGetStatsSite.send(JSON.stringify({"timeFilter":[startDate, endDate]}))

    requestGetStatsSite.onreadystatechange = resultRequestGetStatsSite
}

function requestSetStatsClusterHat(){
    //on crée et exécute une requête js vers un script php pour récupérer les stats du cluster hat en fonction
    let requestGetStatsSite = new XMLHttpRequest()
    requestGetStatsSite.open("POST","script_get_stats_cluster_hat.php");
    requestGetStatsSite.setRequestHeader("Content-Type","application/json-charset=utf-8");

    //on envoi le mode d'exécution du script voulu
    requestGetStatsSite.send(JSON.stringify({"execMode" : 0}))

    requestGetStatsSite.onreadystatechange = resultRequestSetStatsClusterHatInFile
}

function requestResearchUsersOrLogging(){
    let fieldToSearch
    let stringToSearch
    let classResearched
    let error = false
    let elemToStoreErrorMessage
    let errorMessage

    //on regarde quel bouton a été appuyé
    if (this.id === buttonSubmitResearchUsers.id){
        fieldToSearch = attributeSelectedUserResearch.value
        stringToSearch = researchBarUsers.value
        classResearched = buttonSubmitResearchUsers.name

        //on affiche un message d'erreur si le string a recherche est != null et l'attribut de recherche est nul
        if (stringToSearch.length > 0 && (fieldToSearch == null || fieldToSearch.length === 0)){
            error = true
            elemToStoreErrorMessage = document.getElementById("p-message-erreur-recherche-users")
            errorMessage = "Attribut de recherche requis"
        }
    }
    else if (this.id === buttonSubmitResearchLogging.id){
        fieldToSearch = attributeSelectedLoggingResearch.value
        stringToSearch = researchBarLogging.value
        classResearched = buttonSubmitResearchLogging.name

        //on affiche un message d'erreur si le string a recherche est != null et l'attribut de recherche est nul
        if (stringToSearch.length > 0 && (fieldToSearch == null || fieldToSearch.length === 0)){
            error = true
            elemToStoreErrorMessage = document.getElementById("p-message-erreur-recherche-logs")
            errorMessage = "Attribut de recherche requis"
        }
    }
    else{
        //boutton inconnu, on affiche un message d'erreur
        error = true
        elemToStoreErrorMessage = document.getElementById("p-message")
        errorMessage = "Bouton de recherche inconnu"
    }

    if (!error){
        lastClassSearched = classResearched

        //on crée et exécute une requête js vers un script php pour rechercher des users ou logging en fonction d'un attribut sélectionné
        let requestGetStatsSite = new XMLHttpRequest()
        requestGetStatsSite.open("POST","script_get_users_or_logs_with_attribute.php");
        requestGetStatsSite.setRequestHeader("Content-Type","application/json-charset=utf-8");
        requestGetStatsSite.send(JSON.stringify({"fieldToSearch": fieldToSearch, "stringSearch" : stringToSearch, "classResearched" : classResearched}))

        requestGetStatsSite.onreadystatechange = resultRequestResearchUsersOrLogging
    }
    else{
        displayMessage(elemToStoreErrorMessage, errorMessage)
    }

}

function resultRequestGetStatsSite(){
    if (this.readyState === 4 && this.status === 200) {
        //on récupère le résultat du script
        let resultScript = this.response
        //console.log(resultScript)

        let resultScriptParsed = JSON.parse(resultScript)
        //console.log(resultScriptParsed)

        //variables contenant les resultats des requetes
        let connBd = resultScriptParsed.connBd
        let resultRequestGetNbUsers = resultScriptParsed.resultRequestGetNbUsers
        let resultRequestGetNbVisits = resultScriptParsed.resultRequestGetNbVisits
        let resultRequestGetNbModuleUses = resultScriptParsed.resultRequestGetNbModuleUses

        //texte à afficher pour chaque stat
        let newStatNbUsers
        let newStatNbVisits
        let newStatNbModuleUses

        //on va stocker dans une variable le message d'erreur s'il y en a
        let errorMessage = ""

        //s'il y a eu une erreur lors de la connexion à la bd, on affiche nul pour chaque stat
        if (connBd.error === 1){
            newStatNbUsers = 'null'
            newStatNbVisits = 'null'
            newStatNbModuleUses = 'null'
            errorMessage = "Erreur interne lors de la récupération des statistiques"
        }
        else{
            //pour chaque requete de statistiques, on regarde s'il y a une erreur
            if (resultRequestGetNbUsers.error === 0){
                newStatNbUsers = resultRequestGetNbUsers.result
            }
            else{
                errorMessage = "Erreur interne lors de la récupération du nombre de nouveaux utilisateurs"
                newStatNbUsers = 'null'
            }


            if (resultRequestGetNbVisits.error === 0){
                newStatNbVisits = resultRequestGetNbVisits.result["USER"] + resultRequestGetNbVisits.result["VISITEUR"]
            }
            else{
                errorMessage = "Erreur interne lors de la récupération du nombre de visites"
                newStatNbVisits = 'null'
            }


            if (resultRequestGetNbModuleUses.error === 0){
                newStatNbModuleUses = resultRequestGetNbModuleUses.result
            }
            else{
                errorMessage = "Erreur interne lors de la récupération du nombre d'utilisations de modules"
                newStatNbModuleUses = 'null'
            }
        }
        //console.log(newStatNbUsers + " " + newStatNbVisits + " " + newStatNbModuleUses)
        //console.log(resultRequestGetNbVisits.result)

        //on affiche un message d'erreur à l'utilisateur
        if (errorMessage !== ""){
            displayMessage(document.getElementById("p-message"), errorMessage)
        }

        //on met à jour chaque element stat du site
        elemNbUsers.innerHTML = newStatNbUsers
        elemNbVisits.innerHTML = newStatNbVisits
        elemNbModuleUsers.innerHTML = newStatNbModuleUses

        //console.log(chartBar)
        let nbUsers = resultRequestGetNbVisits.result["USER"]
        let nbVisiteurs = resultRequestGetNbVisits.result["VISITEUR"]

        //on change le camembert s'il y a eu de nouvelles connexions
        if (chartBar == null || chartBar.config._config.data.datasets[0].data[0] !== nbUsers || chartBar.config._config.data.datasets[0].data[1] !== nbVisiteurs){
            //on détruit le camembert pour le recréer ensuite s'il existe
            if (chartBar != null)
                chartBar.destroy()

            let configPie = configChartBarCanva(nbUsers, nbVisiteurs)
            chartBar = new Chart(document.getElementById("chartPie"), configPie);
            chartBar.render()


        }
        //on lance le timer pour récupérer les stats du site s'il n'est pas déjà lancé
        if (timerRequestSetStatsSite === null){
            timerRequestSetStatsSite = setInterval(requestSetStatsSite, intertalTimeGetStatsSite)
        }
    }
}

function resultRequestSetStatsClusterHatInFile() {
    if (this.readyState === 4 && this.status === 200) {
        let resultScript = this.response
        //console.log(resultScript)
        console.log("Get cluster hat stats")

        //on essaie de parser le résultat en format JSON
        let resultScriptParsed
        try {
            resultScriptParsed = JSON.parse(resultScript)
            //console.log(resultScriptParsed)
        }
        catch (e){
            //on affiche un message d'erreur
            displayMessage(document.getElementById("p-message"), "Erreur lors de la tentative de récupération de la réponse du serveur")
        }

        if (resultScriptParsed != null){
            //on vérifie qu'il n'y a pas d'erreur
            if (resultScriptParsed.error === 0) {
                //on récupère le nom du fichier contenant les stats du kit cluster hat
                fileNameStatsClusterHat = resultScriptParsed.result

                //on attend n secondes le temps que les stats soient écrites dans le fichier
                //puis on exécute une deuxième requete pour lire dans le fichier
                intervalCheckGetStatsClusterHat = setInterval(requestCheckGetStatsClusterHatInFile, timeCheckGetStatsClusterHat)
            } else {
                displayMessage(document.getElementById("p-message"), resultScriptParsed.errorMessage)
                //console.log("Erreur : " + resultScriptParsed.errorMessage)
            }
        }
    }
}

function requestCheckGetStatsClusterHatInFile(){
    let requestGetStatsSiteInFile = new XMLHttpRequest()
    requestGetStatsSiteInFile.open("POST","script_get_stats_cluster_hat.php");
    requestGetStatsSiteInFile.setRequestHeader("Content-Type","application/json-charset=utf-8");

    //on envoie le mode d'exécution du script voulu
    requestGetStatsSiteInFile.send(JSON.stringify({"execMode" : 1, "fileName" : fileNameStatsClusterHat}))

    requestGetStatsSiteInFile.onreadystatechange = resultRequestCheckGetStatsClusterHatInFile
}

function resultRequestCheckGetStatsClusterHatInFile(){
    if (this.readyState === 4 && this.status === 200) {
        let resultScript = this.response
        //console.log(resultScript)
        console.log("Check stats cluster hat up")

        //on essaie de parser le résultat en format JSON
        let resultScriptParsed
        try {
            resultScriptParsed = JSON.parse(resultScript)
            //console.log(resultScriptParsed)
        }
        catch (e){
            //on clear l'intervalle
            clearInterval(intervalCheckGetStatsClusterHat)

            //on affiche un message d'erreur
            displayMessage(document.getElementById("p-message"), "Erreur lors de la tentative de récupération de la réponse du serveur")
        }

        if (resultScriptParsed != null){
            //on vérifie qu'il n'y a pas eu d'erreur
            if (resultScriptParsed.error === 0){
                //on lance une requete pour récupérer les stats du cluster hat si le resultat vaut true
                if (resultScriptParsed.result){
                    //on clear l'intervalle
                    clearInterval(intervalCheckGetStatsClusterHat)

                    requestGetStatsClusterHat()
                }
            }
            else{
                //on clear l'intervalle
                clearInterval(intervalCheckGetStatsClusterHat)

                displayMessage(document.getElementById("p-message"), "Erreur interne lors de la récupération des statistiques du ClusterHat")
                //console.log("Erreur GetStatsClusterHatInFile")
            }
        }
    }
    else{
        //console.log("Reponse en cours")
    }

}

function requestGetStatsClusterHat(){
    let requestGetStatsSiteInFile = new XMLHttpRequest()
    requestGetStatsSiteInFile.open("POST","script_get_stats_cluster_hat.php");
    requestGetStatsSiteInFile.setRequestHeader("Content-Type","application/json-charset=utf-8");

    //on envoie le mode d'exécution du script voulu
    requestGetStatsSiteInFile.send(JSON.stringify({"execMode" : 2, "fileName" : fileNameStatsClusterHat}))

    requestGetStatsSiteInFile.onreadystatechange = resultRrequestGetStatsClusterHat
}

function resultRrequestGetStatsClusterHat(){
    if (this.readyState === 4 && this.status === 200) {
        let resultScript = this.response
        //console.log(resultScript)
        console.log("Récupération stats réussie")

        //on essaie de parser le résultat en format JSON
        let resultScriptParsed
        try {
            resultScriptParsed = JSON.parse(resultScript)
            //console.log(resultScriptParsed)
        }
        catch (e){
            //on clear l'intervalle
            clearInterval(intervalCheckGetStatsClusterHat)

            //on affiche un message d'erreur
            displayMessage(document.getElementById("p-message"), "Erreur lors de la tentative de récupération de la réponse du serveur")
        }

        if (resultScriptParsed != null){
            let objectStatsClusterHat = resultScriptParsed.result
            //console.log(listStatsClusterHat)

            //on vérifie qu'il n'y a pas eu d'erreur
            if (resultScriptParsed.error === 0){
                //pour chaque rpi du cluster hat, on met à jour les statistiques
                for (let i=0;i<objectStatsClusterHat.length;i++){
                    let statsRpi = objectStatsClusterHat[i]
                    let statCpuPourcent = (statsRpi.cpuUsage)
                    let statCpuFrequency = statsRpi.cpuFrequency
                    let statMemUsedPourcent = ((parseInt(statsRpi.memUsed) / parseInt(statsRpi.memTotal)) * 100).toPrecision(4)
                    let statMemUsed = (parseInt(statsRpi.memUsed) * Math.pow(10,-3)).toPrecision(4)
                    let statUptime = statsRpi.uptime

                    //on met à jour les stats du rpi dans le tableau
                    let trStatsRpi = listStatsClusterHat[i].children
                    //console.log(trStatsRpi[3].children)

                    trStatsRpi[1].children[0].innerHTML = `${statCpuPourcent} %`
                    trStatsRpi[1].children[1].innerHTML = `${statCpuFrequency} GHz`
                    trStatsRpi[2].children[0].innerHTML = `${statMemUsedPourcent} %`
                    trStatsRpi[2].children[1].innerHTML = `${statMemUsed} Go`
                    trStatsRpi[3].innerHTML = statUptime
                }
            }
            else{
                displayMessage(document.getElementById("p-message"), "Erreur interne lors de la récupération des statistiques du ClusterHat")
                //console.log("Erreur GetStatsClusterHatInFile")
            }
        }

        //on lance le timer pour récupérer les stats du cluster hat s'il n'est pas déjà lancé
        if (timerRequestGetStatsClusterHat === null){
            timerRequestGetStatsClusterHat = setInterval(requestSetStatsClusterHat, intertalTimeGetStatsClusterHat)
        }
    }
    else{
        //console.log("Reponse en cours")
    }
}

function resultRequestResearchUsersOrLogging(){
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
            //on affiche un message d'erreur
            let elementToStoreMessage
            if (lastClassSearched === "User")
                elementToStoreMessage = "p-message-erreur-recherche-users"
            else
                elementToStoreMessage = "p-message-erreur-recherche-logs"
            displayMessage(document.getElementById(elementToStoreMessage), "Erreur lors de la tentative de récupération de la réponse du serveur")
        }

        if (resultScriptParsed != null){
            let listResults = resultScriptParsed.result

            //on récupère la classe de recherche qui sera l'identifiant de l'élément html qui va stocker cette liste d'objets serialisés
            let htmlElemClassResearched = listResults.classResearched

            //on regarde où afficher le message en fonction de l'objet de la recherche (user ou log)
            let elementToStoreMessage
            if (htmlElemClassResearched === "User")
                elementToStoreMessage = "p-message-erreur-recherche-users"
            else
                elementToStoreMessage = "p-message-erreur-recherche-logs"

            //on regarde si une erreur a été renvoyée
            if (resultScriptParsed.error === 0){

                //et on récupère la liste des objets de cette classe sérialisé qu'on transforme en objet js
                let listObjectSerialised = JSON.parse(listResults.listObjectSerialised)

                //on enlève les anciens résultats
                deleteChildNodes(listUsers)
                deleteChildNodes(listLogging)

                //nombre d'objets impossibles à désérialiser
                let nbObjectNonUnserialised = 0

                //on ajoute chaque objet serialisé dans l'objet html en fonction de sa classe
                for (let i=0;i<listObjectSerialised.length;i++){
                    //on déserialise l'objet
                    let objectUnserialised

                    try{
                        objectUnserialised = JSON.parse(listObjectSerialised[i])

                        if (htmlElemClassResearched === "User"){
                            createHtmlElementForSerialisedUsers(listUsers, objectUnserialised)
                        }
                        else{
                            createHtmlElementForSerialisedLogging(listLogging, objectUnserialised)
                        }
                    }
                    catch (e){
                        nbObjectNonUnserialised ++
                    }
                    //console.log(objectUnserialised)
                    //on affiche le nombre d'objets ne pouvant pas être désérialisés
                    if (nbObjectNonUnserialised > 0){
                        displayMessage(document.getElementById(elementToStoreMessage), "Nombre d'objets ne pouvant être récupérés : " + nbObjectNonUnserialised)
                    }
                }
            }
            else{
                displayMessage(document.getElementById(elementToStoreMessage), "Erreur interne lors de la recherche des objets")
                //console.log("Erreur : " + resultScriptParsed.errorMessage)
            }
        }
    }
}

function resultRequestDeleteUser() {
    if (this.readyState === 4 && this.status === 200) {
        let resultScript = this.response
        //console.log(resultScript)

        //on essaie de parser le résultat en format JSON
        let resultScriptParsed
        try {
            resultScriptParsed = JSON.parse(resultScript)
            //console.log(resultScriptParsed)
        }
        catch (e){
            //on affiche un message d'erreur
            displayMessage(document.getElementById("p-message-erreur-recherche-users"), "Erreur lors de la tentative de récupération de la réponse du serveur")
        }

        if (resultScriptParsed != null){
            //on regarde si le script a retourné une erreur
            if (resultScriptParsed.error === 0){

                //on parcourt la liste des users retournées par la requete pour le supprimer graphiquement
                for (let i=0;i<listUsers.children.length; i++){
                    let userNode = listUsers.children[i]

                    //on supprime si l'id du user == id du user supprimé
                    if (userNode.children[0].children[1].id === resultScriptParsed["result"]){
                        userNode.remove()
                        break
                    }
                }

            }
            else{
                displayMessage(document.getElementById("p-message-erreur-recherche-users"), "Erreur interne lors de la tentative de suppression d'un utilisateur")
                //console.log("Erreur : " + resultScriptParsed.errorMessage)
            }
        }
    }

}

function getTimeFilterValueFromKey(key){
    return Object.keys(enumTimeFilters).indexOf(key)
}

function configChartBarCanva(nbUsers, nbVisiteurs){
    let dataPie = {
        labels: ["Utilisateurs", "Visiteurs"],
        datasets: [
            {
                label: "Nombres",
                data: [nbUsers, nbVisiteurs],
                backgroundColor: [
                    "rgb(255, 220, 0)",
                    "rgb(0, 35, 70)",
                ],
                hoverOffset: 4,
            },
        ],
    };

    let configPie = {
        type: "pie",
        data: dataPie,
        options: {},
    };

    return configPie
}


function createHtmlElementForSerialisedUsers(divListUsers, userSerialised){
    let divUserGlob = document.createElement("div")
    divUserGlob.setAttribute("class", "flex w-full items-center rounded-lg p-3 pl-4 hover:bg-lightblue relative")

    let divUser = document.createElement("div")
    divUser.setAttribute("class", "grid grid-flow-col grid-rows-2")

    //div contenant le login du user
    let subDivLogin = document.createElement("div")
    let spanDivLogin = document.createElement("span")
    subDivLogin.setAttribute("class", "mr-20 text-lg font-bold text-white w-30 inline-block whitespace-nowrap overflow-hidden span-overflow")
    subDivLogin.appendChild(spanDivLogin)

    //div contenant l'ID du user
    let subDivId = document.createElement("div")
    let spanDivId = document.createElement("span")
    subDivId.setAttribute("id", userSerialised.userId)
    subDivId.setAttribute("class", "text-xs text-white")
    spanDivId.setAttribute("class", "mr-2")
    subDivId.appendChild(spanDivId)

    //div contenant le mail du user
    let subDivMail = document.createElement("div")
    let spanDivMail = document.createElement("span")
    subDivMail.setAttribute("class", "text-lg font-bold text-white mr-20 w-2/4 inline-block whitespace-nowrap overflow-hidden span-overflow")
    subDivMail.appendChild(spanDivMail)

    //div contenant le nom et prénom du user
    let subDivFirstNameLastName = document.createElement("div")
    let spanLastName = document.createElement("span")
    let spanFirstName = document.createElement("span")
    subDivFirstNameLastName.setAttribute("class", "text-xs text-white")
    spanLastName.setAttribute("class", "mr-2")
    spanFirstName.setAttribute("class", "mr-2")
    subDivFirstNameLastName.append(spanLastName, spanFirstName)

    //div pour la date d'inscription du user
    let subDivRegistrationDate = document.createElement("div")
    subDivRegistrationDate.setAttribute("class", "text-lg font-bold text-white")

    //icone pour supprimer un user si ce dernier a le role USER
    let iconeDeleteUser
    if (userSerialised.role === "USER"){
        iconeDeleteUser = document.createElement("ion-icon")
        iconeDeleteUser.setAttribute("name", "trash")
        iconeDeleteUser.setAttribute("class", "w-7 h-7 text-red-700 absolute right-2 cursor-pointer")
        iconeDeleteUser.setAttribute("id", userSerialised.userId)

        //on associe un événement on click pour l'icone pour supprimer le user associé
        iconeDeleteUser.onclick = deleteUser
    }

    //on ajoute une chaine de caractère pour chaque span et div
    spanDivLogin.innerHTML = "Login : " + userSerialised.login
    spanDivId.innerHTML = "ID : " + userSerialised.userId.substring(0,8)
    spanDivMail.innerHTML = "Adresse mail : " + userSerialised.userMail
    spanLastName.innerHTML = "Nom : " + userSerialised.lastName
    spanFirstName.innerHTML = "Prénom : " + userSerialised.firstName
    subDivRegistrationDate.innerHTML = "Inscription : " + userSerialised.registrationDate

    //on ajoute les différents éléments div dans l'élément div
    divUser.append(subDivLogin, subDivId, subDivMail, subDivFirstNameLastName, subDivRegistrationDate)

    //console.log(iconeDeleteUser)
    //on ajoute l'icone pour delete le user si elle a été définie
    if (iconeDeleteUser !== undefined)
        divUser.append(iconeDeleteUser)

    divUserGlob.append(divUser)
    divListUsers.append(divUserGlob)
}

function createHtmlElementForSerialisedLogging(divListLogging, loggingSerialised){
    let divLoggingGlob = document.createElement("div")
    divLoggingGlob.setAttribute("class", "flex w-full items-center rounded-lg p-3 pl-4 hover:bg-lightblue relative")

    let divLogging = document.createElement("div")
    divLogging.setAttribute("class", "grid grid-flow-col grid-rows-2")

    //div pour le log level du logging
    let subDivLogLevel = document.createElement("div")
    subDivLogLevel.setAttribute("class", "mr-20 text-lg font-bold text-white")

    //div pour l'id du logging
    let subDivLogId = document.createElement("div")
    let spanLogId = document.createElement("span")
    subDivLogId.setAttribute("class", "text-xs text-white w-7/12 overflow-hidden")
    spanLogId.setAttribute("class", "mr-2")
    subDivLogId.appendChild(spanLogId)

    //div pour la description du logging
    let subDivDescription = document.createElement("div")
    let spanDivDescription = document.createElement("span")
    subDivDescription.setAttribute("class", "w-2/4 text-lg font-bold text-white mr-20 inline-block whitespace-nowrap overflow-hidden span-overflow")
    spanDivDescription.setAttribute("class", "w-full")
    subDivDescription.appendChild(spanDivDescription)

    //div pour le user id et l'ip du logging
    let subDivUserIdIp = document.createElement("div")
    let spanUserId = document.createElement("span")
    let spanIp = document.createElement("span")
    subDivUserIdIp.setAttribute("class", "text-xs text-white w-7/12 overflow-hidden")
    spanUserId.setAttribute("class", "mr-2")
    spanIp.setAttribute("class", "mr-2")
    subDivUserIdIp.append(spanUserId, spanIp)

    //div pour la date du logging
    let subDivDate = document.createElement("div")
    subDivDate.setAttribute("class", "text-white text-end text-lg font-bold w-6/12 absolute right-2 overflow-hidden")

    //on ajoute une chaine de caractère pour chaque span et div
    subDivLogLevel.innerHTML = "LogLevel : " + loggingSerialised.logLevel
    spanLogId.innerHTML = "LogId : " + loggingSerialised.logId.substring(0,8)
    spanDivDescription.innerHTML = "Description : " + loggingSerialised.description
    spanUserId.innerHTML = "UserId : " + loggingSerialised.userId.substring(0,8)
    spanIp.innerHTML = "IP : " + loggingSerialised.ip
    subDivDate.innerHTML = "Date : " + loggingSerialised.date

    //on ajoute les différents éléments div dans l'élément div
    divLogging.append(subDivLogLevel, subDivLogId, subDivDescription, subDivUserIdIp, subDivDate)
    divLoggingGlob.append(divLogging)
    divListLogging.append(divLoggingGlob)
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

function showLogs() {
    if (!showedL) {
        popUplistLogs.classList.remove("hidden");
        showedL = true;

        document.addEventListener('click', handleClickOutsideL);
    } else {
        popUplistLogs.classList.add("hidden");
        showedL = false;

        document.removeEventListener('click', handleClickOutsideL);
    }

    //on enlève les logging retournés par la dernière requete
    deleteChildNodes(listLogging)
}

function handleClickOutsideL(event) {
    if (!contentLogs.contains(event.target)) {
        popUplistLogs.classList.add("hidden");
        showedL = false;

        document.removeEventListener('click', handleClickOutsideL);
    }
}

function showUsers() {
    if (!showed) {
        popUplistUsers.classList.remove("hidden");
        showed = true;

        document.addEventListener('click', handleClickOutside);
    } else {
        popUplistUsers.classList.add("hidden");
        showed = false;

        document.removeEventListener('click', handleClickOutside);
    }

    //on enlève les users retournés par la dernière requete
    deleteChildNodes(listUsers)
}

function handleClickOutside(event) {
    if (!contentUsers.contains(event.target)) {
        popUplistUsers.classList.add("hidden");
        showed = false;

        document.removeEventListener('click', handleClickOutside);


    }
}

function deleteChildNodes(fatherNode){
    while (fatherNode.hasChildNodes()){
        fatherNode.removeChild(fatherNode.firstChild)
    }
}