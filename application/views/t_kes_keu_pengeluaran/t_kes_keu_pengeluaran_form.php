<main id="js-page-content" role="main" class="page-content">
    <div class="row">
        <div class="col-xl-12">
            <div id="panel-1" class="panel">
                <div class="panel-hdr">
                    <h2>INPUT DATA KEUANGAN - PENGELUARAN</h2>
                    <div class="panel-toolbar">
                        <button class="btn btn-panel" data-action="panel-collapse" data-toggle="tooltip" data-offset="0,10" data-original-title="Collapse"></button>
                        <button class="btn btn-panel" data-action="panel-fullscreen" data-toggle="tooltip" data-offset="0,10" data-original-title="Fullscreen"></button>
                        <button class="btn btn-panel" data-action="panel-close" data-toggle="tooltip" data-offset="0,10" data-original-title="Close"></button>
                    </div>
                </div>
                <div class="panel-container show">
                    <div class="panel-content">
                        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">

                            <table class='table table-striped'>
                                <tr>
                                    <td width='200'>Tanggal Transaksi</td>
                                    <td>
                                        <?php if (empty($tgl_transaksi)) { ?>
                                            <input type="date" id="example-date" class="form-control" name="tgl_transaksi" id="datepicker-1" value="<?php echo $tgl_transaksi; ?>" required />
                                        <?php } else { ?>
                                            <input type="date" id="example-date" class="form-control" name="tgl_transaksi" id="datepicker-1" value="<?php echo $tgl_transaksi; ?>" readonly />
                                        <?php
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td width='200'>Nama Akun</td>
                                    <td>
                                        <?php if (empty($kd_akun)) {
                                            echo select2_dinamis_custom('kd_akun', 'm_akun', 'kode', 'uraian', '', 'LEFT(kode, "1") = "5"', '');
                                        } else {
                                        ?>
                                            <select name="kd_akun" id="kd_akun" class="form-control select2">
                                                <?php foreach ($get_akun as $row) : ?>
                                                    <?php $selected = ''; ?>
                                                    <?php $disabled = 'disabled'; ?>
                                                    <?php if ($row['kode'] == $kd_akun) : ?>
                                                        <?php $selected = 'selected'; ?>
                                                        <?php $disabled = ''; ?>
                                                    <?php endif; ?>
                                                    <option value="<?php echo $row['kode']; ?>" <?php echo $selected; ?><?php echo $disabled; ?>><?php echo $row['uraian']; ?> </option>
                                                <?php endforeach; ?>
                                            </select>
                                        <?php
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td width='200'>Jumlah</td>
                                    <td><input type="text" class="form-control" name="jumlah" id="jumlah" placeholder="Jumlah" value="<?php echo $jumlah; ?>" required /></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td><input type="hidden" name="id" value="<?php echo $id; ?>" />
                                        <button type="submit" class="btn btn-warning waves-effect waves-themed"><i class="fal fa-save"></i> <?php echo $button ?></button>
                                        <a href="<?php echo site_url('t_kes_keu_pengeluaran') ?>" class="btn btn-info waves-effect waves-themed"><i class="fal fa-sign-out"></i> Kembali</a>
                                    </td>
                                </tr>
                            </table>
                        </form>
                        <div>
                            <p style="margin-bottom: 0px; color:red;">
                                - Data transaksi penerimaan yang dikirimkan di-grouping per tanggal transaksi per akun. Data bersifat akumulatif sampai dengan posisi data pada tanggal transaksi berkenaan.
                            </p>
                            <p style="margin-bottom: 0px; color:red;">
                                - Data dikirimkan per periode harian. Data yang dikirimkan termasuk yang belum di SP3B/disahkan.
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</main>
<script src="<?php echo base_url() ?>assets/smartadmin/js/vendors.bundle.js"></script>
<script src="<?php echo base_url() ?>assets/smartadmin/js/app.bundle.js"></script>
<script src="<?php echo base_url() ?>assets/smartadmin/js/formplugins/select2/select2.bundle.js"></script>
<script src="<?php echo base_url() ?>assets/smartadmin/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>
<script src="<?php echo base_url() ?>assets/smartadmin/js/kostum.js"></script>
<script src="<?php echo base_url() ?>assets/smartadmin/js/jquery.mask.min.js"></script>
<!-- <script>
    $(document).ready(function() {
        $('#jumlah').mask('#.##0', {
            reverse: true
        });
    })
</script> -->