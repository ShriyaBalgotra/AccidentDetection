<?php
include('Database/Database.php');
//require('phpMQTT.php');
session_start();

if(!$_SESSION['username'])
{
    header("location: index.php");
}
if(isset($_POST['logout']))
{
    if(isset($_COOKIE[session_name()]))
    {
        setcookie(session_name(),'',time()-86400,'/');
    }
    session_unset();
    session_destroy();
    header("location: index.php");
}

if(isset($_GET['devid']))
{
    $devid=$_GET['devid'];
    $query="delete from vehicle where deviceid=$devid";
    $stmt = db2_prepare($connect,$query);
    $result=db2_execute($stmt);
}
if(isset($_POST['not'])){

$id = $_SESSION['uid'];
$qry="SELECT accident.deviceid FROM accident,vehicle where accident.deviceid = vehicle.deviceid and vehicle.userid= $id ";
$stmt = db2_prepare($connect,$qry);
$result=db2_execute($stmt);
    
if(db2_fetch_both($stmt)){
header("Location: location.php");
}
else{
    echo '
    <div class="alert alert-danger" id="errordiv" role="alert" style="">
  No entries for accident of any vehicle found.
  <button type="button" onclick="hider()" class="btn btn-danger" style="float:right;position:relative;top:-7px">x</button>
</div>';

}
}
if(isset($_POST['add']))
{
    $userid=$_SESSION['uid'];
    $lic=$_POST['lic'];
    $query="insert into vehicle(licenceno,userid) VALUES('$lic','$userid')";
    $stmt = db2_prepare($connect,$query);
    $result=db2_execute($stmt);
    if($result){
        //header("location: userinfo.php?addsuccess=true");
        echo '<script type="text/javascript">alert("Vehicle added! It will be shown in the list as soon as we get the location of your vehicle. Thank you.")</script>';
    }
    else
    {
        echo '<script type="text/javascript">alert("Failed to Add! Sorry for the inconvenience, please try after some time.")</script>';
        //header("location:userinfo.php?addsuccess=false");
    }
}



/*$config['server'] = $config['org_id'] . '.messaging.internetofthings.ibmcloud.com';
$config['client_id'] = 'a:' . $config['org_id'] . ':' . $config['app_id'];
$location = array();

// initialize client
$mqtt = new phpMQTT($config['server'], $config['port'], $config['client_id']); 
$mqtt->debug = false;

// connect to broker
if(!$mqtt->connect(true, null, $config['iotp_api_key'], $config['iotp_api_secret'])){
  echo 'ERROR: Could not connect to IoT cloud';
	exit();
} 


$topics['iot-2/evt/status/fmt/json'] = 
  array('qos' => $config['qos'], 'function' => 'getLocation');
$mqtt->subscribe($topics, $config['qos']);

// process messages
while ($mqtt->proc(true)) { 
}

// disconnect
$mqtt->close();

function getLocation($topic, $msg) {
  //$mysqli = $GLOBALS['mysqli'];
  //$deviceid = $config['device_id'];
  $json = json_decode($msg);
  $latitude = $json->latitude;
  $longitude = $json->longitude;
  $deviceid = $json->device_id;
  $button_pressed = $json->button_pressed;
  $query = "Select * from location where deviceid = '$deviceid'";
  $stmt = db2_prepare($connect,$query);
  if(db2_fetch_both($stmt)){
  $qur = "UPDATE LOCATION SET dtime = NOW(), latitude = '$latitude',longitude =  '$longitude' where deviceid = '$deviceid'";
  $stmt = db2_prepare($connect,$qur);
  $run = db2_execute($stmt);
  if (!$run) {
    echo 'ERROR: Cannot Refresh Location';
    //exit();
  }
  }else{
  $query = "INSERT INTO location(userid,deviceid,dtime, latitude, longitude) VALUES ('$id','$deviceid,NOW(), '$latitude', '$longitude')";
  $stmt = db2_prepare($connect,$query);
  $run = db2_execute($stmt);
  if (!$run) {
    echo 'ERROR: Data insertion failed';
    //exit();
  }
  }
}

*/



?>

