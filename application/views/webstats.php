<b>
Total Regions : <?php echo  $this->db->count_all('regions');?><br/>
Total Users : <?php echo $this->db->count_all('UserAccounts');?><br/>
Online Users : <?php echo $this->db->count_all('GridUser WHERE Online = "True" AND Login > (UNIX_TIMESTAMP() - 86400)');?><br/>
Active users (last 30 days) : <?php echo $this->db->count_all('GridUser WHERE Login > (UNIX_TIMESTAMP() - (30*86400))');?>
</b>
<? _writelog("DEBUG","webstats partial view loaded"); ?>
