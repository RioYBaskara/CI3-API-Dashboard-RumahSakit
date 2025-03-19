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
    </style>
</head>

<body class=" layout-fluid">
    <script src="<?= base_url() ?>public/assets/tabler/dist/js/demo-theme.min.js?1692870487"></script>

    <!-- Refresh -->
    <div class="fixed-button-Refresh">
        <button type="button" class="btn btn-pill px-5 py-3 btn-primary d-none" id="normal-button">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="icon icon-tabler icons-tabler-outline icon-tabler-Refresh">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -4v4h4" />
                <path d="M4 13a8.1 8.1 0 0 0 15.5 2m.5 4v-4h-4" />
            </svg>
            <span class="fs-2">Refresh</span>
        </button>

        <a href="#" class="btn btn-pill px-5 py-3 btn-secondary " id="loading-button">
            <span class="spinner-border spinner-border-sm me-2" role="status"></span>
            <span class="fs-2">Loading</span>
        </a>
    </div>

    <div class="page">
        <!-- Sidebar -->