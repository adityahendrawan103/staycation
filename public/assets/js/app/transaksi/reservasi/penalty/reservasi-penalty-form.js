$(document).ready(function () {
    // ===============================================================
    // Form Max Lenght
    // ===============================================================
    $('#inputKeterangan').maxlength({
        warningClass: "badge badge-success",
        limitReachedClass: "badge badge-danger",
        threshold: 5,
        appendToParent: true
    });

    // ===============================================================
    // Form Entry Room
    // ===============================================================
    $('form').find('input,select').on('keydown', function (e) {
        if (e.which == 13) {
            e.preventDefault();
            var index = $('form').find('input,select').index(this) + 1;
            if ($('form').find('input,select').eq(index).attr('readonly') || $('form').find('input,select').eq(index).hasClass('form-control-solid')) {
                for (let i = index; i < $('form').find('input,select').length; i++) {
                    if (!$('form').find('input,select').eq(i).attr('readonly') || !$('form').find('input,select').eq(i).hasClass('form-control-solid')) {
                        $('form').find('input,select').eq(i).focus();
                        break;
                    }
                }
            } else {
                $('form').find('input,select').eq(index).focus();
            }
        }
    });

    $('#btnTambahItem').on('click', function (e) {
        e.preventDefault();
        $('#formEntryItem').trigger('reset');
        $('#modalTitleItem').html('Entry Data Item');
        $('#modalEntryItem').modal('show');
    });

    $('#btnSimpan').on('click', function (e) {
        e.preventDefault();
        var button = $(e.target);
        var formData = button.parents('form').serialize() + '&' + encodeURI(button.attr('name')) + '=' + encodeURI(button.attr('value'));

        $.ajax({
            url: url.simpan_reservasi_penalty,
            method: 'post',
            data: formData,

            success: function (response) {
                loading.release();
                if (response.status == false) {
                    Swal.fire({
                        text: response.message,
                        icon: 'warning',
                        buttonsStyling: false,
                        confirmButtonText: 'Ok, got it!',
                        customClass: {
                            confirmButton: 'btn btn-warning'
                        }
                    });
                } else {
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
                            window.location.href = url.daftar_reservasi_penalty;
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

    $('#btnBatal').on('click', function() {
        loading.block();
        location.reload();
    });
});
