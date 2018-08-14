<?php 

/**
 * @projectname	 Room Management System <A Case Study: The Computer Center>
 * @author       Thapanon Thongnui <thapanon.t@hotmail.com>
**/

	function getNewID() {
		require "connect.php";

		$sql = $db->prepare("SELECT max(ActivityId) FROM activity");		
		$sql->execute();
			$sql->setFetchMode(PDO::FETCH_ASSOC);
			if ($row = $sql->fetch()) {
				$MaxId=$row["max(ActivityId)"];
			}
			if($MaxId=="NULL") { return 1; }
			else { return $MaxId+1; }
	}
?>
