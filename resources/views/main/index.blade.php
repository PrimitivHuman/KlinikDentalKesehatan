@extends('main.layout.main')

@section('content')
<!-- ======= Hero Section ======= -->
<section id="hero">
    <div id="heroCarousel" data-bs-interval="5000" class="carousel slide carousel-fade" data-bs-ride="carousel">

        <ol class="carousel-indicators" id="hero-carousel-indicators"></ol>

        <div class="carousel-inner" role="listbox">

            <!-- Slide 1 -->
            <div class="carousel-item active" style="background-image: url(assets/img/slide/slide-1.jpg)">
                <div class="container">
                    <h2>Welcome to <span>Family Dental Care</span></h2>
                    <p></p>
                </div>
            </div>

            <!-- Slide 2 -->
            <div class="carousel-item" style="background-image: url(assets/img/slide/slide-2.jpg)">
                <div class="container">
                    <h2>Welcome to <span>Fam ily Dental Care</span></h2>
                    <p></p>
                </div>
            </div>

            <!-- Slide 3 -->
            <div class="carousel-item" style="background-image: url(assets/img/slide/slide-3.jpg)">
                <div class="container">
                    <h2>Welcome to <span>Family Dental Care</span></h2>
                    <p></p>
                </div>
            </div>

        </div>

        <a class="carousel-control-prev" href="#heroCarousel" role="button" data-bs-slide="prev">
            <span class="carousel-control-prev-icon bi bi-chevron-left" aria-hidden="true"></span>
        </a>

        <a class="carousel-control-next" href="#heroCarousel" role="button" data-bs-slide="next">
            <span class="carousel-control-next-icon bi bi-chevron-right" aria-hidden="true"></span>
        </a>

    </div>
</section><!-- End Hero -->

