$(document).ready(function () {
    // ===============================================================
    // Daftar Item
    // ===============================================================
    function loadMasterItem(page = 1, per_page = 10, search = '') {
        loading.block();
        window.location.href = window.location.origin + window.location.pathname + '?page='+ page+'&per_page='+per_page+'&search='+search;
    }

    $('#inputSearch').on('change keydown', function (e) {
        if (e.keyCode == 13 || e.type == 'change') {
            var per_page = $('#selectPerPage').val();
            var search = $('#inputSearch').val();

            loadMasterItem(1, per_page, search);
        }
    });

    $('#selectPerPage').change(function() {
        var start_record = data.start_record;
        var per_page = $('#selectPerPage').val();
        var search = $('#inputSearch').val();
        var page = Math.ceil(start_record / per_page);

        loadMasterItem(page, per_page, search);
    });

    $(document).on('click', '.page-item a', function (e) {
        var page_link = $(this)[0].getAttribute('data-page');
        var page = page_link.split('?page=')[1];

        var per_page = $('#selectPerPage').val();
        var search = $('#inputSearch').val();

        loading.block();
        loadMasterItem(page, per_page, search);
    });

    // ===============================================================
    // Form Max Lenght
    // ===============================================================
    $('#inputKodeItem').maxlength({
        warningClass: "badge badge-success",
        limitReachedClass: "badge badge-danger",
        threshold: 5,
        appendToParent: true
    });

    $('#inputNamaItem').maxlength({
        warningClass: "badge badge-success",
        limitReachedClass: "badge badge-danger",
        threshold: 5,
        appendToParent: true
    });

    // ===============================================================
    // Modal Entry Item
    // ===============================================================
    function clearDataModal() {
        $('#inputKodeItem').removeClass('is-invalid');
        $('#messageKodeItem').html('');

        $('#alertFailed').addClass('d-none');
        $('#messageAlert').html('');

        $('#inputKodeItem').val('');
        $('#inputNamaItem').val('');
        $('#inputHargaDenda').val('');
    }

    $('#inputKodeItem').change(function() {
        var kode_item = $('#inputKodeItem').val();
        var _token = $('input[name="_token"]').val();

        if(!$('#inputKodeItem').hasClass('form-control-solid')) {
            loading.block();
            $.ajax({
                url: url.cek_kode_item,
                method: 'post',
                data: { kode_item: kode_item, _token: _token },

                success: function (response) {
                    loading.release();
                    if (response.status == true) {
                        $('#messageKodeItem').html('');
                        $('#inputKodeItem').removeClass('is-invalid');
                    } else {
                        $('#messageKodeItem').html(response.message);
                        $('#inputKodeItem').addClass('is-invalid');
                    }
                },
                error: function () {
                    loading.release();
                    $('#messageKodeItem').html('Server not responding');
                    $('#inputKodeItem').addClass('is-invalid');
                }
            })
        }
    });

    $('#inputHargaDenda').autoNumeric('init', {
        aSep: ',',
        aDec: '.',
        aForm: true,
        vMax: '9999999999999',
        vMin: '0'
    });

    $('#modalEntryItem').on('shown.bs.modal', function() {
        $('#formEntryItem').focus();
        $('#modalEntryItem').find('input').on('keydown', function (e) {
            if (e.which == 13) {
                e.preventDefault();
                var index = $('#modalEntryItem').find('input').index(this) + 1;
                $('#modalEntryItem').find('input').eq(index).focus();
            }
        });
    });

    $('#btnTambah').on('click', function() {
        clearDataModal();

        $('#inputKodeItem').removeClass('form-control-solid');
        $('#inputKodeItem').attr('readonly', false);

        $('#modalTitleItem').html('Tambah Data Item');
        $('#modalEntryItem').modal('show');

        $('#modalEntryItem').on('shown.bs.modal', function () {
            $('#inputKodeItem').focus();
        });
    });

    $('#btnSimpan').on('click', function(e) {
        e.preventDefault();

        if($('#inputKodeItem').hasClass('is-invalid')) {
            $('#alertFailed').removeClass('d-none');
            $('#messageAlert').html('Data yang anda entry belum benar');
        } else {
            if($('#inputKodeItem').val() == '' || $('#inputNamaItem').val() == '' || $('#inputHargaDenda').val() == '') {
                $('#alertFailed').removeClass('d-none');
                $('#messageAlert').html('Isi data secara lengkap');
            } else {
                $('#formEntryItem').submit();
            }
        }
    });

    $('body').on('click', '#btnEdit', function (e) {
        e.preventDefault();

        loading.block();
        $.ajax({
            url: url.form_item,
            method: 'get',
            data: { kode_item: $(this).data('kode') },

            success: function (response) {
                loading.release();
                if (response.status == true) {
                    $('#modalTitleItem').html('Edit Data Item');

                    $('#inputKodeItem').val(response.data.kode_item);
                    $('#inputNamaItem').val(response.data.nama_item);
                    $('#inputHargaDenda').val(response.data.harga_denda.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ','));

                    $('#inputKodeItem').addClass('form-control-solid');
                    $('#inputKodeItem').attr('readonly', true);

                    $('#modalEntryItem').modal('show');

                    $('#modalEntryItem').on('shown.bs.modal', function () {
                        $('#inputNamaItem').focus();
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
        var kode_item = $(this).data('kode');
        var _token = $('input[name="_token"]').val();

        Swal.fire({
            html: `Anda memilih data item : <span class='badge badge-primary'>`+kode_item+`</span>
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
                    url: url.hapus_item,
                    method: 'post',
                    data: { kode_item: kode_item, _token:_token },

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
                                    loadMasterItem(1, 10, '');
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
