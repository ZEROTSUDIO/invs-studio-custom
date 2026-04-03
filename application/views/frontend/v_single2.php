<!-- application/views/portofolio_detail.php -->
<!--/ Intro Skew Star /-->
<div class="intro intro-single route bg-image" style="background-image: url(<?php echo base_url(); ?>assets_frontend/img/overlay-bg.jpg)">
    <div class="overlay-mf"></div>
    <div class="intro-content display-table">
        <div class="table-cell">
            <div class="container">
                <h2 class="intro-title mb-4">Detail portofolio</h2>
                <ol class="breadcrumb d-flex justify-content-center">
                    <li class="breadcrumb-item">
                        <a href="<?php echo base_url(); ?>">Home</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="<?php echo base_url('layanan'); ?>">portofolio</a>
                    </li>
                    <li class="breadcrumb-item active">Detail portofolio</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<!--/ Intro Skew End /-->
<!--/ Section Blog-Single Star /-->
<section class="product-wrapper sect-pt4" id="product">
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <?php if (count($portofolio) == 0) { ?>
                    <center class="mt-5">portofolio Tidak Ditemukan</center>
                <?php } ?>
                <?php foreach ($portofolio as $a) { ?>
                    <div class="post-box">
                        <div class="post-thumb">
                            <?php if ($a->portofolio_foto != "") { ?>
                                <img src="<?php echo base_url() . '/gambar/portofolio/' . $a->portofolio_foto ?>" class="img-fluid" alt="<?php echo $a->portofolio_nama ?>">
                            <?php } ?>
                        </div>
                        <div class="post-meta">
                            <h1 class="article-title"><?php echo $a->portofolio_nama; ?></h1>
                        </div>
                        <div class="article-content">
                            <p>
                                <?php echo $a->portofolio_deskripsi; ?>
                            </p>
                        </div>
                    </div>
                <?php } ?>
            </div>
            <div class="col-md-4">
                <?php $this->load->view('frontend/v_sidebar'); ?>
            </div>
        </div>
    </div>
</section>
<!--/ Section Blog-Single End /-->