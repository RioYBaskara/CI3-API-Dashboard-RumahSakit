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

        body.modal-open {
            overflow: hidden;
        }
    </style>
</head>

<body class=" d-flex flex-column">
    <script src="<?= base_url() ?>public/assets/tabler/dist/js/demo-theme.min.js?1692870487"></script>
    <div class="page">
        <div class="page-wrapper">
            <div class="page-body">
                <div class="container-xl">
                    <div class="row row-cards">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header flex justify-content-between">
                                    <h3 class="card-title">Product</h3>
                                    <div class="">
                                        <a class="btn btn-icon btn-outline-secondary" id="refresh-btn"><svg
                                                xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="icon icon-tabler icons-tabler-outline icon-tabler-refresh">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path d="M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -4v4h4" />
                                                <path d="M4 13a8.1 8.1 0 0 0 15.5 2m.5 4v-4h-4" />
                                            </svg></a>

                                        <a class="btn btn-icon btn-outline-success" id="add-btn" data-bs-toggle="modal"
                                            data-bs-target="#modal-product"><svg xmlns="http://www.w3.org/2000/svg"
                                                width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round"
                                                class="icon icon-tabler icons-tabler-outline icon-tabler-plus">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path d="M12 5l0 14" />
                                                <path d="M5 12l14 0" />
                                            </svg></a>
                                    </div>
                                </div>
                                <div id="success-message" class="alert alert-success d-none" role="alert"></div>
                                <div id="error-message" class="alert alert-danger d-none" role="alert"></div>
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
    </div>

    <!-- Add/Insert modal -->
    <div class="modal modal-blur fade" id="modal-product" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">New Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="modal-error-message" class="alert alert-danger d-none" role="alert"></div>
                    <!-- Tempat Error -->
                    <form id="product-form">
                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" class="form-control" name="name" id="product-name"
                                placeholder="Your product name">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Price</label>
                            <input type="text" class="form-control" name="price" id="product-price"
                                placeholder="Your product price">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <a href="#" class="btn btn-link link-secondary" data-bs-dismiss="modal">
                        Cancel
                    </a>
                    <button type="button" class="btn btn-primary ms-auto" id="submit-product">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24"
                            stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M12 5l0 14" />
                            <path d="M5 12l14 0" />
                        </svg>
                        Create new product
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Update Modal -->
    <div class="modal modal-blur fade" id="modal-update-product" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="modal-update-error-message" class="alert alert-danger d-none" role="alert"></div>
                    <form id="update-product-form">
                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" class="form-control" name="name" id="update-product-name">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Price</label>
                            <input type="text" class="form-control" name="price" id="update-product-price">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="submit-update-product">Update Product</button>
                </div>
            </div>
        </div>
    </div>

    <!-- DELETE Modal -->
    <div class="modal modal-blur fade" id="modal-danger" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
            <div class="modal-content">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="modal-status bg-danger"></div>
                <div class="modal-body text-center py-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon mb-2 text-danger icon-lg" width="24" height="24"
                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path
                            d="M10.24 3.957l-8.422 14.06a1.989 1.989 0 0 0 1.7 2.983h16.845a1.989 1.989 0 0 0 1.7 -2.983l-8.423 -14.06a1.989 1.989 0 0 0 -3.4 0z" />
                        <path d="M12 9v4" />
                        <path d="M12 17h.01" />
                    </svg>
                    <h3>Are you sure?</h3>
                    <div class="text-secondary">
                        Do you really want to remove <strong id="delete-product-name"></strong>?
                        This action cannot be undone.
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="w-100">
                        <div class="row">
                            <div class="col">
                                <a href="#" class="btn w-100" data-bs-dismiss="modal">Cancel</a>
                            </div>
                            <div class="col">
                                <a href="#" id="confirm-delete-btn" class="btn btn-danger w-100">Delete</a>
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

            document.getElementById("submit-product").addEventListener("click", function () {
                submitProduct();
            });

            document.getElementById("confirm-delete-btn").addEventListener("click", function () {
                let productId = this.getAttribute("data-id");
                deleteProduct(productId);
            });
        });

        // GET
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
                              <button class="btn dropdown-toggle align-text-top btn-outline-primary" data-bs-boundary="viewport" data-bs-toggle="dropdown"><svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-settings-2"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M19.875 6.27a2.225 2.225 0 0 1 1.125 1.948v7.284c0 .809 -.443 1.555 -1.158 1.948l-6.75 4.27a2.269 2.269 0 0 1 -2.184 0l-6.75 -4.27a2.225 2.225 0 0 1 -1.158 -1.948v-7.285c0 -.809 .443 -1.554 1.158 -1.947l6.75 -3.98a2.33 2.33 0 0 1 2.25 0l6.75 3.98h-.033z" /><path d="M12 12m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" /></svg></button>
                              <div class="dropdown-menu dropdown-menu-start">
                                <a class="dropdown-item" href="#" onclick="editProduct(${product.id}, '${product.name}', ${product.price})">Update</a>
                                <a class="dropdown-item delete-btn" href="#" data-id="${product.id}" data-name="${product.name}" data-bs-toggle="modal" data-bs-target="#modal-danger">
                                  Delete
                                </a>
                              </div>
                            </span>
                          </td>
                </tr>
            `;
                tableBody.innerHTML += row;
            });

            document.querySelectorAll(".delete-btn").forEach((btn) => {
                btn.addEventListener("click", function () {
                    let productId = this.getAttribute("data-id");
                    let productName = this.getAttribute("data-name");

                    document.getElementById("delete-product-name").textContent = productName;
                    document.getElementById("confirm-delete-btn").setAttribute("data-id", productId);
                });
            });
        }

        function showSuccess(message) {
            let successMessage = document.getElementById("success-message");
            successMessage.textContent = message;
            successMessage.classList.remove("d-none");

            setTimeout(() => {
                successMessage.classList.add("d-none");
            }, 3000);
        }

        function showError(message) {
            let errorMessage = document.getElementById("error-message");
            errorMessage.textContent = message;
            errorMessage.classList.remove("d-none");

            setTimeout(() => {
                errorMessage.classList.add("d-none");
            }, 3000);
        }

        function formatDate(dateString) {
            let date = new Date(dateString);
            return date.toLocaleDateString("id-ID", { year: "numeric", month: "long", day: "numeric" });
        }

        function formatCurrency(amount) {
            return new Intl.NumberFormat("id-ID", { style: "currency", currency: "IDR" }).format(amount);
        }

        // insert POST
        function submitProduct() {
            let submitButton = document.getElementById("submit-product");
            submitButton.classList.add("btn-loading", "disabled");

            let productName = document.getElementById("product-name").value.trim();
            let productPrice = document.getElementById("product-price").value.trim();
            let modalErrorMessage = document.getElementById("modal-error-message");

            modalErrorMessage.classList.add("d-none");
            modalErrorMessage.textContent = "";

            if (!productName || !productPrice) {
                modalErrorMessage.textContent = "Product Name and Price are required!";
                modalErrorMessage.classList.remove("d-none");
                submitButton.classList.remove("btn-loading", "disabled");
                return;
            }

            let formData = new FormData();
            formData.append("name", productName);
            formData.append("price", productPrice);

            fetch("http://localhost/ci3_api_rs/product", {
                method: "POST",
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.status) {
                        document.getElementById("modal-product").querySelector(".btn-close").click();
                        fetchProducts();
                    } else {
                        modalErrorMessage.textContent = data.error;
                        modalErrorMessage.classList.remove("d-none");
                    }
                })
                .catch(error => {
                    modalErrorMessage.textContent = "Failed to create product. Please try again.";
                    modalErrorMessage.classList.remove("d-none");
                    console.error("POST error:", error);
                })
                .finally(() => {
                    submitButton.classList.remove("btn-loading", "disabled");
                });
        }

        // PUT, *validation on view(variasitesting)
        function editProduct(id, name, price) {
            let modal = document.getElementById("modal-update-product");

            modal.querySelector(".modal-title").innerHTML = `Edit Product <strong>"${name}"</strong>`;

            document.getElementById("update-product-name").value = name;
            document.getElementById("update-product-price").value = price;
            modal.setAttribute("data-product-id", id);

            let modalInstance = new bootstrap.Modal(modal);
            modalInstance.show();
        }

        document.getElementById("submit-update-product").addEventListener("click", function () {
            let modal = document.getElementById("modal-update-product");
            let productId = modal.getAttribute("data-product-id");
            let submitButton = modal.querySelector(".modal-footer .btn-primary");
            submitButton.classList.add("btn-loading", "disabled");

            let productName = document.getElementById("update-product-name").value.trim();
            let productPrice = document.getElementById("update-product-price").value.trim();
            let modalErrorMessage = document.getElementById("modal-update-error-message");

            modalErrorMessage.classList.add("d-none");
            modalErrorMessage.textContent = "";

            if (!productName || !productPrice) {
                modalErrorMessage.textContent = "Product Name and Price are required!";
                modalErrorMessage.classList.remove("d-none");
                submitButton.classList.remove("btn-loading", "disabled");
                return;
            }

            if (isNaN(productPrice)) {
                modalErrorMessage.textContent = "Price must be a valid number!";
                modalErrorMessage.classList.remove("d-none");
                submitButton.classList.remove("btn-loading", "disabled");
                return;
            }

            let requestData = {
                name: productName,
                price: Number(productPrice)
            };

            fetch(`http://localhost/ci3_api_rs/product/${productId}`, {
                method: "PUT",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify(requestData)
            })
                .then(response => response.json())
                .then(data => {
                    if (data.status) {
                        modal.querySelector(".btn-close").click();
                        fetchProducts();
                    } else {
                        modalErrorMessage.textContent = data.error;
                        modalErrorMessage.classList.remove("d-none");
                    }
                })
                .catch(error => {
                    modalErrorMessage.textContent = "Failed to update product. Please try again.";
                    modalErrorMessage.classList.remove("d-none");
                    console.error("PUT error:", error);
                })
                .finally(() => {
                    submitButton.classList.remove("btn-loading", "disabled");
                });
        });

        // DELETE
        function deleteProduct(productId) {
            let deleteButton = document.getElementById("confirm-delete-btn");
            deleteButton.classList.add("btn-loading", "disabled");

            if (!productId) {
                showError("Invalid product ID");
                return;
            }

            fetch(`http://localhost/ci3_api_rs/product/${productId}`, {
                method: "DELETE",
                headers: {
                    "Content-Type": "application/json"
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.status) {
                        showSuccess("Product deleted successfully!");
                        fetchProducts();
                        let modal = bootstrap.Modal.getInstance(document.getElementById("modal-danger"));
                        modal.hide();
                    } else {
                        showError(data.error || "Failed to delete product");
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                    showError("An error occurred while deleting the product");
                })
                .finally(() => {
                    deleteButton.classList.remove("btn-loading", "disabled");
                });
        }
    </script>

</body>

</html>