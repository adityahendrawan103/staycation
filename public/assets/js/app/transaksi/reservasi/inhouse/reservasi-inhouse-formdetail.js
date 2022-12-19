function hitungGrandTotal() {
    // ========================================================================
    // Data Layanan
    // ========================================================================
    var input_layanan_sub_total = $('#inputTotalLayanan').val().replaceAll(',', '');
    var input_layanan_disc_prosen = $('#inputDiskonLayananProsentase').val().replaceAll(',', '');
    var input_layanan_ppn_prosen = $('#inputPPNLayananProsentase').val().replaceAll(',', '');

    var harga_layanan = parseFloat(input_layanan_sub_total);
    var harga_layanan_disc = Math.round((parseFloat(harga_layanan) * parseFloat(input_layanan_disc_prosen)) / 100);
    var harga_layanan_min_disc = parseFloat(harga_layanan) - parseFloat(harga_layanan_disc);
    var harga_layanan_ppn = Math.round((parseFloat(harga_layanan_min_disc) * parseFloat(input_layanan_ppn_prosen)) / 100);
    var harga_layanan_total = parseFloat(harga_layanan_min_disc) + parseFloat(harga_layanan_ppn);

    $('#inputDiskonLayananNominal').val(formatDecimal(harga_layanan_disc));
    $('#inputPPNLayananNominal').val(formatDecimal(harga_layanan_ppn));
    $('#inputGrandTotalLayanan').val(formatDecimal(harga_layanan_total));

    // ========================================================================
    // Data Total
    // ========================================================================
    var input_total_room =  $('#inputGrandTotalRoom').val().replaceAll(',', '');
    var input_total_layanan = $('#inputGrandTotalLayanan').val().replaceAll(',', '');
    var input_biaya_lain = $('#inputBiayaLain').val().replaceAll(',', '');
    var input_pembayaran = $('#inputTotalPembayaran').val().replaceAll(',', '');

    var harga_room_layanan = parseFloat(input_total_room) + parseFloat(input_total_layanan);
    var harga_total = parseFloat(harga_room_layanan) + parseFloat(input_biaya_lain);
    var sisa_pembayaran = parseFloat(harga_total) - parseFloat(input_pembayaran);

    $('#inputTotalRoomLayanan').val(formatDecimal(harga_room_layanan));
    $('#inputGrandTotal').val(formatDecimal(harga_total));
    $('#inputSisaPembayaran').val(formatDecimal(sisa_pembayaran));

}

