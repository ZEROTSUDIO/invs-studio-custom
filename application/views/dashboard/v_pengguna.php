<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1><b>Data Pengguna</b> <small>manajemen pengguna</small> </h1>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fausers"></i> Data Pengguna
                            </h3>
                        </div><!-- /.card-header -->
                        <div class="card-body">
                            <a href="<?php echo base_url('dashboard/pengguna_tambah'); ?>">
                                <button class="btn btn-sm btn-success">
                                    Tambah Pengguna <i class="fas faplus"></i>
                                </button>
                            </a>
                            <hr>
                            <table class="table table-bordered tablehover">
                                <thead>
                                    <tr>
                                        <th width="1%">No</th>
                                        <th>Nama</th>
                                        <th>Email</th>
                                        <th>Username</th>
                                        <th>Level</th>
                                        <th>Status</th>
                                        <th width="10%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    foreach ($pengguna as $p) {
                                    ?>
                                        <tr>
                                            <td><?php echo $no++; ?></td>
                                            <td><?php echo $p->pengguna_nama; ?></td>
                                            <td><?php echo $p->pengguna_email; ?></td>
                                            <td><?php echo $p->pengguna_username; ?></td>
                                            <td><?php echo $p->pengguna_level; ?></td>
                                            <td>
                                                <?php
                                                if ($p->pengguna_status == 1) {
                                                    echo "Aktif";
                                                } else {
                                                    echo "Tidak Aktif";
                                                }
                                                ?>

                                            </td>
                                            <td>
                                                <a href="<?php echo base_url() . 'dashboard/pengguna_edit/' . $p->pengguna_id; ?>"><button class="btn btn-sm btn-warning"><i class="nav-icon fas fa-edit"></i></button></a>
                                                <a href="<?php echo base_url() . 'dashboard/pengguna_hapus/' . $p->pengguna_id; ?>"><button class="btn btn-sm btn-danger" onclick="return confirm('Yakin Hapus Data Ini ?')"><i class="navicon fas fa-trash"></i></button></a>
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