function hitungGrandTotal() {
    var xTotalDenda = $('#inputTotalDenda').val().replaceAll(',', '');
    var xTotalPembayaran = $('#inputTotalPembayaran').val().replaceAll(',', '');
    var sisa_pembayaran = parseFloat(xTotalDenda) - parseFloat(xTotalPembayaran);

    $('#inputSisaPembayaran').val(formatDecimal(sisa_pembayaran));
}

$(document).ready(function () {
    loadDaftarDetailReservasi(data.total_pembayaran);

    function loadDaftarDetailReservasi($total_pembayaran = 0) {
        loading.block();
        $.ajax({
            url: url.daftar_detail_reservasi_penalty,
            method: 'get',
            data: { kode_reservasi: $('#inputKodeReservasi').val() },

            success: function (response) {
                loading.release();
                $('#contentTableDetailReservasiPenalty').html(response.data);

                $('#inputTotalPembayaran').val($total_pembayaran);

                $('#inputTotalPembayaran').autoNumeric('init', {
                    aSep: ',',
                    aDec: '.',
                    aForm: true,
                    vMax: '9999999999999',
                    vMin: '0'
                });

                hitungGrandTotal();

                $('#inputTotalPembayaran').change(function() {
                    if($('#inputTotalPembayaran').val() == '') {
                        $('#inputTotalPembayaran').val(0);
                    }
                    hitungGrandTotal();
                });
            },
            error: function () {
                loading.release();
            }
        })
    }

    $('#inputQuantity').autoNumeric('init', {
        aSep: ',',
        aDec: '.',
        aForm: true,
        vMax: '9999999999999',
        vMin: '0'
    });

    $('#inputHargaDenda').autoNumeric('init', {
        aSep: ',',
        aDec: '.',
        aForm: true,
        vMax: '9999999999999',
        vMin: '0'
    });

    $('#inputKodeItem').on('click', function (e) {
        e.preventDefault();
        loadDataItem(1, 10, '');
        $('#formOptionItem').trigger('reset');
        $('#optionModalItem').modal('show');
    });

    $('#btnOptionItem').on('click', function (e) {
        e.preventDefault();
        loadDataItem(1, 10, '');
        $('#formOptionItem').trigger('reset');
        $('#optionModalItem').modal('show');
    });

    $('body').on('click', '#selectOptionItem', function (e) {
        e.preventDefault();

        $('#inputKodeItem').val($(this).data('kode_item'));
        $('#inputNamaItem').val($(this).data('nama_item'));
        $('#inputHargaDenda').val($(this).data('harga_denda'));
        $('#inputQuantity').val(1);

        $('#inputQuantity').focus();
        $('#optionModalItem').modal('hide');
    });

    $('#btnSimpanItem').on('click', function (e) {
        e.preventDefault();
        var kode_reservasi = $('#inputKodeReservasi').val();
        var kode_item = $('#inputKodeItem').val();
        var keterangan = $('#inputKeterangan').val();
        var qty = $('#inputQuantity').val().replaceAll(',', '');
        var denda = $('#inputHargaDenda').val().replaceAll(',', '');
        var _token = $('input[name="_token"]').val();

        if(kode_item == '' || qty == '' || keterangan == '' || denda == '') {
            Swal.fire({
                text: 'Isi data item penalty secara lengkap',
                icon: 'warning',
                buttonsStyling: false,
                confirmButtonText: 'Ok, got it!',
                customClass: {
                    confirmButton: 'btn btn-warning'
                }
            });
        } else {
            if(qty <= 0) {
                Swal.fire({
                    text: 'Jumlah quantity item harus lebih besar dari nol (0)',
                    icon: 'warning',
                    buttonsStyling: false,
                    confirmButtonText: 'Ok, got it!',
                    customClass: {
                        confirmButton: 'btn btn-warning'
                    }
                });
            } else {
                loading.block();
                $.ajax({
                    url: url.simpan_penalty_item,
                    method: 'post',
                    data: { kode_reservasi: kode_reservasi, kode_item: kode_item, keterangan: keterangan,
                            qty: qty, denda: denda, _token:_token },

                    success:function(response) {
                        loading.release();

                        if (response.status == true) {
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
                                    var total_pembayaran = $('#inputTotalPembayaran').val();
                                    loadDaftarDetailReservasi(total_pembayaran);
                                    $('#modalEntryItem').modal('hide');
                                }
                            });
                        } else {
                            Swal.fire({
                                text: response.message,
                                icon: 'error',
                                buttonsStyling: false,
                                confirmButtonText: 'Ok, got it!',
                                customClass: {
                                    confirmButton: 'btn btn-danger'
                                }
                            });
                        }
                    },
                    error:function() {
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

    $('body').on('click', '#btnEditDetail', function (e) {
        e.preventDefault();

        loading.block();
        $.ajax({
            url: url.form_penalty_item,
            method: 'get',
            data: { kode_reservasi: $('#inputKodeReservasi').val(), kode_item: $(this).data('kode') },

            success: function (response) {
                loading.release();
                if (response.status == true) {
                    $('#modalTitleItem').html('Edit Data Item');

                    $('#inputKodeItem').val(response.data.kode_item);
                    $('#inputNamaItem').val(response.data.nama_item);
                    $('#inputKeterangan').val(response.data.keterangan);
                    $('#inputQuantity').val(response.data.qty.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ','));
                    $('#inputHargaDenda').val(response.data.denda.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ','));

                    $('#modalEntryItem').modal('show');

                    $('#modalEntryItem').on('shown.bs.modal', function () {
                        $('#inputJumlahItem').focus();
                    });
                } else {
                    Swal.fire({
                        text: response.message,
                        icon: 'error',
                        buttonsStyling: false,
                        confirmButtonText: 'Ok, got it!',
                        customClass: {
                            confirmButton: 'btn btn-danger'
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

    $('body').on('click', '#btnHapusDetail', function (e) {
        e.preventDefault();
        var kode_reservasi = $('#inputKodeReservasi').val();
        var kode_item = $(this).data('kode');
        var _token = $('input[name="_token"]').val();

        Swal.fire({
            html: `Anda memilih data layanan : <span class='badge badge-primary'>`+kode_item+`</span>
                    <br>Apakah anda yakin akan menghapus data ini ?`,
            icon: 'info',
            buttonsStyling: false,
            showCancelButton: true,
            confirmButtonText: 'Yes',
            cancelButtonText: 'No',
            customClass: {
                confirmButton: 'btn btn-primary',
                cancelButton: 'btn btn-danger'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                loading.block();
                $.ajax({
                    url: url.hapus_penalty_item,
                    method: 'post',
                    data: { kode_reservasi: kode_reservasi, kode_item: kode_item, _token:_token },

                    success:function(response) {
                        loading.release();

                        if (response.status == true) {
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
                                    var total_pembayaran = $('#inputTotalPembayaran').val();
                                    loadDaftarDetailReservasi(total_pembayaran);
                                }
                            });
                        } else {
                            Swal.fire({
                                text: response.message,
                                icon: 'error',
                                buttonsStyling: false,
                                confirmButtonText: 'Ok, got it!',
                                customClass: {
                                    confirmButton: 'btn btn-danger'
                                }
                            });
                        }
                    },
                    error:function() {
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
    });
});
