<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><b>Data Kategori</b> <small>kategori artikel</small> </h1>
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
                            <a href="<?php echo base_url('dashboard/kategori_tambah'); ?>">
                                <button class="btn btn-sm btn-success">Buat Kategori Baru <i class="fas fa-plus"></i>

                                </button>
                            </a>
                        </div>

                        <div class="card-body">

                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th width="1%">No</th>
                                        <th>Nama Kategori</th>
                                        <th>Slud Kategori</th>
                                        <th width="10%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;

                                    foreach ($kategori as $k) {
                                    ?>
                                        <tr>

                                            <td><?php echo $no++; ?></td>
                                            <td><?php echo $k->kategori_nama; ?></td>
                                            <td><?php echo $k->kategori_slug ?></td>
                                            <td>
                                                <a href="<?php echo base_url() . 'dashboard/kategori_edit/' . $k->kategori_id; ?>"><button class="btn btn-sm btn-warning"><i class="nav-icon fas fa-edit"></i></button></a>

                                                <a href="<?php echo base_url() . 'dashboard/kategori_hapus/' . $k->kategori_id; ?>"><button class="btn btn-sm btn-danger" onclick="return confirm('Yakin Hapus Data Ini ?')"><i class="nav-icon fas fa-trash"></i></button></a>

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