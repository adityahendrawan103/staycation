$(document).ready(function () {
    // ===============================================================
    // Daftar RoomTipe
    // ===============================================================
    function loadMasterRoomTipe(page = 1, per_page = 10, search = '') {
        loading.block();
        window.location.href = window.location.origin + window.location.pathname + '?page='+ page+'&per_page='+per_page+'&search='+search;
    }

    $('#inputSearch').on('change keydown', function (e) {
        if (e.keyCode == 13 || e.type == 'change') {
            var per_page = $('#selectPerPage').val();
            var search = $('#inputSearch').val();

            loadMasterRoomTipe(1, per_page, search);
        }
    });

    $('#selectPerPage').change(function() {
        var start_record = data.start_record;
        var per_page = $('#selectPerPage').val();
        var search = $('#inputSearch').val();
        var page = Math.ceil(start_record / per_page);

        loadMasterRoomTipe(page, per_page, search);
    });

    $(document).on('click', '.page-item a', function (e) {
        var page_link = $(this)[0].getAttribute('data-page');
        var page = page_link.split('?page=')[1];

        var per_page = $('#selectPerPage').val();
        var search = $('#inputSearch').val();

        loadMasterRoomTipe(page, per_page, search);
    });

    // ===============================================================
    // Form Max Lenght
    // ===============================================================
    $('#inputKodeTipe').maxlength({
        warningClass: "badge badge-success",
        limitReachedClass: "badge badge-danger",
        threshold: 5,
        appendToParent: true
    });

    $('#inputNamaTipe').maxlength({
        warningClass: "badge badge-success",
        limitReachedClass: "badge badge-danger",
        threshold: 5,
        appendToParent: true
    });

    $('#inputGrade').maxlength({
        warningClass: "badge badge-success",
        limitReachedClass: "badge badge-danger",
        threshold: 5,
        appendToParent: true
    });

    // ===============================================================
    // Modal Entry RoomTipe
    // ===============================================================
    function clearDataModal() {
        $('#inputKodeTipe').removeClass('is-invalid');
        $('#messageKodeTipe').html('');

        $('#alertFailed').addClass('d-none');
        $('#messageAlert').html('');

        $('#inputKodeTipe').val('');
        $('#inputNamaTipe').val('');
        $('#inputGrade').val('');
        $('#inputHarga').val('');
    }

    $('#inputKodeTipe').change(function() {
        var kode_tipe = $('#inputKodeTipe').val();
        var _token = $('input[name="_token"]').val();

        if(!$('#inputKodeTipe').hasClass('form-control-solid')) {
            loading.block();
            $.ajax({
                url: url.cek_kode_tipe,
                method: 'post',
                data: { kode_tipe: kode_tipe, _token: _token },

                success: function (response) {
                    loading.release();
                    if (response.status == true) {
                        $('#messageKodeTipe').html('');
                        $('#inputKodeTipe').removeClass('is-invalid');
                    } else {
                        $('#messageKodeTipe').html(response.message);
                        $('#inputKodeTipe').addClass('is-invalid');
                    }
                },
                error: function () {
                    loading.release();
                    $('#messageKodeTipe').html('Server not responding');
                    $('#inputKodeTipe').addClass('is-invalid');
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

    $('#modalEntryRoomTipe').on('shown.bs.modal', function() {
        $('#formEntryRoomTipe').focus();
        $('#modalEntryRoomTipe').find('input').on('keydown', function (e) {
            if (e.which == 13) {
                e.preventDefault();
                var index = $('#modalEntryRoomTipe').find('input').index(this) + 1;
                $('#modalEntryRoomTipe').find('input').eq(index).focus();
            }
        });
    });

    $('#btnTambah').on('click', function() {
        clearDataModal();

        $('#inputKodeTipe').removeClass('form-control-solid');
        $('#inputKodeTipe').attr('readonly', false);

        $('#modalTitleRoomTipe').html('Tambah Data Room Tipe');
        $('#modalEntryRoomTipe').modal('show');

        $('#modalEntryRoomTipe').on('shown.bs.modal', function () {
            $('#inputKodeTipe').focus();
        });
    });

    $('#btnSimpan').on('click', function(e) {
        e.preventDefault();

        if($('#inputKodeTipe').hasClass('is-invalid')) {
            $('#alertFailed').removeClass('d-none');
            $('#messageAlert').html('Data yang anda entry belum benar');
        } else {
            if($('#inputKodeTipe').val() == '' || $('#inputNamaTipe').val() == '' || $('#inputGrade').val() == '' || $('#inputHarga').val() == '') {
                $('#alertFailed').removeClass('d-none');
                $('#messageAlert').html('Isi data secara lengkap');
            } else {
                $('#formEntryRoomTipe').submit();
            }
        }
    });

    $('body').on('click', '#btnEdit', function (e) {
        e.preventDefault();

        loading.block();
        $.ajax({
            url: url.form_room_tipe,
            method: 'get',
            data: { kode_tipe: $(this).data('kode') },

            success: function (response) {
                loading.release();
                if (response.status == true) {
                    $('#modalTitleRoomTipe').html('Edit Data RoomTipe');

                    $('#inputKodeTipe').val(response.data.kode_tipe);
                    $('#inputNamaTipe').val(response.data.nama_tipe);
                    $('#inputGrade').val(response.data.grade);
                    $('#inputHarga').val(response.data.harga.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ','));

                    $('#inputKodeTipe').addClass('form-control-solid');
                    $('#inputKodeTipe').attr('readonly', true);

                    $('#modalEntryRoomTipe').modal('show');

                    $('#modalEntryRoomTipe').on('shown.bs.modal', function () {
                        $('#inputNamaTipe').focus();
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
        var kode_tipe = $(this).data('kode');
        var _token = $('input[name="_token"]').val();

        Swal.fire({
            html: `Anda memilih data tipe : <span class='badge badge-primary'>`+kode_tipe+`</span>
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
                    url: url.hapus_room_tipe,
                    method: 'post',
                    data: { kode_tipe: kode_tipe, _token:_token },

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
                                    loadMasterRoomTipe(1, 10, '');
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
