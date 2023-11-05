window.onload = init

//élément dans lequel on affiche des messages pour l'utilisateur
let messageForUser

//élément dans lequel l'utilisateur entre le mail a recherche dans la page
let researchBar

//élément dans lequel on stocke la liste des users trouvés à l'issue de la recherche
let ulUsersList

//bouton d'action dans la page pour lancer la recherche
let buttonSubmitUserSearch

//sert à la pagination, on affiche 5 users par page, pour la partie recherche d'utilisateurs
let nbUsersSearchPerPage = 5

//élément html permettant d'afficher à l'utilisateur sur quelle page il est
let indicePaginationUserSearch

//objet permettant de stocker les pages et la pagination
let usersSearched

//div contenant les bouttons pour naviguer entre les page de la pagination de la recherche de users
let divButtonsPaginationUsersSearched

//input de sélection d'une page selon son indice
let inputGoToPage

//bouttons pour naviguer dans la pagination de la recherche de users
let buttonPreviousPage
let buttonNextPage
let buttonFirstPage
let buttonLastPage

class ResultPagination {
    #usersList
    #pagination
    //#endOfPagination = false
    #currentPage = 0
    //#lastPageSeen = 0
    #endPage = 0
    #mailSearch

    constructor(parPagination) {
        this.#pagination = parPagination
        this.#usersList = []
    }

    get usersList(){
        return this.#usersList
    }

    get currentPage(){
        return this.#currentPage
    }

    get pagination(){
        return this.#pagination
    }

    get endPage(){
        return this.#endPage
    }

    get mailSearch(){
        return this.#mailSearch
    }

    set currentPage(parCurrentPage){
        this.#currentPage = parCurrentPage
    }

    set mailSearch(parMailSearch){
        this.#mailSearch = parMailSearch
    }

    set endPage(parEndPage){
        this.#endPage = parEndPage
    }

