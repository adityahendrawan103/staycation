function loadDataCustomer(page = 1, per_page = 10, search = '') {
    loading.block();
    $.ajax({
        url: url.option_customer + "?search=" + search + "&per_page=" + per_page + "&page=" + page,
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
                $('#optionCustomerContentModal').html(response.data);
            }
        },
        error: function () {
            loading.release();
        }
    });
}

$(document).ready(function () {
    $('body').on('click', '#paginationOptionCustomer', function (e) {
        console.log($(this));
        e.preventDefault();

        var pages = $(this)[0].getAttribute("data-page");
        var page = pages.split('?page=')[1];

        var search = $('#inputSearchOptionCustomer').val();
        var per_page = $('#selectPerPageOptionCustomer').val();

        loadDataCustomer(page, per_page, search);
    });

    $('body').on('change', '#selectPerPageOptionCustomer', function (e) {
        e.preventDefault();

        var start_record = $('#startRecordOptionCustomer').html();
        var search = $('#inputSearchOptionCustomer').val();
        var per_page = $('#selectPerPageOptionCustomer').val();

        var page = Math.ceil(start_record / per_page);

        loadDataCustomer(page, per_page, search);
    });

    $('#inputSearchOptionCustomer').change(function(e) {
        e.preventDefault();
        var search = $('#inputSearchOptionCustomer').val();
        var per_page = $('#selectPerPageOptionCustomer').val();

        loadDataCustomer(1, per_page, search);
    });

    $('#btnSearchOptionCustomer').on('click', function (e) {
        e.preventDefault();
        var search = $('#inputSearchOptionCustomer').val();
        var per_page = $('#selectPerPageOptionCustomer').val();

        loadDataCustomer(1, per_page, search);
    });
});
