<?php
$id_user = $this->session->userdata('id');
$user = $this->db->query("select * from pengguna where pengguna_id='$id_user'")->row();
?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">
                        <b>Dashboard</b> <small>Control Panel</small>
                    </h1>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-4 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3><?php echo $jumlah_artikel; ?></h3>
                            <p>Jumlah Artikel</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-tasks"></i>
                        </div>
                        <a href="<?php echo base_url() . 'dashboard/artikel'; ?>" class="small-box-footer">Selengkapnya <i class="fas fa-arrow-circle-right"></i></a>
                    </div><!-- /.small-box -->
                </div>
                <?php
                    if ($this->session->userdata('level') == "admin") {
                ?>
                <div class="col-lg-4 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3><?php echo $jumlah_kategori; ?></h3>
                            <p>Jumlah Kategori</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-tags"></i>
                        </div>
                        <a href="<?php echo base_url() . 'dashboard/kategori'; ?>" class="small-box-footer">Selengkapnya<i class="fas fa-arrow-circle-right"></i></a>
                    </div><!-- /.small box -->
                </div>
                <div class="col-lg-4 col-6">
                    <!-- small box -->
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3><?php echo $jumlah_pengguna; ?></h3>
                            <p>Jumlah Pengguna</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <a href="<?php echo base_url() . 'dashboard/pengguna'; ?>" class="small-box-footer">Selengkapnya<i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>                
                <?php
                    }
                ?>
            </div>
            <div class="row">
                <section class="col-lg-12 connectedSortable">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-home"></i> Dashboard
                            </h3>
                        </div><!-- /.card-header -->
                        <div class="card-body">
                            <h3>Selamat Datang !</h3>
                            <div class="table-responsive">
                                <table class="table table-borderless table-hover">
                                    <tr>
                                        <th width="10%">Nama</th>
                                        <th width="1%">:</th>
                                        <td>
                                            <?php
                                            $id_user = $this->session->userdata('id');
                                            $user = $this->db->query("select * from pengguna where pengguna_id='$id_user'")->row();
                                            ?>
                                            <p><?php echo $user->pengguna_nama; ?></p> 
                                        </td>
                                    </tr>
                                    <tr>
                                        <th width="10%">Username</th>
                                        <th width="1%">:</th>
                                        <td>
                                            <p><?php echo $this->session->userdata('username') ?></p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th width="10%">Hak Akses</th>
                                        <th width="1%">:</th>
                                        <td>
                                            <p><?php echo $this->session->userdata('level') ?></p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th width="10%">Status</th>
                                        <th width="1%">:</th>
                                        <td>
                                            <p>Aktif</p>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div><!-- /.card-body -->
                    </div>
                </section><!-- /.card -->
            </div>
        </div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<!-- /.content-wrapper -->