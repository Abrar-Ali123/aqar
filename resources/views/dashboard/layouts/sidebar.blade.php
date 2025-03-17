<div class="app-menu navbar-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box">
        <a href="index.html" class="logo logo-dark">
            <span class="logo-sm">
                <img src="{{ asset('dashboard') }}/images/logo-sm.png" alt="" height="50">
            </span>
            <span class="logo-lg">
                <img src="{{ asset('dashboard') }}/images/logo-white.png" alt="" height="50">
            </span>
        </a>
        <a href="index.html" class="logo logo-light">
            <span class="logo-sm">
                <img src="{{ asset('dashboard') }}/images/logo-sm.png" alt="" height="50">
            </span>
            <span class="logo-lg">
                <img src="{{ asset('dashboard') }}/images/logo-white.png" alt="" height="50">
            </span>
        </a>
        <button type="button" class="btn btn-sm p-0 fs-3xl header-item float-end btn-vertical-sm-hover shadow-none"
            id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>
    </div>

    <div id="scrollbar">
        <div class="container-fluid">

            <div id="two-column-menu">
            </div>
            <ul class="navbar-nav" id="navbar-nav">
                <li class="menu-title"><span>القائمة</span></li>

                <li class="nav-item">
                    <a class="nav-link menu-link" href="{{ route('admin.index') }}">
                        <i class="ti ti-dashboard"></i> <span>لوحة التحكم</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link menu-link collapsed" href="#sidebarProducts" data-bs-toggle="collapse"
                        role="button" aria-expanded="false" aria-controls="sidebarProducts">
                        <i class="ti ti-building-store"></i> <span>المنتجات</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarProducts">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ route('admin.products.create') }}" class="nav-link">إضافة منتج</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.products.index') }}" class="nav-link">عرض المنتجات</a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item">
                    <a class="nav-link menu-link collapsed" href="#sidebarCategories" data-bs-toggle="collapse"
                        role="button" aria-expanded="false" aria-controls="sidebarCategories">
                        <i class="ti ti-category"></i> <span>الفئات</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarCategories">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ route('admin.categories.create') }}" class="nav-link">إضافة فئة</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.categories.index') }}" class="nav-link">عرض الفئات</a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item">
                    <a class="nav-link menu-link collapsed" href="#sidebarAttributes" data-bs-toggle="collapse"
                        role="button" aria-expanded="false" aria-controls="sidebarAttributes">
                        <i class="ti ti-list-details"></i> <span>الخصائص</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarAttributes">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ route('admin.attributes.create') }}" class="nav-link">إضافة خاصية</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.attributes.index') }}" class="nav-link">عرض الخصائص</a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item">
                    <a class="nav-link menu-link collapsed" href="#sidebarFeatures" data-bs-toggle="collapse"
                        role="button" aria-expanded="false" aria-controls="sidebarFeatures">
                        <i class="ti ti-star"></i> <span>الميزات</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarFeatures">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ route('admin.features.create') }}" class="nav-link">إضافة ميزة</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.features.index') }}" class="nav-link">عرض الميزات</a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item">
                    <a class="nav-link menu-link collapsed" href="#sidebarFacilities" data-bs-toggle="collapse"
                        role="button" aria-expanded="false" aria-controls="sidebarFacilities">
                        <i class="ti ti-building"></i> <span>المنشآت</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarFacilities">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ route('admin.facilities.create') }}" class="nav-link">إضافة منشأة</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.facilities.index') }}" class="nav-link">عرض المنشآت</a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item">
                    <a class="nav-link menu-link collapsed" href="#sidebarSliders" data-bs-toggle="collapse"
                        role="button" aria-expanded="false" aria-controls="sidebarSliders">
                        <i class="ti ti-photo"></i> <span>الصور</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarSliders">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="#" class="nav-link"> إضافة صورة </a>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link"> عرض كل الصور </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item">
                    <a class="nav-link menu-link collapsed" href="#sidebarBanks" data-bs-toggle="collapse"
                        role="button" aria-expanded="false" aria-controls="sidebarBanks">
                        <i class="ti ti-building-bank"></i> <span>البنوك</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarBanks">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ route('admin.banks.create') }}" class="nav-link">إضافة بنك</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.banks.index') }}" class="nav-link">عرض البنوك</a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="menu-title"><span>الإعدادات</span></li>

                <li class="nav-item">
                    <a class="nav-link menu-link" href="#">
                        <i class="ti ti-settings"></i> <span>إعدادات الموقع</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link menu-link" href="#">
                        <i class="ti ti-user"></i> <span>الملف الشخصي</span>
                    </a>
                </li>
            </ul>
        </div>
        <!-- Sidebar -->
    </div>

    <div class="sidebar-background"></div>
</div>
