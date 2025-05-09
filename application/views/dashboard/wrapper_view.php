<!-- loading spinner -->
<div id="loadingSpinner" class="d-none">
    <div class="spinner-grow text-primary" role="status"></div>
</div>

<div class="page-wrapper">
    <!-- Page header -->
    <div class="page-header d-print-none pb-4">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <!-- Page pre-title -->
                    <div class="page-pretitle fs-5">
                        Dashboard
                    </div>
                    <h2 class="page-title fs-1">
                        <!-- dinamiskan -->
                        <?= $active_fasyankes ? $active_fasyankes['fasyankes_nm'] : 'RS Undefined' ?>
                    </h2>
                </div>
                <!-- Page title actions -->
                <div class="col-auto ms-auto d-print-none">
                    <div class="row g-2 ">
                        <!-- Date Range Picker -->
                        <div class="col">
                            <div tabindex="2" id="reportrange"
                                class="form-control d-flex align-items-center justify-content-between"
                                style="cursor: pointer; border: 1px solid #ccc;">
                                <i class="fa fa-calendar me-2"></i>
                                <span></span>
                                <i class="fa fa-caret-down ms-auto"></i>
                            </div>
                        </div>

                        <!-- Select Filter & Button -->
                        <div class="col-12 d-flex gap-2">
                            <!-- Select Filter -->
                            <div class="flex-grow-1">
                                <div class="form-floating">
                                    <select tabindex="1" class="form-select" id="filterdata"
                                        aria-label="Floating label select example">
                                        <option value="daily">Daily</option>
                                        <option value="weekly">Weekly</option>
                                        <option value="monthly">Monthly</option>
                                    </select>
                                    <label for="filterdata">Filter Data</label>
                                </div>
                            </div>

                            <!-- Button Cari Data -->
                            <button tabindex="3" href="#" class="btn btn-primary cari-data d-none d-sm-inline-block">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round"
                                    class="icon icon-tabler icons-tabler-outline icon-tabler-calendar-search">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M11.5 21h-5.5a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v4.5" />
                                    <path d="M16 3v4" />
                                    <path d="M8 3v4" />
                                    <path d="M4 11h16" />
                                    <path d="M18 18m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
                                    <path d="M20.2 20.2l1.8 1.8" />
                                </svg>
                                Cari Data
                            </button>

                            <!-- Button versi Mobile -->
                            <button href="#" class="btn btn-primary cari-data d-sm-none btn-icon"
                                aria-label="Cari Data">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round"
                                    class="icon icon-tabler icons-tabler-outline icon-tabler-calendar-search">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M11.5 21h-5.5a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v4.5" />
                                    <path d="M16 3v4" />
                                    <path d="M8 3v4" />
                                    <path d="M4 11h16" />
                                    <path d="M18 18m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
                                    <path d="M20.2 20.2l1.8 1.8" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- Page body -->
    <div class="page-body">
        <div class="container-xl">
            <div class="row row-deck row-cards">
                <div class="col-12">
                    <div class="row row-cards">
                        <!-- RS Profile -->
                        <!-- dinamiskan -->
                        <div>
                            <div class="row row-deck">
                                <div class="col-md-4">
                                    <div class="card">
                                        <div class="card-header">
                                            <h3 class="card-title ">
                                                <?= $active_fasyankes ? $active_fasyankes['fasyankes_nm'] : 'RS Undefined' ?>
                                            </h3>
                                        </div>
                                        <div class="card-body">
                                            <div class="text-center">
                                                <!-- Placeholder Gambar -->
                                                <div class="ratio ratio-16x9 mb-3">
                                                    <img src="<?= base_url() ?>private/assets/img/<?= $active_fasyankes['fasyankes_image'] ?>"
                                                        alt="RS Localhost" class="img-fluid rounded"
                                                        style="object-fit: cover;">
                                                </div>
                                                <h4 class="mb-1 fs-2 fw-bold">
                                                    <?= $active_fasyankes ? $active_fasyankes['fasyankes_nm'] : 'RS Undefined' ?>
                                                </h4>
                                                <p class="text-muted fs-5">Rumah Sakit Terbaik di Kota</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="card">
                                        <div class="card-header">
                                            <h3 class="card-title">Informasi Fasyankes</h3>
                                        </div>
                                        <div class="card-body">
                                            <div class="row align-items-center h-100">
                                                <div class="col-md-6">
                                                    <div class="mb-4"> <!-- Tambahkan margin-bottom yang lebih besar -->
                                                        <label class="form-label text-muted small m-0">Kode
                                                            Fasyankes</label>
                                                        <div class="form-control-plaintext fw-bold p-0">
                                                            <b><?= $active_fasyankes ? $active_fasyankes['fasyankes_kode'] : 'undefined' ?></b>
                                                        </div>
                                                    </div>
                                                    <div class="mb-4">
                                                        <label class="form-label text-muted small m-0">Tipe
                                                            Fasyankes</label>
                                                        <div class="form-control-plaintext fw-bold p-0">
                                                            <b><?= $active_fasyankes ? $active_fasyankes['fasyankes_tipe'] : 'undefined' ?>
                                                            </b>
                                                        </div>
                                                    </div>
                                                    <div class="mb-4">
                                                        <label class="form-label text-muted small m-0">Nama
                                                            Fasyankes</label>
                                                        <div class="form-control-plaintext fw-bold p-0">
                                                            <b><?= $active_fasyankes ? $active_fasyankes['fasyankes_nm'] : 'RS undefined' ?>
                                                            </b>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-4">
                                                        <label class="form-label text-muted small m-0">Alamat
                                                            Fasyankes</label>
                                                        <div class="form-control-plaintext fw-bold p-0">
                                                            <b><?= $active_fasyankes ? $active_fasyankes['fasyankes_alamat'] : 'undefined' ?></b>
                                                        </div>
                                                    </div>
                                                    <div class="mb-4">
                                                        <label class="form-label text-muted small m-0">Kepala
                                                            Fasyankes</label>
                                                        <div class="form-control-plaintext fw-bold p-0">
                                                            <b><?= $active_fasyankes ? $active_fasyankes['fasyankes_kepala'] : 'undefined' ?></b>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- summary -->
                        <div class="summary" data-endpoint="summary">
                            <div class="card h-100">
                                <div class="card-header">
                                    <h3 class="card-title w-100 text-center">Summary Data</h3>
                                </div>
                                <div class="card-body py-3 px-4">
                                    <div class="row">
                                        <div class="col-sm-6 col-lg-3  py-2 px-3">
                                            <a href="" onclick="return false;" style="cursor: default;" tabindex="-1"
                                                class="card card-sm h-100">
                                                <div class="card-body d-flex flex-column">
                                                    <div class="row align-items-center flex-grow-1">
                                                        <div class="col-auto">
                                                            <span class="bg-primary text-white avatar">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                    height="24" viewBox="0 0 24 24" fill="none"
                                                                    stroke="currentColor" stroke-width="2"
                                                                    stroke-linecap="round" stroke-linejoin="round"
                                                                    class="icon icon-tabler icons-tabler-outline icon-tabler-calendar-clock">
                                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                                    <path
                                                                        d="M10.5 21h-4.5a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v3" />
                                                                    <path d="M16 3v4" />
                                                                    <path d="M8 3v4" />
                                                                    <path d="M4 11h10" />
                                                                    <path
                                                                        d="M18 18m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" />
                                                                    <path d="M18 16.5v1.5l.5 .5" />
                                                                </svg>
                                                            </span>
                                                        </div>
                                                        <div class="col">
                                                            <div class="text-secondary fs-6">Appointments</div>
                                                            <div class="font-weight-medium total-appointments"
                                                                id="summary-total-appointments">
                                                                <p class="placeholder-glow mb-0">
                                                                    <span class="placeholder col-6"></span>
                                                                </p>
                                                                <span
                                                                    class="data-content d-none data-response fw-bold fs-2">undefined</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>

                                        <div class="col-sm-6 col-lg-3  py-2 px-3">
                                            <a href="" onclick="return false;" style="cursor: default;" tabindex="-1"
                                                class="card card-sm h-100">
                                                <div class="card-body d-flex flex-column">
                                                    <div class="row align-items-center flex-grow-1">
                                                        <div class="col-auto">
                                                            <span class="bg-primary text-white avatar">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                    height="24" viewBox="0 0 24 24" fill="none"
                                                                    stroke="currentColor" stroke-width="2"
                                                                    stroke-linecap="round" stroke-linejoin="round"
                                                                    class="icon icon-tabler icons-tabler-outline icon-tabler-calendar-check">
                                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                                    <path
                                                                        d="M11.5 21h-5.5a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v6" />
                                                                    <path d="M16 3v4" />
                                                                    <path d="M8 3v4" />
                                                                    <path d="M4 11h16" />
                                                                    <path d="M15 19l2 2l4 -4" />
                                                                </svg>
                                                            </span>
                                                        </div>
                                                        <div class="col">
                                                            <div class="text-secondary fs-6">Completed Appointments
                                                            </div>
                                                            <div class="font-weight-medium total-completed-appointments"
                                                                id="summary-total-completed-appointments">
                                                                <p class="placeholder-glow mb-0">
                                                                    <span class="placeholder col-6"></span>
                                                                </p>
                                                                <span
                                                                    class="data-content d-none data-response fw-bold fs-2">undefined</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>

                                        <div class="col-sm-6 col-lg-3  py-2 px-3">
                                            <a href="" onclick="return false;" style="cursor: default;" tabindex="-1"
                                                class="card card-sm h-100">
                                                <div class="card-body d-flex flex-column">
                                                    <div class="row align-items-center flex-grow-1">
                                                        <div class="col-auto">
                                                            <span class="bg-primary text-white avatar">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                    height="24" viewBox="0 0 24 24" fill="none"
                                                                    stroke="currentColor" stroke-width="2"
                                                                    stroke-linecap="round" stroke-linejoin="round"
                                                                    class="icon icon-tabler icons-tabler-outline icon-tabler-calendar-cancel">
                                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                                    <path
                                                                        d="M12.5 21h-6.5a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v5" />
                                                                    <path d="M16 3v4" />
                                                                    <path d="M8 3v4" />
                                                                    <path d="M4 11h16" />
                                                                    <path
                                                                        d="M19 19m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
                                                                    <path d="M17 21l4 -4" />
                                                                </svg>
                                                            </span>
                                                        </div>
                                                        <div class="col">
                                                            <div class="text-secondary fs-6">Cancelled Appointments
                                                            </div>
                                                            <div class="font-weight-medium total-cancelled-appointments"
                                                                id="summary-total-cancelled-appointments">
                                                                <p class="placeholder-glow mb-0">
                                                                    <span class="placeholder col-6"></span>
                                                                </p>
                                                                <span
                                                                    class="data-content d-none data-response fw-bold fs-2">undefined</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>

                                        <div class="col-sm-6 col-lg-3  py-2 px-3">
                                            <a href="" onclick="return false;" style="cursor: default;" tabindex="-1"
                                                class="card card-sm h-100">
                                                <div class="card-body d-flex flex-column">
                                                    <div class="row align-items-center flex-grow-1">
                                                        <div class="col-auto">
                                                            <span class="bg-green text-white avatar">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                    height="24" viewBox="0 0 24 24" fill="none"
                                                                    stroke="currentColor" stroke-width="2"
                                                                    stroke-linecap="round" stroke-linejoin="round"
                                                                    class="icon icon-tabler icons-tabler-outline icon-tabler-report-medical">
                                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                                    <path
                                                                        d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2" />
                                                                    <path
                                                                        d="M9 3m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z" />
                                                                    <path d="M10 14l4 0" />
                                                                    <path d="M12 12l0 4" />
                                                                </svg>
                                                            </span>
                                                        </div>
                                                        <div class="col">
                                                            <div class="text-secondary fs-6">Medical Records</div>
                                                            <div class="font-weight-medium total-medical-records"
                                                                id="summary-total-medical-records">
                                                                <p class="placeholder-glow mb-0">
                                                                    <span class="placeholder col-6"></span>
                                                                </p>
                                                                <span
                                                                    class="data-content d-none data-response fw-bold fs-2">undefined</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>

                                        <div class="col-sm-6 col-lg-3  py-2 px-3">
                                            <a href="" onclick="return false;" style="cursor: default;" tabindex="-1"
                                                class="card card-sm h-100">
                                                <div class="card-body d-flex flex-column">
                                                    <div class="row align-items-center flex-grow-1">
                                                        <div class="col-auto">
                                                            <span class="bg-cyan text-white avatar">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                    height="24" viewBox="0 0 24 24" fill="none"
                                                                    stroke="currentColor" stroke-width="2"
                                                                    stroke-linecap="round" stroke-linejoin="round"
                                                                    class="icon icon-tabler icons-tabler-outline icon-tabler-user-plus">
                                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                                    <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                                                                    <path d="M16 19h6" />
                                                                    <path d="M19 16v6" />
                                                                    <path d="M6 21v-2a4 4 0 0 1 4 -4h4" />
                                                                </svg>
                                                            </span>
                                                        </div>
                                                        <div class="col">
                                                            <div class="text-secondary fs-6">New Patients</div>
                                                            <div class="font-weight-medium total-new-patients"
                                                                id="summary-total-new-patients">
                                                                <p class="placeholder-glow mb-0">
                                                                    <span class="placeholder col-6"></span>
                                                                </p>
                                                                <span
                                                                    class="data-content d-none data-response fw-bold fs-2">undefined</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>

                                        <div class="col-sm-6 col-lg-3  py-2 px-3">
                                            <a href="" onclick="return false;" style="cursor: default;" tabindex="-1"
                                                class="card card-sm h-100">
                                                <div class="card-body d-flex flex-column">
                                                    <div class="row align-items-center flex-grow-1">
                                                        <div class="col-auto">
                                                            <span class="bg-cyan text-white avatar">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                    height="24" viewBox="0 0 24 24" fill="none"
                                                                    stroke="currentColor" stroke-width="2"
                                                                    stroke-linecap="round" stroke-linejoin="round"
                                                                    class="icon icon-tabler icons-tabler-outline icon-tabler-bed-flat">
                                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                                    <path d="M5 11m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                                                    <path d="M10 13h11v-2a3 3 0 0 0 -3 -3h-8v5z" />
                                                                    <path d="M3 16h18" />
                                                                </svg>
                                                            </span>
                                                        </div>
                                                        <div class="col">
                                                            <div class="text-secondary fs-6">Inpatients</div>
                                                            <div class="font-weight-medium total-inpatients"
                                                                id="summary-total-inpatients">
                                                                <p class="placeholder-glow mb-0">
                                                                    <span class="placeholder col-6"></span>
                                                                </p>
                                                                <span
                                                                    class="data-content d-none data-response fw-bold fs-2">undefined</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>

                                        <div class="col-sm-6 col-lg-3  py-2 px-3">
                                            <a href="" onclick="return false;" style="cursor: default;" tabindex="-1"
                                                class="card card-sm h-100">
                                                <div class="card-body d-flex flex-column">
                                                    <div class="row align-items-center flex-grow-1">
                                                        <div class="col-auto">
                                                            <span class="bg-yellow text-white avatar">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                    height="24" viewBox="0 0 24 24" fill="none"
                                                                    stroke="currentColor" stroke-width="2"
                                                                    stroke-linecap="round" stroke-linejoin="round"
                                                                    class="icon icon-tabler icons-tabler-outline icon-tabler-file-dollar">
                                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                                    <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                                                                    <path
                                                                        d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
                                                                    <path
                                                                        d="M14 11h-2.5a1.5 1.5 0 0 0 0 3h1a1.5 1.5 0 0 1 0 3h-2.5" />
                                                                    <path d="M12 17v1m0 -8v1" />
                                                                </svg>
                                                            </span>
                                                        </div>
                                                        <div class="col">
                                                            <div class="text-secondary fs-6">Paid Invoices</div>
                                                            <div class="font-weight-medium total-paid-invoices"
                                                                id="summary-total-paid-invoices">
                                                                <p class="placeholder-glow mb-0">
                                                                    <span class="placeholder col-6"></span>
                                                                </p>
                                                                <span
                                                                    class="data-content d-none data-response fw-bold fs-2">undefined</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>

                                        <div class="col-sm-6 col-lg-3  py-2 px-3">
                                            <a href="" onclick="return false;" style="cursor: default;" tabindex="-1"
                                                class="card card-sm h-100">
                                                <div class="card-body d-flex flex-column">
                                                    <div class="row align-items-center flex-grow-1">
                                                        <div class="col-auto">
                                                            <span class="bg-yellow text-white avatar">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                    height="24" viewBox="0 0 24 24" fill="none"
                                                                    stroke="currentColor" stroke-width="2"
                                                                    stroke-linecap="round" stroke-linejoin="round"
                                                                    class="icon icon-tabler icons-tabler-outline icon-tabler-coin">
                                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                                    <path
                                                                        d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                                                                    <path
                                                                        d="M14.8 9a2 2 0 0 0 -1.8 -1h-2a2 2 0 1 0 0 4h2a2 2 0 1 1 0 4h-2a2 2 0 0 1 -1.8 -1" />
                                                                    <path d="M12 7v10" />
                                                                </svg>
                                                            </span>
                                                        </div>
                                                        <div class="col">
                                                            <div class="text-secondary fs-6">Total Revenue</div>
                                                            <div class="font-weight-medium total-paid-revenue"
                                                                id="summary-total-paid-revenue">
                                                                <p class="placeholder-glow mb-0">
                                                                    <span class="placeholder col-6"></span>
                                                                </p>
                                                                <span
                                                                    class="data-content d-none data-response fw-bold fs-2">undefined</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- patient visits -->
                        <div class="col-md-6 patient-visits" data-endpoint="patient-visits">
                            <div class="card h-100">
                                <div class="card-header">
                                    <h3 class="card-title">Patient Visits</h3>
                                    <div class="card-options">
                                        <p class="placeholder-glow mb-0">
                                            <span class="placeholder col-6 d-inline-block" style="width: 100px;"></span>
                                        </p>
                                        <span class="date-range data-response d-none">undefined to undefined</span>
                                    </div>
                                </div>
                                <div class="card-body d-flex flex-column">
                                    <div class="row mb-3 summary">
                                        <div class="col-md-3 col-6 p-1">
                                            <a href="" onclick="return false;" style="cursor: default;" tabindex="-1"
                                                class="card bg-primary-lt h-100">
                                                <div
                                                    class="card-body text-center p-2 d-flex flex-column justify-content-center">
                                                    <h4 class="mb-0 fs-6 fw-light">Child</h4>
                                                    <p class="placeholder-glow mb-0">
                                                        <span class="placeholder col-6"></span>
                                                    </p>
                                                    <p class="fs-3 fw-bold data-response fs-2 d-none summary-child">
                                                        undefined</p>
                                                </div>
                                            </a>
                                        </div>
                                        <div class="col-md-3 col-6 p-1">
                                            <a href="" onclick="return false;" style="cursor: default;" tabindex="-1"
                                                class="card bg-success-lt h-100">
                                                <div
                                                    class="card-body text-center p-2 d-flex flex-column justify-content-center">
                                                    <h4 class="mb-0 fs-6 fw-light">Adult</h4>
                                                    <p class="placeholder-glow mb-0">
                                                        <span class="placeholder col-6"></span>
                                                    </p>
                                                    <p class="fs-3 fw-bold data-response fs-2 d-none summary-adult">
                                                        undefined</p>
                                                </div>
                                            </a>
                                        </div>
                                        <div class="col-md-3 col-6 p-1">
                                            <a href="" onclick="return false;" style="cursor: default;" tabindex="-1"
                                                class="card bg-warning-lt h-100">
                                                <div
                                                    class="card-body text-center p-2 d-flex flex-column justify-content-center">
                                                    <h4 class="mb-0 fs-6 fw-light">Elderly</h4>
                                                    <p class="placeholder-glow mb-0">
                                                        <span class="placeholder col-6"></span>
                                                    </p>
                                                    <p class="fs-3 fw-bold data-response fs-2 d-none summary-elderly">
                                                        undefined</p>
                                                </div>
                                            </a>
                                        </div>
                                        <div class="col-md-3 col-6 p-1">
                                            <a href="" onclick="return false;" style="cursor: default;" tabindex="-1"
                                                class="card h-100">
                                                <div
                                                    class="card-body text-center p-2 d-flex flex-column justify-content-center">
                                                    <h4 class="mb-0 fs-6 fw-light">Total Patients</h4>
                                                    <p class="placeholder-glow mb-0">
                                                        <span class="placeholder col-6"></span>
                                                    </p>
                                                    <p class="fs-3 fw-bold data-response fs-2 d-none summary-total">
                                                        undefined</p>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                    <div id="patient-visits-chart" class="flex-grow-1"></div>
                                </div>
                            </div>
                        </div>

                        <!-- patient visits by department -->
                        <div class="col-md-6 patient-visit-department" data-endpoint="patient-visit-department">
                            <div class="card h-100">
                                <div class="card-header">
                                    <h3 class="card-title">Patient Visits By Department</h3>
                                    <div class="card-options">
                                        <p class="placeholder-glow mb-0">
                                            <span class="placeholder col-6 d-inline-block" style="width: 100px;"></span>
                                        </p>
                                        <span class="date-range data-response d-none">undefined to undefined</span>
                                    </div>
                                </div>
                                <div class="card-body d-flex flex-column">
                                    <div class="row mb-3" id="summary-container">
                                        <div class="col-12 p-2">
                                            <a href="" onclick="return false;" style="cursor: default;" tabindex="-1"
                                                class="card bg-info-lt h-100">
                                                <div
                                                    class="card-body text-center p-2 d-flex flex-column justify-content-center">
                                                    <h4 class="mb-0 fs-6 fw-light">Total Appointments</h4>
                                                    <p class="placeholder-glow mb-0">
                                                        <span class="placeholder col-6"></span>
                                                    </p>
                                                    <p class="fs-3 fw-bold data-response d-none fs-2"
                                                        id="summary-total-appointments">undefined</p>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                    <div id="patient-visit-department-chart" class="flex-grow-1"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Top Diagnoses -->
                        <div class="col-md-5 top-diagnoses" data-endpoint="top-diagnoses">
                            <div class="card h-100">
                                <div class="card-header">
                                    <h3 class="card-title">Most Common Diagnoses</h3>
                                    <div class="card-options">
                                        <p class="placeholder-glow mb-0">
                                            <span class="placeholder col-6 d-inline-block" style="width: 100px;"></span>
                                        </p>
                                        <span class="date-range data-response d-none">undefined to undefined</span>
                                    </div>
                                </div>
                                <div class="card-body d-flex flex-column">
                                    <div class="table-responsive flex-grow-1">
                                        <table class="table card-table table-hover" id="top-diagnoses-table">
                                            <thead>
                                                <tr></tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- inpatient capacity -->
                        <div class="col-md-7 inpatient-capacity" data-endpoint="inpatient-capacity">
                            <div class="card h-100">
                                <div class="card-header">
                                    <h3 class="card-title">Inpatient Capacity Report</h3>
                                    <div class="card-options">
                                        <p class="placeholder-glow mb-0">
                                            <span class="placeholder col-6 d-inline-block" style="width: 100px;"></span>
                                        </p>
                                        <span class="date-range data-response d-none">undefined to undefined</span>
                                    </div>
                                </div>
                                <div class="card-body d-flex flex-column">
                                    <div id="inpatient-chart" class="flex-grow-1"></div>
                                </div>
                            </div>
                        </div>

                        <!-- revenue -->
                        <div class="col-md-6 revenue" data-endpoint="revenue">
                            <div class="card h-100">
                                <div class="card-header">
                                    <h3 class="card-title">Revenue Report</h3>
                                    <div class="card-options d-flex flex-column">
                                        <p class="placeholder-glow mb-0">
                                            <span class="placeholder col-6 d-inline-block" style="width: 100px;"></span>
                                        </p>
                                        <span class="date-range data-response d-none">undefined to undefined</span>
                                    </div>
                                </div>
                                <div class="card-body d-flex flex-column">
                                    <div id="revenue-chart" class="flex-grow-1"></div>
                                    <div class="mt-3">
                                        <span class="fw-bold">Total Revenue: </span>
                                        <p class="placeholder-glow mb-0">
                                            <span class="placeholder col-6 d-inline-block" style="width: 100px;"></span>
                                        </p>
                                        <span id="total-revenue"
                                            class="fs-3 fw-bold text-success data-response d-none">Rp undefined</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- new vs returning patients -->
                        <div class="col-md-6 patient-comparison" data-endpoint="patient-new-vs-returning">
                            <div class="card h-100">
                                <div class="card-header">
                                    <h3 class="card-title">New vs Returning Patients</h3>
                                    <div class="card-options d-flex flex-column">
                                        <p class="placeholder-glow mb-0">
                                            <span class="placeholder col-6 d-inline-block" style="width: 100px;"></span>
                                        </p>
                                        <span class="date-range data-response d-none">undefined to undefined</span>
                                    </div>
                                </div>
                                <div class="card-body d-flex flex-column">
                                    <div id="patients-chart" class="flex-grow-1"></div>
                                    <div class="mt-3">
                                        <span class="fw-bold">Total New Patients:</span>
                                        <span id="total-new-patients"
                                            class="fs-5 fw-bold text-primary data-response d-none">undefined</span>
                                        <br>
                                        <span class="fw-bold">Total Returning Patients:</span>
                                        <span id="total-returning-patients"
                                            class="fs-5 fw-bold text-success data-response d-none">undefined</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div v>