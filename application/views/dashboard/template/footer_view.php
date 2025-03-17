<!-- Libs JS -->
<script src="<?= base_url() ?>public/assets/tabler/dist/libs/apexcharts/dist/apexcharts.min.js?1692870487"
    defer></script>
<script src="<?= base_url() ?>public/assets/tabler/dist/libs/jsvectormap/dist/js/jsvectormap.min.js?1692870487"
    defer></script>
<script src="<?= base_url() ?>public/assets/tabler/dist/libs/jsvectormap/dist/maps/world.js?1692870487" defer></script>
<script src="<?= base_url() ?>public/assets/tabler/dist/libs/jsvectormap/dist/maps/world-merc.js?1692870487"
    defer></script>
<!-- Tabler Core -->
<script src="<?= base_url() ?>public/assets/tabler/dist/js/tabler.min.js?1692870487" defer></script>
<script src="<?= base_url() ?>public/assets/tabler/dist/js/demo.min.js?1692870487" defer></script>
<!-- fetch api -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // dinamiskan
        const baseUrl = "http://localhost/ci3_api_rs/api/reports";
        const loadingSpinner = document.getElementById("loadingSpinner");

        const endpoints = [
            "summary",
            "patient-visits",
            "patient-visit-department",
            "top-diagnoses",
            "revenue",
            "inpatient-capacity",
            // "patient-new-vs-returning"
        ];

        document.querySelector(".cari-data").addEventListener("click", function (event) {
            event.preventDefault();
            fetchAllData();
        });

        async function fetchAllData() {
            loadingSpinner.classList.remove("d-none");

            try {
                await Promise.all(endpoints.map(fetchData));
            } finally {
                loadingSpinner.classList.add("d-none");
            }
        }

        async function fetchData(endpoint) {
            const params = getFilterParams();
            const url = `${baseUrl}/${endpoint}?filter=${params.filter}&start_date=${params.start_date}&end_date=${params.end_date}`;

            console.log(`Fetching: ${url}`);

            toggleLoading(true, endpoint);

            try {
                const response = await fetch(url);
                const data = await response.json();

                if (data.status) {
                    updateUI(endpoint, data);

                } else {
                    console.error(`API Error (${endpoint}):`, data.message);
                }
            } catch (error) {
                console.error(`Fetch error di ${endpoint}:`, error);
            } finally {
                toggleLoading(false, endpoint);
            }
        }

        function getFilterParams() {
            const selectFilter = document.getElementById("filterdata");
            const dateRangePicker = document.getElementById("reportrange");

            const filterValue = selectFilter.value.toLowerCase();
            const dateText = dateRangePicker.querySelector("span").textContent.trim();

            let startDate = "";
            let endDate = "";

            if (dateText.includes(" - ")) {
                [startDate, endDate] = dateText.split(" - ").map(date => formatDate(date.trim()));
            }

            return { filter: filterValue, start_date: startDate, end_date: endDate };
        }
        function formatDate(dateString) {
            const date = new Date(dateString);
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, "0");
            const day = String(date.getDate()).padStart(2, "0");
            return `${year}-${month}-${day}`;
        }

        function toggleLoading(state, endpoint) {
            const container = document.querySelector(`[data-endpoint="${endpoint}"]`);
            if (container) {
                const placeholders = container.querySelectorAll(".placeholder-glow");
                const progressBar = container.querySelector(".progress");
                const dataResponses = container.querySelectorAll(".data-response");

                placeholders.forEach(ph => ph.classList.toggle("d-none", !state));
                if (progressBar) progressBar.classList.toggle("d-none", !state);

                dataResponses.forEach(dr => dr.classList.toggle("d-none", state));
            }
        }


        function updateUI(endpoint, data) {
            if (endpoint === "summary") {
                updateSummaryData(data.data, endpoint);
            } else if (endpoint === "patient-visits") {
                updateDateRange(endpoint, data.date_range);
                updatePatientVisitsChart(data, endpoint);
            } else if (endpoint === "patient-visit-department") {
                updateDateRange(endpoint, data.date_range);
                updatePatientVisitsDepartmentChart(data, endpoint);
            } else if (endpoint === "top-diagnoses") {
                updateDateRange(endpoint, data.date_range);
                updateTopDiagnosesTable(data, endpoint);
            } else if (endpoint === "revenue") {
                updateDateRange(endpoint, data.date_range);
                updateRevenueChart(data, endpoint);
            } else if (endpoint === "inpatient-capacity") {
                updateDateRange(endpoint, data.date_range);
                updateInpatientCapacityChart(data, endpoint);
            }
        }

        function updateDateRange(endpoint, dateRange) {
            setTimeout(function () {
                const container = document.querySelector(`[data-endpoint="${endpoint}"]`);
                if (!container) {
                    console.error(`Container untuk endpoint "${endpoint}" tidak ditemukan.`);
                    return;
                }

                const dateRangeElements = container.querySelectorAll(".date-range");

                dateRangeElements.forEach(element => {
                    element.textContent = dateRange;
                    element.classList.remove("d-none");
                });
            }, 10);
        }


        function updateSummaryData(summaryData, endpoint) {
            const container = document.querySelector(`[data-endpoint="${endpoint}"]`);

            if (!container) {
                console.warn(`Container dengan endpoint "${endpoint}" tidak ditemukan.`);
                return;
            }

            toggleLoading(true, endpoint);

            Object.entries(summaryData).forEach(([key, value]) => {
                const element = container.querySelector(`#summary-${key.replace(/_/g, "-")}`);

                if (element) {
                    element.innerHTML = `<span class="data-content">${value}</span>`;
                } else {
                    console.warn(`Elemen tidak ditemukan: summary-${key.replace(/_/g, "-")}`);
                }
            });

            toggleLoading(false, endpoint);
        }

        function updatePatientVisitsChart(response, endpoint) {
            const container = document.querySelector(`[data-endpoint="${endpoint}"]`);
            if (!container) return;

            toggleLoading(true, endpoint);

            const { date_range, total_summary, data, filter } = response;

            container.querySelector(".summary-child").textContent = total_summary.child;
            container.querySelector(".summary-adult").textContent = total_summary.adult;
            container.querySelector(".summary-elderly").textContent = total_summary.elderly;
            container.querySelector(".summary-total").textContent = total_summary.total_patients;

            let categoryKey = "date";
            if (filter === "weekly") categoryKey = "week";
            if (filter === "monthly") categoryKey = "month";

            const categories = data.map(item => item[categoryKey]);
            const childData = data.map(item => item.child);
            const adultData = data.map(item => item.adult);
            const elderlyData = data.map(item => item.elderly);

            let chartEl = container.querySelector("#patient-visits-chart");
            if (!chartEl) return;

            if (chartEl.chartInstance) {
                chartEl.chartInstance.updateOptions({
                    xaxis: { categories },
                    series: [
                        { name: "Child", data: childData },
                        { name: "Adult", data: adultData },
                        { name: "Elderly", data: elderlyData }
                    ]
                });
            } else {
                var options = {
                    chart: { type: "line", height: 350, toolbar: { show: true } },
                    series: [
                        { name: "Child", data: childData },
                        { name: "Adult", data: adultData },
                        { name: "Elderly", data: elderlyData }
                    ],
                    xaxis: { categories }
                };

                let chart = new ApexCharts(chartEl, options);
                chart.render();

                chartEl.chartInstance = chart;
            }

            toggleLoading(false, endpoint);
        }

        function updatePatientVisitsDepartmentChart(response, endpoint) {
            const container = document.querySelector(`[data-endpoint="${endpoint}"]`);
            if (!container) return;

            toggleLoading(true, endpoint);

            const { date_range, total_summary, data, filter } = response;

            container.querySelector("#summary-total-appointments").textContent = total_summary.total_appointments;

            const summaryContainer = container.querySelector("#summary-container");

            while (summaryContainer.children.length > 1) {
                summaryContainer.removeChild(summaryContainer.lastChild);
            }

            Object.entries(total_summary).forEach(([dept, count]) => {
                if (dept !== "total_appointments") {
                    const colDiv = document.createElement("div");
                    colDiv.className = "col-md-3";
                    colDiv.innerHTML = `
                <div class="card bg-success-lt">
                    <div class="card-body text-center">
                        <h4 class="mb-1">${dept}</h4>
                        <p class="placeholder-glow mb-0">
                            <span class="placeholder col-6"></span>
                        </p>
                        <p class="fs-3 fw-bold data-response" id="summary-${dept.replace(/\s+/g, "-").toLowerCase()}">${count}</p>
                    </div>
                </div>
            `;
                    summaryContainer.appendChild(colDiv);
                }
            });

            let categoryKey = "date";
            if (filter === "weekly") categoryKey = "week";
            if (filter === "monthly") categoryKey = "month";

            const categories = data.map(item => item[categoryKey]);

            const departmentNames = Object.keys(total_summary).filter(key => key !== "total_appointments");

            const seriesData = departmentNames.map(department => {
                return {
                    name: department,
                    data: data.map(item => item[department] || 0)
                };
            });

            let chartEl = container.querySelector("#patient-visit-department-chart");
            if (!chartEl) return;

            if (chartEl.chartInstance) {
                chartEl.chartInstance.updateOptions({
                    xaxis: { categories },
                    series: seriesData
                });
            } else {
                const options = {
                    chart: { type: "bar", height: 350, toolbar: { show: true } },
                    series: seriesData,
                    xaxis: { categories }
                };

                let chart = new ApexCharts(chartEl, options);
                chart.render();

                chartEl.chartInstance = chart;
            }

            toggleLoading(false, endpoint);
        }

        function updateTopDiagnosesTable(response, endpoint) {
            const container = document.querySelector(`[data-endpoint="${endpoint}"]`);
            if (!container) return;

            toggleLoading(true, endpoint);

            const tableHead = container.querySelector("#top-diagnoses-table thead tr");
            const tableBody = container.querySelector("#top-diagnoses-table tbody");

            tableHead.innerHTML = "";
            tableBody.innerHTML = "";

            if (!response.data || response.data.length === 0) {
                tableBody.innerHTML = `<tr><td colspan="4" class="text-center">No data available</td></tr>`;
                toggleLoading(false, endpoint);
                return;
            }

            let dateKey = "date";
            if (response.filter === "weekly") dateKey = "week";
            if (response.filter === "monthly") dateKey = "month";

            tableHead.innerHTML = `
                <th>${dateKey.charAt(0).toUpperCase() + dateKey.slice(1)}</th>
                <th>ICD-10 Code</th>
                <th>Diagnosis Name</th>
                <th class="text-center">Total Cases</th>
            `;

            response.data.forEach(item => {
                const row = `
            <tr>
                <td>${item[dateKey]}</td>
                <td>${item.icd_10_code}</td>
                <td>${item.diagnosis_name}</td>
                <td class="text-center fw-bold">${item.total_cases}</td>
            </tr>
        `;
                tableBody.innerHTML += row;
            });

            toggleLoading(false, endpoint);
        }

        function updateRevenueChart(response, endpoint) {
            const container = document.querySelector(`[data-endpoint="${endpoint}"]`);
            if (!container) return;

            toggleLoading(true, endpoint);

            const totalRevenueElement = container.querySelector("#total-revenue");
            totalRevenueElement.textContent = formatCurrency(response.total_revenue);

            if (!response.data || response.data.length === 0) {
                toggleLoading(false, endpoint);
                return;
            }

            let dateKey = "date";
            if (response.filter === "weekly") dateKey = "week";
            if (response.filter === "monthly") dateKey = "month";

            const categories = response.data.map(item => item[dateKey]);
            const seriesData = response.data.map(item => item.total_revenue);

            if (window.revenueChart) {
                window.revenueChart.updateOptions({
                    xaxis: { categories },
                    series: [{ name: "Revenue", data: seriesData }]
                });
            } else {
                const options = {
                    chart: { type: "line", height: 350, toolbar: { show: true } },
                    stroke: { curve: "smooth", width: 3 },
                    markers: { size: 5, hover: { size: 7 } },
                    series: [{ name: "Revenue", data: seriesData }],
                    xaxis: { categories },
                    yaxis: { labels: { formatter: value => formatCurrency(value) } },
                    tooltip: { y: { formatter: value => formatCurrency(value) } }
                };

                window.revenueChart = new ApexCharts(container.querySelector("#revenue-chart"), options);
                window.revenueChart.render();
            }

            toggleLoading(false, endpoint);
        }

        function formatCurrency(value) {
            return new Intl.NumberFormat("id-ID", {
                style: "currency",
                currency: "IDR",
                minimumFractionDigits: 0
            }).format(value);
        }

        function updateInpatientCapacityChart(response, endpoint) {
            const container = document.querySelector(`[data-endpoint="${endpoint}"]`);
            if (!container) return;

            toggleLoading(true, endpoint);

            if (!response.data || response.data.length === 0) {
                toggleLoading(false, endpoint);
                return;
            }

            let dateKey = "date";
            if (response.filter === "weekly") dateKey = "week";
            if (response.filter === "monthly") dateKey = "month";

            const categories = response.data.map(item => item[dateKey]);
            const bedsOccupied = response.data.map(item => item.total_beds_occupied);
            const bedsAvailable = response.data.map(item => item.total_beds_available);

            if (window.inpatientChart) {
                window.inpatientChart.updateOptions({
                    xaxis: { categories },
                    series: [
                        { name: "Occupied Beds", data: bedsOccupied },
                        { name: "Available Beds", data: bedsAvailable }
                    ]
                });
            } else {
                const options = {
                    chart: { type: "bar", height: 350, stacked: true, toolbar: { show: true } },
                    series: [
                        { name: "Occupied Beds", data: bedsOccupied },
                        { name: "Available Beds", data: bedsAvailable }
                    ],
                    xaxis: { categories },
                    yaxis: { title: { text: "Number of Beds" } },
                    tooltip: { shared: false },
                    fill: { opacity: 1 }
                };

                window.inpatientChart = new ApexCharts(container.querySelector("#inpatient-chart"), options);
                window.inpatientChart.render();
            }

            toggleLoading(false, endpoint);
        }

    });
</script>

<!-- autofetch -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const cariDataButton = document.querySelector('.cari-data');

        if (cariDataButton) {
            setTimeout(function () {
                cariDataButton.click();
            }, 100);
        }
    });
</script>

<!-- datepicker -->
<script type="text/javascript">
    $(function () {

        var start = moment().startOf('year');
        var end = moment();

        function cb(start, end) {
            $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        }

        $('#reportrange').daterangepicker({
            startDate: start,
            endDate: end,
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                'This Year': [moment().startOf('year'), moment().endOf('year')],
            }
        }, cb);

        cb(start, end);

    });
</script>

<!-- apexchart example -->

</body>

</html>