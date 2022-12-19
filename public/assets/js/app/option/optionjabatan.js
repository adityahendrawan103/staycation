function loadDataJabatan(page = 1, per_page = 10, search = '') {
    loading.block();
    $.ajax({
        url: url.option_jabatan + "?search=" + search + "&per_page=" + per_page + "&page=" + page,
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
                $('#optionJabatanContentModal').html(response.data);
            }
        },
        error: function () {
            loading.release();
        }
    });
}

$(document).ready(function () {
    $('body').on('click', '#paginationOptionJabatan', function (e) {
        console.log($(this));
        e.preventDefault();

        var pages = $(this)[0].getAttribute("data-page");
        var page = pages.split('?page=')[1];

        var search = $('#inputSearchOptionJabatan').val();
        var per_page = $('#selectPerPageOptionJabatan').val();

        loadDataJabatan(page, per_page, search);
    });

    $('body').on('change', '#selectPerPageOptionJabatan', function (e) {
        e.preventDefault();

        var start_record = $('#startRecordOptionJabatan').html();
        var search = $('#inputSearchOptionJabatan').val();
        var per_page = $('#selectPerPageOptionJabatan').val();

        var page = Math.ceil(start_record / per_page);

        loadDataJabatan(page, per_page, search);
    });

    $('#inputSearchOptionJabatan').change(function(e) {
        e.preventDefault();
        var search = $('#inputSearchOptionJabatan').val();
        var per_page = $('#selectPerPageOptionJabatan').val();

        loadDataJabatan(1, per_page, search);
    });

    $('#btnSearchOptionJabatan').on('click', function (e) {
        e.preventDefault();
        var search = $('#inputSearchOptionJabatan').val();
        var per_page = $('#selectPerPageOptionJabatan').val();

        loadDataJabatan(1, per_page, search);
    });
});
