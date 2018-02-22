<?php
  require "dbconnect.php";
  session_start();
  
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
  </div>
</nav>
<div class="container-fluid">        
 <div class="row"> 
  <div class="col-md-12">
    <div class="panel panel-primary">
      <div class="panel-heading">
        <center>
          <h4>PARKING SLOTS</h4>
        </center>

      </div>
      <div class="panel-body" style="padding:0;">
        <table class="table">
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
              $slot_name=$row['slot_name'];
              if($row['status']==="FREE") echo "<tr style='background-color:lightgreen;font-weight:bold;'>";
              if($row['status']==="RESERVED") echo "<tr style='background-color:orange;font-weight:bold;'>";
              if($row['status']==="OCCUPIED") echo "<tr style='background-color:red;font-weight:bold;color:white;'>";
              echo "<td>".$row['slot_name']."</td>";
              echo "<td>".$row['floor']."</td>";
              echo "<td>".$row['status']."</td>";
              if($row['status']==="OCCUPIED" || $row['status']==="RESERVED")
              {

              echo "<td>
                      <button value='$id' class='btn btn-info' style='color:black;' onclick=\"name=prompt('ENTER YOUR NAME: '); location.assign('index.php?slot_id='+this.value+'&person='+name);\" disabled>RESERVE NOW
                      </button>
                    </td>";
              }
              else
              {

              echo "<td>
                      <button value='$id' class='btn btn-info' style='color:black;' onclick=\"name=prompt('ENTER YOUR NAME: '); location.assign('reserve_now.php?slot_id='+this.value+'&person='+name+'&slot_name=$slot_name');\">RESERVE NOW
                      </button>
                    </td>";
              }
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
</div>
</body>
</html>