<?php
  require "dbconnect.php";
  session_start();
  if(!isset($_SESSION['currentUser']))
  {
    header("location:login.php");
  }
  if(isset($_POST['add_slot']))
  {
    $slot_name=strtoupper(mysqli_real_escape_string($conn, $_POST['slot_name']));
    $slot_floor=strtoupper(mysqli_real_escape_string($conn, $_POST['slot_floor']));
    $sql="INSERT INTO slots(slot_name, floor, status) VALUES('$slot_name', '$slot_floor', 'FREE')";
    $conn->query($sql);
  }
  if(isset($_GET['delete_id']))
  {
    $id=$_GET['delete_id'];
    $sql="DELETE FROM slots WHERE id=$id";
    $conn->query($sql);
    header("location: admin-dashboard.php");
  }
  if(isset($_GET['delete_reservation']))
  {
    $id=$_GET['delete_reservation'];
    $sql="DELETE FROM reservations WHERE slot_id=".$_GET['slot_id'];
    $conn->query($sql);
    $sql="UPDATE slots SET status='FREE' WHERE id=".$_GET['slot_id'];
    $conn->query($sql);
    header("location: admin-dashboard.php");
  }
  if(isset($_GET['occupy_reservation']))
  {
    $id=$_GET['occupy_reservation'];
    $sql="DELETE FROM reservations WHERE slot_id=".$_GET['slot_id'];
    $conn->query($sql);
    $sql="UPDATE slots SET status='OCCUPIED' WHERE id=".$_GET['slot_id'];
    $conn->query($sql);
    header("location: admin-dashboard.php");
  }
  if(isset($_POST['update_status']))
  {
    $status=$_POST['update_status'];
    $id_=$_POST['slot_id'];
    $sql="UPDATE slots SET status='$status' WHERE id=".$id_;
    $conn->query($sql);
    $sql="DELETE FROM reservations WHERE slot_id=".$id_;
    $conn->query($sql);
    header("location: admin-dashboard.php");
  }
  if(isset($_POST['new_password']))
  {
    $password=md5(mysqli_real_escape_string($conn, $_POST['new_password']));
    $sql="UPDATE users SET password='$password'";
    $conn->query($sql);
    header("location: admin-dashboard.php");
  }
?>
<!DOCTYPE html>
<html>
<head>
  <title>ONLINE PARKING RESERVATION SYSTEM</title>
<link rel="stylesheet" href="bootstrap-3.3.7-dist/css/bootstrap.min.css">
<link rel="stylesheet" href="bootstrap-3.3.7-dist/css/bootstrap-theme.min.css">
<script src="jquery-1.11.3.min.js"></script>
<script src="bootstrap-3.3.7-dist/js/bootstrap.min.js"></script>
 
 </head>
 <body>
<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="#">ONLINE PARKING RESERVATION SYSTEM</a>
    </div>
   <ul class="nav navbar-nav" style="float: right;">
      <li class="active"><a href="#" data-toggle="modal" data-target="#settingsModal">CHANGE PASSWORD</a></li>
      <li class="active"><a href="logout.php">LOG-OUT</a></li>
    </ul>

  </div>
</nav>
  
