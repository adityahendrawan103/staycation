function loadDataRoomTipe(page = 1, per_page = 10, search = '') {
    loading.block();
    $.ajax({
        url: url.option_roomtipe + "?search=" + search + "&per_page=" + per_page + "&page=" + page,
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
                $('#optionRoomTipeContentModal').html(response.data);
            }
        },
        error:function() {
            loading.release();
        }
    });
}

$(document).ready(function () {
    $('body').on('click', '#paginationOptionRoomTipe', function (e) {
        console.log($(this));
        e.preventDefault();

        var pages = $(this)[0].getAttribute("data-page");
        var page = pages.split('?page=')[1];

        var search = $('#inputSearchOptionRoomTipe').val();
        var per_page = $('#selectPerPageOptionRoomTipe').val();

        loadDataRoomTipe(page, per_page, search);
    });

    $('body').on('change', '#selectPerPageOptionRoomTipe', function (e) {
        e.preventDefault();

        var start_record = $('#startRecordOptionRoomTipe').html();
        var search = $('#inputSearchOptionRoomTipe').val();
        var per_page = $('#selectPerPageOptionRoomTipe').val();

        var page = Math.ceil(start_record / per_page);

        loadDataRoomTipe(page, per_page, search);
    });

    $('#inputSearchOptionRoomTipe').change(function(e) {
        e.preventDefault();
        var search = $('#inputSearchOptionRoomTipe').val();
        var per_page = $('#selectPerPageOptionRoomTipe').val();

        loadDataRoomTipe(1, per_page, search);
    });

    $('#btnSearchOptionRoomTipe').on('click', function (e) {
        e.preventDefault();
        var search = $('#inputSearchOptionRoomTipe').val();
        var per_page = $('#selectPerPageOptionRoomTipe').val();

        loadDataRoomTipe(1, per_page, search);
    });
});
