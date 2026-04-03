<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><b>Data portofolio</b> <small>manajemen portofolio</small> </h1>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-lg-12">
                <a href="<?php echo base_url('dashboard/portofolio'); ?>"><button class="btn btn-sm btn-success">Kembali</button></a>
                <br><br>
                <div class="card card-outline card-info">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-file"></i> Data portofolio <small> Tambah portofolio Baru</small>
                        </h3>
                    </div><!-- /.card-header -->
                    <div class="card-body">
                        <?php
                        foreach ($portofolio as $a) {
                        ?>
                            <form action="<?php echo base_url('dashboard/portofolio_update') ?>" method="post" enctype="multipart/form-data">
                                <div class="row mb-3">
                                    <div class="form-group col-md-9">
                                        <label for="portofolio_nama" class="form-label">Nama portofolio</label>
                                        <input type="hidden" name="id" value="<?php echo $a->portofolio_id; ?>">
                                        <input type="text" class="form-control" id="portofolio_nama" name="portofolio_nama" value="<?php echo $a->portofolio_nama; ?>" required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="form-group col-md-4">
                                        <label for="portofolio_foto" class="form-label">foto portofolio</label>
                                        <input type="file" class="form-control" id="portofolio_foto" name="portofolio_foto">
                                        <?php if (isset($gambar_error)) {

                                            echo $gambar_error;
                                        }

                                        echo form_error('foto');

                                        ?>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="form-group col-md-12">
                                        <label for="portofolio_deskripsi" class="form-label">deskripsi portofolio</label>
                                        <textarea class="form-control" id="summernote" name="portofolio_deskripsi" rows="5" required><?php echo $a->portofolio_deskripsi; ?></textarea>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-12">
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>
                                </div>
                            </form>
                        <?php
                        }
                        ?>
                    </div><!-- /.card-body -->
                </div> <!-- /.card -->
            </div>
        </div>
    </section><!-- /.content -->
</div>