<main id="main">

    <!-- ======= Cta Section ======= -->
    <section id="cta" class="cta">
        <div class="container" data-aos="zoom-in">

            <div class="text-center">
                <h3>Keadaan darurat? Butuh bantuan?</h3>
                <p> Pasien yang akan berkunjung silahkan melakukan Appointment dibawah ini!
                </p>
                <a class="cta-btn scrollto" href="/appointment">Make an Appointment</a>
            </div>

        </div>
    </section><!-- End Cta Section -->

    <!-- ======= About Us Section ======= -->
    <section id="about" class="about">
        <div class="container" data-aos="fade-up">

            <div class="section-title">
                <h2>Family Dental Care</h2>
                <p>{!! html_entity_decode($tentang?->informasi_umum ?? '') !!}</p>
            </div>

            <div class="row">
                <div class="col-lg-6" data-aos="fade-right">
                    <img src="{{ asset('main/img/logo/'.($tentang?->foto_sampul ?? '')) }}" class="img-fluid" alt="" height="100%" width="100%">
                </div>
                <div class="col-lg-6 pt-4 pt-lg-0 content" data-aos="fade-left">
                    <h3>Visi</h3>
                    <p class="fst-italic">
                        {!! html_entity_decode($tentang?->visi ?? '') !!}
                    </p>
                    <h3>Misi</h3>
                    <ul>
                        {!! html_entity_decode($tentang?->misi ?? '') !!}
                    </ul>
                </div>
            </div>

        </div>
    </section><!-- End About Us Section -->

    <!-- ======= Services Section ======= -->
    <section id="services" class="services services">
        <div class="container" data-aos="fade-up">

            <div class="section-title">
                <h2>Layanan Kami</h2>
                <p>Family Dental Care memiliki beragam layanan perawatan gigi profesional. Segera konsultasikan kebutuhan gigi Anda bersama kami.</p>
            </div>

            <div class="row">
                @if(isset($layanans) && $layanans->count() > 0)
                    @foreach($layanans as $index => $layanan)
                    <div class="col-lg-4 col-md-6 icon-box" data-aos="zoom-in" data-aos-delay="{{ ($index % 6 + 1) * 100 }}">
                        <div class="icon">
                            @if($layanan->images)
                                <img src="{{ asset('img/layanan/'.$layanan->images) }}" alt="{{ $layanan->nama_layanan }}" style="width:48px;height:48px;object-fit:cover;border-radius:8px;">
                            @else
                                <i class="bx {{ $layanan->ikon ?? 'bx-plus-medical' }}"></i>
                            @endif
                        </div>
                        <h4 class="title"><a href="/appointment">{{ $layanan->nama_layanan }}</a></h4>
                        <p class="description">{{ Str::limit(strip_tags($layanan->deskripsi), 150) }}</p>
                        @if($layanan->harga_mulai)
                        <small class="text-primary fw-semibold">{{ $layanan->harga_range }}</small>
                        @endif
                    </div>
                    @endforeach
                @else
                    {{-- Fallback: layanan statis jika database belum diisi --}}
                    <div class="col-lg-4 col-md-6 icon-box" data-aos="zoom-in" data-aos-delay="100">
                        <div class="icon"><i class="fas fa-hospital-user"></i></div>
                        <h4 class="title"><a href="/appointment">Konsultasi</a></h4>
                        <p class="description">Kontrol rutin untuk memelihara kesehatan gigi dan mulut, mendeteksi masalah gigi sejak dini.</p>
                    </div>
                    <div class="col-lg-4 col-md-6 icon-box" data-aos="zoom-in" data-aos-delay="200">
                        <div class="icon"><i class="fas fa-teeth"></i></div>
                        <h4 class="title"><a href="/appointment">Penambalan Gigi</a></h4>
                        <p class="description">Prosedur penambalan gigi untuk mengembalikan bentuk dan fungsi gigi yang rusak atau berlubang.</p>
                    </div>
                    <div class="col-lg-4 col-md-6 icon-box" data-aos="zoom-in" data-aos-delay="300">
                        <div class="icon"><i class="fas fa-teeth-open"></i></div>
                        <h4 class="title"><a href="/appointment">Pencabutan Gigi</a></h4>
                        <p class="description">Prosedur pencabutan gigi yang bermasalah dan tidak bisa diperbaiki lagi dari gusi.</p>
                    </div>
                @endif
            </div>

        </div>
    </section><!-- End Services Section -->


    <!-- ======= Doctors Section ======= -->
    <section id="doctors" class="doctors section-bg">
        <div class="container" data-aos="fade-up">

            <div class="section-title">
                <h2>Doctors</h2>
            </div>

            <div class="row">
                @foreach ($dokter as $data)
                <div class="col-lg-3 col-md-6 d-flex align-items-stretch mb-4">
                    <div class="member w-100 d-flex flex-column" data-aos="fade-up" data-aos-delay="100">
                        <div class="member-img">
                            <img src="{{ asset('img/dokter/'.$data->images) }}" class="img-fluid" alt="{{ $data->nama_dokter }}">
                        </div>
                        <div class="member-info d-flex flex-column flex-grow-1 justify-content-between">
                            <div>
                                <h4>{{ $data->nama_dokter }}</h4>
                                <span class="badge bg-light text-secondary mt-1 mb-2">{{ $data->sip_dokter && $data->sip_dokter !== '0' ? 'SIP: '.$data->sip_dokter : 'Dokter Spesialis' }}</span>
                            </div>
                            <div class="pt-2 border-top mt-2">
                                <small class="text-muted d-block fw-bold mb-1"><i class="fas fa-clock text-info me-1"></i> Jadwal Praktik:</small>
                                <span class="small text-dark">{{ $data->jadwal_dokter ?? '-' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
    </section>
    <!--End Doctors Section -->


    <!-- ======= Gallery Section ======= -->
    <section id="gallery" class="gallery">
        <div class="container" data-aos="fade-up">

            <div class="section-title">
                <h2>Galeri Foto & Perawatan</h2>
                <p>Dokumentasi hasil perawatan dan fasilitas pelayanan di Klinik FAM Dental Care.</p>
            </div>
            <div class="gallery-slider swiper">
                <div class="swiper-wrapper align-items-stretch">
                    @foreach ($galeri as $data)
                    <div class="swiper-slide">
                        <div class="gallery-item">
                            <a href="{{ asset('/img/gallery/'.$data->images) }}" class="gallery-lightbox">
                                <img src="{{ asset('/img/gallery/'.$data->images) }}" class="img-fluid" alt="{{ strip_tags($data->judul) }}">
                            </a>
                            <h5 class="gallery-title">{!! html_entity_decode($data->judul) !!}</h5>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="swiper-pagination"></div>
            </div>

        </div>
    </section><!-- End Gallery Section -->



    <!-- ======= Contact Section ======= -->
    <section id="contact" class="contact">
        <div class="container" data-aos="fade-up">

            <div class="section-title">
                <h2>Lokasi & Kontak Klinik</h2>
                <p>Kunjungi alamat klinik kami atau hubungi tim customer service kami untuk informasi dan reservasi.</p>
            </div>

            <div class="mb-4 rounded-3 overflow-hidden shadow-sm" style="border: 1px solid #e2f2f3;">
                <iframe style="border:0; width: 100%; height: 380px; display: block;" src="https://maps.google.com/maps?q=family%20dental%20care%20leuwi%20panjang%20bandung&t=&z=15&ie=UTF8&iwloc=&output=embed" frameborder="0" allowfullscreen></iframe>
            </div>

            <div class="row g-4 mt-2">
                <div class="col-lg-4 col-md-6">
                    <div class="info-box d-flex flex-column align-items-center justify-content-between">
                        <div>
                            <i class="bx bx-map"></i>
                            <h3>Alamat Klinik</h3>
                            <p>Jl. Leuwi Panjang No.52a, Situsaeur, Kec. Bojongloa Kidul, Kota Bandung, Jawa Barat 40234</p>
                        </div>
                        <a href="https://maps.google.com/?q=Jl.+Leuwi+Panjang+No.52a,+Bandung" target="_blank" class="btn btn-outline-primary btn-contact">
                            <i class="bx bx-navigation"></i> Buka Google Maps
                        </a>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="info-box d-flex flex-column align-items-center justify-content-between">
                        <div>
                            <i class="bx bx-envelope"></i>
                            <h3>Email Resmi</h3>
                            <p>fdcbandung52@gmail.com</p>
                        </div>
                        <a href="mailto:fdcbandung52@gmail.com" class="btn btn-outline-primary btn-contact">
                            <i class="bx bx-mail-send"></i> Kirim Email
                        </a>
                    </div>
                </div>

                <div class="col-lg-4 col-md-12">
                    <div class="info-box d-flex flex-column align-items-center justify-content-between">
                        <div>
                            <i class="bx bxl-whatsapp"></i>
                            <h3>WhatsApp & Telepon</h3>
                            <p>0852-6637-9191</p>
                        </div>
                        <a href="https://wa.me/6285266379191?text=Halo%20Klinik%20FAM%20Dental%20Care,%20saya%20ingin%20bertanya%20mengenai%20pelayanan%20dan%20reservasi" target="_blank" class="btn btn-success btn-contact">
                            <i class="bx bxl-whatsapp"></i> Chat WhatsApp
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </section><!-- End Contact Section -->

    @endsection