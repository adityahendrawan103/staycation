function loadDataFasilitas(page = 1, per_page = 10, search = '') {
    loading.block();
    $.ajax({
        url: url.option_fasilitas_checklist + "?search=" + search + "&per_page=" + per_page + "&page=" + page,
        method: 'get',
        success: function (response) {
            loading.release();
            if (response.status == false) {
                Swal.fire({
                    text: response.message,
                    icon: 'error',
                    buttonsStyling: false,
                    confirmButtonText: 'Ok, got it!',
                    customClass: {
                        confirmButton: 'btn btn-danger'
                    }
                });
            } else {
                $('#checkListFasilitasContentModal').html(response.data);
            }
        },
        error: function () {
            loading.release();
            $('#messageNIK').html('Server not responding');
            $('#inputNIK').addClass('is-invalid');
        }
    });
}

$(document).ready(function () {
    $('body').on('click', '#paginationOptionFasilitas', function (e) {
        console.log($(this));
        e.preventDefault();

        var pages = $(this)[0].getAttribute("data-page");
        var page = pages.split('?page=')[1];

        var search = $('#inputSearchOptionFasilitas').val();
        var per_page = $('#selectPerPageOptionFasilitas').val();

        loadDataFasilitas(page, per_page, search);
    });

    $('body').on('change', '#selectPerPageOptionFasilitas', function (e) {
        e.preventDefault();

        var start_record = $('#startRecordOptionFasilitas').html();
        var search = $('#inputSearchOptionFasilitas').val();
        var per_page = $('#selectPerPageOptionFasilitas').val();

        var page = Math.ceil(start_record / per_page);

        loadDataFasilitas(page, per_page, search);
    });

    $('#inputSearchOptionFasilitas').change(function() {
        e.preventDefault();
        var search = $('#inputSearchOptionFasilitas').val();
        var per_page = $('#selectPerPageOptionFasilitas').val();

        loadDataFasilitas(1, per_page, search);
    });

    $('#btnSearchOptionFasilitas').on('click', function (e) {
        e.preventDefault();
        var search = $('#inputSearchOptionFasilitas').val();
        var per_page = $('#selectPerPageOptionFasilitas').val();

        loadDataFasilitas(1, per_page, search);
    });
});
