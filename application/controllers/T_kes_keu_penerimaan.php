<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class T_kes_keu_penerimaan extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        is_login();
        $this->load->model('T_kes_keu_penerimaan_model');
        $this->load->library('form_validation');
        $this->load->library('datatables');
    }

    public function index()
    {
        $data = array(
            'data' => $this->T_kes_keu_penerimaan_model->get_all(),
        );
        $this->template->load('template', 't_kes_keu_penerimaan/t_kes_keu_penerimaan_list', $data);
    }

    public function json()
    {
        header('Content-Type: application/json');
        echo $this->T_kes_keu_penerimaan_model->json();
    }

    public function read($id)
    {
        $row = $this->T_kes_keu_penerimaan_model->get_by_id($id);
        if ($row) {
            $data = array(
                'id' => $row->id,
                'tgl_transaksi' => $row->tgl_transaksi,
                'kd_akun' => $row->kd_akun,
                'jumlah' => $row->jumlah,
                'user' => $row->user,
                'create_date' => $row->create_date,
            );
            $this->template->load('template', 't_kes_keu_penerimaan/t_kes_keu_penerimaan_read', $data);
        } else {
            $this->session->set_flashdata('warning', 'Record Not Found');
            redirect(site_url('t_kes_keu_penerimaan'));
        }
    }

    public function create()
    {
        $data = array(
            'button' => 'Kirim',
            'action' => site_url('t_kes_keu_penerimaan/create_action'),
            'id' => set_value('id'),
            'tgl_transaksi' => set_value('tgl_transaksi'),
            'kd_akun' => set_value('kd_akun'),
            'jumlah' => set_value('jumlah'),
            'user' => set_value('user'),
            'create_date' => set_value('create_date'),
        );
        $this->template->load('template', 't_kes_keu_penerimaan/t_kes_keu_penerimaan_form', $data);
    }

    public function create_action()
    {
        $tanggal = date('Y-m-d', strtotime($this->input->post('tgl_transaksi', TRUE)));
        $data = array(
            'tgl_transaksi' => $tanggal,
            'kd_akun' => $this->input->post('kd_akun', TRUE),
            'jumlah' => $this->input->post('jumlah', TRUE),
        );
        $row = $this->T_kes_keu_penerimaan_model->get_by_param($tanggal, $this->input->post('kd_akun'));
        if (($tanggal == $row->tgl_transaksi) && ($this->input->post('kd_akun') == $row->kd_akun)) {
            $this->T_kes_keu_penerimaan_model->update_kes_keu_penerimaan($data);
        } else {
            $this->T_kes_keu_penerimaan_model->insert_kes_keu_penerimaan($data);
        }
    }

    public function update($tgl_transaksi, $kd_akun)
    {
        $row = $this->T_kes_keu_penerimaan_model->get_by_id($tgl_transaksi, $kd_akun);

        if ($row) {
            $data = array(
                'button' => 'Update',
                'action' => site_url('t_kes_keu_penerimaan/update_action'),
                'id' => set_value('id', $row->id),
                'tgl_transaksi' => set_value('tgl_transaksi', $row->tgl_transaksi),
                'kd_akun' => set_value('kd_akun', $row->kd_akun),
                'jumlah' => set_value('jumlah', $row->jumlah),
                'user' => set_value('user', $row->user),
                'create_date' => set_value('create_date', $row->create_date),
                'get_akun' => $this->T_kes_keu_penerimaan_model->get_akun(),
            );
            $this->template->load('template', 't_kes_keu_penerimaan/t_kes_keu_penerimaan_form', $data);
        } else {
            $this->session->set_flashdata('message', '<div class="alert bg-info-500" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true"><i class="fal fa-times"></i></span>
                    </button><strong> Data Tidak Tersedia</strong></div>');
            redirect(site_url('t_kes_keu_penerimaan'));
        }
    }

    public function update_action()
    {
        $tanggal = date('Y-m-d', strtotime($this->input->post('tgl_transaksi', TRUE)));
        $data = array(
            'tgl_transaksi' => $tanggal,
            'kd_akun' => $this->input->post('kd_akun', TRUE),
            'jumlah' => $this->input->post('jumlah', TRUE),
        );
        $this->T_kes_keu_penerimaan_model->update_kes_keu_penerimaan($data);
        // $row = $this->T_kes_keu_penerimaan_model->get_by_param($tanggal, $this->input->post('kd_akun'));
        // if (($tanggal == $row->tgl_transaksi) && ($this->input->post('kd_akun') == $row->kd_akun)) {
        //     $this->T_kes_keu_penerimaan_model->update_kes_keu_penerimaan($data);
        // } else {
        //     $this->T_kes_keu_penerimaan_model->insert_kes_keu_penerimaan($data);
        // }
    }

    public function delete($id)
    {
        $row = $this->T_kes_keu_penerimaan_model->get_by_id($id);

        if ($row) {
            $this->T_kes_keu_penerimaan_model->delete($id);
            $this->session->set_flashdata('success', ' Delete Record Success');
            redirect(site_url('t_kes_keu_penerimaan'));
        } else {
            $this->session->set_flashdata('warning', 'Record Not Found');
            redirect(site_url('t_kes_keu_penerimaan'));
        }
    }

    //     public function _rules()
    //     {

}

/* End of file T_kes_keu_penerimaan.php */