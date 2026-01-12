import "/node_modules/@kurkle/color/dist/color.esm.js";
import { Chart } from "/node_modules/chart.js/auto/auto.js";

let chartInstance = null;

function dashboardStart() {
    const dashboard = $("#dashboard");
    if (!dashboard.length) {
        console.log("Dashboard not found");
        return;
    }

    const chartCanvas = $("#chart")[0];
    if (!chartCanvas) {
        console.log("Chart canvas not found");
        return;
    }

    const statsJson = dashboard.attr("data-stats");
    if (!statsJson) {
        console.error("No stats data found");
        return;
    }

    const stats = JSON.parse(statsJson);

    const tables = [
        "users",
        "doctors",
        "patients",
        "departments",
        "appointments",
        "prescriptions",
        "medications",
    ];

    const data = tables.map((table) => {
        const value = stats[table] || 0;
        return value;
    });

    const colors = [
        "#3b82f6",
        "#22c55e",
        "#eab308",
        "#ec4899",
        "#f97316",
        "#8b5cf6",
        "#6b7280",
    ];

    const chartData = {
        labels: tables.map((t) => t.charAt(0).toUpperCase() + t.slice(1)),
        datasets: [
            {
                label: "Number of rows",
                data: data,
                backgroundColor: colors,
                borderRadius: 4,
            },
        ],
    };

    const opt = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: "bottom",
                labels: { usePointStyle: true, padding: 20 },
            },
        },
        scales: {
            y: { beginAtZero: true, ticks: { precision: 0 } },
        },
    };

    if (chartCanvas._chartInstance) {
        chartCanvas._chartInstance.destroy();
    }

    chartInstance = new Chart(chartCanvas, {
        type: "bar",
        data: chartData,
        options: opt,
    });

    chartCanvas._chartInstance = chartInstance;
}

export { dashboardStart };
