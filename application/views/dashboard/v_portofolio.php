<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><b>Data portofolio</b></h1>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <a href="<?php echo base_url('dashboard/portofolio_tambah'); ?>">
                                <button class="btn btn-sm btn-success">Buat portofolio Baru <i class="fas fa-plus"></i>
                                </button>
                            </a>
                        </div>

                        <div class="card-body">

                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th width="1%">No</th>
                                        <th>Nama portofolio</th>
                                        <th width="10%">Gambar</th>
                                        <th>Deskripsi</th>
                                        <th>slug</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    foreach ($portofolio as $p) {
                                    ?>
                                        <tr>

                                            <td><?php echo $no++; ?></td>
                                            <td><?php echo $p->portofolio_nama; ?></td>
                                            <td>
                                                <img width="100%" class="img-responsive" src="<?php echo base_url() . '/gambar/portofolio/' . $p->portofolio_foto; ?>">
                                            </td>
                                            <td><?php echo $p->portofolio_deskripsi ?></td>
                                            <td><?php echo $p->portofolio_slug ?></td>
                                            <td>
                                                <a target="_blank" href="<?php echo base_url('portofolio/') . $p->portofolio_slug; ?>">
                                                    <button class="btn btn-sm btn-success "><i class="fa fa-eye"></i></button>
                                                </a>
                                                <a href="<?php echo base_url() . 'dashboard/portofolio_edit/' . $p->portofolio_id; ?>"><button class="btn btn-sm btn-warning"><i class="nav-icon fas fa-edit"></i></button></a>

                                                <a href="<?php echo base_url() . 'dashboard/portofolio_hapus/' . $p->portofolio_id; ?>"><button class="btn btn-sm btn-danger" onclick="return confirm('Yakin Hapus Data Ini ?')"><i class="nav-icon fas fa-trash"></i></button></a>

                                            </td>
                                        </tr>
                                    <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>