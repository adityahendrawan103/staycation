$(document).ready(function () {
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

    $('#inputDeposit').autoNumeric('init', {
        aSep: ',',
        aDec: '.',
        aForm: true,
        vMax: '9999999999999',
        vMin: '0'
    });

    function defaultData() {
        $("#btnSimpan").val('simpan');

        if (data.status_longtime == "1") {
            $("#inputStatusLongtime").prop('checked', true);
        } else {
            $("#inputStatusLongtime").prop('checked', false);
        }

        var tanggal_check_in = data.tanggal_check_in;
        var tanggal_check_out = data.tanggal_check_out;
        var jam_check_in = data.jam_check_in;
        var jam_check_out = data.jam_check_out;

        if(data.tanggal_check_in.trim() == '') {
            tanggal_check_in = data.default_tanggal_check_in.trim();
            tanggal_check_out = data.default_tanggal_check_out.trim();
            jam_check_in = data.default_jam_check_in.trim();
            jam_check_out = data.default_jam_check_out.trim();
        }
        statusForm('', tanggal_check_in, tanggal_check_out, jam_check_in, jam_check_out);
    }
    window.onload = defaultData();



    var inputListFasilitas = document.querySelector("#inputFasilitasRoom");
    new Tagify(inputListFasilitas);

    function hitungDefaultCheckOut(defaultInput, dateCheckIn, timeCheckIn) {
        if ($('#inputStatusLongtime').is(':checked')) {
            var dateTimeCheckIn = new Date(dateCheckIn);
            var addDayCheckOut = dateTimeCheckIn.setDate(dateTimeCheckIn.getDate() + 1);

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

            var timeCheckOut = data.default_jam_check_out;

            statusForm(defaultInput, dateCheckIn, dateCheckOut, timeCheckIn, timeCheckOut);
        } else {
            var dateTimeCheckIn = new Date(dateCheckIn + ' ' + timeCheckIn);
            var addHoursCheckOut = dateTimeCheckIn.setHours(dateTimeCheckIn.getHours() + parseInt(data.default_jam_reservasi));

            var datetimeCheckOut = new Date(addHoursCheckOut);
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

            var timeCheckOut = (datetimeCheckOut.getHours() < 10 ? '0' : '') + datetimeCheckOut.getHours() + ":" +
                            (datetimeCheckOut.getMinutes() < 10 ? '0' : '') + datetimeCheckOut.getMinutes() + ':00';

            statusForm(defaultInput, dateCheckIn, dateCheckOut, timeCheckIn, timeCheckOut);
        }
    }

    function statusForm(defaultInput, defaultTanggalCheckIn, defaultTanggalCheckOut, defaultJamCheckIn, defaultJamCheckOut) {
        $('#inputTanggalCheckIn').addClass('form-control-solid');
        $('#inputTanggalCheckOut').addClass('form-control-solid');
        $('#inputJamCheckIn').addClass('form-control-solid');
        $('#inputJamCheckOut').addClass('form-control-solid');

        if ($('#inputStatusLongtime').is(':checked')) {
            if(data.status_form.indexOf("EDIT") < 0) {
                $('#inputTanggalCheckIn').removeClass('form-control-solid');
                $('#inputTanggalCheckOut').removeClass('form-control-solid');
            }
        } else {
            if(data.status_form.indexOf("EDIT") < 0) {
                $('#inputTanggalCheckIn').removeClass('form-control-solid');
                $('#inputJamCheckIn').removeClass('form-control-solid');
                $('#inputJamCheckOut').removeClass('form-control-solid');
            }
        }

        if(defaultInput != 'tanggal_check_in') {
            if($('#inputTanggalCheckIn').hasClass('form-control-solid')) {
                $("#inputTanggalCheckIn").flatpickr({
                    clickOpens: false,
                    dateFormat: 'Y-m-d',
                    minDate: moment(new Date().fp_incr(-1)).format('YYYY-MM-DD'),
                    defaultDate: moment(new Date(defaultTanggalCheckIn)).format('YYYY-MM-DD')
                });
            } else {
                $("#inputTanggalCheckIn").flatpickr({
                    clickOpens: true,
                    dateFormat: 'Y-m-d',
                    minDate: moment(new Date().fp_incr(-1)).format('YYYY-MM-DD'),
                    defaultDate: moment(new Date(defaultTanggalCheckIn)).format('YYYY-MM-DD')
                });
            }
        }

        if(defaultInput != 'tanggal_check_out') {
            if($('#inputTanggalCheckOut').hasClass('form-control-solid')) {
                $("#inputTanggalCheckOut").flatpickr({
                    clickOpens: false,
                    dateFormat: 'Y-m-d',
                    minDate: moment(new Date(defaultTanggalCheckIn)).format('YYYY-MM-DD'),
                    defaultDate: moment(new Date(defaultTanggalCheckOut)).format('YYYY-MM-DD')
                });
            } else {
                $("#inputTanggalCheckOut").flatpickr({
                    clickOpens: true,
                    dateFormat: 'Y-m-d',
                    minDate: moment(new Date(defaultTanggalCheckIn).fp_incr(+1)).format('YYYY-MM-DD'),
                    defaultDate: moment(new Date(defaultTanggalCheckOut)).format('YYYY-MM-DD')
                });
            }
        }

        if(defaultInput != 'jam_check_in') {
            if($('#inputJamCheckIn').hasClass('form-control-solid')) {
                $("#inputJamCheckIn").flatpickr({
                    clickOpens: false,
                    dateFormat: "H:i:ss",
                    defaultDate: defaultJamCheckIn
                });
            } else {
                $("#inputJamCheckIn").flatpickr({
                    clickOpens: true,
                    noCalendar: true,
                    enableTime: true,
                    dateFormat: "H:i:ss",
                    defaultDate: defaultJamCheckIn
                });
            }
        }

        if(defaultInput != 'jam_check_out') {
            if($('#inputJamCheckOut').hasClass('form-control-solid')) {
                $("#inputJamCheckOut").flatpickr({
                    clickOpens: false,
                    dateFormat: "H:i:ss",
                    defaultDate: defaultJamCheckOut
                });
            } else {
                $("#inputJamCheckOut").flatpickr({
                    clickOpens: true,
                    noCalendar: true,
                    enableTime: true,
                    dateFormat: "H:i:ss",
                    defaultDate: defaultJamCheckOut
                });
            }
        }
    }

    // ===============================================================
    // Form Max Lenght
    // ===============================================================
    $('#inputNamaCP').maxlength({
        warningClass: "badge badge-success",
        limitReachedClass: "badge badge-danger",
        threshold: 5,
        appendToParent: true
    });

    $('#inputTeleponCP').maxlength({
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

    $('#inputCatatan').maxlength({
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

    $('#inputKodePlatform').on('click', function (e) {
        e.preventDefault();

        loadDataPlatform(1, 10, '');
        $('#formOptionPlatform').trigger('reset');
        $('#optionModalPlatform').modal('show');
    });

    $('#btnOptionPlatform').on('click', function (e) {
        e.preventDefault();

        loadDataPlatform(1, 10, '');
        $('#formOptionPlatform').trigger('reset');
        $('#optionModalPlatform').modal('show');
    });

    $('body').on('click', '#selectOptionPlatform', function (e) {
        e.preventDefault();
        $('#inputKodePlatform').val($(this).data('kode_platform'));
        $('#inputNamaPlatform').val($(this).data('nama_platform'));
        $('#optionModalPlatform').modal('hide');
    });

    $('#inputNomorReferensi').change(function() {
        var kode_reservasi = $('#inputKodeReservasi').val();
        var no_referensi = $('#inputNomorReferensi').val();
        var _token = $('input[name="_token"]').val();

        if(no_referensi != '') {
            if(no_referensi != '-') {
                $.ajax({
                    url: url.cek_no_referensi_reservasi,
                    method: 'post',
                    data: { kode_reservasi: kode_reservasi,
                            no_referensi: no_referensi, _token: _token },

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
                            $('#inputNomorReferensi').val('');
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

            }
        }
    });

    $('#inputNoIdentitas').on('click', function (e) {
        e.preventDefault();

        loadDataCustomer(1, 10, '');
        $('#formOptionCustomer').trigger('reset');
        $('#optionModalCustomer').modal('show');
    });

    $('#btnOptionCustomer').on('click', function (e) {
        e.preventDefault();

        loadDataCustomer(1, 10, '');
        $('#formOptionCustomer').trigger('reset');
        $('#optionModalCustomer').modal('show');
    });

    $('body').on('click', '#selectOptionCustomer', function (e) {
        e.preventDefault();

        $('#inputNoIdentitas').val($(this).data('no_identitas'));
        $('#inputNamaCustomer').val($(this).data('nama'));
        $('#inputKotaCustomer').val($(this).data('kota'));
        $('#inputTanggalLahirCustomer').val($(this).data('tanggal_lahir'));

        $('#optionModalCustomer').modal('hide');
    });

    $('#inputTanggalCheckIn').change(function() {
        if ($('#inputStatusLongtime').is(':checked')) {
            var dateCheckIn = $('#inputTanggalCheckIn').val();

            hitungDefaultCheckOut('tanggal_check_in', dateCheckIn, data.default_jam_check_in);
        } else {
            var dateCheckIn = $('#inputTanggalCheckIn').val();
            var timeNow = new Date();
            var timeCheckIn = (timeNow.getHours() < 10 ? '0' : '') + timeNow.getHours() + ":" +
                            (timeNow.getMinutes() < 10 ? '0' : '') + timeNow.getMinutes() + ':00';

            hitungDefaultCheckOut('tanggal_check_in', dateCheckIn, timeCheckIn);
        }
    });

    $('#inputJamCheckIn').change(function() {
        var dateCheckIn = $('#inputTanggalCheckIn').val();
        var timeCheckIn = $('#inputJamCheckIn').val();
        hitungDefaultCheckOut('jam_check_in', dateCheckIn, timeCheckIn);
        hitungGrandTotal();
    });

    $('#inputJamCheckOut').change(function() {
        var dateCheckIn = $('#inputTanggalCheckIn').val();
        var dateCheckOut = $('#inputTanggalCheckOut').val();
        var timeCheckIn = $('#inputJamCheckIn').val();
        var timeCheckOut = $('#inputJamCheckOut').val();

        var datetimeCheckIn = new Date(dateCheckIn + ' ' + timeCheckIn);
        var datetimeCheckOut = new Date(dateCheckIn + ' ' + timeCheckOut);

        if(datetimeCheckIn > datetimeCheckOut) {
            var datetimeCheckOut = new Date(dateCheckIn);
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

            $("#inputTanggalCheckOut").flatpickr({
                dateFormat: 'Y-m-d',
                defaultDate: dateCheckOut,
                minDate: moment(new Date()).format('YYYY-MM-DD')
            });
        } else {
            $("#inputTanggalCheckOut").flatpickr({
                dateFormat: 'Y-m-d',
                defaultDate: dateCheckIn,
                minDate: moment(new Date()).format('YYYY-MM-DD')
            });
        }
        hitungGrandTotal();
    });

    $('#inputTanggalCheckOut').change(function() {
        var checkInDate = new Date($('#inputTanggalCheckIn').val());
        var checkOutDate = new Date($('#inputTanggalCheckOut').val());

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
                $('#inputTanggalCheckOut').val('');
            } else {
                hitungGrandTotal();
            }
        }
    });

    $('#inputStatusLongtime').change(function() {
        if(data.status_form.indexOf("EDIT") < 0) {
            if ($('#inputStatusLongtime').is(':checked')) {
                var dateCheckIn = $('#inputTanggalCheckIn').val();

                hitungDefaultCheckOut('', dateCheckIn, data.default_jam_check_in);
            } else {
                var dateCheckIn = $('#inputTanggalCheckIn').val();
                var timeNow = new Date();
                var timeCheckIn = (timeNow.getHours() < 10 ? '0' : '') + timeNow.getHours() + ":" +
                                (timeNow.getMinutes() < 10 ? '0' : '') + timeNow.getMinutes() + ':00';
                hitungDefaultCheckOut('', dateCheckIn, timeCheckIn);
            }
        } else {
            if ($('#inputStatusLongtime').is(':checked')) {
                if (data.status_longtime == "1") {
                    $("#inputStatusLongtime").prop('checked', true);
                } else {
                    $("#inputStatusLongtime").prop('checked', false);
                }
            } else {
                if (data.status_longtime == "1") {
                    $("#inputStatusLongtime").prop('checked', true);
                } else {
                    $("#inputStatusLongtime").prop('checked', false);
                }
            }
        }
        hitungGrandTotal();
    });

    $('#inputKodeTipe').on('click', function (e) {
        e.preventDefault();

        loadDataRoomTipe(1, 10, '');
        $('#formOptionRoomTipe').trigger('reset');
        $('#optionModalRoomTipe').modal('show');
    });

    $('#btnOptionRoomTipe').on('click', function (e) {
        e.preventDefault();

        var checkInDate = $('#inputTanggalCheckIn').val();
        var checkOutDate = $('#inputTanggalCheckOut').val();

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
        var tipeSebelumnya = $('#inputKodeTipe').val();

        $('#inputKodeTipe').val($(this).data('kode_tipe'));
        $('#inputNamaTipe').val($(this).data('nama_tipe'));
        $('#inputGradeTipe').val($(this).data('grade'));

        if(tipeSebelumnya != $('#inputKodeTipe').val()) {
            $('#inputKodeRoom').val('');
            $('#inputTipeRoom').val('');
            $('#inputHargaLongtime').val(0);
            $('#inputHargaShorttime').val(0);
            $('#inputFasilitasRoom').val('');
            $('#inputTotalRoom').val(0);

            hitungGrandTotal();
        }

        $('#optionModalRoomTipe').modal('hide');
    });

    $('#inputKodeRoom').on('click', function (e) {
        e.preventDefault();

        var kode_tipe = $('#inputKodeTipe').val();
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

    $('#btnOptionRoom').on('click', function (e) {
        e.preventDefault();

        var kode_tipe = $('#inputKodeTipe').val();
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

    $('body').on('click', '#selectOptionRoom', function (e) {
        e.preventDefault();

        $('#inputKodeRoom').val($(this).data('kode_room'));
        $('#inputTipeRoom').val($(this).data('tipe'));
        $('#inputFasilitasRoom').val($(this).data('fasilitas'));
        $('#inputHargaLongtime').val($(this).data('longtime'));
        $('#inputHargaShorttime').val($(this).data('shorttime'));

        $('#optionModalRoomReservasi').modal('hide');

        hitungGrandTotal();
    });

    $('#inputDiskonRoomProsentase').change(function() {
        if($('#inputDiskonRoomProsentase').val() == '') {
            $('#inputDiskonRoomProsentase').val(0);
        }
        $('#inputDiskonRoomProsentase').val(Number($('#inputDiskonRoomProsentase').val()).toFixed(2));
        hitungGrandTotal();
    });

    $('#inputPpnRoomProsentase').change(function() {
        if($('#inputPpnRoomProsentase').val() == '') {
            $('#inputPpnRoomProsentase').val(0);
        }
        $('#inputPpnRoomProsentase').val(Number($('#inputPpnRoomProsentase').val()).toFixed(2));
        hitungGrandTotal();
    });

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

    $('#inputDeposit').change(function() {
        if($('#inputDeposit').val() == '') {
            $('#inputDeposit').val(0);
        }
    });

    $('#btnBatal').on('click', function() {
        loading.block();
        location.reload();
    });

    $('#btnCheckInConfirmation').on('click', function (e) {
        e.preventDefault();

        var no_identitas = $('#inputNoIdentitas').val();
        var grand_total = $('#inputGrandTotal').val();
        var total_pembayaran = $('#inputTotalPembayaran').val();

        var messageCheckIn = '';
        var statusCheckIn = false;

        if(no_identitas == '') {
            statusCheckIn = false;
            messageCheckIn = 'Data customer masih belum dipilih';
        } else if(grand_total == 0 || grand_total == '') {
            statusCheckIn = false;
            messageCheckIn = 'Data grand total masih kosong, pilih data ruangan terlebih dahulu';
        } else if(total_pembayaran == 0 ||  total_pembayaran == '') {
            statusCheckIn = false;
            messageCheckIn = 'Data total pembayaran masih kosong';
        } else if(grand_total > total_pembayaran) {
            statusCheckIn = false;
            messageCheckIn = 'Data total pembayaran tidak boleh kurang dari grand total';
        } else {
            var default_deposit = data.default_deposit.replaceAll(',', '');
            var input_deposit = $('#inputDeposit').val().replaceAll(',', '');

            if(parseFloat(default_deposit) != parseFloat(input_deposit)) {
                statusCheckIn = false;
                messageCheckIn = 'Jumlah deposit tidak boleh lebih atau kurang dari Rp. '+data.default_deposit;
            } else {
                statusCheckIn = true;
                messageCheckIn = '';
            }
        }

        if(statusCheckIn == false) {
            Swal.fire({
                text: messageCheckIn,
                icon: 'warning',
                buttonsStyling: false,
                confirmButtonText: 'Ok, got it!',
                customClass: {
                    confirmButton: 'btn btn-warning'
                }
            });
        } else {
            $('#modalEntryCheckIn').trigger('reset');
            $('#modalEntryCheckIn').modal('show');
        }
    });

    $('#btnCheckInProses').on('click', function (e) {
        e.preventDefault();

        var kode_reservasi = $('#inputKodeReservasi').val();
        var password = $('#inputPassword').val();
        var password_confirm = $('#inputPasswordConfirm').val();
        var _token = $('input[name="_token"]').val();

        if(password == '' || password_confirm == '') {
            Swal.fire({
                text: 'Isi data password anda terlebih dahulu',
                icon: 'warning',
                buttonsStyling: false,
                confirmButtonText: 'Ok, got it!',
                customClass: {
                    confirmButton: 'btn btn-warning'
                }
            });
        } else {
            $.ajax({
                url: url.check_in_reservasi,
                method: 'post',
                data: { kode_reservasi: kode_reservasi, password: password,
                        password_confirm: password_confirm, _token: _token },

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
                        $("#btnSimpan").val('check_in');
                        loading.block();
                        $("#btnSimpan").click();
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
        }
    });

    $('#btnSimpan').on('click', function (e) {
        e.preventDefault();
        var button = $(e.target);
        var formData = button.parents('form').serialize() + '&' + encodeURI(button.attr('name')) + '=' + encodeURI(button.attr('value'));

        $.ajax({
            url: url.simpan_reservasi,
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
