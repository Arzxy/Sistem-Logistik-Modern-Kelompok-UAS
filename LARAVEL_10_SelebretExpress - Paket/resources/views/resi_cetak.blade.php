<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resi Selebet Express — {{ $resi['no_resi'] }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Barlow:wght@400;500;600;700;800&family=Barlow+Condensed:wght@600;700;800;900&family=Share+Tech+Mono&display=swap');

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --red:    #e02020;
            --dark:   #111111;
            --mid:    #444;
            --light:  #f4f4f4;
            --border: #ddd;
        }

        /* ── SCREEN ── */
        body {
            font-family: 'Barlow', sans-serif;
            background: #e8e8e8;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 2rem;
            gap: 1.5rem;
        }

        .screen-toolbar {
            width: 100%;
            max-width: 800px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            padding: .55rem 1.1rem;
            background: #fff;
            border: 2px solid var(--border);
            border-radius: 8px;
            font-family: 'Barlow', sans-serif;
            font-weight: 600;
            font-size: .875rem;
            color: var(--mid);
            text-decoration: none;
            transition: border-color .2s;
        }
        .back-btn:hover { border-color: var(--dark); color: var(--dark); }

        .toolbar-actions { display: flex; gap: .75rem; }

        .btn-print {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            padding: .6rem 1.4rem;
            background: var(--red);
            color: #fff;
            border: none;
            border-radius: 8px;
            font-family: 'Barlow Condensed', sans-serif;
            font-size: 1rem;
            font-weight: 700;
            letter-spacing: .5px;
            cursor: pointer;
            transition: background .2s;
        }
        .btn-print:hover { background: #c01818; }

        /* ── RESI LABEL ── */
        .resi-wrap {
            width: 100%;
            max-width: 800px;
            background: #fff;
            border-radius: 4px;
            box-shadow: 0 8px 40px rgba(0,0,0,.15);
            overflow: hidden;
        }

        /* TOP HEADER BAR */
        .resi-header {
            background: var(--red);
            display: grid;
            grid-template-columns: auto 1fr auto;
            align-items: center;
            gap: 1rem;
            padding: .9rem 1.4rem;
        }

        .logo-box {
            font-family: 'Barlow Condensed', sans-serif;
            font-size: 2.2rem;
            font-weight: 900;
            color: #fff;
            letter-spacing: 2px;
            line-height: 1;
        }
        .logo-box small {
            display: block;
            font-size: .7rem;
            font-weight: 600;
            letter-spacing: 1px;
            opacity: .85;
        }

        .header-service {
            text-align: center;
        }
        .service-name {
            font-family: 'Barlow Condensed', sans-serif;
            font-size: 1.4rem;
            font-weight: 800;
            color: #fff;
            letter-spacing: 1px;
        }
        .service-sub {
            font-size: .75rem;
            color: rgba(255,255,255,.8);
            margin-top: .1rem;
        }

        .header-right {
            text-align: right;
        }
        .resi-number {
            font-family: 'Share Tech Mono', monospace;
            font-size: 1rem;
            color: #fff;
            letter-spacing: 2px;
        }
        .resi-date {
            font-size: .75rem;
            color: rgba(255,255,255,.8);
            margin-top: .2rem;
        }

        /* BARCODE STRIP */
        .barcode-strip {
            background: var(--dark);
            padding: .8rem 1.4rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1.5rem;
        }

        .barcode-svg {
            display: flex;
            align-items: flex-end;
            gap: 2px;
        }
        .barcode-svg span {
            background: #fff;
            display: inline-block;
            height: 40px;
            border-radius: 1px;
        }

        .barcode-text {
            font-family: 'Share Tech Mono', monospace;
            color: #fff;
            font-size: .85rem;
            letter-spacing: 3px;
        }

        /* ROUTE BANNER */
        .route-banner {
            background: var(--light);
            border-bottom: 3px solid var(--border);
            padding: 1rem 1.4rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .route-city {
            font-family: 'Barlow Condensed', sans-serif;
            font-size: 2.4rem;
            font-weight: 900;
            color: var(--dark);
            letter-spacing: 1px;
        }

        .route-arrow {
            font-size: 2rem;
            color: var(--red);
            font-weight: 900;
            flex: 1;
            text-align: center;
        }

        .koli-badge {
            background: var(--dark);
            color: #fff;
            font-family: 'Barlow Condensed', sans-serif;
            font-size: 1.1rem;
            font-weight: 700;
            padding: .4rem .9rem;
            border-radius: 4px;
            letter-spacing: 1px;
        }

        /* BODY GRID */
        .resi-body {
            display: grid;
            grid-template-columns: 1fr 1fr;
            border-bottom: 2px dashed var(--border);
        }

        .address-block {
            padding: 1.2rem 1.4rem;
        }
        .address-block:first-child {
            border-right: 2px dashed var(--border);
        }

        .block-label {
            font-family: 'Barlow Condensed', sans-serif;
            font-size: .75rem;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: var(--red);
            border-bottom: 1px solid var(--border);
            padding-bottom: .4rem;
            margin-bottom: .75rem;
        }

        .addr-name {
            font-size: 1.05rem;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: .35rem;
        }
        .addr-line {
            font-size: .85rem;
            color: var(--mid);
            line-height: 1.5;
            margin-bottom: .15rem;
        }
        .addr-telp {
            margin-top: .5rem;
            font-size: .85rem;
            font-weight: 600;
            color: var(--dark);
        }

        /* DETAIL ROW */
        .detail-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            border-bottom: 2px dashed var(--border);
        }

        .detail-cell {
            padding: .85rem 1.4rem;
            border-right: 1px solid var(--border);
        }
        .detail-cell:last-child { border-right: none; }

        .detail-label {
            font-size: .7rem;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: #888;
            margin-bottom: .25rem;
        }
        .detail-value {
            font-size: .95rem;
            font-weight: 700;
            color: var(--dark);
        }

        /* KETERANGAN */
        .keterangan-row {
            padding: .7rem 1.4rem;
            border-bottom: 2px dashed var(--border);
            display: flex;
            align-items: center;
            gap: .75rem;
        }
        .keterangan-row .detail-label { margin: 0; flex-shrink: 0; }
        .keterangan-row .detail-value { font-size: .9rem; }

        .badge {
            display: inline-block;
            padding: .2rem .6rem;
            border-radius: 4px;
            font-size: .8rem;
            font-weight: 700;
            letter-spacing: .5px;
        }
        .badge-red   { background: #fee2e2; color: var(--red); }
        .badge-green { background: #dcfce7; color: #16a34a; }
        .badge-gray  { background: #f3f4f6; color: #555; }

        /* BIAYA */
        .biaya-row {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            background: var(--dark);
        }
        .biaya-cell {
            padding: .75rem 1.4rem;
            border-right: 1px solid rgba(255,255,255,.1);
        }
        .biaya-cell:last-child { border-right: none; }
        .biaya-cell .detail-label { color: rgba(255,255,255,.55); }
        .biaya-cell .detail-value { color: #fff; font-size: 1rem; }

        /* TRACKING */
        .tracking-section {
            padding: 1rem 1.4rem;
        }

        .section-title {
            font-family: 'Barlow Condensed', sans-serif;
            font-size: 1rem;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: var(--mid);
            border-bottom: 2px solid var(--border);
            padding-bottom: .5rem;
            margin-bottom: .75rem;
        }

        .track-item {
            display: grid;
            grid-template-columns: 160px 130px 1fr;
            align-items: start;
            font-size: .82rem;
            padding: .45rem 0;
            border-bottom: 1px dotted var(--border);
            gap: .75rem;
        }
        .track-item:last-child { border-bottom: none; }
        .track-date  { color: #888; font-family: 'Share Tech Mono', monospace; font-size: .75rem; }
        .track-loc   { color: var(--red); font-weight: 600; }
        .track-stat  { color: var(--dark); }

        /* FOOTER */
        .resi-footer {
            background: var(--light);
            border-top: 2px solid var(--border);
            padding: .6rem 1.4rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: .75rem;
            color: #888;
        }

        .qr-placeholder {
            width: 60px;
            height: 60px;
            border: 2px solid var(--border);
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .55rem;
            color: #aaa;
            text-align: center;
            background: #fff;
            padding: 4px;
            font-weight: 600;
        }

        /* ── PRINT ── */
        @media print {
            body { background: #fff; padding: 0; }
            .screen-toolbar { display: none; }
            .resi-wrap { box-shadow: none; max-width: 100%; border-radius: 0; }
            @page { size: A4; margin: 10mm; }
        }
    </style>
</head>
<body>

{{-- ── TOOLBAR (layar saja) ── --}}
<div class="screen-toolbar">
    <a href="{{ route('resi.index') }}" class="back-btn">← Kembali</a>
    <div class="toolbar-actions">
        <button class="btn-print" onclick="window.print()">🖨️ CETAK RESI</button>
    </div>
</div>

{{-- ── LABEL RESI ── --}}
<div class="resi-wrap">

    {{-- Header --}}
    <div class="resi-header">
        <div class="logo-box">
            SELEBET
            <small>EXPRESS</small>
        </div>
        <div class="header-service">
            <div class="service-name">{{ $resi['layanan'] }}</div>
            <div class="service-sub">Kiriman Paket Dalam Negeri</div>
        </div>
        <div class="header-right">
            <div class="resi-number">{{ $resi['no_resi'] }}</div>
            <div class="resi-date">{{ $resi['tanggal_kirim'] }}</div>
        </div>
    </div>

    {{-- Barcode strip --}}
    <div class="barcode-strip">
        <div class="barcode-svg" id="barcodeSvg"></div>
        <div class="barcode-text">{{ $resi['no_resi'] }}</div>
        <div class="barcode-svg" id="barcodeSvg2"></div>
    </div>

    {{-- Route --}}
    <div class="route-banner">
        <div class="route-city">{{ strtoupper(explode(',', $resi['pengirim']['kota'])[0]) }}</div>
        <div class="route-arrow">→</div>
        <div class="route-city">{{ strtoupper(explode(',', $resi['penerima']['kota'])[0]) }}</div>
        <div class="koli-badge">KOLI {{ $resi['jumlah_koli'] }}/{{ $resi['total_koli'] }}</div>
    </div>

    {{-- Alamat --}}
    <div class="resi-body">
        {{-- Pengirim --}}
        <div class="address-block">
            <div class="block-label">✉ Pengirim</div>
            <div class="addr-name">{{ $resi['pengirim']['nama'] }}</div>
            <div class="addr-line">{{ $resi['pengirim']['alamat'] }}</div>
            <div class="addr-line">{{ $resi['pengirim']['kota'] }}, {{ $resi['pengirim']['provinsi'] }} {{ $resi['pengirim']['kodepos'] }}</div>
            <div class="addr-telp">☎ {{ $resi['pengirim']['telp'] }}</div>
        </div>

        {{-- Penerima --}}
        <div class="address-block">
            <div class="block-label">📍 Penerima</div>
            <div class="addr-name">{{ $resi['penerima']['nama'] }}</div>
            <div class="addr-line">{{ $resi['penerima']['alamat'] }}</div>
            <div class="addr-line">{{ $resi['penerima']['kota'] }}, {{ $resi['penerima']['provinsi'] }} {{ $resi['penerima']['kodepos'] }}</div>
            <div class="addr-telp">☎ {{ $resi['penerima']['telp'] }}</div>
        </div>
    </div>

    {{-- Detail paket --}}
    <div class="detail-grid">
        <div class="detail-cell">
            <div class="detail-label">Berat</div>
            <div class="detail-value">{{ $resi['berat'] }}</div>
        </div>
        <div class="detail-cell">
            <div class="detail-label">Dimensi</div>
            <div class="detail-value">{{ $resi['dimensi'] }}</div>
        </div>
        <div class="detail-cell">
            <div class="detail-label">Isi Paket</div>
            <div class="detail-value">{{ $resi['isi_paket'] }}</div>
        </div>
        <div class="detail-cell">
            <div class="detail-label">Nilai Barang</div>
            <div class="detail-value">{{ $resi['nilai_barang'] }}</div>
        </div>
        <div class="detail-cell">
            <div class="detail-label">Asuransi</div>
            <div class="detail-value">
                <span class="badge {{ $resi['asuransi'] === 'Ya' ? 'badge-green' : 'badge-gray' }}">
                    {{ $resi['asuransi'] }}
                </span>
            </div>
        </div>
        <div class="detail-cell">
            <div class="detail-label">COD</div>
            <div class="detail-value">
                <span class="badge {{ $resi['cod'] === 'Ya' ? 'badge-red' : 'badge-gray' }}">
                    {{ $resi['cod'] }}
                </span>
            </div>
        </div>
    </div>

    {{-- Keterangan --}}
    @if($resi['keterangan'])
    <div class="keterangan-row">
        <div class="detail-label">Keterangan:</div>
        <div class="detail-value">⚠ {{ $resi['keterangan'] }}</div>
    </div>
    @endif

    {{-- Biaya --}}
    <div class="biaya-row">
        <div class="biaya-cell">
            <div class="detail-label">Ongkos Kirim</div>
            <div class="detail-value">{{ $resi['biaya']['ongkir'] }}</div>
        </div>
        <div class="biaya-cell">
            <div class="detail-label">Biaya Asuransi</div>
            <div class="detail-value">{{ $resi['biaya']['asuransi'] }}</div>
        </div>
        <div class="biaya-cell">
            <div class="detail-label">Total Biaya</div>
            <div class="detail-value" style="font-size:1.2rem;">{{ $resi['biaya']['total'] }}</div>
        </div>
    </div>

    {{-- Tracking --}}
    @if(!empty($resi['tracking']))
    <div class="tracking-section">
        <div class="section-title">📦 Riwayat Pengiriman</div>
        @foreach($resi['tracking'] as $track)
        <div class="track-item">
            <span class="track-date">{{ $track['tanggal'] }}</span>
            <span class="track-loc">{{ $track['lokasi'] }}</span>
            <span class="track-stat">{{ $track['status'] }}</span>
        </div>
        @endforeach
    </div>
    @endif

    {{-- Footer --}}
    <div class="resi-footer">
        <div>
            <strong style="color:var(--dark)">Selebet Express</strong><br>
            Hotline: 021-29278888 &nbsp;|&nbsp; selebet.co.id<br>
            Dicetak: {{ now()->format('d/m/Y H:i') }}
        </div>
        <div class="qr-placeholder">
            QR<br>Code<br>{{ $resi['no_resi'] }}
        </div>
    </div>

</div>

{{-- Barcode generator (garis vertikal sederhana) --}}
<script>
    function makeBarcode(containerId, text) {
        const el = document.getElementById(containerId);
        if (!el) return;
        const widths = [2, 1, 3, 1, 2, 1, 1, 3, 2, 1, 3, 1, 2, 1, 1, 3, 2, 1];
        widths.forEach((w, i) => {
            const bar = document.createElement('span');
            bar.style.width = w + 'px';
            bar.style.height = (i % 3 === 0 ? 48 : 40) + 'px';
            bar.style.opacity = i % 2 === 0 ? '1' : '0';
            bar.style.background = '#ffffff';
            el.appendChild(bar);
        });
        // repeat for visual
        for (let j = 0; j < 3; j++) {
            widths.reverse().forEach((w, i) => {
                const bar = document.createElement('span');
                bar.style.width = w + 'px';
                bar.style.height = (i % 4 === 0 ? 48 : 38) + 'px';
                bar.style.opacity = i % 2 === 0 ? '1' : '0';
                bar.style.background = '#ffffff';
                el.appendChild(bar);
            });
        }
    }
    makeBarcode('barcodeSvg', '{{ $resi["no_resi"] }}');
    makeBarcode('barcodeSvg2', '{{ $resi["no_resi"] }}');
</script>

</body>
</html>