<?php
Class Regionlist extends CI_Model {

	function get_regions($num, $offset) {

		$this->db->select("regions.regionName AS regionName, round(regions.locX / 256) as locX, round(regions.locY / 256) AS locY");
		$this->db->select("UserAccounts.firstName AS FirstName, UserAccounts.lastName AS LastName");
		$this->db->join("UserAccounts", "UserAccounts.PrincipalID = regions.owner_uuid","LEFT");
		$this->db->order_by("regionName", "asc"); 

		$query = $this->db->get("regions", $num, $offset);

		return($query);
	}
}
?>
