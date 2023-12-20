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

//intervalle de temps pour rafraichir les stats du cluster hat
let intertalTimeGetStatsClusterHat = 10000

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

function init(){
    btnShowUsers.addEventListener('click', function(event) {
        event.stopPropagation();
    });

    btnShowLogs.addEventListener('click', function(event) {
        event.stopPropagation();
    });

    var showedL = false;

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

    //on load les stats du site au chargement de la page
    requestSetStatsSite()
    //console.log(listStatsClusterHat)

    //on load les stats du cluster hat toutes les n secondes
    //requestGetStatsClusterHat()
    //let timerRequestGetStatsClusterHat = setInterval(requestGetStatsClusterHat, intertalTimeGetStatsClusterHat)
}

function changeStatsBasedTimeFilter(){
    //on récupère l'id du time filter sélectionné, et on change le current time filter
    let timeFilterSelected = this.control.id
    currentTimeFilterValue = getTimeFilterValueFromKey(timeFilterSelected)

    //on appelle une autre méthode pour changer les stats en fonction du time filter
    requestSetStatsSite()
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

    console.log("Date de début : " + startDate)
    console.log("Date de fin : " + endDate)

    //on crée et exécute une requête js vers un script php pour récupérer les stats du site en fonction du filtre de temps
    let requestGetStatsSite = new XMLHttpRequest()
    requestGetStatsSite.open("POST","script_get_stats_site.php");
    requestGetStatsSite.setRequestHeader("Content-Type","application/json-charset=utf-8");
    requestGetStatsSite.send(JSON.stringify({"timeFilter":[startDate, endDate]}))

    requestGetStatsSite.onreadystatechange = resultRequestGetStatsSite
}

function requestGetStatsClusterHat(){
    //on crée et exécute une requête js vers un script php pour récupérer les stats du cluster hat en fonction
    let requestGetStatsSite = new XMLHttpRequest()
    requestGetStatsSite.open("POST","script_get_stats_cluster_hat.php");
    requestGetStatsSite.setRequestHeader("Content-Type","application/json-charset=utf-8");
    requestGetStatsSite.send()

    requestGetStatsSite.onreadystatechange = resultRequestGetStatsClusterHat
}

function requestResearchUsersOrLogging(){
    let fieldToSearch
    let stringToSearch
    let classResearched

    //on regarde quel bouton a été appuyé
    if (this.id === buttonSubmitResearchUsers.id){
        fieldToSearch = attributeSelectedUserResearch.value
        stringToSearch = researchBarUsers.value
        classResearched = buttonSubmitResearchUsers.name
    }
    else{
        fieldToSearch = attributeSelectedLoggingResearch.value
        stringToSearch = researchBarLogging.value
        classResearched = buttonSubmitResearchLogging.name
    }

    //on crée et exécute une requête js vers un script php pour rechercher des users ou logging en fonction d'un attribut sélectionné
    let requestGetStatsSite = new XMLHttpRequest()
    requestGetStatsSite.open("POST","script_get_stats_cluster_hat.php");
    requestGetStatsSite.setRequestHeader("Content-Type","application/json-charset=utf-8");
    requestGetStatsSite.send(JSON.stringify({"fieldToSearch": fieldToSearch, "stringToSeaerch" : stringToSearch, "classResearched" : classResearched}))

    requestGetStatsSite.onreadystatechange = resultRequestResearchUsersOrLogging
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

        //s'il y a eu une erreur lors de la connexion à la bd, on affiche nul pour chaque stat
        if (connBd.error == 1){
            newStatNbUsers = 'null'
            newStatNbVisits = 'null'
            newStatNbModuleUses = 'null'
        }
        else{
            //pour chaque requete de statistiques, on regarde s'il y a une erreur
            if (resultRequestGetNbUsers.error == 0)
                newStatNbUsers = resultRequestGetNbUsers.result
            else
                newStatNbUsers = 'null'

            if (resultRequestGetNbVisits.error == 0)
                newStatNbVisits = resultRequestGetNbVisits.result["USER"] + resultRequestGetNbVisits.result["VISITEUR"]
            else
                newStatNbVisits = 'null'

            if (resultRequestGetNbModuleUses.error == 0)
                newStatNbModuleUses = resultRequestGetNbModuleUses.result
            else
                newStatNbModuleUses = 'null'
        }
        //console.log(newStatNbUsers + " " + newStatNbVisits + " " + newStatNbModuleUses)
        //console.log(resultRequestGetNbVisits.result)

        //on met à jour chaque element stat du site
        elemNbUsers.innerHTML = newStatNbUsers
        elemNbVisits.innerHTML = newStatNbVisits
        elemNbModuleUsers.innerHTML = newStatNbModuleUses

        //enfin, on met a jour le camembert de la repartition des connexions
        //console.log(chartBar)
        if (chartBar != null)
            chartBar.destroy()

        let configPie = configChartBarCanva(resultRequestGetNbVisits.result["USER"], resultRequestGetNbVisits.result["VISITEUR"])
        chartBar = new Chart(document.getElementById("chartPie"), configPie);
        chartBar.render()
    }
}

