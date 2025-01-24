@extends('dashboard.layouts.app')

@section('content')
    <div class="container-fluid">

        <div class="row">
            <div class="col-xl-8">
                <div>
                    <div class="row gy-4">
                        <div class="col-sm-4 border-end-sm">
                            <div class="text-center">
                                <p class="text-uppercase fw-medium text-muted text-truncate fs-md">المجموع
                                    جلسات</p>
                                <h4 class="fw-semibold mb-3"><span class="counter-value" data-target="476.32">0</span>k </h4>
                                <div class="d-flex align-items-center justify-content-center gap-2">
                                    <h5 class="text-success fs-xs mb-0">
                                        <i class="ri-arrow-right-up-line fs-sm align-middle"></i> +19.07 %
                                    </h5>
                                    <p class="text-muted mb-0">من الأسبوع الماضي</p>
                                </div>
                            </div>
                            <!-- end card -->
                        </div>
                        <div class="col-sm-4 border-end-sm">
                            <div class="text-center">
                                <p class="text-uppercase fw-medium text-muted text-truncate fs-md">Avg.
                                    زيارة المدة</p>
                                <h4 class="fw-semibold mb-3"><span class="counter-value" data-target="1.57">0</span>s </h4>
                                <div class="d-flex align-items-center justify-content-center gap-2">
                                    <h5 class="text-success fs-xs mb-0">
                                        <i class="ri-arrow-right-up-line fs-sm align-middle"></i> +19.07 %
                                    </h5>
                                    <p class="text-muted mb-0">من الأسبوع الماضي</p>
                                </div>
                            </div>
                            <!-- end card -->
                        </div>
                        <div class="col-sm-4">
                            <div class="text-center">
                                <p class="text-uppercase fw-medium text-muted text-truncate fs-md">
                                    انطباعات</p>
                                <h4 class="fw-semibold mb-3"><span class="counter-value" data-target="2368">0</span>k </h4>
                                <div class="d-flex align-items-center justify-content-center gap-2">
                                    <h5 class="text-success fs-xs mb-0">
                                        <i class="ri-arrow-right-up-line fs-sm align-middle"></i> +19.07 %
                                    </h5>
                                    <p class="text-muted mb-0">من الأسبوع الماضي</p>
                                </div>
                            </div>
                            <!-- end card -->
                        </div>
                    </div>

                    <div class="mt-4">
                        <div class="mx-n4">
                            <div id="performance_overview" data-colors='["--tb-primary", "--tb-warning"]'
                                class="apex-charts" dir="ltr"></div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-3 col-sm-6">
                            <div class="card">
                                <div class="card-body p-3 d-flex gap-3">
                                    <div class="flex-shrink-0">
                                        <div class="avatar-sm">
                                            <div class="avatar-title bg-body-secondary text-secondary rounded fs-3xl">
                                                <i class="ti ti-brand-facebook"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <p class="text-muted mb-2">Facebook</p>
                                        <h6 class="fw-semibold mb-0"><span class="counter-value"
                                                data-target="354620">0</span>k <small
                                                class="text-muted fw-normal">الزوار</small></h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--end col-->
                        <div class="col-lg-3 col-sm-6">
                            <div class="card">
                                <div class="card-body p-3 d-flex gap-3">
                                    <div class="flex-shrink-0">
                                        <div class="avatar-sm">
                                            <div class="avatar-title bg-body-secondary text-danger rounded fs-3xl">
                                                <i class="ti ti-brand-instagram"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <p class="text-muted mb-2">Instagram </p>
                                        <h6 class="fw-semibold mb-0"><span class="counter-value"
                                                data-target="245631">0</span>k <small
                                                class="text-muted fw-normal">الزوار</small></h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--end col-->
                        <div class="col-lg-3 col-sm-6">
                            <div class="card">
                                <div class="card-body p-3 d-flex gap-3">
                                    <div class="flex-shrink-0">
                                        <div class="avatar-sm">
                                            <div class="avatar-title bg-body-secondary text-info rounded fs-3xl">
                                                <i class="ti ti-brand-twitter"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <p class="text-muted mb-2">Twitter </p>
                                        <h6 class="fw-semibold mb-0"><span class="counter-value"
                                                data-target="154832">0</span>k <small
                                                class="text-muted fw-normal">الزوار</small></h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--end col-->
                        <div class="col-lg-3 col-sm-6">
                            <div class="card">
                                <div class="card-body p-3 d-flex gap-3">
                                    <div class="flex-shrink-0">
                                        <div class="avatar-sm">
                                            <div class="avatar-title bg-body-secondary text-body rounded fs-3xl">
                                                <i class="ti ti-world"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <p class="text-muted mb-2">Website </p>
                                        <h6 class="fw-semibold mb-0"><span class="counter-value"
                                                data-target="489012">0</span>k <small
                                                class="text-muted fw-normal">الزوار</small></h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--end col-->
                    </div>
                    <!--end row-->
                </div>
            </div>
            <!--end col-->
            <div class="col-xl-4">
                <div class="d-none d-xl-block">
                    <div class="card bg-success-subtle shadow-none rounded-0 border-0 dashboard-widgets-wrapper">
                        <div class="card-body text-center mt-5 pt-5">
                            <h5>مرحبًا بك في لوحة تحكم عقاري</h5>
                            <p class="text-muted fs-md  ">هناك آخر تحديث خلال الأيام السبعة الماضية ، تحقق الآن</p>
                            <img src="{{ asset('dashboard') }}/images/dashboard.png" alt="" class="img-fluid">
                        </div>
                    </div>
                </div>
            </div>
            <!--end col-->
        </div>
        <!--end row-->
    </div>
@endsection
