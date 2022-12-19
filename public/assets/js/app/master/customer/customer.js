$(document).ready(function () {
    // ===============================================================
    // Daftar Customer
    // ===============================================================
    function loadMasterCustomer(page = 1, per_page = 10, search = '') {
        loading.block();
        window.location.href = window.location.origin + window.location.pathname + '?page='+ page+'&per_page='+per_page+'&search='+search;
    }

    $('#inputSearch').on('change keydown', function (e) {
        if (e.keyCode == 13 || e.type == 'change') {
            var per_page = $('#selectPerPage').val();
            var search = $('#inputSearch').val();

            loadMasterCustomer(1, per_page, search);
        }
    });

    $('#selectPerPage').change(function() {
        var start_record = data.start_record;
        var per_page = $('#selectPerPage').val();
        var search = $('#inputSearch').val();
        var page = Math.ceil(start_record / per_page);

        loadMasterCustomer(page, per_page, search);
    });

    $(document).on('click', '.page-item a', function (e) {
        var page_link = $(this)[0].getAttribute('data-page');
        var page = page_link.split('?page=')[1];

        var per_page = $('#selectPerPage').val();
        var search = $('#inputSearch').val();

        loading.block();
        loadMasterCustomer(page, per_page, search);
    });

    // ===============================================================
    // Form Max Lenght
    // ===============================================================
    $('#inputNoIdentitas').maxlength({
        warningClass: "badge badge-success",
        limitReachedClass: "badge badge-danger"
    });

    $('#inputNama').maxlength({
        warningClass: "badge badge-success",
        limitReachedClass: "badge badge-danger"
    });

    $('#inputTempatLahir').maxlength({
        warningClass: "badge badge-success",
        limitReachedClass: "badge badge-danger"
    });

    $('#inputAlamat').maxlength({
        warningClass: "badge badge-success",
        limitReachedClass: "badge badge-danger"
    });

    $('#inputKota').maxlength({
        warningClass: "badge badge-success",
        limitReachedClass: "badge badge-danger"
    });

    $('#inputPekerjaan').maxlength({
        warningClass: "badge badge-success",
        limitReachedClass: "badge badge-danger"
    });

    $('#inputTelepon').maxlength({
        warningClass: "badge badge-success",
        limitReachedClass: "badge badge-danger"
    });

    $('#inputEmail').maxlength({
        warningClass: "badge badge-success",
        limitReachedClass: "badge badge-danger"
    });

    // ===============================================================
    // Form Entry Customer
    // ===============================================================
    $('form').find('input,select').on('keydown', function (e) {
        if (e.which == 13) {
            e.preventDefault();
            var index = $('form').find('input,select').index(this) + 1;
            if ($('form').find('input,select').eq(index).attr('readonly') || $('form').find('input,select').eq(index).hasClass('form-control-solid')) {
                for (let i = index; i < $('form').find('input,select').length; i++) {
                    if (!$('form').find('input,select').eq(i).attr('readonly') || !$('form').find('input,select').eq(i).hasClass('form-control-solid')) {
                        $('form').find('input,select').eq(i).focus();
                        break;
                    }
                }
            } else {
                $('form').find('input,select').eq(index).focus();
            }
        }
    });

    $("#inputTanggalLahir").flatpickr();

    $('#inputNoIdentitas').change(function() {
        var no_identitas = $('#inputNoIdentitas').val();
        var _token = $('input[name="_token"]').val();

        if(!$('#inputNoIdentitas').hasClass('form-control-solid')) {
            loading.block();
            $.ajax({
                url: url.cek_no_identitas,
                method: 'post',
                data: { no_identitas: no_identitas, _token: _token },

                success: function (response) {
                    loading.release();
                    if (response.status == true) {
                        $('#messageNoIdentitas').html('');
                        $('#inputNoIdentitas').removeClass('is-invalid');
                    } else {
                        $('#messageNoIdentitas').html(response.message);
                        $('#inputNoIdentitas').addClass('is-invalid');
                    }
                },
                error: function () {
                    loading.release();
                    $('#messageNoIdentitas').html('Server not responding');
                    $('#inputNoIdentitas').addClass('is-invalid');
                }
            })
        }
    });

    $('#btnSimpan').on('click', function(e) {
        e.preventDefault();

        if($('#inputNoIdentitas').hasClass('is-invalid')) {
            Swal.fire({
                text: 'Data yang anda entry belum benar',
                icon: 'error',
                buttonsStyling: false,
                confirmButtonText: 'Ok, got it!',
                customClass: {
                    confirmButton: 'btn btn-danger'
                }
            });
        } else {
            if($('#inputNoIdentitas').val() == '' || $('#inputNama').val() == '' ||
                $('#inputTempatLahir').val() == '' || $('#inputTanggalLahir').val() == '' ||
                $('#inputTelepon').val() == '' || $('#inputEmail').val() == '') {
                Swal.fire({
                    text: 'Isi data secara lengkap',
                    icon: 'error',
                    buttonsStyling: false,
                    confirmButtonText: 'Ok, got it!',
                    customClass: {
                        confirmButton: 'btn btn-danger'
                    }
                });
            } else {
                loading.block();
                $('#formEntryCustomer').submit();
            }
        }
    });

    $('#btnEdit').on('click', function() {
        loading.block();
    });

    $('#btnBatal').on('click', function() {
        loading.block();
        location.reload();
    });

    $('body').on('click', '#btnHapus', function (e) {
        e.preventDefault();
        var no_identitas = $(this).data('kode');
        var _token = $('input[name="_token"]').val();

        Swal.fire({
            html: `Anda memilih data customer : <span class='badge badge-primary'>`+no_identitas+`</span>
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
                    url: url.hapus_customer,
                    method: 'post',
                    data: { no_identitas: no_identitas, _token:_token },

                    success:function(response) {
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
                                    loadMasterCustomer(1, 10, '');
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
