<?php
/*//require 'Technoutsav2'. '/vendor/autoload.php';
use Twilio\Rest\Client;

// Your Account SID and Auth Token from twilio.com/console
$account_sid = 'AC358652019d0d8b5d3f564ffd51ea88b1';
$auth_token = '55891f2f11f8dd02fc803b6d331d08aa';
// In production, these should be environment variables. E.g.:
// $auth_token = $_ENV["TWILIO_ACCOUNT_SID"]

// A Twilio number you own with SMS capabilities
$twilio_number = "+13024645969";

$client = new Client($account_sid, $auth_token);
$client->messages->create(
    // Where to send a text message (your cell phone?)
    '+918005772672',
    array(
        'from' => $twilio_number,
        'body' => 'I sent this message in under 10 minutes!'
    )
);*/
?>




<?php
include('Database/Database.php');
session_start();
if(!$_SESSION['uid'])
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

?>
<!doctype html>
<html>
<head>
	<title>Location</title>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

</head>

<body style="background:#f5eef8">

<?php


$id = $_SESSION['uid'];
$qry="SELECT accident.deviceid,accident.accidentid,accident.latitude,accident.longitude FROM accident,vehicle where accident.deviceid = vehicle.deviceid and vehicle.userid= $id ";
$stmt = db2_prepare($connect,$qry);
$run=db2_execute($stmt);

while($row=db2_fetch_both($stmt)){
$lat1=$row[2];
$lng1=$row[3];
$devid=$row[0];
$accid=$row[1];
}

/*echo $lat1;
echo $lng1;*/
?>

<div class="page-header" style="background: #49274a;margin-top: 0;color:white">
    <div class="container">
        
        <div class="row">
            <div class="col-md-9">
            <a href="userinfo.php" class="logo" style="float: left; text-decoration: none"><h2 style="color: white;font-size: 37px;">Safety First</h2></a>
                </div>
            
            <div class="col-md-3">
            <form action="" method="post" style="margin-top:5px;">
                
                <button name="logout" type="submit" class="btn btn-danger"><span class="glyphicon glyphicon-user"></span>Log Out</button>
            </form>
            </div>
        </div>
            </div>
      </div>    
    
<div class="col-md-12">
</div>
<marquee style="color: red; font-weight: bold; font-size: 20px; margin-bottom: 10px;">An accident occured</marquee>
<div class="container" style="padding:0;">
    <div class="row" style="margin:0">
        <div class="col-md-12">
            <h4 style="background:#f5eef8;">Map to the Nearest Hospital</h4>
        </div>
    </div>
</div>

<div class='container' style="background:white;margin-bottom:50px;" >
    
	<div class='row'>
        
		<div class='col-md-8' id="direction_map" style="height:325px">
		
		</div>
		<div class="col-md-4" id="right-panel" style="height:325px;overflow-y:scroll">
		</div>
		
	</div>
</div>
<?php
$min=95999999;
$min2=9599999;
$min3=9599999;
$minhpid=999;
$minhpid2=999;
$minhpid3=999;
$minlon3=999999;
$minlon=999999;
$minlon2=999999;

$minlat=99999;
$minlat2=99999;
$minlat3=99999;

$timezonedate= new DateTIme("now", new DateTimeZone('Asia/Kolkata'));
$datetime= $timezonedate-> format('Y-m-d h:i:sa');
$qry="SELECT * FROM hospital";
$stmt = db2_prepare($connect,$qry);
$run=db2_execute($stmt);
if(!$run){
echo "query not run";
}
while($row=db2_fetch_both($stmt)){
$hpid=$row[0];
$lat2=$row[5];
$lng2=$row[6];
$dlat=($lat2-$lat1);
$pi=3.141592654/180;
$dmslat=$dlat*$pi;
$dlon=($lng2-$lng1);
$dmslon=$dlon*$pi;
$dlat1=$lat1*$pi;
$dlat2=$lat2*$pi;
$a = sin($dlat/2) * sin($dlat/2) + sin($dlon/2)*sin($dlon/2)* cos($dlat1)*cos($dlat2);

$c = 2* atan2(sqrt($a),sqrt(abs(1-$a)));
$distance= 6371*$c;
// find minimum

if($distance<$min){
$min3=$min2;
$min2=$min;
$min=$distance;

$minlat3=$minlat2;
$minlat2=$minlat;
$minlon3=$minlon2;
$minlon2=$minlon;


$minlat=$lat2;
$minlon=$lng2;
$minhpid3=$minhpid2;
$minhpid2=$minhpid;
$minhpid=$hpid;
}elseif($distance<$min2){

$minlat3=$minlat2;
$minlon3=$minlon2;

$min3=$min2;
$min2=$distance;
$minlat2=$lat2;
$minlon2=$lng2;
$minhpid3=$minhpid2;
$minhpid2=$hpid;
}elseif($distance<$min3){
$min3=$distance;
$minlat3=$lat2;
$minlon3=$lng2;
$minhpid3=$hpid;
}

}

