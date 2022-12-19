$(document).ready(function () {
    // ===============================================================
    // Daftar Karyawan
    // ===============================================================
    function loadMasterKaryawan(page = 1, per_page = 10, search = '') {
        loading.block();
        window.location.href = window.location.origin + window.location.pathname + '?page='+ page+'&per_page='+per_page+'&search='+search;
    }

    $('#inputSearch').on('change keydown', function (e) {
        if (e.keyCode == 13 || e.type == 'change') {
            var per_page = $('#selectPerPage').val();
            var search = $('#inputSearch').val();

            loadMasterKaryawan(1, per_page, search);
        }
    });

    $('#selectPerPage').change(function() {
        var start_record = data.start_record;
        var per_page = $('#selectPerPage').val();
        var search = $('#inputSearch').val();
        var page = Math.ceil(start_record / per_page);

        loadMasterKaryawan(page, per_page, search);
    });

    $(document).on('click', '.page-item a', function (e) {
        var page_link = $(this)[0].getAttribute('data-page');
        var page = page_link.split('?page=')[1];

        var per_page = $('#selectPerPage').val();
        var search = $('#inputSearch').val();

        loading.block();
        loadMasterKaryawan(page, per_page, search);
    });

    // ===============================================================
    // Form Max Lenght
    // ===============================================================
    $('#inputNIK').maxlength({
        warningClass: "badge badge-success",
        limitReachedClass: "badge badge-danger"
    });

    $('#inputNoKTP').maxlength({
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

    $('#inputRT').maxlength({
        warningClass: "badge badge-success",
        limitReachedClass: "badge badge-danger"
    });

    $('#inputRW').maxlength({
        warningClass: "badge badge-success",
        limitReachedClass: "badge badge-danger"
    });

    $('#inputKelurahan').maxlength({
        warningClass: "badge badge-success",
        limitReachedClass: "badge badge-danger"
    });

    $('#inputKecamatan').maxlength({
        warningClass: "badge badge-success",
        limitReachedClass: "badge badge-danger"
    });

    $('#inputKabupaten').maxlength({
        warningClass: "badge badge-success",
        limitReachedClass: "badge badge-danger"
    });

    $('#inputProvinsi').maxlength({
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
    // Form Entry Karyawan
    // ===============================================================
    $("#inputTanggalLahir").flatpickr();

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

    $('#inputNIK').change(function() {
        var nik = $('#inputNIK').val();
        var _token = $('input[name="_token"]').val();

        if(!$('#inputNIK').hasClass('form-control-solid')) {
            loading.block();
            $.ajax({
                url: url.cek_nik,
                method: 'post',
                data: { nik: nik, _token: _token },

                success: function (response) {
                    loading.release();
                    if (response.status == true) {
                        $('#messageNIK').html('');
                        $('#inputNIK').removeClass('is-invalid');
                    } else {
                        $('#messageNIK').html(response.message);
                        $('#inputNIK').addClass('is-invalid');
                    }
                },
                error: function () {
                    loading.release();
                    $('#messageNIK').html('Server not responding');
                    $('#inputNIK').addClass('is-invalid');
                }
            })
        }
    });

    $('#inputNoKTP').change(function() {
        var nik     = $('#inputNIK').val();
        var no_ktp  = $('#inputNoKTP').val();
        var _token  = $('input[name="_token"]').val();

        if(!$('#inputNoKTP').hasClass('form-control-solid')) {
            loading.block();
            $.ajax({
                url: url.cek_ktp,
                method: 'post',
                data: { nik: nik, no_ktp: no_ktp, _token: _token },

                success: function (response) {
                    loading.release();
                    if (response.status == true) {
                        $('#messageNoKTP').html('');
                        $('#inputNoKTP').removeClass('is-invalid');
                    } else {
                        $('#messageNoKTP').html(response.message);
                        $('#inputNoKTP').addClass('is-invalid');
                    }
                },
                error: function () {
                    loading.release();
                    $('#messageNoKTP').html('Server not responding');
                    $('#inputNoKTP').addClass('is-invalid');
                }
            })
        }
    });

    $('#inputKodeJabatan').on('click', function (e) {
        e.preventDefault();

        loadDataJabatan(1, 10, '');
        $('#formOptionJabatan').trigger('reset');
        $('#optionModalJabatan').modal('show');
    });

    $('#btnOptionJabatan').on('click', function (e) {
        e.preventDefault();

        loadDataJabatan(1, 10, '');
        $('#formOptionJabatan').trigger('reset');
        $('#optionModalJabatan').modal('show');
    });

    $('body').on('click', '#selectOptionJabatan', function (e) {
        e.preventDefault();
        $('#inputKodeJabatan').val($(this).data('kode_jabatan'));
        $('#inputNamaJabatan').val($(this).data('nama_jabatan'));
        $('#optionModalJabatan').modal('hide');
    });

    $('#btnSimpan').on('click', function(e) {
        e.preventDefault();

        if($('#inputNIK').hasClass('is-invalid') || $('#inputNoKTP').hasClass('is-invalid')) {
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
            if($('#inputNIK').val() == '' || $('#inputNoKTP').val() == '' ||
                $('#inputNama').val() == '' || $('#inputKodeJabatan').val() == '' ||
                $('#inputTempatLahir').val() == '' || $('#inputTanggalLahir').val() == '') {
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
                $('#formEntryKaryawan').submit();
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
        var nik = $(this).data('kode');
        var _token = $('input[name="_token"]').val();

        Swal.fire({
            html: `Anda memilih data karyawan : <span class='badge badge-primary'>`+nik+`</span>
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
                    url: url.hapus_karyawan,
                    method: 'post',
                    data: { nik: nik, _token:_token },

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
                                    loadMasterKaryawan(1, 10, '');
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
