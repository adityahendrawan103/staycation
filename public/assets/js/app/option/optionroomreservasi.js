function loadDataRoomReservasi(kode_tipe = '', check_in = null, check_out = null, page = 1, per_page = 10, search = '') {
    loading.block();
    $('#inputRoomReservasiKodeTipe').html(kode_tipe);
    $('#inputRoomReservasiCheckIn').html(check_in);
    $('#inputRoomReservasiCheckOut').html(check_out);
    $.ajax({
        url: url.option_room_reservasi + "?kode_tipe="+kode_tipe+"&check_in="+check_in+"&check_out="+check_out+
                    "&search="+search+"&per_page="+per_page+"&page="+page,
        method: "get",
        success: function (response) {
            loading.release();
            if (response.status == false) {
                Swal.fire({
                    text: response.message,
                    icon: "error",
                    buttonsStyling: false,
                    confirmButtonText: "Ok, got it!",
                    customClass: {
                        confirmButton: "btn btn-danger"
                    }
                });
            } else {
                $('#optionRoomReservasiContentModal').html(response.data);
            }
        },
        error:function() {
            loading.release();
        }
    });
}

$(document).ready(function () {
    $('body').on('click', '#paginationOptionRoomReservasi', function (e) {
        e.preventDefault();

        var pages = $(this)[0].getAttribute("data-page");
        var page = pages.split('?page=')[1];
        var search = $('#inputSearchOptionRoomReservasi').val();
        var per_page = $('#selectPerPageOptionRoomReservasi').val();
        var kode_tipe = $('#inputRoomReservasiKodeTipe').html();
        var checkin = $('#inputRoomReservasiCheckIn').html();
        var checkout = $('#inputRoomReservasiCheckOut').html();

        loadDataRoomReservasi(kode_tipe, checkin, checkout, page, per_page, search);
    });

    $('body').on('change', '#selectPerPageOptionRoomReservasi', function (e) {
        e.preventDefault();

        var start_record = $('#startRecordOptionRoomReservasi').html();
        var search = $('#inputSearchOptionRoomReservasi').val();
        var per_page = $('#selectPerPageOptionRoomReservasi').val();

        var page = Math.ceil(start_record / per_page);
        var kode_tipe = $('#inputRoomReservasiKodeTipe').html();
        var checkin = $('#inputRoomReservasiCheckIn').html();
        var checkout = $('#inputRoomReservasiCheckOut').html();

        loadDataRoomReservasi(kode_tipe, checkin, checkout, page, per_page, search);
    });

    $('#inputSearchOptionRoomReservasi').change(function(e) {
        e.preventDefault();
        var search = $('#inputSearchOptionRoomReservasi').val();
        var per_page = $('#selectPerPageOptionRoomReservasi').val();
        var kode_tipe = $('#inputRoomReservasiKodeTipe').html();
        var checkin = $('#inputRoomReservasiCheckIn').html();
        var checkout = $('#inputRoomReservasiCheckOut').html();

        loadDataRoomReservasi(kode_tipe, checkin, checkout, 1, per_page, search);
    });

    $('#btnSearchOptionRoomReservasi').on('click', function (e) {
        e.preventDefault();
        var search = $('#inputSearchOptionRoomReservasi').val();
        var per_page = $('#selectPerPageOptionRoomReservasi').val();
        var kode_tipe = $('#inputRoomReservasiKodeTipe').html();
        var checkin = $('#inputRoomReservasiCheckIn').html();
        var checkout = $('#inputRoomReservasiCheckOut').html();

        loadDataRoomReservasi(kode_tipe, checkin, checkout, 1, per_page, search);
    });
});
