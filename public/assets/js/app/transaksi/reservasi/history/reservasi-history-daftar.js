$(document).ready(function () {
    // ===============================================================
    // Daftar Reservasi
    // ===============================================================
    function loadTransaksiReservasi(page = 1, per_page = 10, filter_date = '', start_date = '', end_date = '', filter = '', search = '', sort_by = '', asc_desc = '') {
        loading.block();
        window.location.href = window.location.origin + window.location.pathname + '?date='+filter_date+'&start_date='+start_date+'&end_date='+end_date+
        '&filter='+filter+'&search='+search+'&sort_by='+sort_by+'&asc_desc='+asc_desc+'&per_page='+per_page+'&page='+page;
    }

    $('#inputSearch').on('change keydown', function (e) {
        if (e.keyCode == 13 || e.type == 'change') {
            var per_page = $('#selectPerPage').val();
            var year = $('#inputTahun').val();
            var month = $('#selectBulan').val();
            var filter = $('#selectFilter').val();
            var search = $('#inputSearch').val();

            loading.block();
            loadTransaksiReservasi(1, per_page, year, month, filter, search);
        }
    });

    $('#selectPerPage').change(function() {
        var start_record = data.start_record;
        var per_page = $('#selectPerPage').val();
        var page = Math.ceil(start_record / per_page);
        var year = $('#inputTahun').val();
        var month = $('#selectBulan').val();
        var filter = $('#selectFilter').val();
        var search = $('#inputSearch').val();

        loading.block();
        loadTransaksiReservasi(page, per_page, year, month, filter, search);
    });

    $(document).on('click', '.page-item a', function (e) {
        var page_link = $(this)[0].getAttribute('data-page');
        var page = page_link.split('?page=')[1];

        var per_page = $('#selectPerPage').val();
        var year = $('#inputTahun').val();
        var month = $('#selectBulan').val();
        var filter = $('#selectFilter').val();
        var search = $('#inputSearch').val();

        loading.block();
        loadTransaksiReservasi(page, per_page, year, month, filter, search);
    });
});
