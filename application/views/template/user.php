<div class="d-flex align-items-center ms-1 ms-lg-3" id="kt_header_user_menu_toggle">
    <div class="cursor-pointer symbol symbol-30px symbol-md-40px" data-kt-menu-trigger="click" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">
        <img src="<?= isset($_SESSION['userid']) && !empty($_SESSION['userid']) ? base_url('assets/media/avatars/' . $_SESSION['userid'] . '.jpg') : base_url('assets/media/avatars/blank.png') ?>" alt="User" onerror="this.onerror=null;this.src='<?= base_url('assets/media/avatars/blank.png') ?>';">
    </div>
    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-primary fw-bold py-4 fs-6 w-350px" data-kt-menu="true">
        <div class="menu-item px-3">
            <div class="menu-content d-flex align-items-center px-3">
                <div class="symbol symbol-50px me-5">
                    <img src="<?= isset($_SESSION['userid']) && !empty($_SESSION['userid']) ? base_url('assets/media/avatars/' . $_SESSION['userid'] . '.jpg') : base_url('assets/media/avatars/blank.png') ?>" alt="User" onerror="this.onerror=null;this.src='<?= base_url('assets/media/avatars/blank.png') ?>';">
                </div>
                <div class="d-flex flex-column">
                    <div class="fw-bolder d-flex align-items-center fs-5"><?php echo $_SESSION['name']?>
                    <span class="badge badge-light-success fw-bolder fs-8 px-2 py-1 ms-2">Pro</span></div>
                    <a href="#" class="fw-bold text-muted text-hover-primary fs-7">RSUD Pasar Minggu</a>
                </div>
            </div>
        </div>
        <div class="separator my-2"></div>
        <!-- <div class="menu-item px-5">
            <a href="../../demo1/dist/account/overview.html" class="menu-link px-5">My Profile</a>
        </div>
        <div class="menu-item px-5">
            <a href="../../demo1/dist/pages/projects/list.html" class="menu-link px-5">
                <span class="menu-text">My Projects</span>
                <span class="menu-badge">
                    <span class="badge badge-light-danger badge-circle fw-bolder fs-7">3</span>
                </span>
            </a>
        </div> -->
        <div class="menu-item px-5" data-kt-menu-trigger="hover" data-kt-menu-placement="left-start">
            <!-- <a href="#" class="menu-link px-5">
                <span class="menu-title">My Subscription</span>
                <span class="menu-arrow"></span>
            </a> -->
            <div class="menu-sub menu-sub-dropdown w-175px py-4">
                <div class="menu-item px-3">
                    <a href="../../demo1/dist/account/referrals.html" class="menu-link px-5">Referrals</a>
                </div>
                <div class="menu-item px-3">
                    <a href="../../demo1/dist/account/billing.html" class="menu-link px-5">Billing</a>
                </div>
                <div class="menu-item px-3">
                    <a href="../../demo1/dist/account/statements.html" class="menu-link px-5">Payments</a>
                </div>
                <div class="menu-item px-3">
                    <a href="../../demo1/dist/account/statements.html" class="menu-link d-flex flex-stack px-5">Statements
                    <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="View your statements"></i></a>
                </div>
                <div class="separator my-2"></div>
                <div class="menu-item px-3">
                    <div class="menu-content px-3">
                        <label class="form-check form-switch form-check-custom form-check-solid">
                            <input class="form-check-input w-30px h-20px" type="checkbox" value="1" checked="checked" name="notifications" />
                            <span class="form-check-label text-muted fs-7">Notifications</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <!-- <div class="menu-item px-5">
            <a href="../../demo1/dist/account/statements.html" class="menu-link px-5">My Statements</a>
        </div>
        <div class="separator my-2"></div>
        <div class="menu-item px-5 my-1">
            <a href="../../demo1/dist/account/settings.html" class="menu-link px-5">Account Settings</a>
        </div> -->
        <div class="menu-item px-5">
            <a class="menu-link px-5" href="#" data-bs-toggle="modal" data-bs-target="#modal_logout">Sign Out</a>
        </div>
        <div class="separator my-2"></div>
        <div class="menu-item px-5">
            <div class="menu-content px-5">
                <label class="form-check form-switch form-check-custom form-check-solid pulse pulse-success" for="kt_user_menu_dark_mode_toggle">
                    <input class="form-check-input w-30px h-20px" type="checkbox" value="1" name="mode" id="kt_user_menu_dark_mode_toggle" data-kt-url="../../demo1/dist/index.html" />
                    <span class="pulse-ring ms-n1"></span>
                    <span class="form-check-label text-gray-600 fs-7">Dark Mode</span>
                </label>
            </div>
        </div>
    </div>
</div>