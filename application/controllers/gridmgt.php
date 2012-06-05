<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Gridmgt extends SH_Controller {
	
	function __construct() {
		parent::__construct();
		
		_writelog("INFO","Gridmgt controller loaded");
	}
	
	function index()
	{
		redirect('splash');
	}
	function browseregions() {
		$CI = get_session();

		_writelog("INFO","Entering Gridmgt/index");

		$route['(altregionlist)/(\d+)'] = '$1/index.php/$2'; 
		$config['base_url'] = base_url().'index.php/altregionlist/';
		$config['uri_segment'] = 2; 
		$config['total_rows'] = $CI->db->count_all('regions');  // since it's a left join, row count will be inherited from the left table
		$config['per_page'] = '5';
		$config['full_tag_open'] = '<div id="pagination">';
		$config['full_tag_close'] = '</div>';

		$offset = (int)$CI->uri->segment(2);
		$CI->pagination->initialize($config);

		$CI->load->model('regionlist');

		$data['results'] = $CI->regionlist->get_regions($config['per_page'],$CI->uri->segment(2));
		
		$CI->table->set_heading('Region Name','X coord','Y coord','Owner First','Owner Last');

		$CI->load->view('header');
		$CI->load->view('altregionlist',$data);
		$CI->load->view('footer');
	}
}
/* End of file altregionlist.php */
/* Location: ./application/controllers/altregionlist.php */
?>