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



            </ul>
        </div>
        <!-- Sidebar -->
    </div>

    <div class="sidebar-background"></div>
</div>
