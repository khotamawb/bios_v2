<?php
$tmp = $_POST['gen'];
$iterator = new MultipleIterator();
$iterator->attachIterator(new ArrayIterator($non_pkx));
$iterator->attachIterator(new ArrayIterator($tmp));
$string = "<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class " . $c . " extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        is_login();
        \$this->load->model('$m');
        \$this->load->library('form_validation');";

if ($jenis_tabel <> 'reguler_table') {
    $string .= "        \n\t\$this->load->library('datatables');";
}

$string .= "
    }";

if ($jenis_tabel == 'reguler_table') {

    $string .= "\n\n    public function index()
    {
        \$q = urldecode(\$this->input->get('q', TRUE));
        \$start = intval(\$this->uri->segment(3));

        if (\$q <> '') {
            \$config['base_url'] = base_url() . '$index.php/c_url/index.html?q=' . urlencode(\$q);
            \$config['first_url'] = base_url() . 'index.php/$c_url/index.html?q=' . urlencode(\$q);
        } else {
            \$config['base_url'] = base_url() . 'index.php/$c_url/index/';
            \$config['first_url'] = base_url() . 'index.php/$c_url/index/';
        }

        \$config['per_page'] = 10;
        \$config['page_query_string'] = FALSE;
        \$config['total_rows'] = \$this->" . $m . "->total_rows(\$q);
        \$$c_url = \$this->" . $m . "->get_limit_data(\$config['per_page'], \$start, \$q);
        \$config['full_tag_open'] = '<ul class=\"pagination justify-content-center\">';
        \$config['full_tag_close'] = '</ul>';
        \$this->load->library('pagination');
        \$this->pagination->initialize(\$config);

        \$data = array(
            '" . $c_url . "_data' => \$$c_url,
            'q' => \$q,
            'pagination' => \$this->pagination->create_links(),
            'total_rows' => \$config['total_rows'],
            'start' => \$start,
        );
        \$this->template->load('template','$c_url/$v_list', \$data);
    }";
} else {

    $string .= "\n\n    public function index()
    {
        \$this->template->load('template','$c_url/$v_list');
    }

    public function json() {
        header('Content-Type: application/json');
        echo \$this->" . $m . "->json();
    }";
}

