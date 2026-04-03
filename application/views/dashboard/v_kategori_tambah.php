<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><b>Data Kategori</b><small>kategori artikel</small> </h1>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 connectedSortable">
                    <a href="<?php echo base_url('dashboard/kategori'); ?>"><button class="btn btn-sm btn-success">Kembali</button></a>
                    <br><br>
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">

                                <i class="fas fa-th"></i> Kategori Artikel <small> Tambah Kategori Baru</small>

                            </h3>
                        </div><!-- /.card-header -->
                        <div class="card-body">
                            <form method="post" action="<?php echo base_url('dashboard/kategori_tambah_aksi'); ?>">
                                <div class="form-group">
                                    <label>Nama Kategori</label>

                                    <input type="text" name="kategori" class="form-control" placeholder="Masukan Nama Kategori . . . " required>

                                    <?php echo form_error('kategori'); ?>
                                </div>
                                <div class="form-group">

                                    <input type="submit" value="Simpan" class="btn btn-sm btn-primary">

                                </div>
                            </form>
                        </div><!-- /.card-body -->
                    </div> <!-- /.card -->
                </div>
            </div>
        </div>
    </section><!-- /.content -->
</div>