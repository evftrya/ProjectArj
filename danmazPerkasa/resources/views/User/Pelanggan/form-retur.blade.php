@extends('layouts.BasicPage1')

@section('css')
<link rel="stylesheet" href="{{ app()->environment('local')? asset('css/ManageProduct.css') : secure_asset('css/ManageProduct.css') }}">
@endsection

@section('content')
<style>
  html, body { overflow-x: hidden; }
  * { box-sizing: border-box; }

  :root{
    --dmz-dark: #4A4947;
    --dmz-accent: #B17457;
    --dmz-soft: #D8D2C2;
    --dmz-white: #ffffff;

    --dmz-border: rgba(74,73,71,.16);
    --dmz-muted: rgba(74,73,71,.78);
    --dmz-shadow: 0 18px 40px rgba(0,0,0,.08);
  }

  .dmz-wrap{ width:100%; padding:24px; display:flex; justify-content:center; background:#fff; }
  .dmz-page{ width:100%; max-width: 980px; }

  .dmz-hero{
    border-radius: 20px;
    border: 1px solid var(--dmz-border);
    background:
      radial-gradient(900px 380px at 15% 10%, rgba(216,210,194,.55), rgba(255,255,255,0)),
      radial-gradient(900px 380px at 85% 30%, rgba(177,116,87,.18), rgba(255,255,255,0)),
      #fff;
    box-shadow: 0 14px 34px rgba(0,0,0,.06);
    padding: 22px;
  }

  .dmz-eyebrow{
    display:inline-flex; align-items:center; gap:10px;
    font-size:12px; letter-spacing:.22em; text-transform:uppercase;
    color: rgba(74,73,71,.72); font-weight:900;
  }
  .dmz-dot{ width:9px; height:9px; border-radius:999px; background: var(--dmz-accent); }

  .dmz-title{ margin:10px 0 6px; font-size:34px; font-weight:900; color: var(--dmz-dark); }
  .dmz-lead{ margin:0; color: var(--dmz-muted); line-height:1.8; font-size:14.5px; }

  .dmz-grid{ margin-top: 14px; display:grid; gap:12px; }
  @media(min-width:980px){ .dmz-grid{ grid-template-columns: 1fr 1fr; align-items:start; } }

  .dmz-card{
    border-radius: 18px;
    border: 1px solid rgba(74,73,71,.16);
    background: rgba(255,255,255,.92);
    box-shadow: var(--dmz-shadow);
    padding: 16px;
  }

  .dmz-boxTitle{
    font-size: 12px;
    font-weight: 900;
    letter-spacing: .16em;
    text-transform: uppercase;
    color: rgba(74,73,71,.92);
    margin-bottom: 10px;
  }

  .dmz-alert{
    border-radius: 16px;
    border: 1px solid rgba(177,116,87,.28);
    background: rgba(177,116,87,.10);
    padding: 12px 12px;
    color: rgba(74,73,71,.92);
    line-height: 1.7;
    font-size: 14px;
  }
  .dmz-alert b{ font-weight: 900; }

  .dmz-steps{ display:grid; gap:10px; }
  .dmz-step{
    display:flex; gap:10px; align-items:flex-start;
    padding: 10px 12px;
    border-radius: 14px;
    border: 1px solid rgba(74,73,71,.12);
    background: rgba(216,210,194,.35);
  }
  .dmz-stepNum{
    min-width: 30px; height: 30px;
    border-radius: 12px;
    display:grid; place-items:center;
    background: rgba(255,255,255,.8);
    border: 1px solid rgba(74,73,71,.16);
    font-weight: 900;
    color: rgba(74,73,71,.92);
  }
  .dmz-stepText{ color: rgba(74,73,71,.88); line-height: 1.65; font-size: 14px; }

  .dmz-label{
    font-size: 11px;
    letter-spacing: .18em;
    text-transform: uppercase;
    font-weight: 900;
    color: rgba(74,73,71,.70);
    margin: 12px 0 6px;
  }

  .dmz-input, .dmz-textarea{
    width:100%;
    border-radius: 14px;
    border: 1px solid rgba(74,73,71,.18);
    background: rgba(255,255,255,.95);
    padding: 12px 12px;
    outline:none;
    font-size: 14px;
    color: rgba(74,73,71,.90);
  }
  .dmz-textarea{ min-height: 120px; resize: vertical; line-height:1.7; }

  .dmz-help{
    margin-top: 6px;
    font-size: 12.5px;
    color: rgba(74,73,71,.62);
    line-height: 1.65;
  }

  .dmz-check{
    margin-top: 10px;
    display:flex; gap:10px; align-items:flex-start;
    padding: 12px;
    border-radius: 14px;
    border: 1px solid rgba(74,73,71,.14);
    background: rgba(255,255,255,.75);
  }
  .dmz-check input{ margin-top: 3px; }
  .dmz-checkText{ font-size: 13.5px; color: rgba(74,73,71,.88); line-height: 1.65; }

  .dmz-actions{ display:flex; gap:10px; flex-wrap:wrap; margin-top: 14px; }
  .dmz-btn{
    cursor:pointer;
    border-radius: 12px;
    padding: 11px 14px;
    font-weight: 900;
    letter-spacing: .10em;
    text-transform: uppercase;
    font-size: 12px;
    border: 1px solid rgba(74,73,71,.22);
    text-decoration:none;
    display:inline-flex; align-items:center; justify-content:center;
  }
  .dmz-btnPrimary{ background: var(--dmz-accent); border-color: var(--dmz-accent); color: #fff; }
  .dmz-btnGhost{ background: rgba(255,255,255,.90); color: rgba(74,73,71,.92); }
</style>

<div class="dmz-wrap">
  <div class="dmz-page">
    {{-- HEADER --}}
    <section class="dmz-hero">
      <div class="dmz-eyebrow">
        <span class="dmz-dot"></span>
        RETUR BARANG {{ $data->nama_product }} ( {{ $data->qty_retur }} Produk)
      </div>

      <h1 class="dmz-title">Form Pengajuan Retur</h1>
      <p class="dmz-lead">
        Isi form ini untuk mengajukan retur. Biar cepat diproses, pastikan alasan jelas dan bukti lengkap.
      </p>
    </section>

    <div class="dmz-grid">

      {{-- KIRI: INFO (MUDAH DIPAHAMI) --}}
      <section class="dmz-card">
        <div class="dmz-boxTitle">Mohon Dibaca Dulu</div>

        <div class="dmz-alert">
          <b>Ketentuan Retur</b><br>
          • Retur <b>hanya untuk penukaran barang</b>.<br>
          • <b>Tidak ada pengembalian uang (refund)</b>.<br>
          • <b>Keputusan retur bergantung pada hasil verifikasi bukti.</b><br>
          Jika bukti (foto & video) <b>tidak cukup jelas atau tidak mendukung alasan retur</b>,
          maka pengajuan retur <b>dapat ditolak</b>.
        </div>


        <div style="height:12px"></div>

        <div class="dmz-boxTitle">Yang Harus Disiapkan</div>
        <div class="dmz-steps">
          <div class="dmz-step">
            <div class="dmz-stepNum">1</div>
            <div class="dmz-stepText">
              Tulis <b>alasan retur</b> dengan jelas (contoh: barang cacat di bagian…, salah kirim tipe/warna, kerusakan saat pengiriman).
            </div>
          </div>

          <div class="dmz-step">
            <div class="dmz-stepNum">2</div>
            <div class="dmz-stepText">
              Siapkan <b>bukti foto & video</b> yang menunjukkan masalahnya (wajib ada <b>dua-duanya</b>: foto dan video).
            </div>
          </div>

          <div class="dmz-step">
            <div class="dmz-stepNum">3</div>
            <div class="dmz-stepText">
              Upload bukti ke <b>folder awan</b> milik kamu (Google Drive/Dropbox/OneDrive), lalu siapkan <b>link folder</b>-nya.
              <div class="dmz-help" style="margin-top:6px">
                Contoh: <i>https://drive.google.com/drive/folders/xxxx</i><br>
                Pastikan aksesnya: <b>“Anyone with the link”</b> / <b>“Siapa saja yang memiliki link”</b>.
              </div>
            </div>
          </div>
        </div>
      </section>
      {{-- {{ dd($data) }} --}}

      {{-- KANAN: FORM --}}
      <section class="dmz-card">
        <div class="dmz-boxTitle">Form Retur</div>

        {{-- Ganti action sesuai route kamu --}}
        <form method="POST" action="{{ url('/Retur-Produk/Ajukan') }}">
          @csrf

          <div class="dmz-label">Alasan Barang Diretur</div>
          <input type="hidden" name="id" value="{{ $data->id }}">
          <textarea
            class="dmz-textarea"
            name="alasan_retur"
            placeholder="Tuliskan alasan retur secara jelas. Contoh: body gitar lecet di sisi kanan, pickup tidak berfungsi, salah kirim warna, dll."
            required>{{ old('alasan_retur') }}</textarea>
          <div class="dmz-help">
            Semakin detail, semakin cepat diverifikasi.
          </div>

          <div class="dmz-label">Link Folder Bukti (Foto & Video)</div>
          <input
            class="dmz-input"
            type="url"
            name="link_bukti"
            value="{{ old('link_bukti') }}"
            placeholder="Tempel link folder (contoh: Google Drive folder)"
            required
          >
          <div class="dmz-help">
            Di sini <b>tidak upload file langsung</b>. Cukup isi <b>link folder</b> tempat foto & video bukti berada.
          </div>

          <div class="dmz-check">
            <input type="checkbox" name="confirm_bukti" value="1" required>
            <div class="dmz-checkText">
              Saya sudah menyiapkan <b>foto</b> dan <b>video</b> di dalam folder bukti, dan link folder dapat diakses untuk verifikasi.
            </div>
          </div>

          <div class="dmz-check">
            <input type="checkbox" name="confirm_norefund" value="1" required>
            <div class="dmz-checkText">
              Saya paham bahwa retur ini <b>hanya penukaran barang</b> dan <b>tanpa refund</b>.
            </div>
          </div>

          <div class="dmz-actions">
            <button type="submit" class="dmz-btn dmz-btnPrimary">Kirim Pengajuan Retur</button>
            <a href="{{ url('/') }}" class="dmz-btn dmz-btnGhost">Batal</a>
          </div>
        </form>
      </section>

    </div>

  </div>
</div>
@endsection
