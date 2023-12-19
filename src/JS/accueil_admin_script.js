const dataPie = {
    labels: ["Utilisateurs", "Visiteurs"],
    datasets: [
    {
        label: "Nombres",
        data: [300, 50],
        backgroundColor: [
        "rgb(255, 220, 0)",
        "rgb(0, 35, 70)",
        ],
        hoverOffset: 4,
    },
    ],
};

const configPie = {
    type: "pie",
    data: dataPie,
    options: {},
};

var chartBar = new Chart(document.getElementById("chartPie"), configPie);

const listUsers = document.querySelector('#popUpUsers');
const btnShowUsers = document.querySelector('#showUsers');

var showed = false;

function showUsers() {
    console.log("click");
    if (!showed) {
      listUsers.classList.remove("hidden");
      showed = true;
  
      document.addEventListener('click', handleClickOutside);
    } else {
      listUsers.classList.add("hidden");
      showed = false;
  
      document.removeEventListener('click', handleClickOutside);
    }
  }

function handleClickOutside(event) {

if (!profil.contains(event.target)) {
    listUsers.classList.add("hidden");
    showed = false;

    document.removeEventListener('click', handleClickOutside);
}
}

btnShowUsers.addEventListener('click', function(event) {
    event.stopPropagation();
  });