function resultRequestGetStatsClusterHat(){
    if (this.readyState === 4 && this.status === 200) {
        let resultScript = this.response
        //console.log(resultScript)

        let resultScriptParsed = JSON.parse(resultScript)
        //console.log(resultScriptParsed)

        let objectStatsClusterHat = resultScriptParsed.result
        //console.log(listStatsClusterHat)

        //pour chaque rpi du cluster hat, on met à jour les statistiques
        for (let i=0;i<listStatsClusterHat.length;i++){
            let statsRpi = objectStatsClusterHat[i]
            let statCpuPourcent = (statsRpi.cpuUsage)
            let statCpuFrequency = statsRpi.cpuFrequency
            let statMemUsedPourcent = ((parseInt(statsRpi.memUsed) / parseInt(statsRpi.memTotal)) * 100).toPrecision(4)
            let statMemUsed = parseInt(statsRpi.memUsed) * Math.pow(10,-3)
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

}

function resultRequestResearchUsersOrLogging(){
    if (this.readyState === 4 && this.status === 200) {
        //on récupère le résultat du script
        let resultScript = this.response
        //console.log(resultScript)

        let resultScriptParsed = JSON.parse(resultScript)

        //on regarde si une erreur a été renvoyée
        if (resultScriptParsed.error === 0){
            let listResults = resultScriptParsed.result

            //on récupère la classe de recherche qui sera l'identifiant de l'élément html qui va stocker cette liste d'objets serialisés
            let htmlElemclassResearched = listResults.classResearched

            //et on récupère la liste des objets de cette classe sérialisé
            let listObjectSerialised = listResults.listObjectSerialised

            //on ajoute chaque objet serialisé dans l'objet html en fonction de sa classe
            for (let i=0;i<listObjectSerialised.length;i++){
                if (htmlElemclassResearched === "Users")
                    createHtmlElementForSerialisedUsers(listUsers, listObjectSerialised[i])
                else
                    createHtmlElementForSerialisedLogging(listLogging, listObjectSerialised[i])
            }
        }
        else{
            console.log("Erreur : " + resultScriptParsed.errorMessage)
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
    subDivLogin.setAttribute("class", "mr-20 text-lg font-bold text-white")

    //div contenant l'ID du user
    let subDivId = document.createElement("div")
    let spanDivId = document.createElement("span")
    spanDivId.setAttribute("class", "mr-2")
    spanDivId.appendChild(spanDivId)

    //div contenant le mail du user
    let subDivMail = document.createElement("div")
    subDivMail.setAttribute("class", "text-lg font-bold text-white")

    //div contenant le nom et prénom du user
    let subDivFirstNameLastName = document.createElement("div")
    let spanLastName = document.createElement("span")
    let spanFirstName = document.createElement("span")
    spanLastName.setAttribute("class", "mr-2")
    spanFirstName.setAttribute("class", "mr-2")
    subDivFirstNameLastName.append(spanLastName, spanFirstName)

    //div pour la date d'inscription du user
    let subDivRegistrationDate = document.createElement("div")
    subDivRegistrationDate.setAttribute("class", "text-lg font-bold text-white")

    //icone pour supprimer un user
    let iconeDeleteUser = document.createElement("ion-icon")
    iconeDeleteUser.setAttribute("name", "trash")
    iconeDeleteUser.setAttribute("class", "w-7 h-7 text-red-700 absolute right-2 cursor-pointer")

    //on ajoute une chaine de caractère pour chaque span et div
    spanDivId.innerHTML = "ID : " + userSerialised.userId
    subDivMail.innerHTML = "Adresse mail : " + userSerialised.userMail
    spanLastName.innerHTML = "Nom : " + userSerialised.lastName
    spanFirstName.innerHTML = "Prénom : " + userSerialised.firstName
    subDivRegistrationDate.innerHTML = "Inscription : " + userSerialised.registrationDate

    //on ajoute les différents éléments div dans l'élément div
    divUser.append(subDivLogin, subDivId, subDivMail, subDivFirstNameLastName, subDivRegistrationDate, iconeDeleteUser)
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
    subDivLogId.setAttribute("class", "text-xs text-white")
    spanLogId.setAttribute("class", "mr-2")
    subDivLogId.appendChild(spanLogId)

    //div pour la description du logging
    let subDivDescription = document.createElement("div")
    subDivDescription.setAttribute("class", "text-lg font-bold text-white mr-20")

    //div pour le user id et l'ip du logging
    let subDivUserIdIp = document.createElement("div")
    let spanUserId = document.createElement("span")
    let spanIp = document.createElement("span")
    subDivUserIdIp.setAttribute("class", "text-xs text-white")
    spanUserId.setAttribute("class", "mr-2")
    spanIp.setAttribute("class", "mr-2")
    subDivUserIdIp.append(spanUserId, spanIp)

    //div pour la date du logging
    let subDivDate = document.createElement("div")
    subDivDate.setAttribute("class", "text-lg font-bold text-white absolute right-2")

    //on ajoute une chaine de caractère pour chaque span et div
    subDivLogLevel.innerHTML = "ID : " + loggingSerialised.logLevel
    spanLogId.innerHTML = "Adresse mail : " + loggingSerialised.logId
    subDivDescription.innerHTML = "Nom : " + loggingSerialised.description
    spanUserId.innerHTML = "Prénom : " + loggingSerialised.userId
    spanIp.innerHTML = "Inscription : " + loggingSerialised.ip
    subDivDate.innerHTML = "Inscription : " + loggingSerialised.date

    //on ajoute les différents éléments div dans l'élément div
    divLogging.append(subDivLogLevel, subDivLogId, subDivDescription, subDivDate)
    divLoggingGlob.append(divLogging)
    divListLogging.append(divLoggingGlob)
}

function showLogs() {
    console.log("click");
    if (!showedL) {
        popUplistLogs.classList.remove("hidden");
        showedL = true;

        document.addEventListener('click', handleClickOutsideL);
    } else {
        popUplistLogs.classList.add("hidden");
        showedL = false;

        document.removeEventListener('click', handleClickOutsideL);
    }
}

function handleClickOutsideL(event) {

    if (!contentLogs.contains(event.target)) {
        popUplistLogs.classList.add("hidden");
        showedL = false;

        document.removeEventListener('click', handleClickOutsideL);
    }
}

function showUsers() {
    console.log("click");
    if (!showed) {
        popUplistUsers.classList.remove("hidden");
        showed = true;

        document.addEventListener('click', handleClickOutside);
    } else {
        popUplistUsers.classList.add("hidden");
        showed = false;

        document.removeEventListener('click', handleClickOutside);
    }
}

function handleClickOutside(event) {

    if (!contentUsers.contains(event.target)) {
        popUplistUsers.classList.add("hidden");
        showed = false;

        document.removeEventListener('click', handleClickOutside);
    }
}