$string .= "\n\n    public function read(\$id)
    {
        \$row = \$this->" . $m . "->get_by_id(\$id);
        if (\$row) {
            \$data = array(";
foreach ($all as $row) {
    $string .= "\n\t\t'" . $row['column_name'] . "' => \$row->" . $row['column_name'] . ",";
}
$string .= "\n\t    );
            \$this->template->load('template','$c_url/$v_read', \$data);
        } else {
            \$this->session->set_flashdata('warning', 'Record Not Found');
            redirect(site_url('$c_url'));
        }
    }";

foreach ($iterator as list($non_pkx, $tmp)) {
    if ($tmp['test'] == 'upload_file') {
        $string .= "\n\t
        function upload_file()
        {
            \$config['upload_path']          = './assets/upload';
            \$config['allowed_types']        = 'pdf|PDF|xls|XLS|xlsx|XLSX|doc|DOC|docx|DOCX';
            //\$config['detect_mime']          = false;
            //\$configx['max_size']             = 1000;
            //\$config['max_width']            = 1024;
            //\$config['max_height']           = 768;
            \$this->load->library('upload', \$config);
            \$this->upload->do_upload('" . $non_pkx['column_name'] . "');
            return \$this->upload->data();
        }";
    }
}

$string .= "\n\n    public function create()
    {
        \$data = array(
            'button' => 'Create',
            'action' => site_url('$c_url/create_action'),";
foreach ($all as $row) {
    $string .= "\n\t    '" . $row['column_name'] . "' => set_value('" . $row['column_name'] . "'),";
}
$string .= "\n\t);
        \$this->template->load('template','$c_url/$v_form', \$data);
    }

    public function create_action()
    {";
foreach ($iterator as list($non_pkx, $tmp)) {
    if ($tmp['test'] == 'upload_file') {
        $string .= "\n\t \$" . $non_pkx['column_name'] . " = \$this->upload_file();";
    }
}
// \$this->_rules();

// if (\$this->form_validation->run() == FALSE) {
//     \$this->create();
// } else {
$string .= "\n\t\t\$data = array(";
//foreach ($non_pk as $row) {
foreach ($iterator as list($non_pkx, $tmp)) {
    if ($tmp['test'] == 'upload_file') {
        $string .= "\n\t\t'" . $non_pkx['column_name'] . "' => \$" . $non_pkx['column_name'] . "['file_name'],";
    } else {
        $string .= "\n\t\t'" . $non_pkx['column_name'] . "' => \$this->input->post('" . $non_pkx['column_name'] . "',TRUE),";
    }
}
//$oke = alert('alert-info', 'Selamat', 'Data Berhasil Diperbaharui');
$string .= "\n\t    );

            \$this->" . $m . "->insert(\$data);
            \$this->session->set_flashdata('success', 'Create Record Success');
            redirect(site_url('$c_url'));
        //}
    }

    public function update(\$id)
    {
        \$row = \$this->" . $m . "->get_by_id(\$id);

        if (\$row) {
            \$data = array(
                'button' => 'Update',
                'action' => site_url('$c_url/update_action'),";
foreach ($all as $row) {
    $string .= "\n\t\t'" . $row['column_name'] . "' => set_value('" . $row['column_name'] . "', \$row->" . $row['column_name'] . "),";
}
$string .= "\n\t    );
            \$this->template->load('template','$c_url/$v_form', \$data);
        } else {
            \$this->session->set_flashdata('warning', 'Record Not Found');
            redirect(site_url('$c_url'));
        }
    }

    public function update_action()
    {
        // \$this->_rules();

        // if (\$this->form_validation->run() == FALSE) {
        //     \$this->update(\$this->input->post('$pk', TRUE));
        // } else {
            \$data = array(";
foreach ($non_pk as $row) {
    $string .= "\n\t\t'" . $row['column_name'] . "' => \$this->input->post('" . $row['column_name'] . "',TRUE),";
}
$string .= "\n\t    );

            \$this->" . $m . "->update(\$this->input->post('$pk', TRUE), \$data);
            \$this->session->set_flashdata('success', ' Update Record Success');
            redirect(site_url('$c_url'));
        // }
    }

    public function delete(\$id)
    {
        \$row = \$this->" . $m . "->get_by_id(\$id);

        if (\$row) {
            \$this->" . $m . "->delete(\$id);
            \$this->session->set_flashdata('success', ' Delete Record Success');
            redirect(site_url('$c_url'));
        } else {
            \$this->session->set_flashdata('warning', 'Record Not Found');
            redirect(site_url('$c_url'));
        }
    }

//     public function _rules()
//     {";
// foreach ($non_pk as $row) {
//     $int = $row3['data_type'] == 'int' || $row['data_type'] == 'double' || $row['data_type'] == 'decimal' ? '|numeric' : '';
//     $string .= "\n\t\$this->form_validation->set_rules('" . $row['column_name'] . "', '" .  strtolower(label($row['column_name'])) . "', 'trim|required$int');";
// }
// $string .= "\n\n\t\$this->form_validation->set_rules('$pk', '$pk', 'trim');";
// $string .= "\n\t\$this->form_validation->set_error_delimiters('<span class=\"text-danger\">', '</span>');
//     }";

if ($export_excel == '1') {
    $string .= "\n\n    public function excel()
    {
        \$this->load->helper('exportexcel');
        \$namaFile = \"$table_name.xls\";
        \$judul = \"$table_name\";
        \$tablehead = 0;
        \$tablebody = 1;
        \$nourut = 1;
        //penulisan header
        header(\"Pragma: public\");
        header(\"Expires: 0\");
        header(\"Cache-Control: must-revalidate, post-check=0,pre-check=0\");
        header(\"Content-Type: application/force-download\");
        header(\"Content-Type: application/octet-stream\");
        header(\"Content-Type: application/download\");
        header(\"Content-Disposition: attachment;filename=\" . \$namaFile . \"\");
        header(\"Content-Transfer-Encoding: binary \");

        xlsBOF();

        \$kolomhead = 0;
        xlsWriteLabel(\$tablehead, \$kolomhead++, \"No\");";
    foreach ($non_pk as $row) {
        $column_name = label($row['column_name']);
        $string .= "\n\txlsWriteLabel(\$tablehead, \$kolomhead++, \"$column_name\");";
    }
    $string .= "\n\n\tforeach (\$this->" . $m . "->get_all() as \$data) {
            \$kolombody = 0;

            //ubah xlsWriteLabel menjadi xlsWriteNumber untuk kolom numeric
            xlsWriteNumber(\$tablebody, \$kolombody++, \$nourut);";
    foreach ($non_pk as $row) {
        $column_name = $row['column_name'];
        $xlsWrite = $row['data_type'] == 'int' || $row['data_type'] == 'double' || $row['data_type'] == 'decimal' ? 'xlsWriteNumber' : 'xlsWriteLabel';
        $string .= "\n\t    " . $xlsWrite . "(\$tablebody, \$kolombody++, \$data->$column_name);";
    }
    $string .= "\n\n\t    \$tablebody++;
            \$nourut++;
        }

        xlsEOF();
        exit();
    }";
}

if ($export_word == '1') {
    $string .= "\n\n    public function word()
    {
        header(\"Content-type: application/vnd.ms-word\");
        header(\"Content-Disposition: attachment;Filename=$table_name.doc\");

        \$data = array(
            '" . $table_name . "_data' => \$this->" . $m . "->get_all(),
            'start' => 0
        );

        \$this->load->view('" . $c_url . "/" . $v_doc . "',\$data);
    }";
}

if ($export_pdf == '1') {
    $string .= "\n\n    function pdf()
    {
        \$data = array(
            '" . $table_name . "_data' => \$this->" . $m . "->get_all(),
            'start' => 0
        );

        ini_set('memory_limit', '32M');
        \$html = \$this->load->view('" . $c_url . "/" . $v_pdf . "', \$data, true);
        \$this->load->library('pdf');
        \$pdf = \$this->pdf->load();
        \$pdf->WriteHTML(\$html);
        \$pdf->Output('" . $table_name . ".pdf', 'D');
    }";
}

$string .= "\n\n}\n\n/* End of file $c_file */";




$hasil_controller = createFile($string, $target . "controllers/" . $c_file);
