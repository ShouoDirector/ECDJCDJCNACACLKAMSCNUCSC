<div class="navbar-custom">
                <div class="topbar container-fluid">
                    <div class="d-flex align-items-center gap-lg-2 gap-1">

                        <!-- Topbar Brand Logo -->
                        <div class="logo-topbar">
                            <!-- Logo light -->
                            <a href="#" class="logo-light">
                                <span class="logo-lg">
                                    <img src="/code/hyper_2/saas/assets/images/logo.png" alt="logo">
                                </span>
                                <span class="logo-sm">
                                <img src="/code/hyper_2/saas/assets/images/logo-sm.png" alt="logo">
                                </span>
                            </a>

                            <!-- Logo Dark -->
                            <a href="#" class="logo-dark">
                                <span class="logo-lg">
                                <img src="/code/hyper_2/saas/assets/images/logo-dark.png" alt=" dark logo">
                                </span>
                                <span class="logo-sm">
                                <img src="/code/hyper_2/saas/assets/images/logo-dark-sm.png" alt="logo">
                                </span>
                            </a>
                        </div>

                        <!-- Sidebar Menu Toggle Button -->
                        <button class="button-toggle-menu">
                            <i class="mdi mdi-menu"></i>
                        </button>

                        <!-- Horizontal Menu Toggle Button -->
                        <button class="navbar-toggle" data-bs-toggle="collapse" data-bs-target="#topnav-menu-content">
                            <div class="lines">
                                <span></span>
                                <span></span>
                                <span></span>
                            </div>
                        </button>
                    </div>

                    <ul class="topbar-menu d-flex align-items-center gap-3">

                        <li class="dropdown notification-list">
                            <?php @include 'top-includes/notification.php' ?>
                        </li>

                        <li class="dropdown d-none d-sm-inline-block">
                            <?php @include 'top-includes/links.php' ?>
                        </li>

                        <li class="d-none d-sm-inline-block">
                            <a class="nav-link" data-bs-toggle="offcanvas" href="#theme-settings-offcanvas">
                                <i class="ri-settings-3-line font-22"></i>
                            </a>
                        </li>

                        <li class="d-none d-sm-inline-block">
                            <div class="nav-link" id="light-dark-mode" data-bs-toggle="tooltip" data-bs-placement="left" title="Theme Mode">
                                <i class="ri-moon-line font-22"></i>
                            </div>
                        </li>


                        <li class="d-none d-md-inline-block">
                            <a class="nav-link" href="#" data-toggle="fullscreen">
                                <i class="ri-fullscreen-line font-22"></i>
                            </a>
                        </li>

                        <li class="dropdown">
                            <?php @include 'top-includes/profile-settings.php' ?>
                        </li>
                    </ul>
                </div>
            </div>