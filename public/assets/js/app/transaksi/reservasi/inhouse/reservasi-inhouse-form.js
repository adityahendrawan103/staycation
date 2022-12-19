function hitungTotalEditRoom() {
    var input_harga_room = $('#inputModalEditHargaRoom').val().replaceAll(',', '');
    var input_diskon_room = $('#inputModalEditDiskonRoomProsentase').val().replaceAll(',', '');
    var input_ppn_room = $('#inputModalEditPPNRoomProsentase').val().replaceAll(',', '');

    var harga_room = parseFloat(input_harga_room);
    var total_harga_room = parseFloat(input_harga_room) * parseFloat(data.lama_inap);
    var diskon_room_nominal = (parseFloat(total_harga_room) * parseFloat(input_diskon_room)) / 100;
    var harga_min_disc = parseFloat(total_harga_room) - parseFloat(diskon_room_nominal);
    var ppn_room_nominal = (parseFloat(harga_min_disc) * parseFloat(input_ppn_room)) / 100;
    var total_room = parseFloat(harga_min_disc) - parseFloat(ppn_room_nominal);

    $('#inpuModalEditHargaRoom').val(formatDecimal(harga_room));
    $('#inpuModalEditTotalRoom').val(formatDecimal(total_harga_room));
    $('#inputModalEditDiscRoomNominal').val(formatDecimal(diskon_room_nominal));
    $('#inputModalEditPPNRoomNominal').val(formatDecimal(ppn_room_nominal));
    $('#inputModalEditGrandTotalRoom').val(formatDecimal(total_room));
}

function hitungTotalExtendRoom() {
    var input_harga_room = $('#inputModalExtendHargaRoom').val().replaceAll(',', '');
    var input_diskon_room = $('#inputModalExtendDiskonRoomProsentase').val().replaceAll(',', '');
    var input_ppn_room = $('#inputModalExtendPPNRoomProsentase').val().replaceAll(',', '');
    var xLamaInap = 0;

    if(data.status_longtime == "1") {
        var oneDay = 24 * 60 * 60 * 1000;
        var checkInDate = new Date($('#inputModalExtendTanggalCheckIn').val());
        var checkOutDate = new Date($('#inputModalExtendTanggalCheckOut').val());

        xLamaInap = Math.round(Math.abs((checkInDate - checkOutDate) / oneDay));
        $('#inputModalExtendLamaInap').val(xLamaInap + ' MALAM');
    } else {
        var time1 = $("#inputJamCheckIn").val().split(':'), time2 = $("#inputJamCheckOut").val().split(':');
        var hours1 = parseInt(time1[0], 10),
            hours2 = parseInt(time2[0], 10),
            mins1 = parseInt(time1[1], 10),
            mins2 = parseInt(time2[1], 10);
        var hours = hours2 - hours1, mins = 0;
        if(hours < 0) hours = 24 + hours;
        if(mins2 >= mins1) {
            mins = mins2 - mins1;
        }
        else {
            mins = (mins2 + 60) - mins1;
            hours--;
        }
        mins = mins / 60;
        hours += mins;
        hours = hours.toFixed(2);

        xLamaInap = Math.ceil(hours / data.default_jam_reservasi);
        $('#inputModalExtendLamaInap').val(xLamaInap + 'x / '+data.default_jam_reservasi+' JAM');
    }

    var total_harga_room = parseFloat(input_harga_room) * parseFloat(xLamaInap);
    var diskon_room_nominal = (parseFloat(total_harga_room) * parseFloat(input_diskon_room)) / 100;
    var harga_min_disc = parseFloat(total_harga_room) - parseFloat(diskon_room_nominal);
    var ppn_room_nominal = (parseFloat(harga_min_disc) * parseFloat(input_ppn_room)) / 100;
    var total_room = parseFloat(harga_min_disc) - parseFloat(ppn_room_nominal);

    $('#inputModalExtendTotalRoom').val(formatDecimal(total_harga_room));
    $('#inputModalExtendDiscRoomNominal').val(formatDecimal(diskon_room_nominal));
    $('#inputModalExtendPPNRoomNominal').val(formatDecimal(ppn_room_nominal));
    $('#inputModalExtendGrandTotalRoom').val(formatDecimal(total_room));
}

