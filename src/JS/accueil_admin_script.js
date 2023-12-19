window.onload = init

const enumTimeFilters = {
    jour: 0,
    semaine:1,
    mois:2,
    tout:3
}

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

function init(){
    //on récupère les différents éléments html stockant les stats du site, du cluster hat et champs select
    elemNbUsers = document.getElementById("nb-users")
    elemNbVisits = document.getElementById("nb-visits")
    elemNbModuleUsers = document.getElementById("nb-module-uses")
    listStatsClusterHat = Array.from(document.getElementById("tbody-table-stats").children)
    listTimeFilters = Array.from(document.getElementsByClassName("time-filter"))

    //par défaut on met le time filter value a 0 (== défaut)
    currentTimeFilterValue = enumTimeFilters.jour
    //console.log(listTimeFilters)

    //on associé des événements onclick pour les time filters des stats
    listTimeFilters.forEach((timeFilter) => timeFilter.onclick = changeStatsBasedTimeFilter)

    //on load les stats du site au chargement de la page
    requestSetStatsSite()
    //console.log(listStatsClusterHat)

    //on load les stats du cluster hat toutes les n secondes
    requestGetStatsClusterHat()
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

    //console.log("Date de début : " + startDate)
    //console.log("Date de fin : " + endDate)

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