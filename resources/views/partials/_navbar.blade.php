    <nav class="navbar default-layout col-lg-12 col-12 p-0 fixed-top d-flex align-items-top flex-row">
        <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start">
            <div class="me-3">
                <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-bs-toggle="minimize">
                    <span class="icon-menu"></span>
                </button>
            </div>
            <div>
                <a class="navbar-brand brand-logo" href="#">
                    <b>
                        PT <b class="text-black"> Takita </b>
                    </b>
                </a>
                <a class="navbar-brand brand-logo-mini" href="index.html">
                    <b>
                        P <b class="text-black"> T</b>
                    </b>
                </a>
            </div>
        </div>
        <div class="navbar-menu-wrapper d-flex pt-4 float-end">
            <ul class="navbar-nav">
                <li class="nav-item font-weight-semibold d-none d-lg-block ms-0">
                    <h1 class="welcome-text mt-2">Welcome, <span
                            class="text-black fw-bold">{{ Auth::user()->name }}</span></h1>
                </li>
            </ul>
            <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button"
                data-bs-toggle="offcanvas">
                <span class="mdi mdi-menu"></span>
            </button>
        </div>
    </nav>
