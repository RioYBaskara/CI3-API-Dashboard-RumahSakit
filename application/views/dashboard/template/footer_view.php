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
            // "patient-visit-department",
            // "top-diagnoses",
            // "revenue",
            // "inpatient-capacity",
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
                updateSummaryData(data.data);
            } else if (endpoint === "patient-visits") {
                updatePatientVisitsChart(data);
            }
        }

        function updateSummaryData(summaryData, endpoint) {
            toggleLoading(true, endpoint);

            Object.entries(summaryData).forEach(([key, value]) => {
                const element = document.getElementById(`summary-${key.replace(/_/g, "-")}`);
                if (element) {
                    element.textContent = value;
                } else {
                    console.warn(`Elemen tidak ditemukan: ${key}`);
                }
            });

            if (summaryData.date_range) {
                document.getElementById("date-range").textContent = `${summaryData.date_range.start_date} to ${summaryData.date_range.end_date}`;
            }

            toggleLoading(false, endpoint);
        }

        function updatePatientVisitsChart(response, endpoint) {
            toggleLoading(true, endpoint);

            const { date_range, total_summary, data, filter } = response;

            document.getElementById("summary-child").textContent = total_summary.child;
            document.getElementById("summary-adult").textContent = total_summary.adult;
            document.getElementById("summary-elderly").textContent = total_summary.elderly;
            document.getElementById("summary-total").textContent = total_summary.total_patients;
            document.getElementById("date-range").textContent = `${date_range.start_date} to ${date_range.end_date}`;

            let categoryKey = "date";
            if (filter === "weekly") categoryKey = "week";
            if (filter === "monthly") categoryKey = "month";

            const categories = data.map(item => item[categoryKey]);
            const childData = data.map(item => item.child);
            const adultData = data.map(item => item.adult);
            const elderlyData = data.map(item => item.elderly);

            if (window.patientVisitsChart) {
                window.patientVisitsChart.updateOptions({
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

                window.patientVisitsChart = new ApexCharts(document.querySelector("#patient-visits-chart"), options);
                window.patientVisitsChart.render();
            }

            toggleLoading(false, endpoint);
        }

        window.getFilterParams = getFilterParams;
        window.fetchData = fetchData;
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

        var start = moment().subtract(29, 'days');
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