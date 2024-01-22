<img src="Images/logoUvsq.jpg" width="500">

_Durand Antonin_ <br>
_Jougla Maxime_ <br>
_Parciany Benjamin_ <br>
_Zehren William_

<h1 style="color:#5d79e7; text-align: center"> Rapport de performance et de fonctionnement: Monte Carlo </h1>

<h1 style="color:#5d79e7; text-align: center; margin-top: 100px"> Table des matières</h1>

<ol>
    <li> <a href="#introduction"> Introduction  </a> </li>
    <ol>
    <li> <a href="#motivation"> Motivation  </a> </li>
    </ol>
    <li> <a href="#pres_code_archi"> Présentation du code et de l'architecture </a> </li>
    <ol>
    <li> <a href="#pres_archi"> Présentation de l'architecture </a> </li>
    <li> <a href="#pres_code"> Présentation du code </a> </li>
    </ol>
    <li> <a href="#pres_crit_obj"> Présentation des critères de qualité et des objectifs </a> </li>
    <ol>
        <li> <a href="#efficiency"> Efficiency </a> </li>
        <ol>
            <li> <a href="#def_effi"> Définition du critère </a> </li>
            <li> <a href="#obj_effi"> Objectif à atteindre </a> </li>
            <li> <a href="#speed_up"> Speedup </a> </li>
            <li> <a href="#scalabilite"> Scalabilité </a> </li>
            <ol>
                <li> <a href="#scalabilite_forte"> Scalabilité forte </a> </li>
                <li> <a href="#scalabilite_faible"> Scalabilité faible</a> </li>
            </ol>
        </ol>
        <li> <a href="#effectiveness"> Effectiveness </a> </li>
        <ol>
            <li> <a href="#def_effec"> Définition du critère </a> </li>
            <li> <a href="#obj_effec"> Objectif à atteindre </a> </li>
        </ol>
    </ol>
    <li> <a href="#tests"> Tests </a> </li>
    <ol>
        <li> <a href="#test_effi"> Test de l'Efficiency </a> </li>
            <ol>
            <li> <a href="#test_Speedup"> Test du Speedup </a> </li>
            <li> <a href="#test_scalabilite"> Test de la scalabilité </a> </li>
                <ol>
                <li> <a href="#test_scaforte"> Test de la scalabilité forte </a> </li>
                <li> <a href="#test_scafaible"> Test de la scalabilité faible </a> </li>
                </ol>
            </ol>
        <li> <a href="#test_effec"> Test de l'Effectiveness </a> </li>
        <li> <a href="#test_limite"> Test des limites du programme </a> </li>
    </ol>
    <li> <a href="#conclusion"> Conclusion  </a> </li>
</ol>


<h2 style="color:#5d79e7; page-break-before: always" id="introduction"> 1) Introduction </h2>

#### Avertissement : Ce rapport à été effectué après la réalisation du rapport personnel sur la performance de l'algorithme de Monte Carlo en cours de qualité de développement. Il est donc possible que des parties similaires aux rapports des membres de notre groupe de SAE se retrouvent dans ce rapport. 

Ce rapport aura pour but d’expliquer le fonctionnement général de l’algorithme de Monte Carlo, ainsi que le fonctionnement du programme DistributedParallelMC qui utilise cet algorithme pour approximer Pi. Nous réaliserons ensuite des tests qui auront pour but de démontrer ou non, le respect des critères de qualité dits d’Efficiency et d’Effectiveness en les ayant définis au préalable. 

En effet, selon les cours de qualité de développement et les normes ISO telles que la norme ISO 25010:2011 et la norme ISO 25022:2012, un programme, un logiciel ou un site web se doit de répondre à des critères de qualité pour répondre au besoin des utilisateurs et leur permettre une expérience optimale. 

# A COMPLETER ? 

<h3 style="color:#5d79e7" id="motivation"> 1.1) Motivation </h3>

