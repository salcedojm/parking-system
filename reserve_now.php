<?php
	require "dbconnect.php";
	session_start();
	date_default_timezone_set("Asia/Manila");
	$key=strtoupper(bin2hex(random_bytes(3)));
	if(isset($_GET['person']))
  	{
	   	$reservation_time=strtoupper(date("l"))." ".strtoupper(date("h:i:s a"));
	    $slot=$_GET['slot_id'];
	    $name=$_GET['person'];
	    echo $name;
	    $sql="SELECT * FROM slots WHERE id=".$slot;
	    $result=$conn->query($sql);
	    $row=$result->fetch_assoc();
	    if($row['status']!="RESERVED" AND $row['status']!="OCCUPIED")
	    {
		    $sql="INSERT INTO reservations(slot_id, name, reservation_time, reservation_key, isread) VALUES($slot, '$name', '$reservation_time', '$key', 'NO')";
		    $conn->query($sql);
		    $sql="UPDATE slots SET status='RESERVED' WHERE id=$slot";
		    $conn->query($sql);
		    $_SESSION['reserved_person']=$name;
		    $_SESSION['slot_name']=$_GET['slot_name'];
		    $_SESSION['reservation_time']=$reservation_time;
		    $_SESSION['key']=$key;
		    header("location:reserve_now.php");
	    }
  	}
?>
<!DOCTYPE html>
<head>
<title>ONLINE PARKING RESERVATION</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="bootstrap-3.3.7-dist/css/bootstrap.min.css">
<link rel="stylesheet" href="bootstrap-3.3.7-dist/css/bootstrap-theme.min.css">
<script src="bootstrap-3.3.7-dist/js/bootstrap.min.js"></script>
</head>
<body>

<div class="container-fluid">
      <div class="row">
      	<div class="col-md-12">
      		<div class="panel panel-primary">
      			<div class="panel-heading">
      				<div class="row">
      					<div class="col-md-2">
      						<a href="index.php" class="btn btn-primary">BACK</a>	
      					</div>
      					<div class="col-md-offset-5"><h4>RESERVATION DETAILS</h4></div>	
      				</div>
      			</div>
      			<div class="panel-body">
      				<p><i>NOTE: PLEASE SHOW THIS AT THE GUARD.</i></p>
      				<H4>
      				<table class="table table-bordered;">
      					<tr>
      						<td>NAME: </td>
      						<td><?php echo @$_SESSION['reserved_person']; ?></td>
      					</tr>
      					<tr>
      						<td>SLOT: </td>
      						<td><?php echo @$_SESSION['slot_name']; ?></td>
      					</tr>
      					<tr>
      						<td>TIME: </td>
      						<td><?php echo @$_SESSION['reservation_time']; ?></td>
      					</tr>
      					<tr>
      						<td>KEY: </td>
      						<td><?php echo @$_SESSION['key']; ?></td>
      					</tr>
      				</table>
      				</H4>
      			</div>
      		</div>
      	</div>
      </div>
</div>
</body>
</html>