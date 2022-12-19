<div class="modal fade" id="optionModalRoomReservasi" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="formOptionRoomReservasi" name="formOptionRoomReservasi" autofill="off" autocomplete="off">
                <div class="modal-header">
                    <h3 class="modal-title">Pilih Room Reservasi</h3>
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                        <span class="svg-icon svg-icon-muted svg-icon-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path opacity="0.3" d="M6 19.7C5.7 19.7 5.5 19.6 5.3 19.4C4.9 19 4.9 18.4 5.3 18L18 5.3C18.4 4.9 19 4.9 19.4 5.3C19.8 5.7 19.8 6.29999 19.4 6.69999L6.7 19.4C6.5 19.6 6.3 19.7 6 19.7Z" fill="currentColor"/>
                                <path d="M18.8 19.7C18.5 19.7 18.3 19.6 18.1 19.4L5.40001 6.69999C5.00001 6.29999 5.00001 5.7 5.40001 5.3C5.80001 4.9 6.40001 4.9 6.80001 5.3L19.5 18C19.9 18.4 19.9 19 19.5 19.4C19.3 19.6 19 19.7 18.8 19.7Z" fill="currentColor"/>
                            </svg>
                        </span>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="fv-row">
                        <label class="form-label">Kategori Data:</label>
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle table-nowrap mb-0">
                                <thead class="border">
                                    <tr class="fs-7 fw-bolder text-gray-400">
                                        <th class="min-w-100px ps-3 pe-3 text-center">Tipe</th>
                                        <th class="min-w-100px ps-3 pe-3 text-center">Check-In</th>
                                        <th class="min-w-100px ps-3 pe-3 text-center">Check-Out</th>
                                    </tr>
                                </thead>
                                <tbody class="border">
                                    <tr>
                                        <td class="ps-3 pe-3 text-center">
                                            <span id="inputRoomReservasiKodeTipe" class="fs-6 fw-boldest text-info"></span>
                                        </td>
                                        <td class="ps-3 pe-3 text-center">
                                            <span id="inputRoomReservasiCheckIn" class="fs-6 fw-boldest text-primary"></span>
                                        </td>
                                        <td class="ps-3 pe-3 text-center">
                                            <span id="inputRoomReservasiCheckOut" class="fs-6 fw-boldest text-danger"></span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="fv-row mt-6 mb-5">
                        <label class="form-label">Cari Data Room:</label>
                        <div class="input-group">
                            <span class="input-group-text">Pencarian</span>
                            <input id="inputSearchOptionRoomReservasi" name="search_option_room_reservasi" type="text" class="form-control" placeholder="Cari Data Room">
                            <button id="btnSearchOptionRoomReservasi" name="btnSearchOptionRoomReservasi" class="btn btn-primary">
                                <i class="fa fa-search"></i>
                            </button>
                        </div>
                    </div>
                    <div id="optionRoomReservasiContentModal"></div>
                </div>
            </form>
        </div>
    </div>
</div>
