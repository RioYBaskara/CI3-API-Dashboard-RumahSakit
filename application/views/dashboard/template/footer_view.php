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

<!-- fasyankes -->
<script>
    const baseUrl = '<?= base_url() ?>';

    document.addEventListener("DOMContentLoaded", function () {
        // create
        $('#form-tambah-fasyankes').on('submit', function (e) {
            e.preventDefault();

            $('#btn-submit').addClass('d-none');
            $('#btn-loading').removeClass('d-none');

            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: new FormData(this),
                processData: false,
                contentType: false,
                success: function (response) {
                    response = JSON.parse(response);

                    $('#btn-submit').removeClass('d-none');
                    $('#btn-loading').addClass('d-none');

                    if (response.status == 'success') {
                        $('#form-tambah-fasyankes')[0].reset();

                        $('#modal-add-fasyankes').modal('hide');

                        showFlashAlert(response.message, 'success');

                        setTimeout(function () {
                            window.location.href = response.redirect;
                        }, 500);

                    } else {
                        showFlashAlert(response.message, 'danger');
                    }
                },
                error: function (xhr, status, error) {
                    $('#btn-submit').removeClass('d-none');
                    $('#btn-loading').addClass('d-none');

                    showFlashAlert('Terjadi kesalahan. Silakan coba lagi.', 'danger');
                }
            });
        });

        // edit
        $('#form-edit-fasyankes').on('submit', function (e) {
            e.preventDefault();

            $('#btn-submit-edit').addClass('d-none');
            $('#btn-loading-edit').removeClass('d-none');

            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: new FormData(this),
                processData: false,
                contentType: false,
                success: function (response) {
                    response = JSON.parse(response);

                    $('#btn-submit-edit').removeClass('d-none');
                    $('#btn-loading-edit').addClass('d-none');

                    if (response.status == 'success') {
                        $('#form-edit-fasyankes')[0].reset();

                        $('#modal-edit-fasyankes').modal('hide');

                        showFlashAlert(response.message, 'success');

                        setTimeout(function () {
                            window.location.href = response.redirect;
                        }, 500);
                    } else {
                        showFlashAlert(response.message, 'danger');
                    }
                },
                error: function (xhr, status, error) {
                    $('#btn-submit-edit').removeClass('d-none');
                    $('#btn-loading-edit').addClass('d-none');

                    showFlashAlert('Terjadi kesalahan. Silakan coba lagi.', 'danger');
                }
            });
        });

        function showFlashAlert(message, type) {
            const alert = `
                        <div class="alert alert-blur alert-important alert-${type} alert-dismissible position-fixed bg-auto"
                            style="top: 20px; right: 20px; z-index: 2050; max-width: 50rem;">
                            <div class="d-flex">
                                <div>
                                    <!-- SVG icon -->
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                        class="icon icon-tabler icons-tabler-outline icon-tabler-${type === 'success' ? 'check' : 'x'}">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        ${type === 'success' ? '<path d="M5 12l5 5l10 -10" />' : '<path d="M18 6l-12 12" /><path d="M6 6l12 12" />'}
                                    </svg>
                                </div>
                                <div class="ms-2">
                                    <h4 class="alert-title text-${type}">${type === 'success' ? 'Success!' : 'Error!'}</h4>
                                    <div class="text-secondary">${message}</div>
                                </div>
                            </div>
                            <a class="btn-close" data-bs-dismiss="alert" aria-label="Close"></a>
                        </div>
                    `;

            $('body').append(alert);

            setTimeout(() => {
                $('.alert').fadeOut('slow', function () {
                    $(this).remove();
                });
            }, 5000);
        }
    })
</script>

<!-- edit fasyankes -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        $('#modal-edit-fasyankes').on('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const fasyankes = JSON.parse(button.getAttribute('data-fasyankes'));

            $('#edit_fasyankes_kode').val(fasyankes.fasyankes_kode);
            $('#edit_fasyankes_tipe').val(fasyankes.fasyankes_tipe);
            $('#edit_fasyankes_nm').val(fasyankes.fasyankes_nm);
            $('#edit_fasyankes_alamat').val(fasyankes.fasyankes_alamat);
            $('#edit_fasyankes_kepala').val(fasyankes.fasyankes_kepala);
            $('#edit_fasyankes_url_api').val(fasyankes.fasyankes_url_api);
            $(`input[name="active_st"][value="${fasyankes.is_active}"]`).prop('checked', true);

            if (fasyankes.fasyankes_image && fasyankes.fasyankes_image !== 'default.jpg') {
                $('#edit_fasyankes_image_preview').attr('src', baseUrl + 'private/assets/img/' + fasyankes.fasyankes_image).removeClass('d-none');
            } else {
                $('#edit_fasyankes_image_preview').addClass('d-none');
            }
        });
    });
</script>

