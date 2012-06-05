<? 
$this->load->view('header');
$this->load->view('feeds',array('login_data' => $login_data, 'feeds' => $feeds)); 
$this->load->view('footer');
_writelog("DEBUG","splash_message_mod view loaded"); 
_writelog("DEBUG","UUID: " . ($login_data['UUID'] == '' ? 'Null' : $login_data['UUID'])); 
?>

