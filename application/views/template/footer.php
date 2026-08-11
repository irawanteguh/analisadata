<?php
    $website     = 'https://rsudpasarminggu.jakarta.go.id/';
    $hospital    = 'RSUD Pasar Minggu';
    $ipAddress   = $_SERVER['REMOTE_ADDR'] ?? '-';
?>

<div class="footer py-4 d-flex flex-lg-column" id="kt_footer">
    <div class="container-fluid d-flex flex-column flex-md-row align-items-center justify-content-between">

        <div class="text-dark order-2 order-md-1">
            <div class="text-muted">
                2026 &copy; Copyright Infinite For Use

                <?php if (!empty($hospital)) : ?>
                    <a href="<?php echo htmlspecialchars($website); ?>" target="_blank">
                        <?php echo htmlspecialchars($hospital); ?>
                    </a> |
                <?php endif; ?>

                Page rendered in <strong>{elapsed_time}</strong> seconds.
                | IP Address : <strong><?php echo htmlspecialchars($ipAddress); ?></strong>
            </div>
        </div>

        <div>
            <a href="#">Privacy Policy</a>
            &middot;
            <a href="#">Terms &amp; Conditions</a>
        </div>

    </div>
</div>