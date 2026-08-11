<?php
$name        = trim((string) $this->session->userdata('name'));
$displayName = $name !== '' ? $name : 'Guest';
$hour        = (int) date('G');

if ($hour >= 5 && $hour < 12) {

    // Morning
    $greeting = 'Good Morning';
    $background = 'morning-rsudpm.png';

    $titleColor     = '#1F2937';
    $textColor      = '#4B5563';
    $kickerColor    = '#374151';
    $badgeBg        = 'rgba(255,255,255,.75)';
    $badgeBorder    = 'rgba(0,0,0,.08)';
    $badgeColor     = '#1F2937';

} elseif ($hour >= 12 && $hour < 16) {

    // Afternoon
    $greeting = 'Good Afternoon';
    $background = 'sunrise-rsudpm.png';

    $titleColor     = '#1E293B';
    $textColor      = '#475569';
    $kickerColor    = '#334155';
    $badgeBg        = 'rgba(255,255,255,.72)';
    $badgeBorder    = 'rgba(0,0,0,.08)';
    $badgeColor     = '#1E293B';

} elseif ($hour >= 16 && $hour < 19) {

    // Evening
    $greeting = 'Good Evening';
    $background = 'sunset-rsudpm.png';

    $titleColor     = '#FFFFFF';
    $textColor      = 'rgba(255,255,255,.88)';
    $kickerColor    = 'rgba(255,255,255,.75)';
    $badgeBg        = 'rgba(255,255,255,.12)';
    $badgeBorder    = 'rgba(255,255,255,.15)';
    $badgeColor     = '#FFFFFF';

} else {

    // Night
    $greeting = 'Good Night';
    $background = 'night-rsudpm.png';

    $titleColor     = '#FFFFFF';
    $textColor      = 'rgba(255,255,255,.85)';
    $kickerColor    = 'rgba(255,255,255,.72)';
    $badgeBg        = 'rgba(255,255,255,.12)';
    $badgeBorder    = 'rgba(255,255,255,.15)';
    $badgeColor     = '#FFFFFF';

}
?>

<style>
    .welcome-banner{
        position:relative;
        overflow:hidden;
        min-height:220px;      /* sebelumnya 320px */
        border-radius:10px;

        background-image:url('<?= base_url('assets/media/ambient/'.$background); ?>');
        background-position:center;
        background-size:cover;
        background-repeat:no-repeat;

        --title-color: <?= $titleColor ?>;
        --text-color: <?= $textColor ?>;
        --kicker-color: <?= $kickerColor ?>;
        --badge-bg: <?= $badgeBg ?>;
        --badge-border: <?= $badgeBorder ?>;
        --badge-color: <?= $badgeColor ?>;
    }

    /* Hilangkan overlay lama */
    .welcome-banner::before,
    .welcome-banner::after{
        display:none;
    }

    .welcome-banner-content{
        position:relative;
        z-index:2;
    }

    .welcome-kicker{
        color:var(--kicker-color);
        font-size:.78rem;
        font-weight:700;
        text-transform:uppercase;
        letter-spacing:.08em;
    }

    .welcome-title{
        color:var(--title-color);
        font-size:clamp(1.8rem,3vw,3rem);
        font-weight:700;
        line-height:1.15;
        margin-bottom:.75rem;
    }

    .welcome-subtitle{
        color:var(--text-color);
        font-size:1rem;
        max-width:560px;
    }

    .welcome-date{
        color:var(--badge-color);
        background:var(--badge-bg);
        border:1px solid var(--badge-border);
        border-radius:8px;
        backdrop-filter:blur(8px);
    }

    @media(max-width:768px){

        .welcome-banner{
            min-height:180px;
        }

        .welcome-title{
            font-size:2rem;
        }
    }
</style>

<section class="welcome-banner d-flex align-items-center p-6 p-lg-8">
    <div class="welcome-banner-content">
        <div class="welcome-kicker mb-2">
            Analisa Data Workspace
        </div>
        <h1 class="welcome-title">
            <?= $greeting; ?>,
            <?= htmlspecialchars($displayName, ENT_QUOTES, 'UTF-8'); ?>
        </h1>
        <p class="welcome-subtitle mb-4">
            Welcome back. Your dashboard is ready, and everything you need is one step away.
        </p>
        <div class="welcome-date d-inline-flex align-items-center px-4 py-2 fw-semibold">
            <i class="bi bi-calendar3 me-2"></i>
            <?= date('l, F j, Y'); ?>
        </div>
    </div>
</section>
