import "./bootstrap";
import Chart from "chart.js/auto";
import ApexCharts from "apexcharts";

import Alpine from "alpinejs";

window.Alpine = Alpine;

Alpine.start();

// Bar Chart
document.addEventListener("DOMContentLoaded", function () {
    let barChart = {};

    function initChart(
        labels,
        dataOut,
        dataIn,
        canvasId = "barChartCanvas",
        labelMode = "combined"
    ) {
        const canvas = document.getElementById(canvasId);
        if (!canvas) return;

        const ctx = canvas.getContext("2d");

        if (barChart[canvasId]) {
            barChart[canvasId].destroy();
        }

        const datasets = [];

        if (labelMode === "combined") {
            datasets.push(
                {
                    label: "Pemasukan",
                    data: dataIn,
                    backgroundColor: "#285539",
                    borderRadius: 8,
                    barThickness: 20,
                },
                {
                    label: "Pengeluaran",
                    data: dataOut,
                    backgroundColor: "#f87171",
                    borderRadius: 8,
                    barThickness: 20,
                }
            );
        } else if (labelMode === "income") {
            datasets.push({
                label: "Pemasukan",
                data: dataIn,
                backgroundColor: "#285539",
                borderRadius: 8,
                barThickness: 20,
            });
        } else if (labelMode === "outcome") {
            datasets.push({
                label: "Pengeluaran",
                data: dataOut,
                backgroundColor: "#f87171",
                borderRadius: 8,
                barThickness: 20,
            });
        }

        barChart[canvasId] = new Chart(ctx, {
            type: "bar",
            data: {
                labels: labels,
                datasets: datasets,
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: (value) =>
                                "Rp" + value.toLocaleString("id-ID") + ",00",
                        },
                    },
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: (context) =>
                                `${
                                    context.dataset.label
                                }: Rp${context.raw.toLocaleString("id-ID")},00`,
                        },
                    },
                    legend: {
                        position: "top",
                        labels: {
                            usePointStyle: true,
                            pointStyle: "rectRounded",
                        },
                    },
                },
            },
        });
    }

    // Chart Dashboard (gabungan income + outcome)
    const dashboardCanvas = document.getElementById("barChartCanvas");
    if (dashboardCanvas) {
        const labels = JSON.parse(
            dashboardCanvas.getAttribute("data-labels") || "[]"
        );
        const dataOut = JSON.parse(
            dashboardCanvas.getAttribute("data-data-out") || "[]"
        );
        const dataIn = JSON.parse(
            dashboardCanvas.getAttribute("data-data-in") || "[]"
        );

        if (labels.length > 0) {
            initChart(labels, dataOut, dataIn, "barChartCanvas", "combined");
        }

        async function fetchChartData() {
            const urlParams = new URLSearchParams(window.location.search);
            const filter = urlParams.get("filter") || "monthly";
            const date =
                urlParams.get("date") || new Date().toISOString().split("T")[0];

            try {
                const response = await fetch(
                    `/dashboard/chart-data?filter=${filter}&date=${date}`
                );
                const data = await response.json();

                if (data.labels && data.labels.length > 0) {
                    initChart(
                        data.labels,
                        data.dataOut,
                        data.dataIn,
                        "barChartCanvas",
                        "combined"
                    );
                }
            } catch (error) {
                console.error("Error fetching chart data:", error);
            }
        }

        setInterval(fetchChartData, 5000);
    }

    // Chart Report View: Income & Outcome terpisah
    const incomeCanvas = document.getElementById("barChartIncome");
    if (incomeCanvas) {
        const labels = JSON.parse(
            incomeCanvas.getAttribute("data-labels") || "[]"
        );
        const dataIn = JSON.parse(
            incomeCanvas.getAttribute("data-data") || "[]"
        );

        if (labels.length > 0) {
            initChart(labels, [], dataIn, "barChartIncome", "income");
        }
    }

    const outcomeCanvas = document.getElementById("barChartOutcome");
    if (outcomeCanvas) {
        const labels = JSON.parse(
            outcomeCanvas.getAttribute("data-labels") || "[]"
        );
        const dataOut = JSON.parse(
            outcomeCanvas.getAttribute("data-data") || "[]"
        );

        if (labels.length > 0) {
            initChart(labels, dataOut, [], "barChartOutcome", "outcome");
        }
    }

    // Filter (bulanan, mingguan, dll)
    document.querySelectorAll("[data-filter]").forEach((button) => {
        button.addEventListener("click", function () {
            const filter = this.getAttribute("data-filter");
            const url = new URL(window.location.href);
            url.searchParams.set("filter", filter);
            window.location.href = url.toString();
        });
    });
});

