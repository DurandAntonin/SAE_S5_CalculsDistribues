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