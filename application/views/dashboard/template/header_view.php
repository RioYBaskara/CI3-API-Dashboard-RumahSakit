<!doctype html>
<!--
* Tabler - Premium and Open Source dashboard template with responsive and high quality UI.
* @version 1.0.0-beta20
* @link https://tabler.io
* Copyright 2018-2023 The Tabler Authors
* Copyright 2018-2023 codecalm.net Paweł Kuna
* Licensed under MIT (https://github.com/tabler/tabler/blob/master/LICENSE)
-->
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <!-- lightmode only -->
    <meta name="color-scheme" content="light only">
    <title>Dashboard - RS Localhost</title>
    <!-- date range picker -->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <!-- CSS files -->
    <link href="<?= base_url() ?>public/assets/tabler/dist/css/tabler.min.css?1692870487" rel="stylesheet" />
    <link href="<?= base_url() ?>public/assets/tabler/dist/css/tabler-flags.min.css?1692870487" rel="stylesheet" />
    <link href="<?= base_url() ?>public/assets/tabler/dist/css/tabler-payments.min.css?1692870487" rel="stylesheet" />
    <link href="<?= base_url() ?>public/assets/tabler/dist/css/tabler-vendors.min.css?1692870487" rel="stylesheet" />
    <link href="<?= base_url() ?>public/assets/tabler/dist/css/demo.min.css?1692870487" rel="stylesheet" />

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Tabler CSS -->
    <link href="https://cdn.jsdelivr.net/npm/@tabler/core@latest/dist/css/tabler.min.css" rel="stylesheet">
    <style>
        @import url('https://rsms.me/inter/inter.css');

        :root {
            --tblr-font-sans-serif: 'Inter Var', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;
            color-scheme: light only;
        }

        body {
            font-feature-settings: "cv03", "cv04", "cv11";
        }

        #loadingSpinner {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.8);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }

        /* button Refresh */
        .fixed-button-Refresh {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1000;
        }

        /* toggle dark/light */
        .toggle-light-dark {
            position: fixed;
            bottom: 28px;
            right: 160px;
            z-index: 1000;
        }

        /* alert */
        .bg-auto {
            background-color: var(--tblr-bg-surface);
            color: var(--tblr-body-color);
        }

        .bg-auto .text-secondary {
            color: var(--tblr-secondary-color) !important;
        }
    </style>
</head>

<body class=" layout-fluid">
    <script src="<?= base_url() ?>public/assets/tabler/dist/js/demo-theme.min.js?1692870487"></script>

    <!-- Dark/Light Mode -->
    <div class="bg-light rounded">
        <div class=" toggle-light-dark p-0" aria-label="Dark/Light Mode Toggle">
            <a href="?theme=dark" class="btn btn-pill btn-outline-dark btn-icon hide-theme-dark"
                title="Enable dark mode" data-bs-toggle="tooltip" data-bs-placement="bottom">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24"
                    stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M12 3c.132 0 .263 0 .393 0a7.5 7.5 0 0 0 7.92 12.446a9 9 0 1 1 -8.313 -12.454z" />
                </svg>
            </a>
            <a href="?theme=light" class="btn btn-pill btn-outline-light btn-icon hide-theme-light"
                title="Enable light mode" data-bs-toggle="tooltip" data-bs-placement="bottom">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24"
                    stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M12 12m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" />
                    <path
                        d="M3 12h1m8 -9v1m8 8h1m-9 8v1m-6.4 -15.4l.7 .7m12.1 -.7l-.7 .7m0 11.4l.7 .7m-12.1 -.7l-.7 .7" />
                </svg>
            </a>
        </div>
    </div>

    <!-- Refresh -->
    <div class="fixed-button-Refresh">
        <button type="button" class="btn btn-pill px-4 py-3 btn-primary d-none" id="normal-button">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="icon icon-tabler icons-tabler-outline icon-tabler-Refresh">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -4v4h4" />
                <path d="M4 13a8.1 8.1 0 0 0 15.5 2m.5 4v-4h-4" />
            </svg>
            <span class="fs-4">Refresh</span>
        </button>

        <a href="#" class="btn btn-pill px-4 py-3 btn-secondary " id="loading-button">
            <span class="spinner-border spinner-border-sm me-2" role="status"></span>
            <span class="fs-4">Loading</span>
        </a>
    </div>

    <!-- Modal Tambah Fasyankes -->
    <div class="modal modal-blur fade" id="modal-add-fasyankes" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Fasyankes Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="form-tambah-fasyankes" action="<?= base_url('fasyankes/create'); ?>" method="post"
                    enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label text-secondary">Kode Fasyankes</label>
                            <input required type="text" class="form-control" name="fasyankes_kode"
                                placeholder="Contoh: 3371014">
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-secondary">Tipe Fasyankes</label>
                            <input required type="text" class="form-control" name="fasyankes_tipe"
                                placeholder="Contoh: RUMAH SAKIT">
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-secondary">Nama Fasyankes</label>
                            <input required type="text" class="form-control" name="fasyankes_nm"
                                placeholder="Contoh: RSU TIDAR">
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-secondary">Alamat Fasyankes</label>
                            <input type="text" class="form-control" name="fasyankes_alamat"
                                placeholder="Contoh: Jl. Tidar No.30 A, Magelang">
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-secondary">Kepala Fasyankes</label>
                            <input type="text" class="form-control" name="fasyankes_kepala" placeholder="Dr. John Doe">
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-secondary">Gambar Fasyankes</label>
                            <input type="file" class="form-control" name="fasyankes_image" accept="image/*">
                            <small class="text-muted">Rasio gambar 16:9, maksimal 2MB. (Opsional)</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-secondary">URL API Fasyankes</label>
                            <input type="text" class="form-control" name="fasyankes_url_api"
                                placeholder="http://localhost/ci3_api_rs/api/reports">
                        </div>
                        <label class="form-label text-secondary">Active?</label>
                        <div class="mb-3 form-selectgroup-boxes row">
                            <div class="col-lg-6">
                                <label class="form-selectgroup-item">
                                    <input type="radio" name="active_st" value="1" class="form-selectgroup-input"
                                        checked>
                                    <span class="p-3 form-selectgroup-label d-flex align-items-center">
                                        <span class="me-3">
                                            <span class="form-selectgroup-check"></span>
                                        </span>
                                        <span class="form-selectgroup-label-content">
                                            <span class="d-block text-secondary">Active</span>
                                        </span>
                                    </span>
                                </label>
                            </div>
                            <div class="col-lg-6">
                                <label class="form-selectgroup-item">
                                    <input type="radio" name="active_st" value="0" class="form-selectgroup-input">
                                    <span class="p-3 form-selectgroup-label d-flex align-items-center">
                                        <span class="me-3">
                                            <span class="form-selectgroup-check"></span>
                                        </span>
                                        <span class="form-selectgroup-label-content">
                                            <span class="d-block text-secondary">Nonactive</span>
                                        </span>
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a href="#" class="btn btn-link link-secondary" data-bs-dismiss="modal">Cancel</a>
                        <button type="submit" id="btn-submit" class="btn btn-primary ms-auto">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M12 5l0 14" />
                                <path d="M5 12l14 0" />
                            </svg>
                            Tambah Fasyankes
                        </button>
                        <a href="#" id="btn-loading" class="btn btn-primary ms-auto d-none">
                            Loading<span class="animated-dots"></span>
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit Fasyankes -->
    <div class="modal modal-blur fade" id="modal-edit-fasyankes" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Fasyankes</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="form-edit-fasyankes" action="<?= base_url('fasyankes/update'); ?>" method="post"
                    enctype="multipart/form-data">
                    <input type="hidden" name="fasyankes_kode" id="edit_fasyankes_kode">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label text-secondary">Tipe Fasyankes</label>
                            <input required type="text" class="form-control" name="fasyankes_tipe"
                                id="edit_fasyankes_tipe">
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-secondary">Nama Fasyankes</label>
                            <input required type="text" class="form-control" name="fasyankes_nm" id="edit_fasyankes_nm">
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-secondary">Alamat Fasyankes</label>
                            <input type="text" class="form-control" name="fasyankes_alamat" id="edit_fasyankes_alamat">
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-secondary">Kepala Fasyankes</label>
                            <input type="text" class="form-control" name="fasyankes_kepala" id="edit_fasyankes_kepala">
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-secondary">Gambar Fasyankes</label>
                            <input type="file" class="form-control" name="fasyankes_image" accept="image/*">
                            <small class="text-muted">Rasio gambar 16:9, maksimal 2MB. (Opsional)</small>
                            <!-- Preview gambar -->
                            <img id="edit_fasyankes_image_preview" src="" alt="Preview Gambar"
                                class="img-fluid mt-2 d-none" style="max-width: 100%;">
                            <!-- Tombol hapus gambar -->
                            <button type="button" id="btn-delete-image" class="btn btn-danger btn-sm mt-2 d-none">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round"
                                    class="icon icon-tabler icons-tabler-outline icon-tabler-trash">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M4 7l16 0" />
                                    <path d="M10 11l0 6" />
                                    <path d="M14 11l0 6" />
                                    <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                                    <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                                </svg>
                                Hapus Gambar
                            </button>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-secondary">URL API Fasyankes</label>
                            <input type="text" class="form-control" name="fasyankes_url_api"
                                id="edit_fasyankes_url_api">
                        </div>
                        <label class="form-label text-secondary">Active?</label>
                        <div class="mb-3 form-selectgroup-boxes row">
                            <div class="col-lg-6">
                                <label class="form-selectgroup-item">
                                    <input type="radio" name="active_st" value="1" class="form-selectgroup-input"
                                        id="edit_active_st_1">
                                    <span class="p-3 form-selectgroup-label d-flex align-items-center">
                                        <span class="me-3">
                                            <span class="form-selectgroup-check"></span>
                                        </span>
                                        <span class="form-selectgroup-label-content">
                                            <span class="d-block text-secondary">Active</span>
                                        </span>
                                    </span>
                                </label>
                            </div>
                            <div class="col-lg-6">
                                <label class="form-selectgroup-item">
                                    <input type="radio" name="active_st" value="0" class="form-selectgroup-input"
                                        id="edit_active_st_0">
                                    <span class="p-3 form-selectgroup-label d-flex align-items-center">
                                        <span class="me-3">
                                            <span class="form-selectgroup-check"></span>
                                        </span>
                                        <span class="form-selectgroup-label-content">
                                            <span class="d-block text-secondary">Nonactive</span>
                                        </span>
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a href="#" class="btn btn-link link-secondary" data-bs-dismiss="modal">Cancel</a>
                        <button type="submit" id="btn-submit-edit" class="btn btn-primary ms-auto">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M12 5l0 14" />
                                <path d="M5 12l14 0" />
                            </svg>
                            Update
                        </button>
                        <a href="#" id="btn-loading-edit" class="btn btn-primary ms-auto d-none">
                            Loading<span class="animated-dots"></span>
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="page">
        <!-- Sidebar -->