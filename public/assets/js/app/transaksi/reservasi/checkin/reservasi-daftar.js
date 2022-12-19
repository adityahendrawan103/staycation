$(document).ready(function () {
    // ===============================================================
    // Daftar Reservasi
    // ===============================================================
    function loadTransaksiReservasi(page = 1, per_page = 10, status = 'ALL', filter = '', search = '') {
        loading.block();
        window.location.href = window.location.origin + window.location.pathname + '?status='+status+'&filter='+filter+
            '&search='+search+'&per_page='+per_page+'&page='+page;
    }

    $('#inputSearch').on('change keydown', function (e) {
        if (e.keyCode == 13 || e.type == 'change') {
            var per_page = $('#selectPerPage').val();
            var status = $('#selectStatus').val();
            var filter = $('#selectFilter').val();
            var search = $('#inputSearch').val();

            loading.block();
            loadTransaksiReservasi(1, per_page, status, filter, search);
        }
    });

    $('#selectPerPage').change(function() {
        var start_record = data.start_record;
        var per_page = $('#selectPerPage').val();
        var page = Math.ceil(start_record / per_page);
        var status = $('#selectStatus').val();
        var filter = $('#selectFilter').val();
        var search = $('#inputSearch').val();

        loading.block();
        loadTransaksiReservasi(page, per_page, status, filter, search);
    });

    $(document).on('click', '.page-item a', function (e) {
        var page_link = $(this)[0].getAttribute('data-page');
        var page = page_link.split('?page=')[1];

        var per_page = $('#selectPerPage').val();
        var status = $('#selectStatus').val();
        var filter = $('#selectFilter').val();
        var search = $('#inputSearch').val();

        loading.block();
        loadTransaksiReservasi(page, per_page, status, filter, search);
    });

    $('body').on('click', '#btnHapus', function (e) {
        e.preventDefault();
        var kode_reservasi = $(this).data('kode');
        var _token = $('input[name="_token"]').val();

        Swal.fire({
            html: `Anda memilih data item : <span class='badge badge-primary'>`+kode_reservasi+`</span>
                    <br>Apakah anda yakin akan menghapus data ini ?`,
            icon: 'info',
            buttonsStyling: false,
            showCancelButton: true,
            confirmButtonText: 'Yes',
            cancelButtonText: 'No',
            customClass: {
                confirmButton: 'btn btn-primary',
                cancelButton: 'btn btn-danger'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                loading.block();
                $.ajax({
                    url: url.hapus_reservasi,
                    method: 'post',
                    data: { kode_reservasi: kode_reservasi, _token:_token },

                    success:function(response) {
                        console.log(response);
                        loading.release();

                        if (response.status == true) {
                            Swal.fire({
                                text: response.message,
                                icon: 'success',
                                buttonsStyling: false,
                                confirmButtonText: 'Ok, got it!',
                                customClass: {
                                    confirmButton: 'btn btn-success'
                                }
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    loadTransaksiReservasi();
                                }
                            });
                        } else {
                            Swal.fire({
                                text: response.message,
                                icon: 'error',
                                buttonsStyling: false,
                                confirmButtonText: 'Ok, got it!',
                                customClass: {
                                    confirmButton: 'btn btn-danger'
                                }
                            });
                        }
                    },
                    error:function() {
                        loading.release();
                        Swal.fire({
                            text: 'Server Not Responding',
                            icon: 'error',
                            buttonsStyling: false,
                            confirmButtonText: 'Ok, got it!',
                            customClass: {
                                confirmButton: 'btn btn-danger'
                            }
                        });
                    }
                })
            }
        });
    });
});