// Donat Chart - Anggaran
document.addEventListener("DOMContentLoaded", function () {
    const el = document.getElementById("donutChartPersen");

    if (!el) return;

    const persenSisa = Number(el.getAttribute("data-sisa")) || 100;
    const persenPakai = Number(el.getAttribute("data-pakai")) || 0;

    const options = {
        chart: {
            type: "donut",
            width: 200,
            height: 200,
        },
        series: [persenSisa, persenPakai],
        labels: ["Sisa", "Terpakai"],
        colors: ["#88cf0f", "#285539"],
        dataLabels: {
            enabled: true,
            formatter: function (val) {
                return val + "%";
            },
        },
        legend: {
            show: false,
        },
        tooltip: {
            y: {
                formatter: function (val) {
                    return val + "%";
                },
            },
        },
        plotOptions: {
            pie: {
                donut: {
                    labels: {
                        show: true,
                        name: {
                            show: true,
                            fontSize: "16px",
                            color: "#285539",
                            fontFamily: "Arial, sans-serif",
                        },
                        value: {
                            show: true,
                            fontSize: "14px",
                            color: "#285539",
                            fontFamily: "Arial, sans-serif",
                            formatter: function (val) {
                                return val + "%";
                            },
                        },
                    },
                },
            },
        },
    };

    const chart = new ApexCharts(el, options);
    chart.render();
});

// Laporan - Report
document.addEventListener("DOMContentLoaded", () => {
    function createDonutChart(
        containerId,
        dataCategoriesAttr,
        dataValuesAttr,
        colors,
        legendReportId
    ) {
        const container = document.getElementById(containerId);
        if (!container) return;

        let categories = [];
        let values = [];

        try {
            categories = JSON.parse(container.getAttribute(dataCategoriesAttr));
            values = JSON.parse(container.getAttribute(dataValuesAttr));
            if (!values.length || values.every((v) => v === 0)) {
                categories = ["Belum Ada Data"];
                values = [1];
            }
        } catch {
            categories = ["Belum Ada Data"];
            values = [1];
        }

        const displayCategories = categories.map((cat) => {
            if (cat.toLowerCase().includes("income")) {
                return cat.replace(/income/gi, "Pemasukkan");
            } else if (cat.toLowerCase().includes("outcome")) {
                return cat.replace(/outcome/gi, "Pengeluaran");
            }
            return cat;
        });

        const options = {
            chart: {
                type: "pie",
                height: 200,
            },
            labels: displayCategories,
            series: values,
            colors: colors,
            dataLabels: {
                enabled: true,
                formatter: (val) => val.toFixed(1) + "%",
            },
            legend: { show: false },
            tooltip: {
                y: {
                    formatter: (value) =>
                        "Rp " + value.toLocaleString("id-ID") + ",00",
                },
                x: {
                    formatter: (value, { seriesIndex }) => {
                        return displayCategories[seriesIndex];
                    },
                },
            },
        };

        container.innerHTML = "";
        const chart = new ApexCharts(container, options);
        chart.render();

        if (legendReportId) {
            const legendReport = document.getElementById(legendReportId);
            if (legendReport) {
                legendReport.innerHTML = "";

                displayCategories.forEach((cat, i) => {
                    const color = colors[i % colors.length];

                    const li = document.createElement("li");
                    li.className =
                        "flex items-center w-full text-sm font-semibold text-dark mb-1 justify-between";

                    const left = document.createElement("div");
                    left.className = "inline-flex items-center gap-2";

                    const colorBox = document.createElement("span");
                    colorBox.className = "inline-block w-3 h-3 rounded-full";
                    colorBox.style.backgroundColor = color;

                    const labelSpan = document.createElement("span");
                    labelSpan.textContent = cat;

                    left.appendChild(colorBox);
                    left.appendChild(labelSpan);
                    li.appendChild(left);

                    // legend dashboard
                    if (legendReportId === "legend-Report-Combined") {
                        legendReport.className =
                            "flex flex-wrap gap-x-6 gap-y-2 space-x-4 text-sm font-semibold text-dark mb-1";
                        li.className =
                            "flex items-center transition hover:scale-105 mb-1 space-x-2";
                    } else {
                        // legend Report
                        const nominal = document.createElement("p");
                        nominal.textContent =
                            "Rp " + values[i].toLocaleString("id-ID") + ",00";
                        li.appendChild(nominal);
                    }

                    legendReport.appendChild(li);
                });
            }
        }
    }

    // Warna combined chart pemasukkan dan pengeluaran
    const combinedColors = [
        "#285539", // Pemasukkan
        "#88cf0f",
        "#00bfa6",
        "#ff9f1c",
        "#2979ff",
        "#ff4081",
        "#f87171", // Pengeluaran
        "#ef4444",
        "#991b1b",
        "#fb923c",
        "#dc2626",
        "#7f1d1d",
    ];

    const pemasukkanColors = [
        "#285539",
        "#88cf0f",
        "#00bfa6",
        "#ff9f1c",
        "#2979ff",
        "#ff4081",
    ];
    const pengeluaranColors = [
        "#f87171",
        "#ff9f1c",
        "#ff4081",
        "#2979ff",
        "#ef4444",
        "#991b1b",
    ];

    createDonutChart(
        "pie-chart-Combined",
        "data-categories-combined",
        "data-values-combined",
        combinedColors,
        "legend-Report-Combined"
    );

    createDonutChart(
        "pie-chart-Income",
        "data-categories",
        "data-values",
        pemasukkanColors,
        "legend-Report-Income"
    );

    createDonutChart(
        "pie-chart-Outcome",
        "data-categories-out",
        "data-values-out",
        pengeluaranColors,
        "legend-Report-Outcome"
    );
});

// Loader
window.addEventListener("load", () => {
    const loader = document.getElementById("global-loader");
    const content = document.getElementById("app-content");

    if (loader) {
        loader.style.display = "none";
    }

    if (content) {
        content.classList.remove("invisible");
    }
});
