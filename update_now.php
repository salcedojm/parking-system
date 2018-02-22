<?php
	require 'dbconnect.php';
	$sql="SELECT * FROM reservations WHERE isread='NO'";
	$result=$conn->query($sql);
	$sql="UPDATE reservations SET isread='YES'";
	$conn->query($sql);
	echo $result->num_rows;	
?>