$(document).ready(function () {
    loadDaftarDetailRoom();

    function loadDaftarDetailRoom() {
        loading.block();
        $.ajax({
            url: url.daftar_detail_room,
            method: 'get',

            success: function (response) {
                loading.release();
                $('#contentTableDetailRoom').html(response.data);
            },
            error: function () {
                loading.release();
            }
        })
    }

    $('#btnSimpanFasilitas').on('click', function (e) {
        e.preventDefault();

        var data_fasilitas = [];
        var jml_checked = 0;

        $('table tr').each(function() {
            var $checkbox = $(this).find('input[type="checkbox"]#inputCheckRow');

            if($checkbox.length) {
                var kode = $(this).closest('tr').find('td:nth-child(2)').text();
                var status = $checkbox.prop('checked');

                if(status == true) {
                    jml_checked = jml_checked + 1;
                    data_fasilitas.push(kode);
                }
            }
        });

        if(jml_checked <= 0) {
            Swal.fire({
                text: 'Belum ada data yang dipilih, pilih salah satu data terlebih dahulu',
                icon: 'error',
                buttonsStyling: false,
                confirmButtonText: 'Ok, got it!',
                customClass: {
                    confirmButton: 'btn btn-danger'
                }
            });
        } else {
            var _token = $('input[name="_token"]').val();
            loading.block();
            $.ajax({
                url: url.simpan_fasilitas,
                method: 'post',
                data: { data_fasilitas: JSON.stringify(data_fasilitas), _token: _token },

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
                        loadDaftarDetailRoom();
                        $('#modalCheckListFasilitas').modal('hide');
                    }
                },
                error: function () {
                    loading.release();
                }
            });
        }
    });

    $('body').on('click', '#btnHapusDetail', function (e) {
        e.preventDefault();
        var kode = $(this).data('kode');
        var _token = $('input[name="_token"]').val();

        Swal.fire({
            html: `Anda memilih data fasilitas : <span class='badge badge-primary'>`+kode+`</span>
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
                    url: url.hapus_fasilitas,
                    method: 'post',
                    data: { kode: kode, _token:_token },

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
                                    loadDaftarDetailRoom();
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
