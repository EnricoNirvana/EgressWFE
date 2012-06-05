<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Regionlist extends SH_Controller {
	function __construct()
	{
		parent::__construct();
		_writelog("INFO","Regionlist controller loaded");
    }



	function index()
	{
		_writelog("INFO","Entering Reggionlist/index");
		if(isLoggedIn() == FALSE):
			redirect('splash');
		endif;

		$data['query'] = $this->db->query("SELECT regions.regionName AS regionName, regions.locX as locX, regions.locY AS locY, UserAccounts.firstName AS FirstName, UserAccounts.lastName AS LastName FROM regions LEFT JOIN UserAccounts ON UserAccounts.PrincipalID = regions.owner_uuid ORDER BY regionName ASC;");

		$this->load->helper('url');
		$this->load->helper('html');
		$this->load->library('session');

		$this->load->view('regionlist', $data);

       }

}
/* End of file gridmap.php */
/* Location: ./application/controllers/gridmap.php */
?>
