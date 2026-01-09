@extends('layouts.BasicPage1')

@section('css')
<link rel="stylesheet" href="{{ app()->environment('local')? asset('css/ManageProduct.css') : secure_asset('css/ManageProduct.css') }}">
@endsection

@section('content')
<style>
    /* =========================
   Prevent horizontal scroll
   ========================= */
html, body { overflow-x: hidden; }
* { box-sizing: border-box; }

/* =========================
   Danmaz Perkasa About Page
   Palette:
   #4A4947 (dark) | #B17457 (accent) | #D8D2C2 (soft) | white | black
   Base: white
   ========================= */
:root{
  --dmz-dark: #4A4947;
  --dmz-accent: #B17457;
  --dmz-soft: #D8D2C2;
  --dmz-white: #ffffff;
  --dmz-black: #000000;

  --dmz-border: rgba(74,73,71,.16);
  --dmz-muted: rgba(74,73,71,.78);
  --dmz-shadow: 0 18px 40px rgba(0,0,0,.08);
}

.dmz-wrap{
  width: 100%;
  min-height: 100%;
  background: var(--dmz-white);
  padding: 24px;
  display: flex;
  justify-content: center;
}

.dmz-page{
  width: 100%;
  max-width: 1120px;
}

/* HERO */
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

.dmz-heroInner{
  display: grid;
  grid-template-columns: 1fr;
  gap: 16px;
}

.dmz-eyebrow{
  display: inline-flex;
  align-items: center;
  gap: 10px;
  font-size: 12px;
  letter-spacing: .22em;
  text-transform: uppercase;
  color: rgba(74,73,71,.72);
  font-weight: 800;
}

.dmz-dot{
  width: 9px;
  height: 9px;
  border-radius: 999px;
  background: var(--dmz-accent);
}

.dmz-title{
  margin: 10px 0 8px;
  font-size: 40px;
  line-height: 1.12;
  color: var(--dmz-dark);
  font-weight: 900;
}

.dmz-lead{
  margin: 0;
  max-width: 900px;
  font-size: 15px;
  line-height: 1.9;
  color: var(--dmz-muted);
}

.dmz-tags{
  margin-top: 12px;
  display: flex;
  gap: 8px;
  flex-wrap: wrap;
}

.dmz-tag{
  background: rgba(255,255,255,.75);
  border: 1px solid rgba(74,73,71,.14);
  color: rgba(74,73,71,.88);
  font-weight: 800;
  font-size: 12px;
  padding: 8px 10px;
  border-radius: 999px;
}

.dmz-cta{
  margin-top: 14px;
  display: flex;
  gap: 10px;
  flex-wrap: wrap;
}

.dmz-btn{
  text-decoration: none;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  padding: 11px 14px;
  border-radius: 12px;
  font-weight: 900;
  letter-spacing: .10em;
  text-transform: uppercase;
  font-size: 12px;
  border: 1px solid rgba(74,73,71,.22);
  transition: transform .12s ease, filter .12s ease, background .12s ease;
}

.dmz-btn:active{ transform: translateY(1px); }

.dmz-btnPrimary{
  background: var(--dmz-accent);
  border-color: var(--dmz-accent);
  color: var(--dmz-white);
}

.dmz-btnPrimary:hover{ filter: brightness(1.05); }

.dmz-btnGhost{
  background: rgba(255,255,255,.85);
  color: var(--dmz-dark);
}

.dmz-btnGhost:hover{
  background: rgba(216,210,194,.55);
}

/* Stats card */
.dmz-statCard{
  border-radius: 18px;
  border: 1px solid rgba(74,73,71,.14);
  background: rgba(255,255,255,.78);
  padding: 16px;
  box-shadow: 0 18px 34px rgba(0,0,0,.06);
}

.dmz-statTitle{
  font-weight: 900;
  color: rgba(74,73,71,.92);
  letter-spacing: .14em;
  text-transform: uppercase;
  font-size: 12px;
  margin-bottom: 10px;
}

.dmz-statList{
  display: grid;
  gap: 10px;
}

.dmz-statItem{
  display: flex;
  align-items: baseline;
  justify-content: space-between;
  gap: 12px;
  padding: 10px 12px;
  border-radius: 14px;
  background: rgba(216,210,194,.45);
  border: 1px solid rgba(74,73,71,.12);
}

.dmz-statKey{
  font-weight: 900;
  color: rgba(74,73,71,.92);
}

.dmz-statVal{
  color: rgba(74,73,71,.78);
  font-size: 13.5px;
}

.dmz-divider{
  height: 1px;
  background: rgba(74,73,71,.14);
  margin: 12px 0;
}

.dmz-miniLabel{
  font-size: 11px;
  letter-spacing: .16em;
  text-transform: uppercase;
  font-weight: 900;
  color: rgba(74,73,71,.68);
}

.dmz-miniValue{
  margin-top: 4px;
  color: rgba(74,73,71,.88);
  font-size: 14px;
  line-height: 1.6;
}

/* Sections */
.dmz-section{
  margin-top: 18px;
}