    configureListUsersForNextPages(parNbUsersTotal){
        //on connait le nombre total de users pour cette recherche, on agrandit usersList, pour qu'il puisse stocker les n users des prochaines pagination avec des null
        for (let i=0;parNbUsersTotal-this.#usersList.length;i++){
            this.#usersList.push(null)
        }

        //on indique aussi la dernière page pour cette pagination
        if (parNbUsersTotal < this.#pagination.offset)
            this.#endPage = 0
        else
            this.endPage = Math.floor(parNbUsersTotal / this.#pagination.offset) + parNbUsersTotal % this.#pagination.offset - 1
        //console.log("Set de l'indice de la  page end : " + this.#endPage)
        //console.log(parNbUsersTotal / this.#pagination.offset)
        //console.log(parNbUsersTotal % this.#pagination.offset - 1)
    }

    addPage(listUsersToAdd, indicePage){
        //console.log(this)
        //console.log("Ajout de la page avec indice debut : " + indicePage)
        //console.log("Liste des users de la nouvelle page")
        //console.log(listUsersToAdd)

        //on ajoute les nouveaux users au bon endroit dans la page
        let indiceDebutPage = indicePage * this.#pagination.limit
        for (let i=0;i<listUsersToAdd.length;i++){
            this.#usersList[indiceDebutPage+i] = listUsersToAdd[i]
        }

        //console.log("addPage limit : " + this.#pagination.limit + " | offset : " + this.#pagination.offset)
    }

    getPage(indicePage){
        //console.log(this)
        //console.log("Get de la page avec indice :" + indicePage)
        let listeUsersOnPage = []
        let debut = indicePage * this.#pagination.limit
        let fin = debut + this.#pagination.limit

        //on vérifie qu'on n'est pas sorti de la liste
        if (fin > this.#usersList.length)
            fin = this.#usersList.length

        //console.log("Debut : " + debut + "\nfin : " + fin)

        //on récupère la liste des users pour cette page
        for (let i=debut;i<fin;i++){
            //console.log(i)
            listeUsersOnPage.push(this.#usersList[i])
        }

        //on update la page actuelle
        this.#currentPage = indicePage

        return listeUsersOnPage
    }

    isPageExists(indicePage){
        //on regarde si la page existe avec cet indice, i.e s'il y a au moins 1 user dans cette page
        let debut = indicePage * this.#pagination.limit

        //console.log("On regarde si la page existe")
        //console.log(indicePage)
        //console.log("Indice de début de page : " + debut)

        return debut < this.#usersList.length && this.#usersList[debut] !== null;
    }
}

class User{
    #userMail
    #lastName
    #firstName

    constructor(parUserMail, parLastName, parFirstName) {
        this.#userMail = parUserMail
        this.#lastName = parLastName
        this.#firstName = parFirstName
    }

    get userMail(){
        return this.#userMail
    }

    getLastName(){
        return this.#lastName
    }

    get firstName(){
        return this.#firstName
    }
}

class Message{
    #userMailSender
    #userMailReceiver
    #message
    #messageDate
    #messageDateMilliSeconds

    constructor(parUserMailSender, parUserMailReceiver, parMessage, parMessageDate, parMessageDateMilliSeconds) {
        this.#userMailSender = parUserMailSender
        this.#userMailReceiver = parUserMailReceiver
        this.#message = parMessage
        this.#messageDate = parMessageDate
        this.#messageDateMilliSeconds = parMessageDateMilliSeconds
    }

    get userMailSender(){
        return this.#userMailSender
    }

    get userMailReceiver(){
        return this.#userMailReceiver
    }

    get message(){
        return this.#message
    }

    get messageDate(){
        return this.#messageDate
    }

    get messageDateMilliSeconds(){
        return this.#messageDateMilliSeconds
    }
}

class Pagination{
    #limit
    #offset

    constructor(parLimit, parOffset) {
        this.#limit = parLimit
        this.#offset = parOffset
    }

    get limit(){
        return this.#limit
    }

    get offset(){
        return this.#offset
    }

    set limit(newLimit){
        this.#limit = newLimit
    }

    set offset(newOffset){
        this.#offset = newOffset
    }

    incrementPagination(){
        this.#offset += this.#limit
    }

    decrementPagination(){
        this.#offset -= this.#limit
    }
}


function init(){
    //on récupère les différents éléments dans la page pour pouvoir effectuer la recherche
    researchBar = document.getElementById("input_recherche_user")
    buttonSubmitUserSearch = document.getElementById("submitRechercheUser")
    ulUsersList = document.getElementById("ul_users_recherches")
    messageForUser = document.getElementById("message_pour_user")
    divButtonsPaginationUsersSearched = document.getElementById("div_navigation_pagination_recherche_user")
    indicePaginationUserSearch = document.getElementById("p_indice_pagination_recherche_users")
    inputGoToPage = document.getElementById("input_indice_page_recherche_user")

    buttonFirstPage = document.getElementById("button_first_page")
    buttonPreviousPage = document.getElementById("button_previous_page")
    buttonNextPage = document.getElementById("button_next_page")
    buttonLastPage = document.getElementById("button_last_page")


    //on lance la recherche d'utilisateurs quand on appuie sur le bon boutton de recherche
    buttonSubmitUserSearch.onclick = lancerActionRechercheUsers

    //on lance aussi la recherche quand il appuie sur la touche entrée dans la barre de recherche
    researchBar.addEventListener('keyup', function(event) {
            if (event.code === 'Enter')
            {
                event.preventDefault();
                buttonSubmitUserSearch.click();
            }
        });

    //on initialise une variable pour sotcker les users recherchés et la pagination
    let pagination = new Pagination(nbUsersSearchPerPage, 0)
    usersSearched = new ResultPagination(pagination)
}

function lancerActionRechercheUsers(){
    //on récupère le mail saisi dans la barre de recherche
    let chaineSaisieMail = researchBar.value

    //on supprime les espaces et on vérifie que le champ n'est pas vide
    chaineSaisieMail = chaineSaisieMail.trim()
    if (chaineSaisieMail.length < 0){
        displayMessage(messageForUser, "Not empty !", 1)
    }

    //on remet a zero les objets liés à la pagination
    let pagination = new Pagination(nbUsersSearchPerPage, 0)
    usersSearched = new ResultPagination(pagination)

    //on enregistre dans l'objet le mail recherché
    usersSearched.mailSearch = chaineSaisieMail

    //console.log("Chaine saisie par le user : " + usersSearched.mailSearch)

    //on lance une requete vers un script php pour sélectionner les mails contenant la chaine entrée par le user, et pour retourner le nombre total d'utilisateurs
    let requeteSelectFirstPage = new XMLHttpRequest()
    requeteSelectFirstPage.open("POST","script_recherche_users_par_mail.php");
    requeteSelectFirstPage.setRequestHeader("Content-Type","application/json-charset=utf-8");
    //console.log(usersSearched)
    requeteSelectFirstPage.send(JSON.stringify({"chaineSaisie" : usersSearched.mailSearch, "pagination" : [usersSearched.pagination.limit, usersSearched.pagination.offset], "modeExec" : "1"}))

    requeteSelectFirstPage.onreadystatechange = traitementRequeteRecherchePremierePageUsers
}

function traitementRequeteRecherchePremierePageUsers(){
    //on attend que la requete s'est exécutée sans erreur
    if (this.readyState === 4 && this.status === 200) {
        let reponse = this.response
        let reponseJson
        console.log(reponse)

        //on essaie de le parser
        try{
            reponseJson = JSON.parse(reponse)
        }
        catch (e){
            console.log(e)
        }
        //console.log("On va parcourir la liste des users trouvés")
        //console.log(reponseJson)

        //on regarde si le script a envoyé une erreur ou un objet
        if (typeof(reponseJson) == "object"){
            let listeUsers = JSON.parse(reponseJson["listeUsers"])
            let nbUsersTotal = reponseJson["numberOfUsers"]

            //on mappe la liste des users retournés à des objets js user
            let listUserObjects = mapJsonUsersToUserObjects(listeUsers)

            //on affiche l'élément pour naviguer entre les pages de la pagination
            if (divButtonsPaginationUsersSearched.style.display === ""){
                divButtonsPaginationUsersSearched.style.display = "flex"
            }

            //on update la pagination
            usersSearched.pagination.offset += usersSearched.pagination.limit

            //on ajoute la liste des users dans l'objet rechercheUsers
            let indicePage = (usersSearched.currentPage) * usersSearched.pagination.limit
            usersSearched.addPage(listUserObjects, indicePage)

            //on connait le nombre total de users pour cette recherche, on stocke cet info et on agrandit la liste des users
            usersSearched.configureListUsersForNextPages(nbUsersTotal)

            //console.log("traitementRequeteRechercheUsers : " + usersSearched.currentPage)

            //on affiche la liste des users dans l'élément html
            //usersSearched.incrementPage()
            let page = usersSearched.getPage(usersSearched.currentPage)
            //console.log(page)
            displayUsersPage(page)

            //on indique à l'utilisateur sur quelle page il est
            indicePaginationUserSearch.innerHTML = usersSearched.currentPage + 1


            //on vérifie s'il faut désasctiver les boutons en fonction de notre position dans la pagination
            checkButtonsInPagination()
        }
        else{
            displayMessage(messageForUser, "Internal error occured, please retry", 1)
        }
    }
}

function buttonSwitchPageUsersSearch(mode){
    //console.log("On clique sur le bouton avec comme mode : " + mode)
    //console.log(usersSearched)
    //console.log(usersSearched.currentPage)
    //console.log(usersSearched.lastPageSeen + "\n\n")

    let currentPage = usersSearched.currentPage
    let listUsersOnPage = []
    let notARequest = false

    if (mode === 0){
        //on va à la première page
        let indiceFirstPage = 0
        notARequest = true
        listUsersOnPage = usersSearched.getPage(indiceFirstPage)
    }

    else if (mode === 1){
        //on va à la page précédente

        //on regarde si la page précédente existe, si les users de la page précédente ont déja été récupérés
        if (usersSearched.isPageExists(currentPage-1) === true){
            notARequest = true
            listUsersOnPage = usersSearched.getPage(currentPage-1)
        }
        else{
            //on n'a pas les users de la page précédente, on exécute un script php pour récuper les users précédents
            //console.log("Requete pour sélectionner la nouvelle page")

            //on change l'offset de la pagination pour pouvoir récupérer la page de users précédent
            usersSearched.pagination.offset = (usersSearched.currentPage-1) * usersSearched.pagination.limit

            let requete = new XMLHttpRequest()
            requete.open("POST","script_recherche_users_par_mail.php");
            requete.setRequestHeader("Content-Type","application/json-charset=utf-8");
            //console.log('Chaine : ' + usersSearched.mailSearch)
            requete.send(JSON.stringify({"chaineSaisie" : usersSearched.mailSearch, "pagination" : [usersSearched.pagination.limit, usersSearched.pagination.offset], "modeExec" : "0"}))

            requete.onreadystatechange = traitementRequeteRechercheUsersPreviousPage
        }
    }
    else
        if (mode === 2){
        //on va à la page suivante

        //on regarde si la page suivante existe, si les users de la page suivante ont déja été récupérés
        if (usersSearched.isPageExists(currentPage+1) === true){
            //console.log("On sélectionne la page suivante déjà existente")
            notARequest = true
            listUsersOnPage = usersSearched.getPage(currentPage+1)
        }
        else{
            //on n'a pas les users de la page suivante, on exécute un script php pour récupérer la page de users suivante
            //console.log("Requete pour sélectionner la nouvelle page")
            //console.log(usersSearched.pagination)

            //on change l'offset de la pagination pour pouvoir récupérer la page de users précédent
            usersSearched.pagination.offset = (usersSearched.currentPage+1) * usersSearched.pagination.limit

            let requete = new XMLHttpRequest()
            requete.open("POST","script_recherche_users_par_mail.php");
            requete.setRequestHeader("Content-Type","application/json-charset=utf-8");
            //console.log('Chaine : ' + usersSearched.mailSearch)
            requete.send(JSON.stringify({"chaineSaisie" : usersSearched.mailSearch, "pagination" : [usersSearched.pagination.limit, usersSearched.pagination.offset], "modeExec" : "0"}))

            requete.onreadystatechange = traitementRequeteRechercheUsersNextPage
        }

    }

    else if (mode === 3){
        //on regarde si la dernière page existe, si les users de la dernière page ont déja été récupérés
        let indiceLastPage = usersSearched.endPage
        if (usersSearched.isPageExists(indiceLastPage) === true){
            notARequest = true
            listUsersOnPage = usersSearched.getPage(indiceLastPage)
        }

        else{
            //on exécute une requete vers un script php pour récupérer la dernière page
            //console.log("Requete pour sélectionner la dernière page")

            //on change l'offset de la pagination pour pouvoir récupérer la derniere page de users
            usersSearched.pagination.offset = usersSearched.endPage * usersSearched.pagination.limit

            //console.log("Limit : " + usersSearched.pagination.limit)
            //console.log("Offset : " + usersSearched.pagination.offset)

            let requete = new XMLHttpRequest()
            requete.open("POST","script_recherche_users_par_mail.php");
            requete.setRequestHeader("Content-Type","application/json-charset=utf-8");
            //console.log('Chaine : ' + usersSearched.mailSearch)
            requete.send(JSON.stringify({"chaineSaisie" : usersSearched.mailSearch, "pagination" : [usersSearched.pagination.limit, usersSearched.pagination.offset], "modeExec" : "0"}))

            requete.onreadystatechange = traitementRequeteRechercheUsersLastPage
        }
    }

    //comme les requetes vers les scripts php sont asynchronnes, on décide d'update ici seulement si on a n'a envoyé de requete
    if (notARequest === true){
        //on update la page actuelle et on indique à l'utilisateur sur quelle page il est
        indicePaginationUserSearch.innerHTML = usersSearched.currentPage + 1

        //on ajoute les nouveaux users de la nouvelle page
        displayUsersPage(listUsersOnPage)

        //on vérifie s'il faut désasctiver les boutons en fonction de notre position dans la pagination
        checkButtonsInPagination()
    }
}

function traitementRequeteRechercheUsersPreviousPage(){
    //on attend que la requete s'est exécutée sans erreur
    if (this.readyState === 4 && this.status === 200) {
        let reponse = this.response
        let reponseJson
        //console.log(reponse)

        //on essaie de le parser
        try{
            reponseJson = JSON.parse(reponse)
        }
        catch (e){
            console.log(e)
        }
        //console.log("On va parcourir la liste des users trouvés")
        //console.log(reponseJson)

        //on regarde si le script a envoyé une erreur ou un objet
        if (typeof(reponseJson) == "object"){
            let listeUsers = JSON.parse(reponseJson["listeUsers"])

            //on mappe la liste des users retournés à des objets js user
            let listUserObjects = mapJsonUsersToUserObjects(listeUsers)

            //console.log("Liste users recus : ")
            //console.log(listUserObjects)

            //on ajoute les nouveaux users de la nouvelle page
            let indicePage = usersSearched.currentPage - 1
            usersSearched.addPage(listUserObjects, indicePage)
            //console.log(usersSearched)

            //on récupère la dernière page de users
            let listUsersOnPage = usersSearched.getPage(indicePage)

            displayUsersPage(listUsersOnPage)

            //on indique à l'utilisateur sur quelle page il est
            indicePaginationUserSearch.innerHTML = usersSearched.currentPage + 1

            //on vérifie s'il faut désasctiver les boutons en fonction de notre position dans la pagination
            checkButtonsInPagination()
        }

        else{
            displayMessage(messageForUser, "Internal error occured, please retry", 1)
        }
    }
}

function traitementRequeteRechercheUsersLastPage(){
    //on attend que la requete s'est exécutée sans erreur
    if (this.readyState === 4 && this.status === 200) {
        let reponse = this.response
        let reponseJson
        //console.log(reponse)

        //on essaie de le parser
        try{
            reponseJson = JSON.parse(reponse)
        }
        catch (e){
            console.log(e)
        }
        //console.log("On va parcourir la liste des users trouvés")
        //console.log(reponseJson)

        //on regarde si le script a envoyé une erreur ou un objet
        if (typeof(reponseJson) == "object"){
            let listeUsers = JSON.parse(reponseJson["listeUsers"])

            //on mappe la liste des users retournés à des objets js user
            let listUserObjects = mapJsonUsersToUserObjects(listeUsers)

            //console.log("Liste users recus : ")
            //console.log(listUserObjects)

            let indiceLastPage = usersSearched.endPage
            //on ajoute les nouveaux users de la nouvelle page
            usersSearched.addPage(listUserObjects, indiceLastPage)
            //console.log(usersSearched)

            //on récupère la dernière page de users
            let listUsersOnPage = usersSearched.getPage(indiceLastPage)

            displayUsersPage(listUsersOnPage)

            //on indique à l'utilisateur sur quelle page il est
            indicePaginationUserSearch.innerHTML = usersSearched.currentPage + 1

            //on vérifie s'il faut désasctiver les boutons en fonction de notre position dans la pagination
            checkButtonsInPagination()
        }

        else{
            displayMessage(messageForUser, "Internal error occured, please retry", 1)
        }
    }
}

function traitementRequeteRechercheUsersNextPage(){
    //on attend que la requete s'est exécutée sans erreur
    if (this.readyState === 4 && this.status === 200) {
        let reponse = this.response
        let reponseJson
        //console.log(reponse)

        //on essaie de le parser
        try{
            reponseJson = JSON.parse(reponse)
        }
        catch (e){
            console.log(e)
        }
        //console.log("On va parcourir la liste des users trouvés")
        //console.log(reponseJson)

        //on regarde si le script a envoyé une erreur ou un objet
        if (typeof(reponseJson) == "object"){
            let listeUsers = JSON.parse(reponseJson["listeUsers"])

            //on mappe la liste des users retournés à des objets js user
            let listUserObjects = mapJsonUsersToUserObjects(listeUsers)

            //console.log("Liste users recus : ")
            //console.log(listUserObjects)
            //console.log(usersSearched.currentPage)

            //on update la pagination
            usersSearched.pagination.offset += usersSearched.pagination.limit

            //on ajoute les nouveaux users de la nouvelle page
            let indiceDebutPage = usersSearched.currentPage+1
            usersSearched.addPage(listUserObjects, indiceDebutPage)
            //console.log(usersSearched)

            //on récupère la dernière page de users
            let listUsersOnPage = usersSearched.getPage(indiceDebutPage)

            displayUsersPage(listUsersOnPage)

            //on indique à l'utilisateur sur quelle page il est
            indicePaginationUserSearch.innerHTML = usersSearched.currentPage + 1

            //on vérifie s'il faut désasctiver les boutons en fonction de notre position dans la pagination
            checkButtonsInPagination()
        }

        else{
            displayMessage(messageForUser, "Internal error occured, please retry", 1)
        }
    }
}

function goToPage(){
    //on récupère la valeur contenue dans l'input de sélection d'une page
    let chaineIndicePageSaisi = inputGoToPage.value
    //console.log("GoToPage avec indice : " + chaineIndicePageSaisi)

    let indicePageSaisi = parseInt(chaineIndicePageSaisi)

    //on vérifie que l'indice entrée par le user est bien un integer
    if (!isNaN(indicePageSaisi)){

        //on vérifie que le user a déjà entré un mail a recherche
        //l'indice entrée doit etre >= 1 et <= endPage pour la recherche
        if (usersSearched.mailSearch != null && indicePageSaisi >= 1 && indicePageSaisi <= (usersSearched.endPage+1)){
            indicePageSaisi -= 1
            //on regarde si la page existe
            if (usersSearched.isPageExists(indicePageSaisi) === true){
                let listUsersOnPage = usersSearched.getPage(indicePageSaisi)

                //on update la page actuelle et on indique à l'utilisateur sur quelle page il est
                indicePaginationUserSearch.innerHTML = usersSearched.currentPage + 1

                //on ajoute les nouveaux users de la nouvelle page
                displayUsersPage(listUsersOnPage)

                //on vérifie s'il faut désasctiver les boutons en fonction de notre position dans la pagination
                checkButtonsInPagination()
            }

            else{
                //la page n'existe pas, on effectue une requete pour obtenir la page souhaitée
                //console.log("Requete pour sélectionner une page")

                //on change l'offset de la pagination pour pouvoir récupérer la derniere page de users
                usersSearched.pagination.offset = indicePageSaisi * usersSearched.pagination.limit

                //on update la prochaine page actuelle
                usersSearched.currentPage = indicePageSaisi

                //console.log("Limit : " + usersSearched.pagination.limit)
                //console.log("Offset : " + usersSearched.pagination.offset)

                let requete = new XMLHttpRequest()
                requete.open("POST","script_recherche_users_par_mail.php");
                requete.setRequestHeader("Content-Type","application/json-charset=utf-8");
                //console.log('Chaine : ' + usersSearched.mailSearch)
                requete.send(JSON.stringify({"chaineSaisie" : usersSearched.mailSearch, "pagination" : [usersSearched.pagination.limit, usersSearched.pagination.offset], "modeExec" : "0"}))

                requete.onreadystatechange = traitementRequeteRechercheUsersPage
            }
        }
        else{
            //console.log("Indice de page incorrect")
        }
    }
    else{
        //console.log("Indice entré invalide")
    }

}

function traitementRequeteRechercheUsersPage(){
    //on attend que la requete s'est exécutée sans erreur
    if (this.readyState === 4 && this.status === 200) {
        let reponse = this.response
        let reponseJson
        console.log(reponse)

        //on essaie de le parser
        try{
            reponseJson = JSON.parse(reponse)
        }
        catch (e){
            console.log(e)
        }
        //console.log("On va parcourir la liste des users trouvés")
        console.log(reponseJson)

        //on regarde si le script a envoyé une erreur ou un objet
        if (typeof(reponseJson) == "object"){
            let listeUsers = JSON.parse(reponseJson["listeUsers"])

            //on mappe la liste des users retournés à des objets js user
            let listUserObjects = mapJsonUsersToUserObjects(listeUsers)

            //console.log("Liste users recus : ")
            //console.log(listUserObjects)

            //on ajoute les nouveaux users de la nouvelle page
            usersSearched.addPage(listUserObjects, usersSearched.currentPage)
            //console.log(usersSearched)

            //on récupère la dernière page de users
            let listUsersOnPage = usersSearched.getPage(usersSearched.currentPage)

            displayUsersPage(listUsersOnPage)

            //on indique à l'utilisateur sur quelle page il est
            indicePaginationUserSearch.innerHTML = usersSearched.currentPage + 1

            //on vérifie s'il faut désasctiver les boutons en fonction de notre position dans la pagination
            checkButtonsInPagination()
        }

        else{
            displayMessage(messageForUser, "Internal error occured, please retry", 1)
        }
    }
}


function checkButtonsInPagination(){
    let indiceCurrentPage = usersSearched.currentPage
    //let lastPageSeen = usersSearched.lastPageSeen
    let indiceLastPage = usersSearched.endPage
    //let endOfPagin = usersSearched.endOfPagination

    //console.log("Fonction checkButtons")
    //console.log("\tcurrentPage : " + indiceCurrentPage)
    //console.log("\tlastPage : " + indiceLastPage)
    //console.log("\tendOfPagin : " + endOfPagin)


    //on rend cliquable le bouton de la page précédente et de la premiere page si on n'est plus au début
    if (indiceCurrentPage > 0){
        //console.log("On desable le bouton previous et first")
        buttonPreviousPage.disabled = false
        buttonFirstPage.disabled = false
    }

    //on rend non cliquable le bouton de la page précédente et de la premiere page si on est au début
    else if (indiceCurrentPage === 0){
        //console.log("On enable le bouton previous et first")
        buttonPreviousPage.disabled = true
        buttonFirstPage.disabled = true
    }

    //on rend cliquable le bouton de la page suivante si on n'est plus à la fin de la pagination
    if (indiceCurrentPage < indiceLastPage){
        //console.log("On enable le bouton next")
        buttonNextPage.disabled = false
    }

    //on rend non cliquable le bouton de la page suivante si on est à la dernière page et en fin de pagination
    else if (indiceCurrentPage === indiceLastPage){
        //console.log("On disable le bouton next")
        buttonNextPage.disabled = true
    }

    //on rend cliquable le boutton de la derniere page si on n'est pas à la derniere page
    if (indiceCurrentPage < indiceLastPage){
        //console.log("On enable le bouton last")
        buttonLastPage.disabled = false
    }

    //sinon on le rend non cliquable
    else{
        //console.log("On disable le bouton last")
        buttonLastPage.disabled = true
        //console.log(buttonLastPage)

    }
}

function displayUsersPage(usersList){
    //on ajoute la liste des users dans l'élément ul, pour la recherche de users
    clearElem(ulUsersList)
    //console.log(usersList)
    if (usersList.length > 0){
        for (let i=0;i<usersList.length;i++){
            let li_user = document.createElement("li")
            li_user.appendChild(document.createTextNode(usersList[i].userMail))

            ulUsersList.appendChild(li_user)
        }
    }
}

function mapJsonUsersToUserObjects(listeJsonUsers){
    let listUserObjects = [];

    //on vérifie bien que l'objet passé en paramètre est une liste
    if (Array.isArray(listeJsonUsers)){
        for (let i=0;i<listeJsonUsers.length;i++){
            let user = listeJsonUsers[i]

            let userObject = new User(user["userMail"], user["lastName"], user["firstName"])
            listUserObjects.push(userObject)
        }
    }
    return listUserObjects
}

function clearElem(elemToClear){
    while (elemToClear.firstElementChild){
        //console.log("On enleve l'enfant " + elemToClear.firstElementChild)
        elemToClear.removeChild(elemToClear.firstElementChild)
    }
}

function displayMessage(domElement, message, typeMessage){
    clearElem(domElement)

    let messageColor = "#32ac4f"
    //on regarde si le message est un message d'erreur ou un message de notification
    if (typeMessage === 1){
        messageColor = "#EB3939"
    }

    let b_message = document.createElement("b")
    b_message.appendChild(document.createTextNode(message))
    b_message.style.color =  messageColor
    domElement.appendChild(b_message)
}