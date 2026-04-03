<!-- Intro Section -->
<div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
    <ol class="carousel-indicators">
        <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
        <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
        <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
    </ol>
    <div class="carousel-inner">
        <div class="carousel-item active">
            <div class="intro route bg-image" style="background-image:url(<?php echo base_url(); ?>gambar/website/bg1.jpg)">
                <div class="overlay-itro"></div>
                <div class="intro-content display-table">
                    <div class="table-cell">
                        <div class="container">
                            <p class="display-6 color-d">Selamat Datang</p>
                            <h1 class="intro-title mb-4">INVS Studio Custom</h1>
                            <p class="intro-subtitle">
                                <span class="text-slider-ps">Kopi Premium, Suasana Nyaman, Pelayanan Terbaik</span>
                                <strong class="text-slider"></strong>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="carousel-item">
            <div class="intro route bg-image" style="background-image:url(<?php echo base_url(); ?>gambar/website/bg2.jpg)">
                <div class="overlay-itro"></div>
                <div class="intro-content display-table">
                    <div class="table-cell">
                        <div class="container">
                            <p class="display-6 color-d">Nikmati Kelezatan</p>
                            <h1 class="intro-title mb-4">Varian Kopi Terbaik</h1>
                            <p class="intro-subtitle">
                                <span class="text-slider-ps">Espresso, Latte, Cappuccino</span>
                                <strong class="text-slider"></strong>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="carousel-item">
            <div class="intro route bg-image" style="background-image:url(<?php echo base_url(); ?>gambar/website/bg3.jpg)">
                <div class="overlay-itro"></div>
                <div class="intro-content display-table">
                    <div class="table-cell">
                        <div class="container">
                            <p class="display-6 color-d">Tempat Nyaman</p>
                            <h1 class="intro-title mb-4">Bersantai di INVS Studio Custom</h1>
                            <p class="intro-subtitle">
                                <span class="text-slider-ps">Ruang Indoor & Outdoor, Wi-Fi Gratis</span>
                                <strong class="text-slider"></strong>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
    </a>
    <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
    </a>
</div>

