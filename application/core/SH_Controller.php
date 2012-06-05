<?
abstract class SH_Controller extends CI_Controller {

	public $options;
	
   	public $login_data;
   	
   	public function __construct()
    {    		
		parent::__construct();
		$this->config->load('egress-config');
		$this->options = $this->config->item('options');
		
		_writelog("INFO","Base SimHost Site Controller Loaded");
    }

    public function getLoginData() {
    	return(session_get('login_data'));
    }
    
    public function setLoginData($data) {
    	session_set('login_data',$data);
    }
}
?>
