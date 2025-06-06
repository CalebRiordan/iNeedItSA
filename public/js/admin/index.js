const chartElement = document.querySelector(".chart");
let chart;

function setChart(data, type, period) {
    // Make label
    const validChartType = {
        "orders-volume": "Order volume",
        revenue: "Revenue",
        "new-users": "New users",
        sales: "Sales",
    };

    const duration = {
        week: "7 days",
        month: "30 days",
        year: "12 months",
    };

    if (!validChartType[type]) return;

    // Clear container content and set title
    const chartTitle =
        validChartType[type] + ` over the past ${duration[period]}`;
    chartElement.innerHTML = '';
    document.getElementById("chart-label").textContent = chartTitle;

    if (chart) chart.destroy();

    // Set up chart values
    const reversedData = [...data].reverse();
    const labels = Array.from(
        { length: reversedData.length },
        (_, i) => reversedData.length - i
    );

    // Make new Chart object
    chart = new Chart(chartElement, {
        type: "line",
        data: {
            labels: labels,
            datasets: [
                {
                    label: labels,
                    data: reversedData,
                    borderColor: "#27671C",
                    borderWidth: 3,
                    fill: false,
                },
            ],
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        font: {
                            size: 13,
                        },
                    },
                },
                x: {
                    title: {
                        display: true,
                        text: `Past ${duration[period]}`,
                    },
                    ticks: {
                        font: {
                            size: 13,
                        },
                    },
                },
            },
        },
    });
}

// Fetch and render data
async function loadData(type, period, targetEl) {
    const card = targetEl.closest(".card");
    const loading = card.querySelector(".loading-overlay");
    loading.hidden = false;

    try {
        const response = await fetch(
            `/admin/reports?type=${type}&period=${period}`
        );

        if (!response.ok) {
            loading.hidden = true;
            card.HTMLContent = "<h1>Error loading statistic</h1>";
            return null;
        }

        let data = await response.json();
        if (typeof data === "object") {
            data = Object.values(data);
        }

        const stat = data.reduce((sum, val) => sum + val, 0);
        loading.hidden = true;
        targetEl.textContent = `${stat.toLocaleString()}`;

        return data;
    } catch (err) {
        console.error(err);
        return null;
    }
}

async function getStat(type, period, targetEl) {}

// For each card:
document.querySelectorAll(".dashboard-grid .card").forEach((card) => {
    let loading = false;
    const statType = card.dataset.type;
    const targetEl = card.querySelector(".stat-value");

    // For each switch within the card:
    card.querySelectorAll('.switch input[type="radio"]').forEach((input) => {
        const period = input.value;

        // Detect change on switch
        input.addEventListener("change", async (e) => {
            if (!input.checked || loading) {
                e.preventDefault;
                return;
            }

            // Fetch and load data from server
            loading = true;
            const data = await loadData(statType, period, targetEl);

            if (data) {
                // Update Chart
                setChart(data, statType, period);
            }

            loading = false;
        });
    });

    // Initial load
    const dataPromise = loadData(statType, "month", targetEl);
    if (statType === "revenue") {
        dataPromise.then((data) => {
            if (data) setChart(data, statType, "month");
        });
    }
});
