<?php
$success = "this->session->flashdata('success')";
$warning = "this->session->flashdata('warning')";
$string = "<main id=\"js-page-content\" role=\"main\" class=\"page-content\">
<div class=\"row\">
    <div class=\"col-xl-12\">
        <div id=\"panel-1\" class=\"panel\">
            <div class=\"panel-hdr\">
                <h2>KELOLA DATA " .  strtoupper($tombol) . "</h2>
                <div class=\"panel-toolbar\">
                    <button class=\"btn btn-panel\" data-action=\"panel-collapse\" data-toggle=\"tooltip\" data-offset=\"0,10\" data-original-title=\"Collapse\"></button>
                    <button class=\"btn btn-panel\" data-action=\"panel-fullscreen\" data-toggle=\"tooltip\" data-offset=\"0,10\" data-original-title=\"Fullscreen\"></button>
                    <button class=\"btn btn-panel\" data-action=\"panel-close\" data-toggle=\"tooltip\" data-offset=\"0,10\" data-original-title=\"Close\"></button>
                </div>
            </div>
            <div class=\"panel-container show\">
                <div class=\"panel-content\">
                    <div class=\"text-center\">
        <?php echo anchor(site_url('" . $c_url . "/create'), '<i class=\"fal fa-plus-square\" aria-hidden=\"true\"></i> Tambah Data', 'class=\"btn btn-primary btn-sm waves-effect waves-themed\"'); ?>";

if ($export_excel == '1') {
    $string .= "\n\t\t<?php echo anchor(site_url('" . $c_url . "/excel'), '<i class=\"fal fa-file-excel\" aria-hidden=\"true\"></i> Export Ms Excel', 'class=\"btn btn-outline-success btn-sm waves-effect waves-themed\"'); ?>";
}
if ($export_word == '1') {
    $string .= "\n\t\t<?php echo anchor(site_url('" . $c_url . "/word'), '<i class=\"fal fa-file-word\" aria-hidden=\"true\"></i> Export Ms Word', 'class=\"btn btn-outline-primary btn-sm waves-effect waves-themed\"'); ?>";
}
if ($export_pdf == '1') {
    $string .= "\n\t\t<?php echo anchor(site_url('" . $c_url . "/pdf'), 'PDF', 'class=\"btn btn-outline-danger btn-sm waves-effect waves-themed\"'); ?>";
}
$string .= "</div>
        <table class=\"table table-bordered table-hover table-striped w-100\" id=\"dt-basic-example\">
            <thead>
                <tr>
                    <th width=\"30px\">No</th>";
foreach ($non_pk as $row) {
    $string .= "\n\t\t    <th>" . label($row['column_name']) . "</th>";
}
$string .= "\n\t\t    <th width=\"200px\">Action</th>
                </tr>
            </thead>";

$column_non_pk = array();
foreach ($non_pk as $row) {
    $column_non_pk[] .= "{\"data\": \"" . $row['column_name'] . "\"}";
}
$col_non_pk = implode(',', $column_non_pk);

$string .= "\n\t
        </table>
        </div>
                </div>
            </div>
        </div>
    </div>