$(document).ready(function () {
    loadDaftarDetailReservasi(data.default_layanan_disc_prosentase, data.default_layanan_ppn_prosentase);

    function loadDaftarDetailReservasi($disc_layanan = 0, $ppn_layanan = 0) {
        loading.block();
        $.ajax({
            url: url.daftar_detail_reservasi,
            method: 'get',
            data: { kode_reservasi: $('#inputKodeReservasi').val(),
                    diskon_layanan_prosentase: $disc_layanan,
                    ppn_layanan_prosentase: $ppn_layanan },

            success: function (response) {
                loading.release();
                $('#contentTableDetailReservasi').html(response.data);

                $('#inputDiskonLayananProsentase').change(function() {
                    if($('#inputDiskonLayananProsentase').val() == '') {
                        $('#inputDiskonLayananProsentase').val(0);
                    }
                    $('#inputDiskonLayananProsentase').val(Number($('#inputDiskonLayananProsentase').val()).toFixed(2));

                    hitungGrandTotal();
                });

                hitungGrandTotal();
            },
            error: function () {
                loading.release();
            }
        })
    }

    $('#modalEntryLayanan').on('shown.bs.modal', function() {
        $('#formEntryLayanan').focus();
        $('#modalEntryLayanan').find('input').on('keydown', function (e) {
            if (e.which == 13) {
                e.preventDefault();
                var index = $('#modalEntryLayanan').find('input').index(this) + 1;
                $('#modalEntryLayanan').find('input').eq(index).focus();
            }
        });
    });

    $('#btnTambahLayanan').on('click', function (e) {
        e.preventDefault();
        $('#formEntryLayanan').trigger('reset');
        $('#modalTitleLayanan').html('Entry Data Layanan');
        $('#inputHargaLayanan').val(0);
        $('#inputJumlahLayanan').val(1);
        $('#inputDiskonLayanan').val(Number(0).toFixed(2));
        $('#modalEntryLayanan').modal('show');
    });

    $('body').on('click', '#btnEditDetail', function (e) {
        e.preventDefault();

        loading.block();
        $.ajax({
            url: url.form_detail_reservasi,
            method: 'get',
            data: { kode_reservasi: $('#inputKodeReservasi').val(), kode_layanan: $(this).data('kode') },

            success: function (response) {
                loading.release();
                if (response.status == true) {
                    $('#modalTitleLayanan').html('Edit Data Layanan');

                    $('#inputKodeLayanan').val(response.data.kode_layanan);
                    $('#inputNamaLayanan').val(response.data.nama_layanan);
                    $('#inputSatuanLayanan').val(response.data.satuan);
                    $('#inputDiskonLayanan').val(response.data.diskon);
                    $('#inputHargaLayanan').val(response.data.harga.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ','));
                    $('#inputJumlahLayanan').val(response.data.qty.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ','));

                    $('#modalEntryLayanan').modal('show');

                    $('#modalEntryLayanan').on('shown.bs.modal', function () {
                        $('#inputJumlahLayanan').focus();
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

    $('#inputKodeLayanan').on('click', function (e) {
        e.preventDefault();
        loadDataLayanan(1, 10, '');
        $('#formOptionLayanan').trigger('reset');
        $('#optionModalLayanan').modal('show');
    });

    $('#btnOptionLayanan').on('click', function (e) {
        e.preventDefault();
        loadDataLayanan(1, 10, '');
        $('#formOptionLayanan').trigger('reset');
        $('#optionModalLayanan').modal('show');
    });

    $('#inputDiskonLayanan').change(function() {
        if($('#inputDiskonLayanan').val() == '') {
            $('#inputDiskonLayanan').val(0);
        }
        $('#inputDiskonLayanan').val(Number($('#inputDiskonLayanan').val()).toFixed(2));

        hitungGrandTotal();
    });


    $('body').on('click', '#selectOptionLayanan', function (e) {
        e.preventDefault();

        $('#inputKodeLayanan').val($(this).data('kode_layanan'));
        $('#inputNamaLayanan').val($(this).data('nama_layanan'));
        $('#inputSatuanLayanan').val($(this).data('satuan'));
        $('#inputHargaLayanan').val($(this).data('harga'));
        $('#inputJumlahLayanan').val(1);
        $('#inputDiskonLayanan').val(Number(0).toFixed(2));

        $('#inputJumlahLayanan').focus();
        $('#optionModalLayanan').modal('hide');
    });

    $('#btnSimpanLayanan').on('click', function (e) {
        e.preventDefault();
        var kode_reservasi = $('#inputKodeReservasi').val();
        var kode_layanan = $('#inputKodeLayanan').val();
        var diskon = $('#inputDiskonLayanan').val();
        var jumlah = $('#inputJumlahLayanan').val();
        var _token = $('input[name="_token"]').val();

        if(kode_layanan == '' || jumlah == '' || diskon == '') {
            Swal.fire({
                text: 'Pilih data layanan dan jumlah layanan terlebih dahulu',
                icon: 'warning',
                buttonsStyling: false,
                confirmButtonText: 'Ok, got it!',
                customClass: {
                    confirmButton: 'btn btn-warning'
                }
            });
        } else {
            if(jumlah <= 0) {
                Swal.fire({
                    text: 'Jumlah layanan harus lebih besar dari nol (0)',
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
                    url: url.simpan_layanan,
                    method: 'post',
                    data: { kode_reservasi: kode_reservasi, kode_layanan: kode_layanan,
                            jumlah: jumlah, diskon: diskon, _token:_token },

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
                                    var prosentase_disc = $('#inputDiskonLayananProsentase').val();
                                    var prosentase_ppn = $('#inputPPNLayananProsentase').val();
                                    loadDaftarDetailReservasi(prosentase_disc, prosentase_ppn);
                                    $('#modalEntryLayanan').modal('hide');
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

    $('body').on('click', '#btnHapusDetail', function (e) {
        e.preventDefault();
        var kode_reservasi = $('#inputKodeReservasi').val();
        var kode_layanan = $(this).data('kode');
        var _token = $('input[name="_token"]').val();

        Swal.fire({
            html: `Anda memilih data layanan : <span class='badge badge-primary'>`+kode_layanan+`</span>
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
                    url: url.hapus_layanan,
                    method: 'post',
                    data: { kode_reservasi: kode_reservasi, kode_layanan: kode_layanan, _token:_token },

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
                                    var prosentase_disc = $('#inputDiskonLayananProsentase').val();
                                    var prosentase_ppn = $('#inputPPNLayananProsentase').val();
                                    loadDaftarDetailReservasi(prosentase_disc, prosentase_ppn);
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
