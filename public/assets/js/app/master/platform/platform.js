$(document).ready(function () {
    // ===============================================================
    // Daftar Platform
    // ===============================================================
    function loadMasterPlatform(page = 1, per_page = 10, search = '') {
        loading.block();
        window.location.href = window.location.origin + window.location.pathname + '?page='+ page+'&per_page='+per_page+'&search='+search;
    }

    $('#inputSearch').on('change keydown', function (e) {
        if (e.keyCode == 13 || e.type == 'change') {
            var per_page = $('#selectPerPage').val();
            var search = $('#inputSearch').val();

            loadMasterPlatform(1, per_page, search);
        }
    });

    $('#selectPerPage').change(function() {
        var start_record = data.start_record;
        var per_page = $('#selectPerPage').val();
        var search = $('#inputSearch').val();
        var page = Math.ceil(start_record / per_page);

        loadMasterPlatform(page, per_page, search);
    });

    $(document).on('click', '.page-item a', function (e) {
        var page_link = $(this)[0].getAttribute('data-page');
        var page = page_link.split('?page=')[1];

        var per_page = $('#selectPerPage').val();
        var search = $('#inputSearch').val();

        loadMasterPlatform(page, per_page, search);
    });

    // ===============================================================
    // Form Max Lenght
    // ===============================================================
    $('#inputKodePlatform').maxlength({
        warningClass: "badge badge-success",
        limitReachedClass: "badge badge-danger",
        threshold: 5,
        appendToParent: true
    });

    $('#inputKeterangan').maxlength({
        warningClass: "badge badge-success",
        limitReachedClass: "badge badge-danger",
        threshold: 5,
        appendToParent: true
    });

    // ===============================================================
    // Modal Entry Platform
    // ===============================================================
    function clearDataModal() {
        $('#inputKodePlatform').removeClass('is-invalid');
        $('#messageKodePlatform').html('');

        $('#alertFailed').addClass('d-none');
        $('#messageAlert').html('');

        $('#inputKodePlatform').val('');
        $('#inputKeterangan').val('');
    }

    $('#inputKodePlatform').change(function() {
        var kode_platform = $('#inputKodePlatform').val();
        var _token = $('input[name="_token"]').val();

        if(!$('#inputKodePlatform').hasClass('form-control-solid')) {
            loading.block();
            $.ajax({
                url: url.cek_kode_platform,
                method: 'post',
                data: { kode_platform: kode_platform, _token: _token },

                success: function (response) {
                    loading.release();
                    if (response.status == true) {
                        $('#messageKodePlatform').html('');
                        $('#inputKodePlatform').removeClass('is-invalid');
                    } else {
                        $('#messageKodePlatform').html(response.message);
                        $('#inputKodePlatform').addClass('is-invalid');
                    }
                },
                error: function () {
                    loading.release();
                    $('#messageKodePlatform').html('Server not responding');
                    $('#inputKodePlatform').addClass('is-invalid');
                }
            })
        }
    });

    $('#modalEntryPlatform').on('shown.bs.modal', function() {
        $('#formEntryPlatform').focus();
        $('#modalEntryPlatform').find('input').on('keydown', function (e) {
            if (e.which == 13) {
                e.preventDefault();
                var index = $('#modalEntryPlatform').find('input').index(this) + 1;
                $('#modalEntryPlatform').find('input').eq(index).focus();
            }
        });
    });

    $('#btnTambah').on('click', function() {
        clearDataModal();

        $('#inputKodePlatform').removeClass('form-control-solid');
        $('#inputKodePlatform').attr('readonly', false);

        $('#modalTitlePlatform').html('Tambah Data Platform');
        $('#modalEntryPlatform').modal('show');

        $('#modalEntryPlatform').on('shown.bs.modal', function () {
            $('#inputKodePlatform').focus();
        });
    });

    $('#btnSimpan').on('click', function(e) {
        e.preventDefault();

        if($('#inputKodePlatform').hasClass('is-invalid')) {
            $('#alertFailed').removeClass('d-none');
            $('#messageAlert').html('Data yang anda entry belum benar');
        } else {
            if($('#inputKodePlatform').val() == '' || $('#inputKeterangan').val() == '') {
                $('#alertFailed').removeClass('d-none');
                $('#messageAlert').html('Isi data secara lengkap');
            } else {
                $('#formEntryPlatform').submit();
            }
        }
    });

    $('body').on('click', '#btnEdit', function (e) {
        e.preventDefault();

        loading.block();
        $.ajax({
            url: url.form_platform,
            method: 'get',
            data: { kode_platform: $(this).data('kode') },

            success: function (response) {
                loading.release();
                if (response.status == true) {
                    $('#modalTitlePlatform').html('Edit Data Platform');

                    $('#inputKodePlatform').val(response.data.kode_platform);
                    $('#inputKeterangan').val(response.data.nama_platform);

                    $('#inputKodePlatform').addClass('form-control-solid');
                    $('#inputKodePlatform').attr('readonly', true);

                    $('#modalEntryPlatform').modal('show');

                    $('#modalEntryPlatform').on('shown.bs.modal', function () {
                        $('#inputKeterangan').focus();
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
            error: function () {
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
    });

    $('body').on('click', '#btnHapus', function (e) {
        e.preventDefault();
        var kode_platform = $(this).data('kode');
        var _token = $('input[name="_token"]').val();

        Swal.fire({
            html: `Anda memilih data platform : <span class='badge badge-primary'>`+kode_platform+`</span>
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
                    url: url.hapus_platform,
                    method: 'post',
                    data: { kode_platform: kode_platform, _token:_token },

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
                                    loadMasterPlatform(1, 10, '');
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
