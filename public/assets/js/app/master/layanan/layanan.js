$(document).ready(function () {
    // ===============================================================
    // Daftar Layanan
    // ===============================================================
    function loadMasterLayanan(page = 1, per_page = 10, search = '') {
        loading.block();
        window.location.href = window.location.origin + window.location.pathname + '?page='+ page+'&per_page='+per_page+'&search='+search;
    }

    $('#inputSearch').on('change keydown', function (e) {
        if (e.keyCode == 13 || e.type == 'change') {
            var per_page = $('#selectPerPage').val();
            var search = $('#inputSearch').val();

            loadMasterLayanan(1, per_page, search);
        }
    });

    $('#selectPerPage').change(function() {
        var start_record = data.start_record;
        var per_page = $('#selectPerPage').val();
        var search = $('#inputSearch').val();
        var page = Math.ceil(start_record / per_page);

        loadMasterLayanan(page, per_page, search);
    });

    $(document).on('click', '.page-item a', function (e) {
        var page_link = $(this)[0].getAttribute('data-page');
        var page = page_link.split('?page=')[1];

        var per_page = $('#selectPerPage').val();
        var search = $('#inputSearch').val();

        loadMasterLayanan(page, per_page, search);
    });

    // ===============================================================
    // Form Max Lenght
    // ===============================================================
    $('#inputKodeLayanan').maxlength({
        warningClass: "badge badge-success",
        limitReachedClass: "badge badge-danger",
        threshold: 5,
        appendToParent: true
    });

    $('#inputNamaLayanan').maxlength({
        warningClass: "badge badge-success",
        limitReachedClass: "badge badge-danger",
        threshold: 5,
        appendToParent: true
    });

    // ===============================================================
    // Modal Entry Layanan
    // ===============================================================
    function clearDataModal() {
        $('#inputKodeLayanan').removeClass('is-invalid');
        $('#messageKodeLayanan').html('');

        $('#alertFailed').addClass('d-none');
        $('#messageAlert').html('');

        $('#inputKodeLayanan').val('');
        $('#inputNamaLayanan').val('');
        $('#selectSatuan').prop('selectedIndex', 0).change();
        $('#inputHarga').val('');
    }

    $('#inputKodeLayanan').change(function() {
        var kode_layanan = $('#inputKodeLayanan').val();
        var _token = $('input[name="_token"]').val();

        if(!$('#inputKodeLayanan').hasClass('form-control-solid')) {
            loading.block();
            $.ajax({
                url: url.cek_kode_layanan,
                method: 'post',
                data: { kode_layanan: kode_layanan, _token: _token },

                success: function (response) {
                    loading.release();
                    if (response.status == true) {
                        $('#messageKodeLayanan').html('');
                        $('#inputKodeLayanan').removeClass('is-invalid');
                    } else {
                        $('#messageKodeLayanan').html(response.message);
                        $('#inputKodeLayanan').addClass('is-invalid');
                    }
                },
                error: function () {
                    loading.release();
                    $('#messageKodeLayanan').html('Server not responding');
                    $('#inputKodeLayanan').addClass('is-invalid');
                }
            })
        }
    });

    $('#inputHarga').autoNumeric('init', {
        aSep: ',',
        aDec: '.',
        aForm: true,
        vMax: '9999999999999',
        vMin: '0'
    });

    $('#modalEntryLayanan').on('shown.bs.modal', function() {
        $('#formEntryLayanan').focus();
        $('#modalEntryLayanan').find('input').on('keydown', function (e) {
            if (e.which == 13) {
                e.preventDefault();
                var index = $('#modalEntryLayanan').find('input').index(this) + 1;
                $('#modalEntryLayanan').find('input').eq(index).focus();
            }
        });
    });

    $('#btnTambah').on('click', function() {
        clearDataModal();

        $('#inputKodeLayanan').removeClass('form-control-solid');
        $('#inputKodeLayanan').attr('readonly', false);

        $('#modalTitleLayanan').html('Tambah Data Layanan');
        $('#modalEntryLayanan').modal('show');

        $('#modalEntryLayanan').on('shown.bs.modal', function () {
            $('#inputKodeLayanan').focus();
        });
    });

    $('#btnSimpan').on('click', function(e) {
        e.preventDefault();

        if($('#inputKodeLayanan').hasClass('is-invalid')) {
            $('#alertFailed').removeClass('d-none');
            $('#messageAlert').html('Data yang anda entry belum benar');
        } else {
            if($('#inputKodeLayanan').val() == '' || $('#inputNamaLayanan').val() == '' || $('#inputHarga').val() == '') {
                $('#alertFailed').removeClass('d-none');
                $('#messageAlert').html('Isi data secara lengkap');
            } else {
                $('#formEntryLayanan').submit();
            }
        }
    });

    $('body').on('click', '#btnEdit', function (e) {
        e.preventDefault();

        loading.block();
        $.ajax({
            url: url.form_layanan,
            method: 'get',
            data: { kode_layanan: $(this).data('kode') },

            success: function (response) {
                loading.release();
                if (response.status == true) {
                    $('#modalTitleLayanan').html('Edit Data Layanan');

                    $('#inputKodeLayanan').val(response.data.kode_layanan);
                    $('#inputNamaLayanan').val(response.data.nama_layanan);
                    $('#selectSatuan option').each(function() {
                        console.log($(this).text());
                        if ($(this).text() == response.data.satuan) {
                            $(this).attr('selected', 'selected');
                        }
                    });
                    $('#inputHarga').val(response.data.harga.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ','));

                    $('#inputKodeLayanan').addClass('form-control-solid');
                    $('#inputKodeLayanan').attr('readonly', true);

                    $('#modalEntryLayanan').modal('show');

                    $('#modalEntryLayanan').on('shown.bs.modal', function () {
                        $('#inputNamaLayanan').focus();
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
        var kode_layanan = $(this).data('kode');
        var _token = $('input[name="_token"]').val();

        Swal.fire({
            html: `Anda memilih data layanan : <span class='badge badge-primary'>`+kode_layanan+`</span>
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
                    url: url.hapus_layanan,
                    method: 'post',
                    data: { kode_layanan: kode_layanan, _token:_token },

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
                                    loadMasterLayanan(1, 10, '');
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
