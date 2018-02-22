<?php
	$server="localhost";
	$username="root";
	$password="jmsalcedo";
	$database="parking_system";
	$conn=new mysqli($server, $username, $password);
	$sql="show databases";
	$db_found=$user_table_found=$slots_table_found=$reservations_table_found=false;

	$result=$conn->query($sql);
	while($row=$result->fetch_assoc()){
		if($row['Database']===$database){
			$db_found=true;
		}
	}
	if($db_found){
		$conn=new mysqli($server, $username, $password, $database);
	}
	else{
		echo "DATABASE NOT FOUND";
		$sql="CREATE database ".$database;
		$conn->query($sql);
		$conn=new mysqli($server, $username, $password, $database);
	}
	$sql="show tables";
	$result=$conn->query($sql);
	while($row=$result->fetch_assoc())
	{
		if($row['Tables_in_parking_system']==="users")
			$user_table_found=true;
		if($row['Tables_in_parking_system']==="slots")
			$slots_table_found=true;
		if($row['Tables_in_parking_system']==="reservations")
			$reservations_table_found=true;
	}
	if(!$user_table_found)
	{
		$sql="CREATE table users(
		id INT(6) PRIMARY KEY NOT NULL AUTO_INCREMENT,
		username VARCHAR(100) NOT NULL,
		password VARCHAR(100) NOT NULL)";
		$conn->query($sql);
		$username="admin";
		$password=md5("admin");
		$sql="INSERT INTO users(username, password) VALUES('$username', '$password')";
		$conn->query($sql);
	}
	if(!$slots_table_found)
	{
		$sql="CREATE TABLE slots(
		id INT(6) PRIMARY KEY NOT NULL AUTO_INCREMENT,
		slot_name VARCHAR(100) NOT NULL,
		floor VARCHAR(100) NOT NULL,
		status VARCHAR(100) NOT NULL)";
		$conn->query($sql);
	}
	if(!$reservations_table_found)
	{
		$sql="CREATE TABLE reservations(
		id int(6) PRIMARY KEY NOT NULL AUTO_INCREMENT,
		slot_id int(6) NOT NULL,
		name VARCHAR(100) NOT NULL,
		reservation_time VARCHAR(100) NOT NULL,
		reservation_key VARCHAR(100) NOT NULL,
		isread VARCHAR(10) NOT NULL)";
		$conn->query($sql);
	}
?>