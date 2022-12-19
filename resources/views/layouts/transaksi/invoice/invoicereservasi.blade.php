@extends('layouts.main.index')
@section('caption','Transaksi')
@section('title','Reservasi')
@section('subtitle','')
@section('container')
<div class="card">
    <div class="card-body p-lg-20">
        <div class="d-flex flex-column flex-xl-row">
            <div class="flex-lg-row-fluid me-xl-18 mb-10 mb-xl-0">
                <div class="mt-n1">
                    <div class="d-flex flex-stack pb-10">
                        <a href="#">
                            <img alt="Logo" src="assets/media/svg/brand-logos/code-lab.svg">
                        </a>
                    </div>
                    <div class="m-0">
                        <div class="fw-boldest fs-3 text-gray-800 mb-8">001/RV/EB/XI/2022</div>
                        <div class="row g-5 mb-12">
                            <div class="col-sm-6">
                                <div class="fw-bold fs-7 text-gray-600 mb-1">Guest:</div>
                                <div class="fw-bolder fs-6 text-gray-800">3515132507940001</div>
                                <div class="fw-bold fs-7 text-gray-600">Aditya Hendrawan
                                    <br>Sidoarjo
                                </div>
                            </div>
                        </div>
                        <div class="row g-5 mb-11">
                            <div class="col-sm-6">
                                <div class="fw-bold fs-7 text-gray-600 mb-1">Check In:</div>
                                <div class="fw-bolder fs-6 text-gray-800">01 November 2022
                                    <br><div class="fw-bold fs-7 text-gray-600">14:00:00</div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="fw-bold fs-7 text-gray-600 mb-1">Check Out:</div>
                                <div class="fw-bolder fs-6 text-gray-800">02 November 2022
                                    <br><div class="fw-bold fs-7 text-gray-600">12:00:00</div>
                                </div>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <div class="fs-3 fw-boldest text-dark mt-4 mb-4">ROOM</div>
                            <div class="table-responsive border-top">
                                <table class="table table-row-bordered">
                                    <thead>
                                        <tr class="fw-bolder text-muted fs-8">
                                            <th class="min-w-175px pb-2">KETERANGAN</th>
                                            <th class="min-w-70px text-end pb-2">HARGA</th>
                                            <th class="min-w-80px text-end pb-2">QUANTITY</th>
                                            <th class="min-w-100px text-end pb-2">TOTAL</th>
                                        </tr>
                                    </thead>
                                    <tbody class="border-bottom">
                                        <tr class="fs-6 fw-bolder text-gray-700">
                                            <td class="pt-3 pb-3">Deluxe Room 502</td>
                                            <td class="pt-3 pb-3 text-end">1,000,000</td>
                                            <td class="pt-3 pb-3 text-end">1 Malam</td>
                                            <td class="pt-3 pb-3 text-end">1,000,000</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="d-flex justify-content-end">
                                    <div class="mw-300px">
                                        <div class="d-flex flex-stack mb-3">
                                            <div class="fw-bolder text-muted fs-8 pe-20">SUBTOTAL:</div>
                                            <div class="text-end fw-bolder fs-6 text-gray-800">1,000,000</div>
                                        </div>
                                        <div class="d-flex flex-stack mb-3">
                                            <div class="fw-bolder text-muted fs-8 pe-20">OTHER:</div>
                                            <div class="text-end fw-bolder fs-6 text-gray-800">1,000,000</div>
                                        </div>
                                        <div class="d-flex flex-stack mb-3">
                                            <div class="fw-bolder text-muted fs-8 pe-20">DISC (0%):</div>
                                            <div class="text-end fw-bolder fs-6 text-gray-800">50,000</div>
                                        </div>
                                        <div class="d-flex flex-stack mb-3">
                                            <div class="fw-bolder text-muted fs-8 pe-20">PPN (0%):</div>
                                            <div class="text-end fw-bolder fs-6 text-gray-800">55,000</div>
                                        </div>
                                        <div class="d-flex flex-stack">
                                            <div class="fw-bolder text-muted fs-8 pe-20">GRAND TOTAL:</div>
                                            <div class="text-end fw-bolder fs-6 text-gray-800">1,000,000</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <div class="fs-3 fw-boldest text-dark mt-6 mb-6">LAYANAN</div>
                            <div class="table-responsive border-top">
                                <table class="table table-row-bordered">
                                    <thead>
                                        <tr class="fw-bolder text-muted fs-8">
                                            <th class="min-w-175px pb-2">KETERANGAN</th>
                                            <th class="min-w-70px text-end pb-2">HARGA</th>
                                            <th class="min-w-80px text-end pb-2">QUANTITY</th>
                                            <th class="min-w-80px text-end pb-2">DISC(%)</th>
                                            <th class="min-w-100px text-end pb-2">TOTAL</th>
                                        </tr>
                                    </thead>
                                    <tbody class="border-bottom">
                                        <tr class="fs-6 fw-bolder text-gray-700">
                                            <td class="pt-3 pb-3">Deluxe Room 502</td>
                                            <td class="pt-3 pb-3 text-end">1,000,000</td>
                                            <td class="pt-3 pb-3 text-end">1</td>
                                            <td class="pt-3 pb-3 text-end">5.00</td>
                                            <td class="pt-3 pb-3 text-end">1,000,000</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="d-flex justify-content-end">
                                    <div class="mw-300px">
                                        <div class="d-flex flex-stack mb-3">
                                            <div class="fw-bolder text-muted fs-8 pe-20">SUBTOTAL:</div>
                                            <div class="text-end fw-bolder fs-6 text-gray-800">1,000,000</div>
                                        </div>
                                        <div class="d-flex flex-stack mb-3">
                                            <div class="fw-bolder text-muted fs-8 pe-20">DISC (0%):</div>
                                            <div class="text-end fw-bolder fs-6 text-gray-800">50,000</div>
                                        </div>
                                        <div class="d-flex flex-stack mb-3">
                                            <div class="fw-bolder text-muted fs-8 pe-20">PPN (0%):</div>
                                            <div class="text-end fw-bolder fs-6 text-gray-800">55,000</div>
                                        </div>
                                        <div class="d-flex flex-stack">
                                            <div class="fw-bolder text-muted fs-8 pe-20">GRAND TOTAL:</div>
                                            <div class="text-end fw-bolder fs-6 text-gray-800">1,000,000</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="flex-grow-1 mt-10">
                            <div class="row">
                                <div class="col-sm-6">
                                    <table class="table">
                                        <tr><td class="fw-bolder text-muted fs-8 text-center">TANDA TANGAN</td></tr>
                                        <tr><td class="fw-bolder text-gray-400 pt-15 text-center" style="font-style: italic;">(Receptionist)<br>--------------------</td></tr>
                                    </table>
                                </div>
                                <div class="col-sm-6">
                                    <table class="table">
                                        <tr><td class="fw-bolder text-muted fs-8 text-center">TANDA TANGAN</td></tr>
                                        <tr><td class="fw-bolder text-gray-400 pt-15 text-center" style="font-style: italic;">(Guest)<br>--------------------</td></tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="m-0">
                <!--begin::Invoice 2 sidebar-->
                <div class="d-print-none border border-dashed border-gray-300 card-rounded h-lg-100 min-w-md-350px p-9 bg-lighten">
                    <!--begin::Labels-->
                    <div class="mb-8">
                        <span class="badge badge-light-success me-2">Approved</span>
                        <span class="badge badge-light-warning">Pending Payment</span>
                    </div>
                    <!--end::Labels-->
                    <!--begin::Title-->
                    <h6 class="mb-8 fw-boldest text-gray-600 text-hover-primary">PAYMENT DETAILS</h6>
                    <!--end::Title-->
                    <!--begin::Item-->
                    <div class="mb-6">
                        <div class="fw-bold text-gray-600 fs-7">Paypal:</div>
                        <div class="fw-bolder text-gray-800 fs-6">codelabpay@codelab.co</div>
                    </div>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <div class="mb-6">
                        <div class="fw-bold text-gray-600 fs-7">Account:</div>
                        <div class="fw-bolder text-gray-800 fs-6">Nl24IBAN34553477847370033
                        <br>AMB NLANBZTC</div>
                    </div>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <div class="mb-15">
                        <div class="fw-bold text-gray-600 fs-7">Payment Term:</div>
                        <div class="fw-bolder fs-6 text-gray-800 d-flex align-items-center">14 days
                        <span class="fs-7 text-danger d-flex align-items-center">
                        <span class="bullet bullet-dot bg-danger mx-2"></span>Due in 7 days</span></div>
                    </div>
                    <!--end::Item-->
                    <!--begin::Title-->
                    <h6 class="mb-8 fw-boldest text-gray-600 text-hover-primary">PROJECT OVERVIEW</h6>
                    <!--end::Title-->
                    <!--begin::Item-->
                    <div class="mb-6">
                        <div class="fw-bold text-gray-600 fs-7">Project Name</div>
                        <div class="fw-bolder fs-6 text-gray-800">SaaS App Quickstarter
                        <a href="#" class="link-primary ps-1">View Project</a></div>
                    </div>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <div class="mb-6">
                        <div class="fw-bold text-gray-600 fs-7">Completed By:</div>
                        <div class="fw-bolder text-gray-800 fs-6">Mr. Dewonte Paul</div>
                    </div>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <div class="m-0">
                        <div class="fw-bold text-gray-600 fs-7">Time Spent:</div>
                        <div class="fw-bolder fs-6 text-gray-800 d-flex align-items-center">230 Hours
                        <span class="fs-7 text-success d-flex align-items-center">
                        <span class="bullet bullet-dot bg-success mx-2"></span>35$/h Rate</span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
