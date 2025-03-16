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
            "patient-new-vs-returning"
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
                    updateUI(endpoint, data.data);
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
            document.querySelectorAll(`[data-endpoint="${endpoint}"] .placeholder`).forEach(placeholder => {
                placeholder.classList.toggle("d-none", !state);
            });

            document.querySelectorAll(`[data-endpoint="${endpoint}"] .data-content`).forEach(content => {
                content.classList.toggle("d-none", state);
            });
        }

        function updateUI(endpoint, data) {
            if (endpoint === "summary") {
                updateSummaryData(data);
            } else {
                updateSingleData(endpoint, data);
            }

            if (chartRegistry[endpoint]) {
                chartRegistry[endpoint].updateSeries([data.total || 0]);
            }
        }
        function updateSummaryData(summaryData) {
            Object.keys(summaryData).forEach(key => {
                const element = document.querySelector(`.${key.replace(/_/g, "-")} .data-content`);
                if (element) {
                    element.textContent = summaryData[key];
                } else {
                    console.warn(`Elemen tidak ditemukan: ${key}`);
                }
            });
        }
        function updateSingleData(endpoint, data) {
            const element = document.querySelector(`[data-endpoint="${endpoint}"] .data-content`);
            if (element) {
                element.textContent = data.total || "N/A";
            }
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
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            }
        }, cb);

        cb(start, end);

    });
</script>

<!-- apexchart example -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // contoh response api
        const response = {
            "status": true,
            "message": "Patient visit report retrieved successfully",
            "filter": "weekly",
            "date_range": "2025-01-01 to 2025-05-01",
            "total_summary": {
                "child": 1,
                "adult": 8,
                "elderly": 0,
                "total_patients": 9
            },
            "data": [
                { "week": "Week 1, January 2025", "child": 1, "adult": 0, "elderly": 0, "total_patients": 1 },
                { "week": "Week 4, January 2025", "child": 0, "adult": 1, "elderly": 0, "total_patients": 1 },
                { "week": "Week 5, February 2025", "child": 0, "adult": 3, "elderly": 0, "total_patients": 3 },
                { "week": "Week 2, March 2025", "child": 0, "adult": 1, "elderly": 0, "total_patients": 1 },
                { "week": "Week 3, March 2025", "child": 0, "adult": 1, "elderly": 0, "total_patients": 1 },
                { "week": "Week 5, March 2025", "child": 0, "adult": 1, "elderly": 0, "total_patients": 1 },
                { "week": "Week 4, April 2025", "child": 0, "adult": 1, "elderly": 0, "total_patients": 1 }
            ]
        };

        // total-summary
        document.getElementById("summary-child").textContent = response.total_summary.child;
        document.getElementById("summary-adult").textContent = response.total_summary.adult;
        document.getElementById("summary-elderly").textContent = response.total_summary.elderly;
        document.getElementById("summary-total").textContent = response.total_summary.total_patients;
        document.getElementById("date-range").textContent = response.date_range;

        const weeks = response.data.map(item => item.week);
        const childData = response.data.map(item => item.child);
        const adultData = response.data.map(item => item.adult);
        const elderlyData = response.data.map(item => item.elderly);

        // apexchart
        var options = {
            chart: {
                type: "line",
                height: 350,
                toolbar: { show: false }
            },
            series: [
                { name: "Child", data: childData },
                { name: "Adult", data: adultData },
                { name: "Elderly", data: elderlyData }
            ],
            xaxis: {
                categories: weeks
            }
        };

        var chart = new ApexCharts(document.querySelector("#patient-visits-chart"), options);
        chart.render();
    });
</script>
</body>

</html>