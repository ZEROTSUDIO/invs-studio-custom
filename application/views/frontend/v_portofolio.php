<div class="intro intro-single route bg-image" style="background-image: url(<?php echo base_url(); ?>assets_frontend/img/overlay-bg.jpg)">
    <div class="overlay-mf"></div>
    <div class="intro-content display-table">
        <div class="table-cell">
            <div class="container">
                <h2 class="intro-title mb-4">daftar portofolio</h2>
                <ol class="breadcrumb d-flex justify-content-center">
                    <li class="breadcrumb-item">
                        <a href="<?php echo base_url(); ?>">Home</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="<?php echo base_url('portofolio'); ?>">portofolio</a>
                    </li>
                    <li class="breadcrumb-item active">portofolio</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<!--/ Intro Skew End /-->
<!--/ Section Blog-Single Star /-->
<section class="blog-wrapper sect-pt4" id="blog">
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <!-- application/views/portofolio_view.php -->
                <div class="row">
                    <?php foreach ($portofolio as $a) { ?>
                        <div class="col-md-4">
                            <div class="card mb-4 shadow-sm">
                                <div class="card-body">
                                    <?php if ($a->portofolio_foto != "") { ?>
                                        <img src="<?php echo base_url() . 'gambar/portofolio/' . $a->portofolio_foto; ?>" class="img-fluid mb-3" alt="<?php echo $a->portofolio_nama; ?>" style="width: 100%; height: 200px; object-fit: cover;">
                                    <?php } ?>
                                    <h5 class="card-title"><?php echo $a->portofolio_nama; ?></h5>
                                    <a href="<?php echo base_url() .'portofolio/'. $a->portofolio_slug; ?>" class="btn btn-primary">View Details</a>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>

                <nav aria-label="...">
                    <?php echo $this->pagination->create_links(); ?>
                </nav>
            </div>
            <div class="col-md-4">
                <?php $this->load->view('frontend/v_sidebar'); ?>
            </div>
        </div>
    </div>
</section>