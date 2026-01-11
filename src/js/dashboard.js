import "/node_modules/@kurkle/color/dist/color.esm.js";
import { Chart } from "/node_modules/chart.js/auto/auto.js";

let chartInstance = null;

function dashboardStart() {
    const dashboard = $("#dashboard");
    if (!dashboard.length) return;

    const chartCanvas = $("#chart")[0];
    if (!chartCanvas) return;

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
        const span = $(`[data-chart-${table}]`);
        return span.length ? Number(span.text()) : 0;
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