$(document).ready(function () {
    $('#inputModalEditAlasan').maxlength({
        warningClass: "badge badge-success",
        limitReachedClass: "badge badge-danger",
        threshold: 5,
        appendToParent: true
    });

    $('#inputCatatan').maxlength({
        warningClass: "badge badge-success",
        limitReachedClass: "badge badge-danger",
        threshold: 5,
        appendToParent: true
    });

    $('#inputBiayaLain').autoNumeric('init', {
        aSep: ',',
        aDec: '.',
        aForm: true,
        vMax: '9999999999999',
        vMin: '0'
    });

    $('#inputTotalPembayaran').autoNumeric('init', {
        aSep: ',',
        aDec: '.',
        aForm: true,
        vMax: '9999999999999',
        vMin: '0'
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

    $('#inputStatusLongtime').change(function() {
        if (data.status_longtime == "1") {
            $("#inputStatusLongtime").prop('checked', true);
        } else {
            $("#inputStatusLongtime").prop('checked', false);
        }
    });

    $('#inputModalExtendStatusLongtime').change(function() {
        if (data.status_longtime == "1") {
            $("#inputModalExtendStatusLongtime").prop('checked', true);
        } else {
            $("#inputModalExtendStatusLongtime").prop('checked', false);
        }
    });

    $('#btnEditReservasi').on('click', function (e) {
        e.preventDefault();
        $('#formEntryEditReservasi').trigger('reset');
        $('#modalTitleEditReservasi').html('Entry Data Reservasi');
        $('#modalEntryEditReservasi').modal('show');
    });

    $('#btnExtendReservasi').on('click', function (e) {
        e.preventDefault();
        $('#formEntryExtendReservasi').trigger('reset');
        $('#modalTitleExtendReservasi').html('Entry Extend Reservasi');
        $('#modalEntryExtendReservasi').modal('show');
    });

    $('#modalEntryEditReservasi').on('shown.bs.modal', function() {
        $('#formEntryEditReservasi').focus();
        $('#modalEntryEditReservasi').find('input').on('keydown', function (e) {
            if (e.which == 13) {
                e.preventDefault();
                var index = $('#modalEntryEditReservasi').find('input').index(this) + 1;
                $('#modalEntryEditReservasi').find('input').eq(index).focus();
            }
        });
    });

    $('#modalEntryExtendReservasi').on('shown.bs.modal', function() {
        $('#formEntryExtendReservasi').focus();
        $('#modalEntryExtendReservasi').find('input').on('keydown', function (e) {
            if (e.which == 13) {
                e.preventDefault();
                var index = $('#modalEntryExtendReservasi').find('input').index(this) + 1;
                $('#modalEntryExtendReservasi').find('input').eq(index).focus();
            }
        });

        if (data.status_longtime == "1") {
            $('#inputModalExtendTanggalCheckOut').removeClass('form-control-solid');
            $('#inputModalExtendJamCheckOut').addClass('form-control-solid');

            $("#inputModalExtendTanggalCheckOut").flatpickr({
                clickOpens: true,
                dateFormat: 'Y-m-d',
                defaultDate: $('#inputModalExtendTanggalCheckOut').val(),
                minDate: $('#inputModalExtendTanggalCheckOut').val()
            });
        } else {
            $('#inputModalExtendTanggalCheckOut').addClass('form-control-solid');
            $('#inputModalExtendJamCheckOut').removeClass('form-control-solid');
            $("#inputModalExtendJamCheckOut").flatpickr({
                clickOpens: true,
                noCalendar: true,
                enableTime: true,
                dateFormat: "H:i:ss",
                defaultDate: $('#inputModalExtendJamCheckOut').val()
            });
        }
    });

    // ===============================================================
    // Modal Edit Room
    // ===============================================================
    $('#inputModalEditKodeTipe').on('click', function (e) {
        e.preventDefault();

        loadDataRoomTipe(1, 10, '');
        $('#formOptionRoomTipe').trigger('reset');
        $('#optionModalRoomTipe').modal('show');
    });

    $('#btnModalOptionTipeRoom').on('click', function (e) {
        e.preventDefault();

        var checkInDate = $('#inputModalEditTanggalCheckIn').val();
        var checkOutDate = $('#inputModalEditTanggalCheckOut').val();

        if(checkInDate == '' || checkOutDate == '') {
            Swal.fire({
                text: 'Isi data tanggal check in dan check out terlebih dahulu',
                icon: 'warning',
                buttonsStyling: false,
                confirmButtonText: 'Ok, got it!',
                customClass: {
                    confirmButton: 'btn btn-warning'
                }
            });
        } else {
            loadDataRoomTipe(1, 10, '');
            $('#formOptionRoomTipe').trigger('reset');
            $('#optionModalRoomTipe').modal('show');
        }
    });

    $('body').on('click', '#selectOptionRoomTipe', function (e) {
        e.preventDefault();

        var tipeSebelumnya = $('#inputModalEditKodeTipe').val();

        $('#inputModalEditKodeTipe').val($(this).data('kode_tipe'));
        $('#inputModalEditNamaTipe').val($(this).data('nama_tipe'));

        if(tipeSebelumnya != $('#inputModalEditKodeTipe').val()) {
            $('#inputModalEditKodeRoom').val('');
            $('#inputModalEditHargaRoom').val(0);
            $('#inputModalEditTotalRoom').val(0);
        }

        $('#optionModalRoomTipe').modal('hide');
    });

    $('#inputModalEditKodeRoom').on('click', function (e) {
        e.preventDefault();

        var kode_tipe = $('#inputModalEditKodeTipe').val();
        var checkInDate = $('#inputTanggalCheckIn').val()+' '+$('#inputJamCheckIn').val();
        var checkOutDate = $('#inputTanggalCheckOut').val()+' '+$('#inputJamCheckOut').val();

        if(kode_tipe == '' || checkInDate == '' || checkOutDate == '') {
            Swal.fire({
                text: 'Data tipe ruangan, tanggal check in, dan tanggal check out tidak boleh kosong',
                icon: 'warning',
                buttonsStyling: false,
                confirmButtonText: 'Ok, got it!',
                customClass: {
                    confirmButton: 'btn btn-warning'
                }
            });
        } else {
            loadDataRoomReservasi(kode_tipe, checkInDate, checkOutDate, 1, 10, '');
            $('#formOptionRoomReservasi').trigger('reset');
            $('#optionModalRoomReservasi').modal('show');
        }
    });

    $('#btnModalOptionRoom').on('click', function (e) {
        e.preventDefault();

        var kode_tipe = $('#inputModalEditKodeTipe').val();
        var checkInDate = $('#inputModalEditTanggalCheckIn').val()+' '+$('#inputModalEditJamCheckIn').val();
        var checkOutDate = $('#inputModalEditTanggalCheckOut').val()+' '+$('#inputModalEditJamCheckOut').val();

        if(kode_tipe == '' || checkInDate == '' || checkOutDate == '') {
            Swal.fire({
                text: 'Data tipe ruangan, tanggal check in, dan tanggal check out tidak boleh kosong',
                icon: 'warning',
                buttonsStyling: false,
                confirmButtonText: 'Ok, got it!',
                customClass: {
                    confirmButton: 'btn btn-warning'
                }
            });
        } else {
            loadDataRoomReservasi(kode_tipe, checkInDate, checkOutDate, 1, 10, '');
            $('#formOptionRoomReservasi').trigger('reset');
            $('#optionModalRoomReservasi').modal('show');
        }
    });

    $('body').on('click', '#selectOptionRoom', function (e) {
        e.preventDefault();
        $('#inputModalEditKodeRoom').val($(this).data('kode_room'));

        if(data.status_longtime == "1") {
            $('#inputModalEditHargaRoom').val($(this).data('longtime'));
            $('#inputModalEditTotalRoom').val($(this).data('longtime'));
        } else {
            $('#inputModalEditHargaRoom').val($(this).data('shorttime'));
            $('#inputModalEditTotalRoom').val($(this).data('shorttime'));
        }

        $('#optionModalRoomReservasi').modal('hide');
    });

    $('#inputModalEditDiskonRoomProsentase').change(function() {
        if($('#inputModalEditDiskonRoomProsentase').val() == '') {
            $('#inputModalEditDiskonRoomProsentase').val(0);
        }
        $('#inputModalEditDiskonRoomProsentase').val(Number($('#inputModalEditDiskonRoomProsentase').val()).toFixed(2));
        hitungTotalEditRoom();
    });

    $('#inputModalEditPpnRoomProsentase').change(function() {
        if($('#inputModalEditpnRoomProsentase').val() == '') {
            $('#inputModalEditPpnRoomProsentase').val(0);
        }
        $('#inputModalEditPpnRoomProsentase').val(Number($('#inputModalEditPpnRoomProsentase').val()).toFixed(2));
        hitungTotalEditRoom();
    });
    // ===============================================================
    // End Modal Edit Reservasi
    // ===============================================================

    // ===============================================================
    // Modal Edit Extend Reservasi
    // ===============================================================
    $('#inputModalExtendJamCheckOut').change(function() {
        var dateCheckIn = $('#inputModalExtendTanggalCheckIn').val();
        var dateCheckOut = $('#inputModalExtendTanggalCheckOut').val();
        var timeCheckOut = $('#inputModalExtendJamCheckOut').val();

        var datetimeNow = new Date();
        var datetimeCheckOut = new Date(dateCheckIn + ' ' + timeCheckOut);

        if(datetimeNow > datetimeCheckOut) {
            var datetimeCheckOut = new Date(datetimeNow);
            var addDayCheckOut = datetimeCheckOut.setDate(datetimeCheckOut.getDate() + 1);

            var datetimeCheckOut = new Date(addDayCheckOut);
            var dateCheckOut = datetimeCheckOut.getFullYear() + "-";

            if (datetimeCheckOut.getMonth() < 9) {
                dateCheckOut += "0";
            }
            dateCheckOut += (datetimeCheckOut.getMonth() + 1);
            dateCheckOut += "-";

            if(datetimeCheckOut.getDate() < 10) {
                dateCheckOut += "0";
            }
            dateCheckOut += datetimeCheckOut.getDate();

            $("#inputModalExtendTanggalCheckOut").flatpickr({
                clickOpens: false,
                dateFormat: 'Y-m-d',
                defaultDate: dateCheckOut,
                minDate: moment(new Date()).format('YYYY-MM-DD')
            });
        } else {
            $("#inputModalExtendTanggalCheckOut").flatpickr({
                clickOpens: false,
                dateFormat: 'Y-m-d',
                defaultDate: moment(datetimeNow).format('YYYY-MM-DD'),
                minDate: moment(new Date()).format('YYYY-MM-DD')
            });
        }
        hitungGrandTotal();
    });

    $('#inputModalExtendTanggalCheckOut').change(function() {
        var checkInDate = new Date($('#inputModalExtendTanggalCheckIn').val());
        var checkOutDate = new Date($('#inputModalExtendTanggalCheckOut').val());

        if(checkInDate == '' || checkOutDate == '') {
            Swal.fire({
                text: 'Isi data tanggal check in dan check out dengan benar',
                icon: 'warning',
                buttonsStyling: false,
                confirmButtonText: 'Ok, got it!',
                customClass: {
                    confirmButton: 'btn btn-warning'
                }
            });
        } else {
            if (checkInDate > checkOutDate){
                Swal.fire({
                    text: 'Tanggal check out harus lebih besar dari tanggal check in',
                    icon: 'warning',
                    buttonsStyling: false,
                    confirmButtonText: 'Ok, got it!',
                    customClass: {
                        confirmButton: 'btn btn-warning'
                    }
                });
                $('#inputModalExtendTanggalCheckOut').val('');
            } else {
                hitungTotalExtendRoom();
            }
        }
    });
    // ===============================================================
    // End Modal Extend Reservasi
    // ===============================================================

    $('#inputBiayaLain').change(function() {
        if($('#inputBiayaLain').val() == '') {
            $('#inputBiayaLain').val(0);
        }
        hitungGrandTotal();
    });

    $('#inputTotalPembayaran').change(function() {
        if($('#inputTotalPembayaran').val() == '') {
            $('#inputTotalPembayaran').val(0);
        }
        hitungGrandTotal();
    });

    $('#btnSimpan').on('click', function (e) {
        e.preventDefault();
        var button = $(e.target);
        var formData = button.parents('form').serialize() + '&' + encodeURI(button.attr('name')) + '=' + encodeURI(button.attr('value'));

        $.ajax({
            url: url.simpan_reservasi_inhouse,
            method: 'post',
            data: formData,

            success: function (response) {
                loading.release();
                if (response.status == false) {
                    Swal.fire({
                        text: response.message,
                        icon: 'warning',
                        buttonsStyling: false,
                        confirmButtonText: 'Ok, got it!',
                        customClass: {
                            confirmButton: 'btn btn-warning'
                        }
                    });
                } else {
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
                            window.location.href = url.daftar_reservasi;
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
});
