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
    document.addEventListener("DOMContentLoaded", function () {
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

                    } else {
                        showFlashAlert(response.message, 'error');
                    }
                },
                error: function (xhr, status, error) {
                    $('#btn-submit').removeClass('d-none');
                    $('#btn-loading').addClass('d-none');

                    showFlashAlert('Terjadi kesalahan. Silakan coba lagi.', 'error');
                }
            });
        });

        function showFlashAlert(message, type) {
            const alert = `
                        <div class="alert alert-blur alert-${type} alert-dismissible position-fixed"
                            style="bottom: 70px; right: 20px; z-index: 1050; max-width: 50rem;">
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
                                    <h4 class="alert-title">${type === 'success' ? 'Success!' : 'Error!'}</h4>
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
</body>

</html>