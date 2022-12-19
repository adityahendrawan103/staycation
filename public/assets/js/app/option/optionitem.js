function loadDataItem(page = 1, per_page = 10, search = '') {
    loading.block();
    $.ajax({
        url: url.option_item + "?search=" + search + "&per_page=" + per_page + "&page=" + page,
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
                $('#optionItemContentModal').html(response.data);
            }
        },
        error: function() {
            loading.release();
        }
    });
}

$(document).ready(function () {
    $('body').on('click', '#paginationOptionItem', function (e) {
        console.log($(this));
        e.preventDefault();

        var pages = $(this)[0].getAttribute("data-page");
        var page = pages.split('?page=')[1];

        var search = $('#inputSearchOptionItem').val();
        var per_page = $('#selectPerPageOptionItem').val();

        loadDataItem(page, per_page, search);
    });

    $('body').on('change', '#selectPerPageOptionItem', function (e) {
        e.preventDefault();

        var start_record = $('#startRecordOptionItem').html();
        var search = $('#inputSearchOptionItem').val();
        var per_page = $('#selectPerPageOptionItem').val();

        var page = Math.ceil(start_record / per_page);

        loadDataItem(page, per_page, search);
    });

    $('#inputSearchOptionItem').change(function(e) {
        e.preventDefault();
        var search = $('#inputSearchOptionItem').val();
        var per_page = $('#selectPerPageOptionItem').val();

        loadDataItem(1, per_page, search);
    });

    $('#btnSearchOptionItem').on('click', function (e) {
        e.preventDefault();
        var search = $('#inputSearchOptionItem').val();
        var per_page = $('#selectPerPageOptionItem').val();

        loadDataItem(1, per_page, search);
    });
});
