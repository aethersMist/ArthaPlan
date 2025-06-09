import "./bootstrap";
import Chart from "chart.js/auto";
import ApexCharts from "apexcharts";

import Alpine from "alpinejs";

window.Alpine = Alpine;

Alpine.start();

// Bar Chart - Dash
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
                                "Rp" + value.toLocaleString("id-ID"),
                        },
                    },
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: (context) =>
                                `${
                                    context.dataset.label
                                }: Rp${context.raw.toLocaleString("id-ID")}`,
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

    // Event: Filter button (bulanan, mingguan, dll)
    document.querySelectorAll("[data-filter]").forEach((button) => {
        button.addEventListener("click", function () {
            const filter = this.getAttribute("data-filter");
            const url = new URL(window.location.href);
            url.searchParams.set("filter", filter);
            window.location.href = url.toString();
        });
    });

    // Event: Ganti tanggal
    const dateFilter = document.getElementById("dateFilter");
    if (dateFilter) {
        dateFilter.addEventListener("change", function () {
            const url = new URL(window.location.href);
            url.searchParams.set("date", this.value);
            window.location.href = url.toString();
        });
    }

    // Tampilkan waktu lokal real-time
    function updateWaktu() {
        const sekarang = new Date();
        const options = {
            weekday: "long",
            day: "numeric",
            month: "long",
            year: "numeric",
            timeZone: "Asia/Jakarta",
        };

        const elemenTanggal = document.getElementById("tanggal-terpilih");
        if (elemenTanggal) {
            elemenTanggal.textContent = sekarang.toLocaleDateString(
                "id-ID",
                options
            );
        }
    }

    updateWaktu();
    setInterval(updateWaktu, 1000);
});

// Donat Chart Anggaran(Dash)
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

// Laporan - Dash - Income
document.addEventListener("DOMContentLoaded", () => {
    function createDonutChart(
        containerId,
        dataCategoriesAttr,
        dataValuesAttr,
        chartLabel,
        colors,
        legendDashboardId,
        legendReportId
    ) {
        const container = document.getElementById(containerId);
        if (!container) return;

        const rawCategories = container.getAttribute(dataCategoriesAttr);
        const rawValues = container.getAttribute(dataValuesAttr);

        let categories = [];
        let values = [];

        if (!rawCategories || !rawValues) {
            categories = ["Belum Ada Data"];
            values = [1];
        } else {
            try {
                categories = JSON.parse(rawCategories);
                values = JSON.parse(rawValues);

                if (values.length === 0 || values.every((v) => v === 0)) {
                    categories = ["Belum Ada Data"];
                    values = [1];
                }
            } catch (e) {
                console.error("JSON parse error:", e);
                categories = ["Belum Ada Data"];
                values = [1];
            }
        }

        const options = {
            chart: {
                type: "pie",
                height: 200,
            },
            labels: categories,
            series: values,
            colors: colors,
            dataLabels: {
                enabled: true,
                formatter: function (val) {
                    return val.toFixed(1) + "%";
                },
            },
            legend: { show: false },
            tooltip: {
                y: {
                    formatter: function (value) {
                        return "Rp " + value.toLocaleString("id-ID");
                    },
                },
            },
        };

        // Render ApexChart
        container.innerHTML = ""; // Bersihin isi kontainer dulu
        const chart = new ApexCharts(container, options);
        chart.render();

        // Legend Dashboard (Kiri)
        if (legendDashboardId) {
            const legendDashboard = document.getElementById(legendDashboardId);
            if (legendDashboard) {
                legendDashboard.innerHTML = "";
                categories.forEach((cat, i) => {
                    const li = document.createElement("li");
                    li.className =
                        "flex items-center transition hover:scale-105 mb-1 space-x-2";

                    const colorBox = document.createElement("span");
                    colorBox.className = "w-3 h-3 rounded-full inline-block";
                    colorBox.style.backgroundColor = colors[i % colors.length];

                    const labelNode = document.createElement("p");
                    labelNode.textContent = cat;
                    labelNode.className = "text-sm text-dark";

                    li.appendChild(colorBox);
                    li.appendChild(labelNode);
                    legendDashboard.appendChild(li);
                });
            }
        }

        // Legend Report (Kanan)
        if (legendReportId) {
            const legendReport = document.getElementById(legendReportId);
            if (legendReport) {
                legendReport.innerHTML = "";
                const totalValue = values.reduce((a, b) => a + b, 0) || 1;

                categories.forEach((cat, i) => {
                    const value = values[i];
                    const percentage = ((value / totalValue) * 100).toFixed(2);
                    const color = colors[i % colors.length];

                    const li = document.createElement("li");
                    li.className =
                        "flex justify-between items-center w-full text-sm font-semibold text-dark mb-1";

                    const left = document.createElement("div");
                    left.className = "inline-flex items-center gap-2";

                    const colorBox = document.createElement("span");
                    colorBox.className = "inline-block w-3 h-3 rounded-full";
                    colorBox.style.backgroundColor = color;

                    const labelSpan = document.createElement("span");
                    labelSpan.textContent = cat;

                    left.appendChild(colorBox);
                    left.appendChild(labelSpan);

                    const nominal = document.createElement("p");
                    nominal.textContent = "Rp " + value.toLocaleString("id-ID");

                    li.appendChild(left);
                    li.appendChild(nominal);

                    legendReport.appendChild(li);
                });
            }
        }
    }

    // Income Chart
    createDonutChart(
        "pie-chart-Income",
        "data-categories",
        "data-values",
        "Total Pemasukan",
        ["#285539", "#88cf0f", "#00bfa6", "#ff9f1c", "#2979ff", "#ff4081"],
        "legend-Dashboard",
        "legend-Report"
    );

    // Outcome Chart
    createDonutChart(
        "pie-chart-Outcome",
        "data-categories-out",
        "data-values-out",
        "Total Pengeluaran",
        ["#f87171", "#ff9f1c", "#ff4081", "#2979ff", "#ef4444", "#991b1b"],
        null,
        "legend-Report-Outcome"
    );
});