<!DOCTYPE html>
<html lang="en">
    
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Your info</title>
      <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

      
    <!-- Bootstrap -->

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

  </head>
  <body style="background:#f5eef8">
    <div class="page-header" style="background: #49274a;margin-top: 0">
    <div class="container">
        <div class="row">
            <div class="col-md-9">
            <a href="userinfo.php" class="logo" style="float: left; text-decoration: none"><h2 style="color: white;font-size: 37px;">Safety First</h2></a>
                </div>
            
            <div class="col-md-3">
            <form action="" method="post" style="margin-top:5px;float:left;position:relative;left:-5px">
                <button name="not" type="submit" class="btn btn-info"><span class="glyphicon glyphicon-user"></span>Notification</button>
            </form>
            <form action="" method="post" style="margin-top:5px;">
                <button name="logout" type="submit" class="btn btn-danger"><span class="glyphicon glyphicon-user"></span>Log Out</button>
            </form>
            </div>
        </div>
            </div>
      </div>
  <div class="container"  >
  <? php
    ?>
  <div class="row">
  	<div class="col-md-11">
	<h1 style="font-size:50px;font-family:Georgia;color:#045561">Welcome <?php echo $_SESSION['username']; ?></h1>
	<p class="lead">Your current vehicle's record</p>
	</div>
  </div>
  
    </div>
    <div class="container">
		<div class="row" style="background:white">
        <table class="table table-striped table-hover text-center">
        <thead>
			<tr class="info">
	            <th>Device ID</th>
	            <th>License No</th>
	            <th>Date &amp; Time</th>
	            <th>Longitude</th>
	            <th>Latitude</th>
	            <th>Map Show</th>
	            <th>Delete</th>
	            <th></th>
	        </tr>
		</thead>
        <tbody>
        <?php
        $userid=$_SESSION['uid'];
        $query="select vehicle.deviceid,licenceno,dtime,latitude,longitude from vehicle,location where
         vehicle.userid='$userid' and location.userid='$userid' and vehicle.deviceid=location.deviceid";
         $stmt = db2_prepare($connect,$query);
         $result=db2_execute($stmt);
        if($result)
        {
            
                while($row=db2_fetch_both($stmt))
                {
					$deviceid=$row[0];
					$licno=$row[1];
					$dtime=$row[2];
					$lat=$row[3];
					$lon=$row[4];
				
                   // echo "<tr><td>".$row[deviceid]."</td><td>".$row[licenceno]."</td><td>".
                   //     $row[dtime]."</td><td>".$row[latitude]."</td><td>".$row[longitude].
                    //    "</td><td><a href='vechicle.php?lat1=$row[latitude]&lon1=$row[longitude]' role='button'>Show map</a></td><td><a href='delete.php?userid=$_POST[uid]&deviceid=$row[deviceid]' role='button'>Delete</a>";
                ?>
				<tr style="width:100%">
					<td><?php echo $deviceid ?></td>
					<td><?php echo $licno ?></td>
					<td><?php  echo $dtime?></td>
					<td><?php  echo $lat?></td>
					<td><?php  echo $lon?></td>
					<td><a href="showmap.php?lat=<?php echo $lat?>&lon=<?php echo $lon?>" class="btn btn-success">Show Map</a></td>
					<td><form action="userinfo.php?devid=<?php echo $deviceid ?>" method="post"><input type="submit" class="btn btn-danger" name="delete" value="Delete"></form></td>
				</tr>
				
				<?php
				}
            
        }
        else
        {
            echo db2_conn_error();
        }
        ?>
     </tbody>
      </table>
	  </div>

        <div class="row">
            <div class="col-md-12" style="text-align:center;margin-top:8px">
            <input type="button" class="btn btn-success" onclick="disp()" value="Add vehicle">
            </div>
        </div>
        
        <div class="row" id="addlic" style="display:none">
            <div class="col-md-12">
                <center >
                    <form action="userinfo.php" method="post">
                        
                        <div class="form-group" style="width:30%;margin-top:15px;display:inline-block">
                            <label for="lic">License No</label>
                            <input name="lic" class="form-control" type="text" placeholder="Your vehicle's license number" id="lic">
                        </div>
            
                        <input type="submit" class="btn btn-success" name="add" value="Add" style="margin-bottom:5px">
                        

                    </form>
              
                </center>
            </div>
        
        </div>
        
      </div>
     
    
    <script src="javascript.js"></script>  
      
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    
      
    
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
<!--    <script src="js/bootstrap.min.js"></script>-->
    
    <script type="text/javascript">
    function hider()
        {
            var divid=document.getElementById('errordiv');
            divid.style.display="none";
        }
    </script>  
      
    </body>
    <footer style="background: #49274a;height: 50px;position: fixed;bottom:0;width:100%;color:white">
       
      <div class="text-center" style="position: relative;top: 40%">
        <small>&copy; 2016-2019 All rights reserved.</small>
        </div>
        
    </footer>
    
</html>