<div class="container-fluid">        
 <div class="row">
  <div class="col-md-8">
    <div class="panel panel-primary">
      <div class="panel-heading">
        <h4>
          <center>RESERVATIONS</center>
        </h4>
      </div>
      <div class="panel-body" style="padding: 0;">
      <div class="row" style="padding:6px;">
        <div class="col-md-12">
          <form class="form-inline" method="POST">
            <label>SEARCH: </label>
            <input type="text" class="form-control" name="search_keyword">
            <input type="submit" name="search_btn" value="SEARCH" class="form-control btn btn-primary">
          </form>
        </div>
      </div>
      <table class="table table-bordered">
      <thead>
        <tr>
          <th>SLOT</th>
          <th>FLOOR</th>
          <th>PERSON</th>
          <th>TIME</th>
          <th>KEY</th>
          <th>UPDATE</th>
        </tr>
      </thead>
      <tbody>
        <?php
          if(isset($_POST['search_keyword']))
          {
            $keyword=$_POST['search_keyword'];
            $sql="SELECT * FROM reservations INNER JOIN slots ON reservations.slot_id=slots.id WHERE name LIKE '%$keyword%' OR reservation_time LIKE '%$keyword%' OR reservation_key LIKE '%$keyword%'";
          }
          else
          {

            $sql="SELECT * FROM reservations INNER JOIN slots ON reservations.slot_id=slots.id";
          }
          $result=$conn->query($sql);
          if($result->num_rows>0)
          {
            while($row=$result->fetch_assoc())
            {
              $reservation_id=$row['id'];
              $slot_name=$row['slot_name'];
              $floor=$row['floor'];
              $person=$row['name'];
              $time=$row['reservation_time'];
              $key=$row['reservation_key'];
              $slot_id=$row['slot_id'];
              echo "<tr>";
              echo "<td>".$slot_name."</td>";
              echo "<td>".$floor."</td>";
              echo "<td>".$person."</td>";
              echo "<td>".$time."</td>";
              echo "<td>".$key."</td>";
                echo "<td>
                        <button class='btn btn-danger' onclick=\"if(confirm('DO YOU WANT TO DELETE SLOT?')) location.assign('admin-dashboard.php?delete_reservation=$reservation_id&slot_id=$slot_id');\">DELETE
                        </button>;
                        <button class='btn btn-info' style='color:black;' onclick=\"if(confirm('DO YOU WANT TO OCCUPY RESERVATION?')) location.assign('admin-dashboard.php?occupy_reservation=$reservation_id&slot_id=$slot_id');\">OCCUPY
                        </button></td>";
              echo "</tr>";
            }
          }
        ?>
      </tbody>
    </table>
      </div>
    </div>
  </div> 
  <div class="col-md-4">
    <div class="panel panel-primary">
      <div class="panel-heading">
        <center>
          <h4>PARKING SLOTS</h4>
        </center><hr>
        <form class="form-inline" method="POST">
        <input type="text" class="form-control" name="slot_name" placeholder="SLOT NAME" required style="width: 150px;">
        <input type="text" class="form-control" name="slot_floor" placeholder="FLOOR" required style="width:120px;">
        <button class="btn btn-primary" name="add_slot" value="ADD">ADD</button>
      </form>
      </div>
      <div class="panel-body" style="padding:0;">
        <table class="table small">
      <thead style="background-color: grey; color:white;">
        <tr>
          <th>SLOT</th>
          <th>FLOOR</th>
          <th>STATUS</th>
          <th>ACTION</th>
        </tr>
        <tbody>
          <?php
            $sql="SELECT * FROM slots";
            $result=$conn->query($sql);
            while($row=$result->fetch_assoc())
            {
              $id=$row['id'];
              if($row['status']==="FREE") echo "<tr style='background-color:lightgreen;font-weight:bold;'>";
              if($row['status']==="RESERVED") echo "<tr style='background-color:orange;font-weight:bold;'>";
              if($row['status']==="OCCUPIED") echo "<tr style='background-color:red;font-weight:bold;color:white;'>";
              echo "<td>".$row['slot_name']."</td>";
              echo "<td>".$row['floor']."</td>";
              echo "<td>";
              echo "<form method='POST'>";
              echo "<input type='hidden' name='slot_id' value='$id'>";
              echo "<select name='update_status' onchange='this.form.submit()' class='form-control'>";
              if($row['status']==="FREE")
                echo "<option value='FREE' selected>FREE</option>";
              else
                echo "<option value='FREE'>FREE</option>";
              if($row['status']==="RESERVED")
                echo "<option value='RESERVED' selected>RESERVED</option>";
              else
                echo "<option value='RESERVED'>RESERVED</option>";

              if($row['status']==="OCCUPIED")
                echo "<option value='OCCUPIED' selected>OCCUPIED</option>";
              else
                echo "<option value='OCCUPIED'>OCCUPIED</option>";
              echo "</select>";
              echo "</form>";
              echo "</td>";
              echo "<td>
                      <button class='btn btn-danger' onclick=\"if(confirm('DO YOU WANT TO DELETE SLOT?')) location.assign('admin-dashboard.php?delete_id=$id');\">DELETE
                      </button>
                    </td>";
              echo "</tr>";
            }
          ?>
        </tbody>
      </thead>
    </table>
      </div>
    </div>
  </div>
 </div>
</div><div id="settingsModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><span class="glyphicon glyphicon-pencil"></span> EDIT PASSWORD</h4>
      </div>
      <div class="modal-body"><center>
        <form class="form-inline" method="POST" onsubmit="return checkPword(this)">
          <table>
            <tr>
              <td>NEW PASSWORD: </td>
              <td>
                <input type="password" class="form-control" name="new_password">
              </td>
            <tr>
              <td>CONFIRM PASSWORD: </td>
              <td>
                <input type="password" class="form-control" name="confirm_password">
              </td>
            </tr>
          </table><hr>
           <div style="margin-left: 60%;">
              <button class="btn btn-primary">SAVE CHANGES</button>
              <button class="btn btn-default" data-dismiss="modal">CLOSE</button>
          </div>
        </form></center>
      </div>
    </div>
  </div>
</div>
<script>
  setInterval(update_now, 500);
  function checkPword(frm)
    {
      if(frm.new_password.value==frm.confirm_password.value)
      {
          return true;
      }
      else
      {
        alert("PASSWORD MISMATCH");
        return false;
      }
    }
    function update_now()
    {
      $.post('update_now.php',
        function(data, status)
        {
          if(parseInt(data)>0)
          {
            location.reload();
          }
        })
    }
</script>
</body>
</html>