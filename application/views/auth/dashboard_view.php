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
    <title>Dashboard</title>
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
        }

        body {
            font-feature-settings: "cv03", "cv04", "cv11";
        }
    </style>
</head>

<body class=" d-flex flex-column">
    <script src="<?= base_url() ?>public/assets/tabler/dist/js/demo-theme.min.js?1692870487"></script>
    <div class="page">
        <div class="container container-tight py-4">
            <div class="text-center mb-4">
                <a href="." class="navbar-brand navbar-brand-autodark">
                    <img src="<?= base_url() ?>public/assets/tabler/static/logo.svg" width="110" height="32"
                        alt="Tabler" class="navbar-brand-image">
                </a>
            </div>
            <div class="card card-md">
                <div class="card-body">
                    <h2 class="h2 text-center mb-4">Selamat Datang di Dashboard!</h2>
                    <p class="text-center">Anda berhasil login.</p>
                    <div class="col-xl py-3">
                        <a href="<?= base_url() ?>/auth/logout" class="btn btn-6 btn-danger w-100">
                            Logout
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="page-wrapper">
            <div class="page-body">
                <div class="container-xl">
                    <div class="row row-cards">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header flex justify-content-between">
                                    <h3 class="card-title">Product</h3>
                                    <a class="btn btn-icon" id="refresh-btn"><svg xmlns="http://www.w3.org/2000/svg"
                                            width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            class="icon icon-tabler icons-tabler-outline icon-tabler-refresh">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -4v4h4" />
                                            <path d="M4 13a8.1 8.1 0 0 0 15.5 2m.5 4v-4h-4" />
                                        </svg></a>
                                </div>
                                <div id="error-message" class="alert alert-danger d-none" role="alert">
                                </div>
                                <div class="card-body border-bottom py-3">
                                    <div class="d-flex">
                                        <div class="text-secondary">
                                            Show
                                            <div class="mx-2 d-inline-block">
                                                <input type="text" class="form-control form-control-sm" value="8"
                                                    size="3" aria-label="Invoices count">
                                            </div>
                                            entries
                                        </div>
                                        <div class="ms-auto text-secondary">
                                            Search:
                                            <div class="ms-2 d-inline-block">
                                                <input type="text" class="form-control form-control-sm"
                                                    aria-label="Search invoice">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table card-table table-vcenter text-nowrap datatable">
                                        <thead>
                                            <tr>
                                                <th class="w-1"><input class="form-check-input m-0 align-middle"
                                                        type="checkbox" aria-label="Select all invoices"></th>
                                                <th class="w-1">No.
                                                </th>
                                                <th>ID</th>
                                                <th>Name</th>
                                                <th>Price</th>
                                                <th>Created</th>
                                                <th>Updated</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody id="product-table-body">
                                            <tr id="loading-row">
                                                <td colspan="7" class="text-center">
                                                    <div class="progress">
                                                        <div class="progress-bar progress-bar-indeterminate bg-green">
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <!-- footer -->
                                <div class="card-footer d-flex align-items-center">
                                    <p class="m-0 text-secondary">Showing <span>1</span> to <span>8</span> of
                                        <span>16</span> entries
                                    </p>
                                    <ul class="pagination m-0 ms-auto">
                                        <li class="page-item disabled">
                                            <a class="page-link" href="#" tabindex="-1" aria-disabled="true">
                                                <!-- Download SVG icon from http://tabler-icons.io/i/chevron-left -->
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24"
                                                    height="24" viewBox="0 0 24 24" stroke-width="2"
                                                    stroke="currentColor" fill="none" stroke-linecap="round"
                                                    stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path d="M15 6l-6 6l6 6" />
                                                </svg>
                                                prev
                                            </a>
                                        </li>
                                        <li class="page-item"><a class="page-link" href="#">1</a></li>
                                        <li class="page-item active"><a class="page-link" href="#">2</a></li>
                                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                                        <li class="page-item"><a class="page-link" href="#">4</a></li>
                                        <li class="page-item"><a class="page-link" href="#">5</a></li>
                                        <li class="page-item">
                                            <a class="page-link" href="#">
                                                next
                                                <!-- Download SVG icon from http://tabler-icons.io/i/chevron-right -->
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24"
                                                    height="24" viewBox="0 0 24 24" stroke-width="2"
                                                    stroke="currentColor" fill="none" stroke-linecap="round"
                                                    stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path d="M9 6l6 6l-6 6" />
                                                </svg>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Libs JS -->
    <!-- Tabler Core -->
    <script src="<?= base_url() ?>public/assets/tabler/dist/js/tabler.min.js?1692870487" defer></script>
    <script src="<?= base_url() ?>public/assets/tabler/dist/js/demo.min.js?1692870487" defer></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            fetchProducts();

            document.getElementById("refresh-btn").addEventListener("click", function () {
                fetchProducts();
            });
        });

        function fetchProducts() {
            let tableBody = document.getElementById("product-table-body");
            let errorMessage = document.getElementById("error-message");

            tableBody.innerHTML = `
            <tr id="loading-row">
                <td colspan="7" class="text-center">
                    <div class="progress">
                        <div class="progress-bar progress-bar-indeterminate bg-green"></div>
                    </div>
                </td>
            </tr>
        `;

            errorMessage.classList.add("d-none");

            fetch("http://localhost/ci3_api_rs/product/")
                .then(response => response.json())
                .then(data => {
                    if (data.status) {
                        populateTable(data.data);
                    } else {
                        showError(data.message);
                    }
                })
                .catch(error => {
                    showError("Failed to fetch products. Please try again.");
                    console.error("Fetch error:", error);
                });
        }

        function populateTable(products) {
            let tableBody = document.getElementById("product-table-body");
            tableBody.innerHTML = "";

            if (products.length === 0) {
                tableBody.innerHTML = `
                <tr>
                    <td colspan="7" class="text-center">No products available</td>
                </tr>
            `;
                return;
            }

            products.forEach((product, index) => {
                let createdAt = product.created_at ? formatDate(product.created_at) : "-";
                let updatedAt = product.updated_at ? formatDate(product.updated_at) : "-";
                let formattedPrice = formatCurrency(product.price);

                let row = `
                <tr>
                    <td><input class="form-check-input m-0 align-middle" type="checkbox" aria-label="Select invoice"></td>
                    <td><span class="text-secondary">${index + 1}</span></td>
                    <td>${product.id}</td>
                    <td>${product.name}</td>
                    <td>${formattedPrice}</td>
                    <td>${createdAt}</td>
                    <td>${updatedAt}</td>
                    <td class="text">
                            <span class="dropdown">
                              <button class="btn dropdown-toggle align-text-top" data-bs-boundary="viewport" data-bs-toggle="dropdown">Actions</button>
                              <div class="dropdown-menu dropdown-menu-start">
                                <a class="dropdown-item" href="#">
                                  Update
                                </a>
                                <a class="dropdown-item" href="#">
                                  Delete
                                </a>
                              </div>
                            </span>
                          </td>
                </tr>
            `;
                tableBody.innerHTML += row;
            });
        }

        function showError(message) {
            let errorMessage = document.getElementById("error-message");
            errorMessage.textContent = message;
            errorMessage.classList.remove("d-none");
        }

        function formatDate(dateString) {
            let date = new Date(dateString);
            return date.toLocaleDateString("id-ID", { year: "numeric", month: "long", day: "numeric" });
        }

        function formatCurrency(amount) {
            return new Intl.NumberFormat("id-ID", { style: "currency", currency: "IDR" }).format(amount);
        }
    </script>

</body>

</html>