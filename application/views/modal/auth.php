<div class="modal fade" id="modal_logout" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">Konfirmasi Logout</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center py-5">
                <?php
                    $userid = isset($_SESSION['userid']) ? $_SESSION['userid'] : '';
                    $nama   = isset($_SESSION['name']) ? $_SESSION['name'] : 'User';

                    if (!empty($userid)) {
                        $avatar = base_url('assets/media/avatars/' . $userid . '.jpg');
                    } else {
                        $avatar = base_url('assets/media/avatars/blank.png');
                    }

                    $avatarBlank = base_url('assets/media/avatars/blank.png');
                ?>

                <img src="<?php echo $avatar; ?>" alt="User" class="rounded-circle object-fit-cover mb-4" width="80" height="80" onerror="this.onerror=null;this.src='<?php echo $avatarBlank; ?>';">

                <div class="fw-bold fs-4 mb-1">
                    <?php echo htmlspecialchars($nama, ENT_QUOTES, 'UTF-8'); ?>
                </div>

                <div class="text-muted">Apakah Anda yakin ingin logout?</div>
                <div class="text-muted fs-7">Sesi Anda akan diakhiri dan Anda perlu login kembali.</div>
            </div>
            <div class="modal-footer border-0 justify-content-center pb-5">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" onclick="logout()">Ya, Logout</button>
            </div>
        </div>
    </div>
</div>