<!-- fetch api -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // dinamiskan
        const baseUrl = "<?= $active_fasyankes ? $active_fasyankes['fasyankes_url_api'] : 'undefined' ?>";

        // loading stuff
        const loadingSpinner = document.getElementById("loadingSpinner");
        const normalButton = document.getElementById("normal-button");
        const loadingButton = document.getElementById("loading-button");

        // API Endpoints
        const endpoints = [
            "summary",
            "patient-visits",
            "patient-visit-department",
            "top-diagnoses",
            "revenue",
            "inpatient-capacity",
            "patient-new-vs-returning"
        ];

        // cari-data
        document.querySelector(".cari-data").addEventListener("click", function (event) {
            event.preventDefault();
            fetchAllData();
        });

        // refresh-button
        normalButton.addEventListener("click", function () {
            event.preventDefault();
            fetchAllData();
        });

        // fetch All Data
        async function fetchAllData() {
            loadingSpinner.classList.remove("d-none");
            normalButton.classList.add("d-none");
            loadingButton.classList.remove("d-none");

            try {
                await Promise.all(endpoints.map(fetchData));
            } finally {
                loadingSpinner.classList.add("d-none");
                loadingButton.classList.add("d-none");
                normalButton.classList.remove("d-none");
            }
        }

        // fetch data per endpoint
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

        // get Filter
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
        // format Date
        function formatDate(dateString) {
            const date = new Date(dateString);
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, "0");
            const day = String(date.getDate()).padStart(2, "0");
            return `${year}-${month}-${day}`;
        }

        // Toggle loading
        // loading types: placeholder glow, progress bar
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

        // Update UI Report based on data from fetchdata()
        // new endpoint=new updateUI function
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
            } else if (endpoint === "patient-new-vs-returning") {
                updateDateRange(endpoint, data.date_range);
                updatePatientChart(data, endpoint);
            }
        }

        // update Date Range
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

        // endpoint:/summary
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

        // endpoint:/patient-visits
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
                    chart: { type: "line", height: 250, toolbar: { show: true } },
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

        // endpoint:/patient-visit-department
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
                    colDiv.className = "col-md-3 col-6";
                    colDiv.innerHTML = `
                <a href="" onclick="return false;" style="cursor: default;" tabindex="-1" class="card bg-primary-lt">
                    <div class="card-body text-center p-2">
                        <h4 class="mb-0 fs-6 fw-light">${dept}</h4>
                        <p class="placeholder-glow mb-0">
                            <span class="placeholder col-6"></span>
                        </p>
                        <p class="fs-3 fw-bold data-response d-none fs-2" id="summary-${dept.replace(/\s+/g, "-").toLowerCase()}">${count}</p>
                    </div>
                </a>
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
                    chart: { type: "bar", height: 250, toolbar: { show: true } },
                    series: seriesData,
                    xaxis: { categories }
                };

                let chart = new ApexCharts(chartEl, options);
                chart.render();

                chartEl.chartInstance = chart;
            }

            toggleLoading(false, endpoint);
        }

        // endpoint:/top-diagnoses
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

        // endpoint:/revenue
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
                    chart: { type: "line", height: 250, toolbar: { show: true } },
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
        // to IDR
        function formatCurrency(value) {
            return new Intl.NumberFormat("id-ID", {
                style: "currency",
                currency: "IDR",
                minimumFractionDigits: 0
            }).format(value);
        }

        // endpoint:/inpatient-capacity
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
                    chart: { type: "bar", height: 250, stacked: true, toolbar: { show: true } },
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

        // endpoint:/patient-new-vs-returning
        function updatePatientChart(response, endpoint) {
            const container = document.querySelector(`[data-endpoint="${endpoint}"]`);
            if (!container) return;

            toggleLoading(true, endpoint);

            const totalNewPatientsElement = container.querySelector("#total-new-patients");
            const totalReturningPatientsElement = container.querySelector("#total-returning-patients");

            totalNewPatientsElement.textContent = response.total_summary.new_patients;
            totalReturningPatientsElement.textContent = response.total_summary.returning_patients;

            if (!response.data || response.data.length === 0) {
                toggleLoading(false, endpoint);
                return;
            }

            let dateKey = "date";
            if (response.filter === "weekly") dateKey = "week";
            if (response.filter === "monthly") dateKey = "month";

            const categories = response.data.map(item => item[dateKey]);
            const newPatientsData = response.data.map(item => item.new_patients);
            const returningPatientsData = response.data.map(item => item.returning_patients);

            if (window.patientsChart) {
                window.patientsChart.updateOptions({
                    xaxis: { categories },
                    series: [
                        { name: "New Patients", data: newPatientsData },
                        { name: "Returning Patients", data: returningPatientsData }
                    ]
                });
            } else {
                const options = {
                    chart: { type: "line", height: 250, toolbar: { show: true } },
                    series: [
                        { name: "New Patients", data: newPatientsData },
                        { name: "Returning Patients", data: returningPatientsData }
                    ],
                    xaxis: { categories },
                    yaxis: { labels: { formatter: value => value.toLocaleString() } },
                    tooltip: { y: { formatter: value => value.toLocaleString() } },
                    plotOptions: {
                        bar: { columnWidth: "50%", grouped: true }
                    }
                };

                window.patientsChart = new ApexCharts(container.querySelector("#patients-chart"), options);
                window.patientsChart.render();
            }

            toggleLoading(false, endpoint);
        }

    });
</script>

<!-- autofetch after page load -->
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
</body>

</html>