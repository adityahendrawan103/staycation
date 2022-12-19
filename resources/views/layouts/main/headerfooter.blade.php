<div class="wrapper d-flex flex-column flex-row-fluid" id="kt_wrapper">
    <div id="kt_header" style="" class="header align-items-stretch">
        <div class="container-fluid d-flex align-items-stretch justify-content-between">
            <div class="d-flex align-items-center d-lg-none ms-n2 me-2" title="Show aside menu">
                <div class="btn btn-icon btn-active-light-primary w-30px h-30px w-md-40px h-md-40px" id="kt_aside_mobile_toggle">
                    <span class="svg-icon svg-icon-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path d="M21 7H3C2.4 7 2 6.6 2 6V4C2 3.4 2.4 3 3 3H21C21.6 3 22 3.4 22 4V6C22 6.6 21.6 7 21 7Z" fill="currentColor" />
                            <path opacity="0.3" d="M21 14H3C2.4 14 2 13.6 2 13V11C2 10.4 2.4 10 3 10H21C21.6 10 22 10.4 22 11V13C22 13.6 21.6 14 21 14ZM22 20V18C22 17.4 21.6 17 21 17H3C2.4 17 2 17.4 2 18V20C2 20.6 2.4 21 3 21H21C21.6 21 22 20.6 22 20Z" fill="currentColor" />
                        </svg>
                    </span>
                </div>
            </div>

            <div class="d-flex align-items-center flex-grow-1 flex-lg-grow-0">
                <a href="{{ env('APP_URL') }}" class="d-lg-none">
                    <img alt="Logo" src="{{ asset('assets/media/logos/logo-1.svg') }}" class="h-30px" />
                </a>
            </div>

            <div class="d-flex align-items-stretch justify-content-between flex-lg-grow-1">
                <div class="d-flex align-items-center" id="kt_header_nav">
                    <div data-kt-swapper="true" data-kt-swapper-mode="prepend" data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_header_nav'}" class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                        <h1 class="d-flex text-dark fw-bolder fs-3 align-items-center my-1">@yield('caption')</h1>
                        <span class="h-20px border-gray-300 border-start mx-4"></span>
                        <ul class="breadcrumb breadcrumb-separatorless fw-bold fs-7 my-1">
                            <li class="breadcrumb-item text-muted">
                                <span class="text-muted">@yield('title')</span>
                            </li>
                            @if(trim($__env->yieldContent('subtitle')))
                            <li class="breadcrumb-item">
                                <span class="bullet bg-gray-300 w-5px h-2px"></span>
                            </li>
                            <li class="breadcrumb-item text-dark">@yield('subtitle')</li>
                            @endif
                        </ul>
                    </div>
                </div>

                <!--begin::Toolbar wrapper-->
                <div class="d-flex align-items-stretch flex-shrink-0">
                    <div class="d-flex align-items-center ms-1 ms-lg-3" id="kt_header_user_menu_toggle">
                        <div class="cursor-pointer symbol symbol-30px symbol-md-40px" data-kt-menu-trigger="click" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">
                            <img src={{ Session::get('app_user_photo') }}" alt="user" />
                        </div>
                        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-primary fw-bold py-4 fs-6 w-275px" data-kt-menu="true">
                            <div class="menu-item px-3">
                                <div class="menu-content d-flex align-items-center px-3">
                                    <div class="symbol symbol-50px me-5">
                                        <img alt="Logo" src="{{ Session::get('app_user_photo') }}" />
                                    </div>
                                    <div class="d-flex flex-column">
                                        <div class="fw-bolder d-flex align-items-center fs-5">{{ Session::get('app_user_id') }}
                                        <span class="badge badge-light-success fw-bolder fs-8 px-2 py-1 ms-2">{{ Session::get('app_user_role') }}</span></div>
                                        <span class="fw-bold text-muted text-hover-primary fs-7">{{ Session::get('app_user_email') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="separator my-2"></div>
                            <div class="menu-item px-5">
                                <a href="#" class="menu-link px-5">My Profile</a>
                            </div>
                            <div class="menu-item px-5">
                                <a href="#" class="menu-link px-5">
                                    <span class="menu-text">Change Password</span>
                                </a>
                            </div>
                            <div class="separator my-2"></div>
                            <div class="menu-item px-5">
                                <a href="#" class="menu-link px-5">
                                    <span class="menu-text">Log Out</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <div class="post d-flex flex-column-fluid" id="kt_post">
            <div id="kt_content_container" class="container-xxl">
                <div class="row gy-5 g-xl-8">
                    @yield('container')
                </div>
            </div>
        </div>
    </div>

    <!--begin::Footer-->
    <div class="footer py-4 d-flex flex-lg-column" id="kt_footer">
        <div class="container-fluid d-flex flex-column flex-md-row align-items-center justify-content-between">
            <div class="text-dark order-2 order-md-1">
                <span class="text-muted fw-bold me-1">2022©</span>
                <a href="#" target="_blank" class="text-gray-800 text-hover-primary">Enterbiner</a>
            </div>
            <!--end::Copyright-->
            <ul class="menu menu-gray-600 menu-hover-primary fw-bold order-1">
                <li class="menu-item">
                    <a href="#" target="_blank" class="menu-link px-2">About</a>
                </li>
                <li class="menu-item">
                    <a href="#" target="_blank" class="menu-link px-2">Support</a>
                </li>
                <li class="menu-item">
                    <a href="#" target="_blank" class="menu-link px-2">Purchase</a>
                </li>
            </ul>
        </div>
    </div>
</div>