<!-- Layanan Kami -->
<section id="service" class="services-mf route">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="title-box text-center">
                    <h3 class="title-a">Selamat Datang di INVS Studio Custom!</h3>
                    <p class="subtitle-a">Nikmati Cita Rasa Kopi dan Suasana yang Nyaman</p>
                    <div class="line-mf"></div>
                </div>
            </div>
        </div>
        
        <!-- Koleksi Terbaru -->
        <div class="row">
            <div class="col-sm-12">
                <div class="title-box text-center">
                    <h3 class="title-a">Koleksi Terbaru Kami</h3>
                    <p class="subtitle-a">Rasakan kopi yang baru diseduh, kue-kue lezat, dan menu baru kami. Temukan cita rasa unik yang tidak Anda temukan di tempat lain!</p>
                    <div class="line-mf"></div>
                </div>
            </div>
        </div>

        <!-- Galeri -->
        <section id="work" class="portfolio-mf sect-pt4 route">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="title-box text-center">
                            <h3 class="title-a">Galeri Kami</h3>
                            <p class="subtitle-a">Sekilas tentang kenyamanan di INVS Studio Custom.</p>
                            <div class="line-mf"></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <?php foreach ($portofolio as $p) : ?>
                        <div class="col-md-4">
                            <div class="work-box">
                                <a href="<?php echo base_url('portofolio/') . $p->portofolio_slug; ?>">
                                    <div class="work-img">
                                        <img src="<?php echo base_url('gambar/portofolio/') . $p->portofolio_foto; ?>" alt="" class="img-fluid">
                                    </div>
                                    <div class="work-content">
                                        <div class="row">
                                            <div class="col-sm-8">
                                                <h2 class="w-title"><?php echo $p->portofolio_nama; ?></h2>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="w-like">
                                                    <span class="ion-ios-plus-outline"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    <?php endforeach ?>
                </div>
            </div>
        </section>

        <!-- Tentang Kami -->
        <div class="row">
            <div class="col-sm-12">
                <div class="title-box text-center">
                    <h3 class="title-a">Tentang Kami</h3>
                    <p class="subtitle-a">Di INVS Studio Custom, kami berkomitmen untuk menyajikan kopi berkualitas tinggi dan suasana yang nyaman. Setiap cangkir kopi menceritakan sebuah kisah, dan kami ingin membantu Anda menemukan kisah Anda di setiap tegukan.</p>
                    <div class="line-mf"></div>
                </div>
            </div>
        </div>

        <!-- Keunggulan Kami -->
        <div class="row">
            <div class="col-sm-12">
                <div class="title-box text-center">
                    <h3 class="title-a">Keunggulan Kami</h3>
                    <p class="subtitle-a">
                        - Praktik Berkelanjutan: Kami menggunakan produk dan metode ramah lingkungan untuk mengurangi dampak lingkungan.<br>
                        - Harga Terjangkau: Nikmati kopi premium tanpa harus merogoh kocek dalam-dalam.<br>
                        - Pilihan Unik: Setiap item di menu kami dirancang untuk menawarkan pengalaman rasa yang unik.
                    </p>
                    <div class="line-mf"></div>
                </div>
            </div>
        </div>

        <!-- Hubungi Kami -->
        <div class="row">
            <div class="col-sm-12">
                <div class="title-box text-center">
                    <h3 class="title-a">Hubungi Kami</h3>
                    <p class="subtitle-a">Punya pertanyaan atau butuh bantuan? Hubungi tim layanan pelanggan kami yang siap membantu Anda.</p>
                    <div class="socials">
                        <ul>
                            <li>
                                <a href="<?php echo $pengaturan->link_facebook; ?>">
                                    <span class="ico-circle"><i class="ion-social-facebook"></i></span>
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo $pengaturan->link_instagram; ?>">
                                    <span class="ico-circle"><i class="ion-social-instagram"></i></span>
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo $pengaturan->link_twitter; ?>">
                                    <span class="ico-circle"><i class="ion-social-twitter"></i></span>
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo $pengaturan->link_whatsapp; ?>">
                                    <span class="ico-circle"><i class="ion-social-whatsapp"></i></span>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="line-mf"></div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Berita -->
<section id="blog" class="blog-mf sect-pt4 route">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="title-box text-center">
                    <h3 class="title-a">Berita</h3>
                    <p class="subtitle-a">Artikel Terbaru Dari Kami</p>
                    <div class="line-mf"></div>
                </div>
            </div>
        </div>
        <div class="row">
            <?php foreach ($artikel as $a) { ?>
                <div class="col-md-4">
                    <div class="card card-blog">
                        <div class="card-img">
                            <a href="<?php echo base_url() . $a->artikel_slug; ?>">
                                <?php if ($a->artikel_sampul != "") { ?>
                                    <img src="<?php echo base_url(); ?>gambar/artikel/<?php echo $a->artikel_sampul ?>" alt="<?php echo base_url() . $a->artikel_judul ?>" class="img-fluid">
                                <?php } ?>
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="card-category-box">
                                <div class="card-category">
                                    <a href="<?php echo base_url() . 'kategori/' . $a->kategori_slug; ?>">
                                        <h6 class="category"><?php echo $a->kategori_nama ?></h6>
                                    </a>
                                </div>
                            </div>
                            <h3 class="card-title">
                                <a href="<?php echo base_url() . $a->artikel_slug ?>"><?php echo $a->artikel_judul ?></a>
                            </h3>
                        </div>
                        <div class="card-footer">
                            <div class="post-author">
                                <span class="author"><?php echo $a->pengguna_nama; ?></span>
                            </div>
                            <div class="post-date">
                                <span class="ion-ios-clock-outline"></span> <?php echo date('D-M-Y', strtotime($a->artikel_tanggal)); ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</section>
<!-- Section Blog End -->
