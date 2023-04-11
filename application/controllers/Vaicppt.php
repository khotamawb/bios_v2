<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Vaicppt extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        is_login();
        $this->load->library('form_validation');
        $this->load->library('datatables');
    }

    public function index()
    {
        //$this->load->view('table');
        $this->template->load('template', 'vaicppt');
    }
}
