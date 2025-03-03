<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 * Pagination Config Bootstrap 3 CSS Style
 * harviacode.com
 */

$config['query_string_segment'] = 'start';

$config['full_tag_open'] = '<div class="frame-wrap"><nav aria-label="Page navigation example"><ul class="pagination" style="margin-top:0px">';
$config['full_tag_close'] = '</ul></nav></div>';

$config['first_link'] = 'First';
$config['first_tag_open'] = '<li class="page-item"><span class="page-link" href="#">';
$config['first_tag_close'] = '</span></li>';

$config['last_link'] = 'Last';
$config['last_tag_open'] = '<li class="page-item"><span class="page-link" href="#">';
$config['last_tag_close'] = '</span></li>';

$config['next_link'] = 'Next';
$config['next_tag_open'] = '<li class="page-item"><span class="page-link" href="#">';
$config['next_tag_close'] = '</span></li>';

$config['prev_link'] = 'Prev';
$config['prev_tag_open'] = '<li class="page-item"><span class="page-link" href="#">';
$config['prev_tag_close'] = '</span></li>';

$config['cur_tag_open'] = '<li class="page-item active" aria-current="page"><span class="page-link">';
$config['cur_tag_close'] = '<span class="sr-only">(current)</span></span></li>';

$config['num_tag_open'] = '<li class="page-item"><span class="page-link" href="#">';
$config['num_tag_close'] = '</span></li>';


/* End of file pagination.php */