L’objectif principal de ce rapport est de comparer les résultats des tests sur le matériel de l'IUT aux résultats des tests sur Cluster Kit Hat. Il est également indispensable de démontrer si notre projet respecte ou non les critères de qualités définis précédemment et donc s'il respecte les besoins en termes de qualité des utilisateurs de notre site web. 

<h2 style="color:#5d79e7; page-break-before: always" id="pres_code_archi">2) Présentation du code et de l'architecture</h2>

# A FAIRE

<h3 style="color:#5d79e7" id="#pres_archi"> 2.1) Présentation de l'architecture </h3>

<h3 style="color:#5d79e7" id="#pres_code"> 2.2) Présentation du code </h3>

<h2 style="color:#5d79e7; page-break-before: always" id="pres_crit_obj">3) Présentation des critères de qualité et des objectifs</h2>

# A COMPLETER EN FONCTION DE CE QUI EST FAIT DANS LA PARTIE 2) ?

<h3 style="color:#5d79e7;" id="efficiency"> 3.1) Efficiency </h3> 

<h4 style="color:#5d79e7;" id="def_effi">  3.1.1) Définition du critère </h4>

Selon la norme ISO 25010:2012, le critère de qualité Efficiency correspond à l’efficacité, et dans notre cas, à la vitesse avec laquelle les utilisateurs atteignent les objectifs spécifiés.  

<h4 style="color:#5d79e7;" id="obj_effi"> 3.1.2) Objectif à atteindre </h4>

Cependant, l'objectif à atteindre ici pour considèrer que le critère de l'Efficiency est respecté ou non va dépendre du Speedup, de la scalabilité forte ainsi que de la scalabilité faible. On ne peut en effet pas déterminer si un temps d'execution est acceptable ou non sans considérer ces éléments.

<h4 style="color:#5d79e7;" id="speed_up"> 3.1.3) Speedup </h4>

Le Speedup est un principe général du calcul haute performance ayant entre autres pour objectif d'aider
à déterminer l'Efficiency d'un programme ou d'un logiciel. Il correspond au phénomène d'augmentations de
ressources de calcul allouées à l'exécution d'un programme et donc à une augmentation des performances.

La formule du Speedup est la suivante : $Speedup = \frac{Temps_1}{Temps_n}$ 

Dans notre cas, cette augmentation de ressources correspond à une augmentation de threads. Par conséquent, le Speedup correspond ici au rapport entre la vitesse d'exécution de référence (pour un thread) et la vitesse
d'exécution pour n threads 

De ce principe, on retire le principe de la scalabilité

<h4 style="color:#5d79e7;" id="scalabilite"> 3.1.4) Scalabilité </h4>

La scalabilité correspond à la capacité d'un programme à utiliser des ressources de calcul supplémentaires.
Cette dernière se décline en deux formes : La scalabilité forte et la scalabilité faible.

<h5 style="color:#5d79e7;" id="scalabilite_forte"> 3.1.4.1) Scalabilité forte </h5>

La scalabilité forte correspond au gain de vitesse d'exécution en fonction du nombre de threads. Cela signifie,
par exemple, que si on double le nombre de threads s'occupant d'une tache, cette tâche est censée s'exécuter
deux fois plus vite.

Dans l'idéal, l'objectif serait de retrouver ce gain de vitesse d'exécution linéaire lors de l'augmentation du
nombre de threads. Cependant, ce gain ne se retrouve pas forcément dans la théorie, et encore moins dans la
pratique.

L'objectif sera par conséquent d'atteindre une efficacité supérieure à 75%

<h5 style="color:#5d79e7;" id="scalabilite_faible"> 3.1.4.2) Scalabilité faible </h5>

