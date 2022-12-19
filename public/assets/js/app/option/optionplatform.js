function loadDataPlatform(page = 1, per_page = 10, search = '') {
    loading.block();
    $.ajax({
        url: url.option_platform + "?search=" + search + "&per_page=" + per_page + "&page=" + page,
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
                $('#optionPlatformContentModal').html(response.data);
            }
        },
        error: function() {
            loading.release();
        }
    });
}

$(document).ready(function () {
    $('body').on('click', '#paginationOptionPlatform', function (e) {
        console.log($(this));
        e.preventDefault();

        var pages = $(this)[0].getAttribute("data-page");
        var page = pages.split('?page=')[1];

        var search = $('#inputSearchOptionPlatform').val();
        var per_page = $('#selectPerPageOptionPlatform').val();

        loadDataPlatform(page, per_page, search);
    });

    $('body').on('change', '#selectPerPageOptionPlatform', function (e) {
        e.preventDefault();

        var start_record = $('#startRecordOptionPlatform').html();
        var search = $('#inputSearchOptionPlatform').val();
        var per_page = $('#selectPerPageOptionPlatform').val();

        var page = Math.ceil(start_record / per_page);

        loadDataPlatform(page, per_page, search);
    });

    $('#inputSearchOptionPlatform').change(function(e) {
        e.preventDefault();
        var search = $('#inputSearchOptionPlatform').val();
        var per_page = $('#selectPerPageOptionPlatform').val();

        loadDataPlatform(1, per_page, search);
    });

    $('#btnSearchOptionPlatform').on('click', function (e) {
        e.preventDefault();
        var search = $('#inputSearchOptionPlatform').val();
        var per_page = $('#selectPerPageOptionPlatform').val();

        loadDataPlatform(1, per_page, search);
    });
});
