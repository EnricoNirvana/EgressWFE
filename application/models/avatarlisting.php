<?php
Class Avatarlisting extends CI_Model {

	function get_users($num, $offset) {

		$this->db->select("UserAccounts.firstName AS FirstName, UserAccounts.lastName AS LastName, UserAccounts.UserLevel AS UserLevel, UserAccounts.UserTitle AS UserTitle");

		$query = $this->db->get("UserAccounts", $num, $offset);

		return($query);
	}
}
?>
