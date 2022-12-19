$(document).ready(function () {
    // ===============================================================
    // Daftar Jabatan
    // ===============================================================
    function loadMasterJabatan(page = 1, per_page = 10, search = '') {
        loading.block();
        window.location.href = window.location.origin + window.location.pathname + '?page='+ page+'&per_page='+per_page+'&search='+search;
    }

    $('#inputSearch').on('change keydown', function (e) {
        if (e.keyCode == 13 || e.type == 'change') {
            var per_page = $('#selectPerPage').val();
            var search = $('#inputSearch').val();

            loadMasterJabatan(1, per_page, search);
        }
    });

    $('#selectPerPage').change(function() {
        var start_record = data.start_record;
        var per_page = $('#selectPerPage').val();
        var search = $('#inputSearch').val();
        var page = Math.ceil(start_record / per_page);

        loadMasterJabatan(page, per_page, search);
    });

    $(document).on('click', '.page-item a', function (e) {
        var page_link = $(this)[0].getAttribute('data-page');
        var page = page_link.split('?page=')[1];

        var per_page = $('#selectPerPage').val();
        var search = $('#inputSearch').val();

        loadMasterJabatan(page, per_page, search);
    });

    // ===============================================================
    // Form Max Lenght
    // ===============================================================
    $('#inputKodeJabatan').maxlength({
        warningClass: "badge badge-success",
        limitReachedClass: "badge badge-danger",
        threshold: 5,
        appendToParent: true
    });

    $('#inputNamaJabatan').maxlength({
        warningClass: "badge badge-success",
        limitReachedClass: "badge badge-danger",
        threshold: 5,
        appendToParent: true
    });

    // ===============================================================
    // Modal Entry Jabatan
    // ===============================================================
    function clearDataModal() {
        $('#inputKodeJabatan').removeClass('is-invalid');
        $('#messageKodeJabatan').html('');

        $('#alertFailed').addClass('d-none');
        $('#messageAlert').html('');

        $('#inputKodeJabatan').val('');
        $('#inputNamaJabatan').val('');
    }

    $('#inputKodeJabatan').change(function() {
        var kode_jabatan = $('#inputKodeJabatan').val();
        var _token = $('input[name="_token"]').val();

        if(!$('#inputKodeJabatan').hasClass('form-control-solid')) {
            loading.block();
            $.ajax({
                url: url.cek_kode_jabatan,
                method: 'post',
                data: { kode_jabatan: kode_jabatan, _token: _token },

                success: function (response) {
                    loading.release();
                    if (response.status == true) {
                        $('#messageKodeJabatan').html('');
                        $('#inputKodeJabatan').removeClass('is-invalid');
                    } else {
                        $('#messageKodeJabatan').html(response.message);
                        $('#inputKodeJabatan').addClass('is-invalid');
                    }
                },
                error: function () {
                    loading.release();
                    $('#messageKodeJabatan').html('Server not responding');
                    $('#inputKodeJabatan').addClass('is-invalid');
                }
            })
        }
    });

    $('#modalEntryJabatan').on('shown.bs.modal', function() {
        $('#formEntryJabatan').focus();
        $('#modalEntryJabatan').find('input').on('keydown', function (e) {
            if (e.which == 13) {
                e.preventDefault();
                var index = $('#modalEntryJabatan').find('input').index(this) + 1;
                $('#modalEntryJabatan').find('input').eq(index).focus();
            }
        });
    });

    $('#btnTambah').on('click', function() {
        clearDataModal();

        $('#inputKodeJabatan').removeClass('form-control-solid');
        $('#inputKodeJabatan').attr('readonly', false);

        $('#modalTitleJabatan').html('Tambah Data Jabatan');
        $('#modalEntryJabatan').modal('show');

        $('#modalEntryJabatan').on('shown.bs.modal', function () {
            $('#inputKodeJabatan').focus();
        });
    });

    $('#btnSimpan').on('click', function(e) {
        e.preventDefault();

        if($('#inputKodeJabatan').hasClass('is-invalid')) {
            $('#alertFailed').removeClass('d-none');
            $('#messageAlert').html('Data yang anda entry belum benar');
        } else {
            if($('#inputKodeJabatan').val() == '' || $('#inputNamaJabatan').val() == '') {
                $('#alertFailed').removeClass('d-none');
                $('#messageAlert').html('Isi data secara lengkap');
            } else {
                $('#formEntryJabatan').submit();
            }
        }
    });

    $('body').on('click', '#btnEdit', function (e) {
        e.preventDefault();

        loading.block();
        $.ajax({
            url: url.form_jabatan,
            method: 'get',
            data: { kode_jabatan: $(this).data('kode') },

            success: function (response) {
                loading.release();
                if (response.status == true) {
                    $('#modalTitleJabatan').html('Edit Data Jabatan');

                    $('#inputKodeJabatan').val(response.data.kode_jabatan);
                    $('#inputNamaJabatan').val(response.data.nama_jabatan);

                    $('#inputKodeJabatan').addClass('form-control-solid');
                    $('#inputKodeJabatan').attr('readonly', true);

                    $('#modalEntryJabatan').modal('show');

                    $('#modalEntryJabatan').on('shown.bs.modal', function () {
                        $('#inputNamaJabatan').focus();
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
        var kode_jabatan = $(this).data('kode');
        var _token = $('input[name="_token"]').val();

        Swal.fire({
            html: `Anda memilih data jabatan : <span class='badge badge-primary'>`+kode_jabatan+`</span>
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
                    url: url.hapus_jabatan,
                    method: 'post',
                    data: { kode_jabatan: kode_jabatan, _token:_token },

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
                                    loadMasterJabatan(1, 10, '');
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
