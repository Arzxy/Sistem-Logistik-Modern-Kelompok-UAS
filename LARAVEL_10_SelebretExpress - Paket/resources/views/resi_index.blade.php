<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Resi Selebet Express</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Barlow:wght@400;500;600;700;800&family=Barlow+Condensed:wght@600;700;800&display=swap');

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --red:    #e02020;
            --dark:   #1a1a1a;
            --mid:    #3a3a3a;
            --light:  #f5f5f5;
            --border: #e0e0e0;
        }

        body {
            font-family: 'Barlow', sans-serif;
            background: var(--light);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .header {
            text-align: center;
            margin-bottom: 2.5rem;
        }

        .logo-jne {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            background: var(--red);
            color: #fff;
            font-family: 'Barlow Condensed', sans-serif;
            font-size: 2.4rem;
            font-weight: 800;
            letter-spacing: 2px;
            padding: .4rem 1.2rem;
            border-radius: 4px;
            margin-bottom: 1rem;
        }

        .logo-jne span { font-size: 1rem; font-weight: 600; letter-spacing: 0; }
        .header h2 { font-size: 1.5rem; font-weight: 700; color: var(--dark); margin-top: .5rem; }
        .header p  { color: #666; margin-top: .3rem; font-size: .95rem; }

        .card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 24px rgba(0,0,0,.08);
            padding: 2.5rem;
            width: 100%;
            max-width: 480px;
        }

        .form-group { margin-bottom: 1.2rem; }
        label { display: block; font-weight: 600; font-size: .875rem; color: var(--mid); margin-bottom: .5rem; }

        input[type="text"] {
            width: 100%;
            padding: .75rem 1rem;
            border: 2px solid var(--border);
            border-radius: 8px;
            font-family: 'Barlow', sans-serif;
            font-size: 1rem;
            color: var(--dark);
            transition: border-color .2s;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        input[type="text"]:focus { outline: none; border-color: var(--red); }
        input[type="text"]::placeholder { text-transform: none; letter-spacing: 0; color: #aaa; }

        .btn {
            width: 100%;
            padding: .85rem;
            background: var(--red);
            color: #fff;
            border: none;
            border-radius: 8px;
            font-family: 'Barlow Condensed', sans-serif;
            font-size: 1.1rem;
            font-weight: 700;
            letter-spacing: 1px;
            cursor: pointer;
            transition: background .2s, transform .1s;
        }
        .btn:hover  { background: #c01818; }
        .btn:active { transform: scale(.99); }

        .error {
            background: #fff5f5;
            border: 1px solid #ffcccc;
            border-radius: 8px;
            padding: .75rem 1rem;
            color: var(--red);
            font-size: .875rem;
            margin-bottom: 1.2rem;
        }

        .info-strip {
            display: flex;
            gap: 1rem;
            margin-top: 1.5rem;
            flex-wrap: wrap;
        }
        .info-item {
            flex: 1;
            min-width: 120px;
            background: var(--light);
            border-radius: 8px;
            padding: .75rem;
            text-align: center;
            font-size: .8rem;
            color: #555;
        }
        .info-item strong { display: block; color: var(--dark); font-size: 1.5rem; margin-bottom: .2rem; }
    </style>
</head>
<body>

<div class="header">
    <div class="logo-jne">SELEBET <span>EXPRESS</span></div>
    <h2>Cetak Resi Pengiriman</h2>
    <p>Masukkan nomor resi untuk mencetak label pengiriman</p>
</div>

<div class="card">
    @if ($errors->any())
        <div class="error">
            @foreach ($errors->all() as $err) {{ $err }} @endforeach
        </div>
    @endif

    <form action="{{ route('resi.cetak') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="no_resi">Nomor Resi / Airwaybill</label>
            <input
                type="text"
                id="no_resi"
                name="no_resi"
                placeholder="Contoh: JNE123456789ID"
                value="{{ old('no_resi') }}"
                autocomplete="off"
                autofocus
            >
        </div>
        <button type="submit" class="btn">🔍 CARI &amp; CETAK RESI</button>
    </form>

    <div class="info-strip">
        <div class="info-item"><strong>24/7</strong>Layanan Aktif</div>
        <div class="info-item"><strong>A4</strong>Format Cetak</div>
        <div class="info-item"><strong>PDF</strong>Siap Ekspor</div>
    </div>
</div>

</body>
</html>