La scalabilité faible correspond à la conservation d'une vitesse d'exécution constante si l'on augmente le
nombre de threads ainsi que le nombre de lancers (experiences aléatoires) de manière proportionnelle. Cela
signifie, par exemple, que si on double le nombre de threads et le nombre de lancers lors de l'exécution du
programme, la vitesse d'exécution est censée etre la même qu'avant cette augmentation.

Comme pour le cas de la scalabilité forte, l'objectif, dans l'idéal, serait de retrouver cette conservation de la
vitesse d'exécution mais elle n'est pas toujours possible dans la théorie et encore moins dans la pratique.

L'objectif sera donc d'atteindre une efficacité supérieure à 75%.

<h3 style="color:#5d79e7; page-break-before: always" id="effectiveness"> 3.2) Effectiveness </h3>

<h4 style="color:#5d79e7;" id="def_effec"> 3.2.1) Définition du critère </h4>

Selon la norme ISO 25010:2012, le critère de qualité Effectiveness correspond à l’exactitude (la précision) et la
complétude avec lesquelles les utilisateurs atteignent les objectifs spécifiés.

Dans notre cas, nous cherchons à calculer Pi à l'aide de l'algorithme de Monte Carlo. On récupère donc une
approximation de la valeur de Pi. On pourrait, par exemple, considérer que ce critère de qualité est rempli
lorsqu'on trouve une valeur de Pi approximée égale à 0.001 près à la valeur réele de Pi.

Dans le cas de notre programme, la valeur de Pi dite réele est égale à 3.141592653589793, c'est la valeur
utilisée dans la classe Math de Java.

Par ailleurs, on calcule l'erreur entre la valeur approximée de Pi et la valeur dite réele de Pi de la manière
suivante : 

$Erreur = \frac{Pi_a - Pi_r}{Pi_r}$ 

<h4 style="color:#5d79e7;" id="obj_effec"> 3.2.2) Objectif à atteindre </h4>

Trouver la valeur réele pourrait être un objectif à atteindre pour considèrer que le critère de l'Effectiveness a
bien été atteint ou non, mais serait bien trop coûteux et long à trouver à l'aide de tests, et d'autres critères de
qualité s'en retrouveraient fortement impactés négativement.

Ici, l'objectif à atteindre pour considèrer que le critère de l'Effectiveness est atteint ou non, sera une valeur
approximée de Pi égale à 0.0001 près de la valeur réele de Pi et donc une erreur inférieure à $1×10^{−4}$.


<h2 style="color:#5d79e7; page-break-before: always" id="tests"> 4) Tests </h2>

# A FAIRE (et changer l'envt de test si nécéssaire)

Tous les tests à venir seront réalisés selon un environnement de test défini ici :

- Les tests seront tous réalisés sur un seul Worker
- Les tests se feront de manière générale avec un minimum de 5/6 valeurs pour observer différents cas
de figure. Ils s'arrêteront si on ne constate aucune evolution plusieurs fois d'affilée.
- Chaque test sera répété 5 fois puis les résultats de chaque instance d'un test seront divisés par 5 pour
obtenir une moyenne. Les résultats obtenus seront alors inscrits dans leurs tableaux respectifs

<h3 style="color:#5d79e7;" id=test_effi"> 4.1) Test de l'Efficiency </h3>

<h4 style="color:#5d79e7;" id="test_Speedup"> 4.1.1) Test du Speedup </h4>

<h4 style="color:#5d79e7; page-break-before: always" id="test_scalabilite"> 4.1.2) Test de la scalabilité </h4>

<h5 style="color:#5d79e7;" id="test_scaforte"> 4.1.2.1) Test de la scalabilité forte </h5>

<h5 style="color:#5d79e7;" id="test_scafaible"> 4.1.2.2) Test de la scalabilité faible </h5>

<h3 style="color:#5d79e7;" id="test_effec"> 4.2) Test de l'Effectiveness </h3>

<h2 style="color:#5d79e7; page-break-before: always" id="conclusion"> 5) Conclusion </h2>

# A FAIRE