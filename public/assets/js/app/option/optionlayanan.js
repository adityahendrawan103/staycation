function loadDataLayanan(page = 1, per_page = 10, search = '') {
    loading.block();
    $.ajax({
        url: url.option_layanan + "?search=" + search + "&per_page=" + per_page + "&page=" + page,
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
                $('#optionLayananContentModal').html(response.data);
            }
        },
        error: function() {
            loading.release();
        }
    });
}

$(document).ready(function () {
    $('body').on('click', '#paginationOptionLayanan', function (e) {
        console.log($(this));
        e.preventDefault();

        var pages = $(this)[0].getAttribute("data-page");
        var page = pages.split('?page=')[1];

        var search = $('#inputSearchOptionLayanan').val();
        var per_page = $('#selectPerPageOptionLayanan').val();

        loadDataLayanan(page, per_page, search);
    });

    $('body').on('change', '#selectPerPageOptionLayanan', function (e) {
        e.preventDefault();

        var start_record = $('#startRecordOptionLayanan').html();
        var search = $('#inputSearchOptionLayanan').val();
        var per_page = $('#selectPerPageOptionLayanan').val();

        var page = Math.ceil(start_record / per_page);

        loadDataLayanan(page, per_page, search);
    });

    $('#inputSearchOptionLayanan').change(function(e) {
        e.preventDefault();
        var search = $('#inputSearchOptionLayanan').val();
        var per_page = $('#selectPerPageOptionLayanan').val();

        loadDataLayanan(1, per_page, search);
    });

    $('#btnSearchOptionLayanan').on('click', function (e) {
        e.preventDefault();
        var search = $('#inputSearchOptionLayanan').val();
        var per_page = $('#selectPerPageOptionLayanan').val();

        loadDataLayanan(1, per_page, search);
    });
});
