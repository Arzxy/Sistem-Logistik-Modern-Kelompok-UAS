<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Layanan — Selebet Express</title>
    <meta name="description" content="Pantau status semua layanan backend Selebet Express.">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', sans-serif;
            background: #f5f6f8;
            color: #1a1d23;
            min-height: 100vh;
        }

        /* ── TOPBAR ── */
        .topbar {
            background: #fff;
            border-bottom: 1px solid #e8eaed;
            padding: 0 24px;
            height: 56px;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .topbar-logo {
            width: 30px; height: 30px;
            background: #4f46e5;
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-size: 16px;
        }
        .topbar-name { font-size: 15px; font-weight: 600; color: #1a1d23; }
        .topbar-name span { color: #4f46e5; }
        .topbar-back {
            margin-left: auto;
            display: flex; align-items: center; gap: 5px;
            font-size: 13px; color: #6b7280;
            text-decoration: none;
            padding: 6px 12px;
            border-radius: 6px;
            transition: background .15s;
        }
        .topbar-back:hover { background: #f3f4f6; color: #1a1d23; }

        /* ── WRAP ── */
        .wrap { padding: 32px 20px 64px; }

        /* ── OVERALL BANNER ── */
        .banner {
            border-radius: 12px;
            padding: 20px 22px;
            display: flex; align-items: center; gap: 14px;
            margin-bottom: 28px;
            border: 1px solid transparent;
        }
        .banner.ok    { background: #f0fdf4; border-color: #bbf7d0; }
        .banner.warn  { background: #fffbeb; border-color: #fde68a; }
        .banner.error { background: #fff1f2; border-color: #fecdd3; }

        .banner-icon { font-size: 22px; flex-shrink: 0; }
        .banner.ok    .banner-icon { color: #16a34a; }
        .banner.warn  .banner-icon { color: #d97706; }
        .banner.error .banner-icon { color: #dc2626; }

        .banner-title { font-size: 14px; font-weight: 600; }
        .banner.ok    .banner-title { color: #15803d; }
        .banner.warn  .banner-title { color: #b45309; }
        .banner.error .banner-title { color: #b91c1c; }

        .banner-sub { font-size: 12px; margin-top: 2px; }
        .banner.ok    .banner-sub { color: #4ade80; color: #166534; }
        .banner.warn  .banner-sub { color: #92400e; }
        .banner.error .banner-sub { color: #9f1239; }

        .btn-refresh {
            margin-left: auto; flex-shrink: 0;
            display: flex; align-items: center; gap: 5px;
            background: #fff; border: 1px solid #e5e7eb;
            border-radius: 7px; padding: 6px 14px;
            font-size: 12px; font-weight: 500; color: #374151;
            cursor: pointer; font-family: inherit; transition: .15s;
        }
        .btn-refresh:hover { background: #f9fafb; border-color: #d1d5db; }
        .btn-refresh i { font-size: 14px; }

        /* ── SECTION HEAD ── */
        .section-head {
            font-size: 11px; font-weight: 600; letter-spacing: .06em;
            text-transform: uppercase; color: #9ca3af;
            margin-bottom: 10px;
        }

        /* ── SERVICE CARD ── */
        .service-card {
            background: #fff;
            border: 1px solid #e8eaed;
            border-radius: 12px;
            padding: 18px 20px;
            margin-bottom: 8px;
        }

        .card-row { display: flex; align-items: center; gap: 12px; }

        .svc-icon {
            width: 38px; height: 38px; border-radius: 9px;
            display: flex; align-items: center; justify-content: center;
            font-size: 18px; flex-shrink: 0;
        }

        /* icon colors */
        .ic-indigo  { background: #eef2ff; color: #4f46e5; }
        .ic-blue    { background: #eff6ff; color: #2563eb; }
        .ic-violet  { background: #f5f3ff; color: #7c3aed; }
        .ic-orange  { background: #fff7ed; color: #ea580c; }
        .ic-emerald { background: #f0fdf4; color: #059669; }

        .svc-name { font-size: 14px; font-weight: 600; color: #1a1d23; }
        .svc-desc { font-size: 12px; color: #9ca3af; margin-top: 1px; }

        /* badge */
        .badge {
            margin-left: auto; flex-shrink: 0;
            display: flex; align-items: center; gap: 5px;
            font-size: 11px; font-weight: 600;
            padding: 4px 10px; border-radius: 99px;
        }
        .badge.online   { background: #f0fdf4; color: #15803d; }
        .badge.degraded { background: #fffbeb; color: #b45309; }
        .badge.offline  { background: #fff1f2; color: #b91c1c; }

        .dot { width: 6px; height: 6px; border-radius: 50%; flex-shrink: 0; }
        .dot.online   { background: #22c55e; animation: blink 2s ease-in-out infinite; }
        .dot.degraded { background: #f59e0b; }
        .dot.offline  { background: #ef4444; }
        @keyframes blink { 0%,100%{opacity:1} 50%{opacity:.35} }

        /* progress bar */
        .bar-section { margin-top: 14px; }
        .bar-meta {
            display: flex; justify-content: space-between;
            font-size: 11px; color: #9ca3af; margin-bottom: 6px;
        }
        .bar-pct { font-weight: 600; }
        .bar-pct.online   { color: #16a34a; }
        .bar-pct.degraded { color: #d97706; }
        .bar-pct.offline  { color: #6b7280; }

        .bar-track {
            height: 6px; background: #f3f4f6; border-radius: 99px; overflow: hidden;
        }
        .bar-fill {
            height: 100%; border-radius: 99px;
            transition: width 1s cubic-bezier(.4,0,.2,1);
        }
        .bar-fill.online   { background: #22c55e; }
        .bar-fill.degraded { background: #f59e0b; }
        .bar-fill.offline  { background: #e5e7eb; }

        /* stat chips */
        .stat-row { display: flex; gap: 12px; margin-top: 12px; flex-wrap: wrap; }
        .stat { font-size: 11px; color: #6b7280; display: flex; align-items: center; gap: 4px; }
        .stat i { font-size: 12px; color: #d1d5db; }

        /* footer */
        .footer-note {
            text-align: center; font-size: 12px; color: #9ca3af; margin-top: 24px;
        }
        .footer-note span { color: #374151; font-weight: 500; }
    </style>
</head>
<body>

<div class="wrap">

    {{-- OVERALL BANNER --}}
    @php
        $bannerClass = match($overallStatus) {
            'all_operational' => 'ok',
            'partial_outage'  => 'warn',
            default           => 'error',
        };
        $bannerIcon = match($overallStatus) {
            'all_operational' => 'bx-check-circle',
            'partial_outage'  => 'bx-error',
            default           => 'bx-x-circle',
        };
        $bannerTitle = match($overallStatus) {
            'all_operational' => 'Semua layanan beroperasi normal',
            'partial_outage'  => 'Sebagian layanan mengalami gangguan',
            default           => 'Semua layanan tidak dapat dijangkau',
        };
        $bannerSub = match($overallStatus) {
            'all_operational' => 'Seluruh infrastruktur berjalan dengan baik.',
            'partial_outage'  => 'Beberapa layanan sedang dalam penanganan.',
            default           => 'Tidak ada layanan yang dapat dijangkau saat ini.',
        };
    @endphp

    <div class="banner {{ $bannerClass }}" id="overall-banner">
        <i class='bx {{ $bannerIcon }} banner-icon'></i>
        <div>
            <div class="banner-title" id="banner-title">{{ $bannerTitle }}</div>
            <div class="banner-sub"   id="banner-sub">{{ $bannerSub }}</div>
        </div>
        <button class="btn-refresh" id="btn-refresh" onclick="refreshStatus()">
            <i class='bx bx-refresh' id="refresh-icon"></i>
            Refresh
        </button>
    </div>

    <div class="section-head">Status Layanan</div>

    {{-- SERVICE CARDS --}}
    <div id="services-list">
        @foreach($results as $svc)
            @php
                $st  = $svc['status'];
                $pct = $svc['uptime_percent'];
                $ic  = 'ic-' . $svc['color'];
            @endphp

            <div class="service-card" id="card-{{ $svc['key'] }}">

                <div class="card-row">
                    <div class="svc-icon {{ $ic }}">
                        <i class='bx {{ $svc['icon'] }}'></i>
                    </div>
                    <div>
                        <div class="svc-name">{{ $svc['name'] }}</div>
                        <div class="svc-desc">{{ $svc['desc'] }}</div>
                    </div>
                    <div class="badge {{ $st }}" id="badge-{{ $svc['key'] }}">
                        <span class="dot {{ $st }}"></span>
                        {{ $st === 'online' ? 'Online' : ($st === 'degraded' ? 'Gangguan' : 'Offline') }}
                    </div>
                </div>

                <div class="bar-section">
                    <div class="bar-meta">
                        <span>Uptime</span>
                        <span class="bar-pct {{ $st }}" id="pct-{{ $svc['key'] }}">{{ $pct }}%</span>
                    </div>
                    <div class="bar-track">
                        <div class="bar-fill {{ $st }}"
                             id="bar-{{ $svc['key'] }}"
                             style="width:{{ $pct }}%">
                        </div>
                    </div>
                </div>

                <div class="stat-row">
                    <div class="stat">
                        <i class='bx bx-time-five'></i>
                        Latency: <strong>{{ $svc['latency'] !== null ? $svc['latency'].' ms' : '—' }}</strong>
                    </div>
                    <div class="stat">
                        <i class='bx bx-code'></i>
                        HTTP: <strong>{{ $svc['status_code'] ?? '—' }}</strong>
                    </div>
                    <div class="stat">
                        <i class='bx bx-time'></i>
                        Pukul: <strong>{{ $svc['checked_at'] }}</strong>
                    </div>
                </div>

            </div>
        @endforeach
    </div>

    <div class="footer-note">
        Terakhir dicek pukul <span id="last-time">{{ now()->format('H:i:s') }}</span>
        &nbsp;·&nbsp; Auto-refresh setiap 30 detik
    </div>

</div>

<script>
const INTERVAL = 30000;

/* ── TOAST via SweetAlert2 (sama seperti login.blade.php) ── */
function showToast(icon, title) {
    Swal.fire({
        toast: true,
        position: 'top-end',
        icon: icon,
        title: title,
        showConfirmButton: false,
        timer: 3000
    });
}

/* ── REFRESH ── */
function refreshStatus() {
    const icon = document.getElementById('refresh-icon');
    icon.classList.add('bx-spin');

    fetch('/status/api')
        .then(r => r.json())
        .then(data => {
            data.services.forEach(updateCard);
            updateBanner(data.overall_status);
            document.getElementById('last-time').textContent = new Date().toLocaleTimeString('id-ID');

            if (data.overall_status === 'all_operational') {
                showToast('success', 'Semua layanan aktif');
            } else if (data.overall_status === 'partial_outage') {
                const offlineCount = data.services.filter(s => s.status === 'offline').length;
                showToast('warning', offlineCount + ' layanan tidak dapat dijangkau');
            } else {
                showToast('error', 'Semua layanan tidak merespons');
            }
        })
        .catch(() => {
            showToast('error', 'Refresh gagal, coba lagi');
        })
        .finally(() => icon.classList.remove('bx-spin'));
}

function updateCard(svc) {
    const badge = document.getElementById('badge-' + svc.key);
    const bar   = document.getElementById('bar-'   + svc.key);
    const pct   = document.getElementById('pct-'   + svc.key);
    if (!badge || !bar || !pct) return;

    const st     = svc.status;
    const label  = st === 'online' ? 'Online' : st === 'degraded' ? 'Gangguan' : 'Offline';

    // badge
    badge.className = 'badge ' + st;
    const dot = badge.querySelector('.dot');
    if (dot) dot.className = 'dot ' + st;
    badge.lastChild.textContent = label;

    // bar
    bar.style.width = svc.uptime_percent + '%';
    bar.className   = 'bar-fill ' + st;

    // pct
    pct.textContent = svc.uptime_percent + '%';
    pct.className   = 'bar-pct ' + st;

    // stats
    const card   = bar.closest('.service-card');
    const strongs = card.querySelectorAll('.stat strong');
    if (strongs[0]) strongs[0].textContent = svc.latency !== null ? svc.latency + ' ms' : '—';
    if (strongs[1]) strongs[1].textContent = svc.status_code ?? '—';
    if (strongs[2]) strongs[2].textContent = svc.checked_at;
}

function updateBanner(overall) {
    const banner = document.getElementById('overall-banner');
    const title  = document.getElementById('banner-title');
    const sub    = document.getElementById('banner-sub');
    const map = {
        all_operational: { cls: 'ok',    title: 'Semua layanan beroperasi normal',           sub: 'Seluruh infrastruktur berjalan dengan baik.' },
        partial_outage:  { cls: 'warn',  title: 'Sebagian layanan mengalami gangguan',       sub: 'Beberapa layanan sedang dalam penanganan.' },
        major_outage:    { cls: 'error', title: 'Semua layanan tidak dapat dijangkau',        sub: 'Tidak ada layanan yang dapat dijangkau saat ini.' },
    };
    const info = map[overall] || map.major_outage;
    banner.className   = 'banner ' + info.cls;
    title.textContent  = info.title;
    sub.textContent    = info.sub;
}

// animasi bar saat load
window.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.bar-fill').forEach(b => {
        const w = b.style.width;
        b.style.width = '0%';
        setTimeout(() => b.style.width = w, 150);
    });
});

setInterval(refreshStatus, INTERVAL);
</script>
</body>
</html>