</main>
<script src=\"<?php echo base_url() ?>assets/smartadmin/js/vendors.bundle.js\"></script>
<script src=\"<?php echo base_url() ?>assets/smartadmin/js/app.bundle.js\"></script>
<script src=\"<?php echo base_url() ?>assets/smartadmin/js/datagrid/datatables/datatables.bundle.js\"></script>
<script src=\"<?php echo base_url() ?>assets/smartadmin/js/datagrid/datatables/datatables.export.js\"></script>
<script src=\"<?php echo base_url() ?>assets/smartadmin/js/notifications/toastr/toastr.js\"></script>
<script type=\"text/javascript\">
    toastr.options = {
        \"closeButton\": true,
        \"debug\": false,
        \"newestOnTop\": true,
        \"progressBar\": true,
        \"positionClass\": \"toast-top-center\",
        \"preventDuplicates\": true,
        \"onclick\": null,
        \"showDuration\": 300,
        \"hideDuration\": 100,
        \"timeOut\": 5000,
        \"extendedTimeOut\": 1000,
        \"showEasing\": \"swing\",
        \"hideEasing\": \"linear\",
        \"showMethod\": \"fadeIn\",
        \"hideMethod\": \"fadeOut\"
    }
    <?php if ($" . $success . ") { ?>
        toastr.success(\"<?php echo $" . $success . " ?>\");
        <?php } else if ($" . $warning . ") {  ?>
            toastr.warning(\"<?php echo $" . $warning . " ?>\");
            <?php } ?>
</script>
        <script type=\"text/javascript\">
            $(document).ready(function() {
                $.fn.dataTableExt.oApi.fnPagingInfo = function(oSettings)
                {
                    return {
                        \"iStart\": oSettings._iDisplayStart,
                        \"iEnd\": oSettings.fnDisplayEnd(),
                        \"iLength\": oSettings._iDisplayLength,
                        \"iTotal\": oSettings.fnRecordsTotal(),
                        \"iFilteredTotal\": oSettings.fnRecordsDisplay(),
                        \"iPage\": Math.ceil(oSettings._iDisplayStart / oSettings._iDisplayLength),
                        \"iTotalPages\": Math.ceil(oSettings.fnRecordsDisplay() / oSettings._iDisplayLength)
                    };
                };

                var t = $(\"#dt-basic-example\").dataTable({
                    initComplete: function() {
                        var api = this.api();
                        $('#mytable_filter input')
                                .off('.DT')
                                .on('keyup.DT', function(e) {
                                    if (e.keyCode == 13) {
                                        api.search(this.value).draw();
                            }
                        });
                    },
                    oLanguage: {
                        sProcessing: \"loading...\"
                    },
                    processing: true,
                    serverSide: true,
                    ajax: {\"url\": \"" . $c_url . "/json\", \"type\": \"POST\"},
                    columns: [
                        {
                            \"data\": \"$pk\",
                            \"orderable\": false
                        }," . $col_non_pk . ",
                        {
                            \"data\" : \"action\",
                            \"orderable\": false,
                            \"className\" : \"text-center\"
                        }
                    ],
                    order: [[0, 'desc']],
                    dom: \"<'row mb-3'<'col-sm-12 col-md-6 d-flex align-items-center justify-content-start'f><'col-sm-12 col-md-6 d-flex align-items-center justify-content-end'lB>>\" +
                        \"<'row'<'col-sm-12'tr>>\" +
                        \"<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>\",
                    buttons: [
                        // {
                        //     extend: \"colvis\",
                        //     text: \"Column Visibility\",
                        //     titleAttr: \"Col visibility\",
                        //     className: \"mr-sm-3\"
                        // },
                        {
                            extend: \"pdfHtml5\",
                            text: \"PDF\",
                            //exportOptions: {
                                //columns: [1, 2, 3, 4, 5, 6, 7, 8],
                            //},
                            titleAttr: \"Generate PDF\",
                            className: \"btn-outline-danger btn-sm mr-1\"
                        },
                        {
                            extend: \"excelHtml5\",
                            text: \"Excel\",
                            //exportOptions: {
                                //columns: [1, 2, 3, 4, 5, 6, 7, 8],
                            //},
                            titleAttr: \"Generate Excel\",
                            className: \"btn-outline-success btn-sm mr-1\"
                        },
                        {
                            extend: \"print\",
                            text: \"Print\",
                            //exportOptions: {
                                //columns: [1, 2, 3, 4, 5, 6, 7, 8],
                            //},
                            titleAttr: \"Print Table\",
                            className: \"btn-outline-primary btn-sm\"
                        }
                    ],
                    //-----------------------------------------------------//
                    rowCallback: function(row, data, iDisplayIndex) {
                        var info = this.fnPagingInfo();
                        var page = info.iPage;
                        var length = info.iLength;
                        var index = page * length + (iDisplayIndex + 1);
                        $('td:eq(0)', row).html(index);
                    }
                });
            });
        </script>";


$hasil_view_list = createFile($string, $target . "views/" . $c_url . "/" . $v_list_file);