.dmz-sectionHead{
  margin: 14px 2px 10px;
}

.dmz-h2{
  margin: 0;
  font-size: 18px;
  font-weight: 900;
  color: rgba(74,73,71,.92);
}

.dmz-sub{
  margin: 6px 0 0;
  color: rgba(74,73,71,.72);
  line-height: 1.75;
  font-size: 14px;
}

/* Cards */
.dmz-cards{
  display: grid;
  grid-template-columns: 1fr;
  gap: 12px;
}

.dmz-card{
  border-radius: 18px;
  border: 1px solid rgba(74,73,71,.16);
  background: rgba(255,255,255,.92);
  box-shadow: var(--dmz-shadow);
  padding: 16px;
}

.dmz-cardAccent{
  background: linear-gradient(180deg, rgba(216,210,194,.50), rgba(255,255,255,.92));
}

.dmz-cardTop{
  display: flex;
  align-items: center;
  gap: 10px;
  margin-bottom: 10px;
}

.dmz-cardIcon{
  width: 34px;
  height: 34px;
  border-radius: 14px;
  display: grid;
  place-items: center;
  background: rgba(216,210,194,.75);
  border: 1px solid rgba(74,73,71,.16);
  color: rgba(74,73,71,.92);
  font-weight: 900;
}

.dmz-cardIconAccent{
  background: rgba(177,116,87,.20);
  border-color: rgba(177,116,87,.30);
  color: rgba(74,73,71,.95);
}

.dmz-cardTitle{
  font-size: 12px;
  font-weight: 900;
  letter-spacing: .16em;
  text-transform: uppercase;
  color: rgba(74,73,71,.92);
}

.dmz-list{
  margin: 0;
  padding-left: 18px;
  color: rgba(74,73,71,.86);
  line-height: 1.9;
  font-size: 14px;
}

/* Steps */
.dmz-steps{
  display: grid;
  gap: 10px;
  margin-top: 6px;
}

.dmz-step{
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 10px 12px;
  border-radius: 14px;
  background: rgba(255,255,255,.75);
  border: 1px solid rgba(74,73,71,.12);
}

.dmz-stepDot{
  width: 9px;
  height: 9px;
  border-radius: 999px;
  background: var(--dmz-accent);
}

.dmz-stepText{
  color: rgba(74,73,71,.86);
  font-size: 14px;
  line-height: 1.65;
}

/* Contact */
.dmz-contact{
  margin-top: 10px;
  display: grid;
  grid-template-columns: 1fr;
  gap: 10px;
}

.dmz-contactItem{
  border-radius: 18px;
  border: 1px solid rgba(74,73,71,.14);
  background: rgba(255,255,255,.92);
  box-shadow: 0 14px 30px rgba(0,0,0,.07);
  padding: 14px;
  display: flex;
  gap: 12px;
  align-items: flex-start;
}

.dmz-contactIcon{
  width: 40px;
  height: 40px;
  border-radius: 16px;
  display: grid;
  place-items: center;
  background: rgba(216,210,194,.75);
  border: 1px solid rgba(74,73,71,.16);
  color: rgba(74,73,71,.95);
  font-weight: 900;
}

.dmz-contactLabel{
  font-size: 11px;
  letter-spacing: .18em;
  text-transform: uppercase;
  font-weight: 900;
  color: rgba(74,73,71,.70);
}

.dmz-contactValue{
  margin-top: 5px;
  color: rgba(74,73,71,.92);
  font-size: 14px;
  line-height: 1.6;
}

.dmz-footnote{
  margin-top: 10px;
  font-size: 12.5px;
  color: rgba(74,73,71,.62);
  line-height: 1.65;
}

/* Desktop layout */
@media (min-width: 980px){
  .dmz-heroInner{
    grid-template-columns: 1.25fr .75fr;
    align-items: start;
  }
  .dmz-cards{
    grid-template-columns: repeat(3, 1fr);
  }
  .dmz-contact{
    grid-template-columns: repeat(2, 1fr);
  }
}