$qr="SELECT husername FROM hospital WHERE hid= '$minhpid'";
$stmt = db2_prepare($connect,$qr);
$re=db2_execute($stmt);
if(!$re){
echo "final query not run";
}
while($ro=db2_fetch_both($stmt)){
$hpname= $ro[0];
}

?>
<script>
// map with text and direction
function initMap() {
var location= new google.maps.LatLng(<?php echo $lat1; ?>,<?php echo $lng1; ?>);
var directionsService = new google.maps.DirectionsService;
var directionsDisplay = new google.maps.DirectionsRenderer({
      suppressMarkers : true,
	  preserveViewport: true,
});
var map = new google.maps.Map(document.getElementById('direction_map'), {
zoom:11	,
center: location,
});
var accident = new google.maps.MarkerImage('imgs/accident.png');
var help = new google.maps.MarkerImage('imgs/health.png');
//you set your icon for each of the direction points Origin
var marker1 = new google.maps.Marker({
position: new google.maps.LatLng(<?php echo $lat1; ?>,<?php echo $lng1; ?>),
map: map,
icon: accident
});
//you set your icon for each of the direction points Destination,
var marker2 = new google.maps.Marker({
position: new google.maps.LatLng(<?php echo $minlat; ?>,<?php echo $minlon; ?>),
map: map,
icon: help
});
directionsDisplay.setMap(map);
directionsDisplay.setPanel(document.getElementById('right-panel'));
calculateAndDisplayRoute(directionsService, directionsDisplay);
}
function calculateAndDisplayRoute(directionsService, directionsDisplay) {
directionsService.route({
origin: new google.maps.LatLng(<?php echo $lat1; ?>,<?php echo $lng1; ?>),
destination: new google.maps.LatLng(<?php echo $minlat; ?>,<?php echo $minlon; ?>),
travelMode: google.maps.TravelMode.DRIVING
}, function(response, status) {
if (status === 'OK') {
directionsDisplay.setDirections(response);
} else {
window.alert('Directions request failed due to ' + status);
}
});
}
</script>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBK3ulqogBpz8y6XrEZWpnnsoV37TgCkc0&callback=initMap"></script>
<div class="container" style="background:white">
<h2>2nd and 3rd nearest hospital</h2>
<div class="table-responsive">
<table class="table table-striped table-hover text-center">
<tr class="info">
<th class="text-center">Nearest serial</th>
<th class="text-center">Hospital Name</th>
<th class="text-center">latitude</th>
<th class="text-center">Longitude</th>
<th class="text-center">Map Show</th>
</tr>
<?php
$ql="SELECT husername FROM hospital WHERE hid= '$minhpid2'";
$stmt = db2_prepare($connect,$ql);
$res=db2_execute($stmt);
if(!$res){
echo "mquery not run!!";
}
while($r=db2_fetch_both($stmt)){
$hpname2= $r[0];
?>
<tr class="success">
<td><?php echo "2nd" ?></td>
<td><?php echo $hpname2 ?></td>
<td><?php echo $minlat2 ?></td>
<td><?php echo $minlon2 ?></td>
<td><a href="2ndnearesthospitalmap.php?lat2=<?php echo $minlat2 ?>&lon2=<?php echo $minlon2 ?>&lat1=<?php echo $lat1?>&lon1=<?php echo $lng1?>" class="btn btn-success no-rad">Show Map</a></td>
</tr>
<?php } ?>
<?php
$ql="SELECT husername FROM hospital WHERE hid= '$minhpid3'";
$stmt = db2_prepare($connect,$ql);
$result=db2_execute($stmt);
if(!$result){
echo "<h3>There is no 3rd nearest hospital found !!!!!</h3>"."<br>";
}
while($r=db2_fetch_both($stmt)){
$hpname3= $r[0];
?>
<tr class="danger">
<td><?php echo "3rd" ?></td>
<td><?php echo $hpname3 ?></td>
<td><?php echo $minlat3 ?></td>
<td><?php echo $minlon3 ?></td>
<td><a href="3rdnearesthospitalmap.php?lat3=<?php echo $minlat3; ?>&lon3=<?php echo $minlon3; ?>&lat1=<?php echo $lat1; ?>&lon1=<?php echo $lng1; ?>" class="btn btn-success no-rad">Show Map</a></td>
</tr>
<?php } ?>
</table>
</div>
</div>
<div class="container" style="background:white">
<h2>Nearest Police station</h2>
<div class="table-responsive">
<table class="table table-striped table-hover text-center">
<tr class="info">
<th class="text-center">Police Station Name</th>
<th class="text-center">latitude</th>
<th class="text-center">Longitude</th>
<th class="text-center">Map Show</th>
</tr>
<?php
$psmin=999999;
$qry="SELECT * FROM policestation";
$stmt = db2_prepare($connect,$qry);
$run=db2_execute($stmt);
if(!$run){
echo "ps query not run";
}
while($row=db2_fetch_both($stmt)){
$psid=$row[0];
$lat2=$row[5];
$lng2=$row[6];
$dlat=($lat2-$lat1);
$pi=3.141592654/180;
$dmslat=$dlat*$pi;
$dlon=($lng2-$lng1);
$dmslon=$dlon*$pi;
$dlat1=$lat1*$pi;
$dlat2=$lat2*$pi;
$a = sin($dlat/2) * sin($dlat/2) + sin($dlon/2)*sin($dlon/2)* cos($dlat1)*cos($dlat2);
$c = 2* atan2(sqrt($a),sqrt(abs(1-$a)));
$distance= 6371*$c;
// find minimum
if($distance<$psmin){
$psmin=$distance;
$minpsid=$psid;
$minpslat=$lat2;
$minpslon=$lng2;
}
}
// echo $minpslat.$minpslon;
$q="SELECT psusername FROM policestation WHERE psid= $minpsid";
$stmt = db2_prepare($connect,$q);
$res=db2_execute($stmt);
if(!$res){
echo "psm query not run";
}
while($r=db2_fetch_both($stmt)){
$psname= $r[0];
}
?>
<tr class="danger">
<td><?php echo $psname ?></td>
<td><?php echo $minpslat ?></td>
<td><?php echo $minpslon ?></td>
<td><a href="nearestpolicestationmap.php?lat2=<?php echo $minpslat ?>&lon2=<?php echo $minpslon ?>&lat1=<?php echo $lat1?>&lon1=<?php echo $lng1?>&psname=<?php echo $psname; ?>" class="btn btn-success no-rad">Show Map</a></td>
</tr>
</table>
</div>
</div>
    
      
<?php
$uid=$_SESSION['uid'];
$sqlqry= "INSERT INTO rescue VALUES (DEFAULT,'$uid','$accid','$minpsid','$minhpid')";
$stmt = db2_prepare($connect,$sqlqry);
$exec=db2_execute($stmt) or die ("not insert in rescue information");

 
    
?>
	<script src="google_maps.js"></script>
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  
</body>
    <footer>
    <div style="background: #49274a;height: 50px;position: relative;bottom: 0;width:100%;margin-top:100px;color:white;">
      <div class="text-center" style="position: relative;top: 40%">
        <small>&copy; 2016-2019 All rights reserved.</small>
        </div>
    </div>
    </footer>
</html>
