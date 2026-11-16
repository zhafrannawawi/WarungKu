// Inisialisasi Grafik Dashboard Owner
document.addEventListener("DOMContentLoaded", function () {
    // 1. Grafik Tren Penjualan 7 Hari
    var profit_chart_element = document.getElementById("chart-total-profit");
    if (profit_chart_element && window.ApexCharts) {
        var profit_chart_options = {
            series: [{
                name: "Omzet",
                data: [320000, 410000, 385000, 440000, 415000, 490000, 478500]
            }],
            chart: {
                type: "area",
                height: 250,
                toolbar: { show: false },
                zoom: { enabled: false }
            },
            colors: ["#2563eb"],
            dataLabels: { enabled: false },
            stroke: { curve: "smooth", width: 2 },
            fill: {
                type: "gradient",
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.35,
                    opacityTo: 0.05,
                    stops: [0, 95, 100]
                }
            },
            xaxis: {
                categories: ["18 Sep", "19 Sep", "20 Sep", "21 Sep", "22 Sep", "23 Sep", "24 Sep"],
                labels: { style: { colors: "#64748b", fontSize: "12px" } }
            },
            yaxis: {
                labels: {
                    style: { colors: "#64748b", fontSize: "12px" },
                    formatter: function (value) {
                        return "Rp " + (value / 1000).toLocaleString("id-ID") + "k";
                    }
                }
            },
            tooltip: {
                y: {
                    formatter: function (value) {
                        return "Rp " + value.toLocaleString("id-ID");
                    }
                }
            }
        };

        var profit_chart = new ApexCharts(profit_chart_element, profit_chart_options);
        profit_chart.render();
    }

    // 2. Grafik Donut Rekonsiliasi Kasir (Tunai vs QRIS)
    var payment_chart_element = document.getElementById("chart-customer-rate");
    if (payment_chart_element && window.ApexCharts) {
        var payment_chart_options = {
            series: [369000, 109500],
            labels: ["Tunai", "QRIS"],
            chart: {
                type: "donut",
                height: 200
            },
            colors: ["#16a34a", "#2563eb"],
            dataLabels: { enabled: false },
            legend: { show: false },
            tooltip: {
                y: {
                    formatter: function (value) {
                        return "Rp " + value.toLocaleString("id-ID");
                    }
                }
            }
        };

        var payment_chart = new ApexCharts(payment_chart_element, payment_chart_options);
        payment_chart.render();
    }
});