</style>
<div class="dmz-wrap">
  <div class="dmz-page">

    {{-- HERO --}}
    <section class="dmz-hero">
      <div class="dmz-heroInner">
        <div class="dmz-heroLeft">
          <div class="dmz-eyebrow">
            <span class="dmz-dot"></span>
            TENTANG KAMI
          </div>

          <h1 class="dmz-title">CV Danmaz Perkasa</h1>

          <p class="dmz-lead">
            CV Danmaz Perkasa adalah toko gitar yang melayani penjualan unit gitar, part & aksesoris,
            serta layanan custom dan setup. Kami fokus pada kualitas, playability, dan experience yang jelas
            dari konsultasi sampai after-sales.
          </p>

          <div class="dmz-tags">
            <span class="dmz-tag">Gitar</span>
            <span class="dmz-tag">Part & Aksesoris</span>
            <span class="dmz-tag">Custom & Setup</span>
          </div>

          <div class="dmz-cta">
            <a class="dmz-btn dmz-btnPrimary" href="{{ url('/Product/AllProduct') }}">Lihat Produk</a>
            <a class="dmz-btn dmz-btnGhost" href="https://wa.me/6282338309541">Hubungi Kami</a>
          </div>
        </div>

        <div class="dmz-heroRight">
          <div class="dmz-statCard">
            <div class="dmz-statTitle">Fokus Layanan</div>

            <div class="dmz-statList">
              <div class="dmz-statItem">
                <div class="dmz-statKey">Quality</div>
                <div class="dmz-statVal">QC sebelum kirim</div>
              </div>
              <div class="dmz-statItem">
                <div class="dmz-statKey">Playability</div>
                <div class="dmz-statVal">Setup yang nyaman</div>
              </div>
              <div class="dmz-statItem">
                <div class="dmz-statKey">Support</div>
                <div class="dmz-statVal">After-sales</div>
              </div>
            </div>

            <div class="dmz-divider"></div>

            <div class="dmz-mini">
              <div class="dmz-miniLabel">Jam Operasional</div>
              <div class="dmz-miniValue">Senin–Sabtu • 10.00–19.00</div>
            </div>
          </div>
        </div>
      </div>
    </section>

    {{-- ABOUT --}}
    <section class="dmz-section">
      <div class="dmz-sectionHead">
        <h2 class="dmz-h2">Tentang Toko</h2>
        <p class="dmz-sub">
          Kami bantu kamu memilih gear yang sesuai style main—bukan sekadar rekomendasi umum.
        </p>
      </div>

      <div class="dmz-cards">
        <div class="dmz-card">
          <div class="dmz-cardTop">
            <div class="dmz-cardIcon">01</div>
            <div class="dmz-cardTitle">Produk</div>
          </div>
          <ul class="dmz-list">
            <li>Gitar & Bass</li>
          </ul>
        </div>

        <div class="dmz-card">
          <div class="dmz-cardTop">
            <div class="dmz-cardIcon">02</div>
            <div class="dmz-cardTitle">Custom</div>
          </div>
          <ul class="dmz-list">
            <li>Pilih Part Sesuai Preferensi</li>
            <li>Wiring / switching sesuai kebutuhan</li>
          </ul>
        </div>

        <div class="dmz-card dmz-cardAccent">
          <div class="dmz-cardTop">
            <div class="dmz-cardIcon dmz-cardIconAccent">03</div>
            <div class="dmz-cardTitle">Cara Kami Kerja</div>
          </div>
          <div class="dmz-steps">
            <div class="dmz-step">
              <div class="dmz-stepDot"></div>
              <div class="dmz-stepText"><a href="https://wa.me/6282338309541?text=Halo%20saya%20ingin%20Berkonsultasi" 
                style="text-decoration: none; color: black;">Konsultasi kebutuhan & budget</a></div>
            </div>
            <div class="dmz-step">
              <div class="dmz-stepDot"></div>
              <div class="dmz-stepText"><a href="/Index" style="text-decoration: none; color: black;">Rekomendasi Guitar</a></div>
            </div>
            <div class="dmz-step">
              <div class="dmz-stepDot"></div>
              <div class="dmz-stepText" style="color: black;">Quality Checking sebelum Dikirim</div>
            </div>
          </div>
        </div>
      </div>
    </section>

    {{-- CONTACT --}}
    <section class="dmz-section">
      <div class="dmz-sectionHead">
        <h2 class="dmz-h2">Contact</h2>
        <p class="dmz-sub">
          Untuk tanya stok, konsultasi spek, atau jadwal setup tolong hubungi kami lewat kontak berikut.
        </p>
      </div>

      <div class="dmz-contact">
        <div class="dmz-contactItem">
          <div class="dmz-contactIcon">☎</div>
          <div>
            <div class="dmz-contactLabel">Telepon / WhatsApp</div>
            <div class="dmz-contactValue">082338309541</div>
          </div>
        </div>

        <div class="dmz-contactItem">
          <div class="dmz-contactIcon">✉</div>
          <div>
            <div class="dmz-contactLabel">Email</div>
            <div class="dmz-contactValue">cs@danmazperkasa.com</div>
          </div>
        </div>

        <div class="dmz-contactItem">
          <div class="dmz-contactIcon">⌂</div>
          <div>
            <div class="dmz-contactLabel">Alamat</div>
            <div class="dmz-contactValue">Jalan Desa Sumberbendo Dusun Lolawang, RT.02/RW.03, Lolawang, Kec. Ngoro, Kabupaten Mojokerto, Jawa Timur 61385</div>
          </div>
        </div>

        <div class="dmz-contactItem">
          <div class="dmz-contactIcon">⏱</div>
          <div>
            <div class="dmz-contactLabel">Jam Operasional</div>
            <div class="dmz-contactValue">Senin–Sabtu • 10.00–19.00</div>
          </div>
        </div>
      </div>

      <div class="dmz-footnote">
        *Silakan ganti nomor/email/alamat sesuai data asli CV Danmaz Perkasa.
      </div>
    </section>

  </div>
</div>
@endsection
