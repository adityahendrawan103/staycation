$(document).ready(function () {
    // ===============================================================
    // Daftar Room
    // ===============================================================
    function loadMasterRoom(page = 1, per_page = 10, search = '') {
        loading.block();
        window.location.href = window.location.origin + window.location.pathname + '?page='+ page+'&per_page='+per_page+'&search='+search;
    }

    $('#inputSearch').on('change keydown', function (e) {
        if (e.keyCode == 13 || e.type == 'change') {
            var per_page = $('#selectPerPage').val();
            var search = $('#inputSearch').val();

            loadMasterRoom(1, per_page, search);
        }
    });

    $('#selectPerPage').change(function() {
        var start_record = data.start_record;
        var per_page = $('#selectPerPage').val();
        var search = $('#inputSearch').val();
        var page = Math.ceil(start_record / per_page);

        loadMasterRoom(page, per_page, search);
    });

    $(document).on('click', '.page-item a', function (e) {
        var page_link = $(this)[0].getAttribute('data-page');
        var page = page_link.split('?page=')[1];

        var per_page = $('#selectPerPage').val();
        var search = $('#inputSearch').val();

        loading.block();
        loadMasterRoom(page, per_page, search);
    });

    // ===============================================================
    // Form Max Lenght
    // ===============================================================
    $('#inputKodeRoom').maxlength({
        warningClass: "badge badge-success",
        limitReachedClass: "badge badge-danger",
        threshold: 5,
        appendToParent: true
    });

    $('#inputLantai').maxlength({
        warningClass: "badge badge-success",
        limitReachedClass: "badge badge-danger",
        threshold: 5,
        appendToParent: true
    });

    $('#inputKapasitas').maxlength({
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
    // Form Entry Room
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

    $('#inputKodeRoom').change(function() {
        var kode_room = $('#inputKodeRoom').val();
        var _token = $('input[name="_token"]').val();

        if(!$('#inputKodeRoom').hasClass('form-control-solid')) {
            loading.block();
            $.ajax({
                url: url.cek_kode_room,
                method: 'post',
                data: { kode_room: kode_room, _token: _token },

                success: function (response) {
                    loading.release();
                    if (response.status == true) {
                        $('#messageKodeRoom').html('');
                        $('#inputKodeRoom').removeClass('is-invalid');
                    } else {
                        $('#messageKodeRoom').html(response.message);
                        $('#inputKodeRoom').addClass('is-invalid');
                    }
                },
                error: function () {
                    loading.release();
                    $('#messageKodeRoom').html('Server not responding');
                    $('#inputKodeRoom').addClass('is-invalid');
                }
            })
        }
    });

    $('#inputLongTime').autoNumeric('init', {
        aSep: ',',
        aDec: '.',
        aForm: true,
        vMax: '9999999999999',
        vMin: '0'
    });

    $('#inputShortTime').autoNumeric('init', {
        aSep: ',',
        aDec: '.',
        aForm: true,
        vMax: '9999999999999',
        vMin: '0'
    });

    $('#inputKodeTipe').on('click', function (e) {
        e.preventDefault();

        loadDataRoomTipe(1, 10, '');
        $('#formOptionRoomTipe').trigger('reset');
        $('#optionModalRoomTipe').modal('show');
    });

    $('#btnOptionRoomTipe').on('click', function (e) {
        e.preventDefault();

        loadDataRoomTipe(1, 10, '');
        $('#formOptionRoomTipe').trigger('reset');
        $('#optionModalRoomTipe').modal('show');
    });

    $('body').on('click', '#selectOptionRoomTipe', function (e) {
        e.preventDefault();
        $('#inputKodeTipe').val($(this).data('kode_tipe'));
        $('#inputNamaTipe').val($(this).data('nama_tipe'));
        $('#inputHarga').val($(this).data('harga_tipe'));
        $('#optionModalRoomTipe').modal('hide');
    });

    $('#btnTambahFasilitas').on('click', function (e) {
        e.preventDefault();

        loadDataFasilitas(1, 10, '');
        $('#formCheckListFasilitas').trigger('reset');
        $('#modalCheckListFasilitas').modal('show');
    });

    $('#btnBatal').on('click', function() {
        loading.block();
        location.reload();
    });

    $('body').on('click', '#btnHapus', function (e) {
        e.preventDefault();
        var kode_room = $(this).data('kode');
        var _token = $('input[name="_token"]').val();

        Swal.fire({
            html: `Anda memilih data room : <span class='badge badge-primary'>`+kode_room+`</span>
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
                    url: url.hapus_room,
                    method: 'post',
                    data: { kode_room: kode_room, _token:_token },

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
                                    loadMasterRoom(1, 10, '');
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
