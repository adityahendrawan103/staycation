$(document).ready(function () {
    // ===============================================================
    // Daftar Fasilitas
    // ===============================================================
    function loadMasterFasilitas(page = 1, per_page = 10, search = '') {
        loading.block();
        window.location.href = window.location.origin + window.location.pathname + '?page='+ page+'&per_page='+per_page+'&search='+search;
    }

    $('#inputSearch').on('change keydown', function (e) {
        if (e.keyCode == 13 || e.type == 'change') {
            var per_page = $('#selectPerPage').val();
            var search = $('#inputSearch').val();

            loadMasterFasilitas(1, per_page, search);
        }
    });

    $('#selectPerPage').change(function() {
        var start_record = data.start_record;
        var per_page = $('#selectPerPage').val();
        var search = $('#inputSearch').val();
        var page = Math.ceil(start_record / per_page);

        loadMasterFasilitas(page, per_page, search);
    });

    $(document).on('click', '.page-item a', function (e) {
        var page_link = $(this)[0].getAttribute('data-page');
        var page = page_link.split('?page=')[1];

        var per_page = $('#selectPerPage').val();
        var search = $('#inputSearch').val();

        loadMasterFasilitas(page, per_page, search);
    });

    // ===============================================================
    // Form Max Lenght
    // ===============================================================
    $('#inputKodeFasilitas').maxlength({
        warningClass: "badge badge-success",
        limitReachedClass: "badge badge-danger",
        threshold: 5,
        appendToParent: true
    });

    $('#inputNamaFasilitas').maxlength({
        warningClass: "badge badge-success",
        limitReachedClass: "badge badge-danger",
        threshold: 5,
        appendToParent: true
    });

    // ===============================================================
    // Modal Entry Fasilitas
    // ===============================================================
    function clearDataModal() {
        $('#inputKodeFasilitas').removeClass('is-invalid');
        $('#messageKodeFasilitas').html('');

        $('#alertFailed').addClass('d-none');
        $('#messageAlert').html('');

        $('#inputKodeFasilitas').val('');
        $('#inputNamaFasilitas').val('');
    }

    $('#inputKodeFasilitas').change(function() {
        var kode_fasilitas = $('#inputKodeFasilitas').val();
        var _token = $('input[name="_token"]').val();

        if(!$('#inputKodeFasilitas').hasClass('form-control-solid')) {
            loading.block();
            $.ajax({
                url: url.cek_kode_fasilitas,
                method: 'post',
                data: { kode_fasilitas: kode_fasilitas, _token: _token },

                success: function (response) {
                    loading.release();
                    if (response.status == true) {
                        $('#messageKodeFasilitas').html('');
                        $('#inputKodeFasilitas').removeClass('is-invalid');
                    } else {
                        $('#messageKodeFasilitas').html(response.message);
                        $('#inputKodeFasilitas').addClass('is-invalid');
                    }
                },
                error: function () {
                    loading.release();
                    $('#messageKodeFasilitas').html('Server not responding');
                    $('#inputKodeFasilitas').addClass('is-invalid');
                }
            })
        }
    });

    $('#modalEntryFasilitas').on('shown.bs.modal', function() {
        $('#formEntryFasilitas').focus();
        $('#modalEntryFasilitas').find('input').on('keydown', function (e) {
            if (e.which == 13) {
                e.preventDefault();
                var index = $('#modalEntryFasilitas').find('input').index(this) + 1;
                $('#modalEntryFasilitas').find('input').eq(index).focus();
            }
        });
    });

    $('#btnTambah').on('click', function() {
        clearDataModal();

        $('#inputKodeFasilitas').removeClass('form-control-solid');
        $('#inputKodeFasilitas').attr('readonly', false);

        $('#modalTitleFasilitas').html('Tambah Data Fasilitas');
        $('#modalEntryFasilitas').modal('show');

        $('#modalEntryFasilitas').on('shown.bs.modal', function () {
            $('#inputKodeFasilitas').focus();
        });
    });

    $('#btnSimpan').on('click', function(e) {
        e.preventDefault();

        if($('#inputKodeFasilitas').hasClass('is-invalid')) {
            $('#alertFailed').removeClass('d-none');
            $('#messageAlert').html('Data yang anda entry belum benar');
        } else {
            if($('#inputKodeFasilitas').val() == '' || $('#inputNamaFasilitas').val() == '') {
                $('#alertFailed').removeClass('d-none');
                $('#messageAlert').html('Isi data secara lengkap');
            } else {
                $('#formEntryFasilitas').submit();
            }
        }
    });

    $('body').on('click', '#btnEdit', function (e) {
        e.preventDefault();

        loading.block();
        $.ajax({
            url: url.form_fasilitas,
            method: 'get',
            data: { kode_fasilitas: $(this).data('kode') },

            success: function (response) {
                loading.release();
                if (response.status == true) {
                    $('#modalTitleFasilitas').html('Edit Data Fasilitas');

                    $('#inputKodeFasilitas').val(response.data.kode_fasilitas);
                    $('#inputNamaFasilitas').val(response.data.nama_fasilitas);

                    $('#inputKodeFasilitas').addClass('form-control-solid');
                    $('#inputKodeFasilitas').attr('readonly', true);

                    $('#modalEntryFasilitas').modal('show');

                    $('#modalEntryFasilitas').on('shown.bs.modal', function () {
                        $('#inputNamaFasilitas').focus();
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
        var kode_fasilitas = $(this).data('kode');
        var _token = $('input[name="_token"]').val();

        Swal.fire({
            html: `Anda memilih data fasilitas : <span class='badge badge-primary'>`+kode_fasilitas+`</span>
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
                    url: url.hapus_fasilitas,
                    method: 'post',
                    data: { kode_fasilitas: kode_fasilitas, _token:_token },

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
                                    loadMasterFasilitas(1, 10, '');
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
