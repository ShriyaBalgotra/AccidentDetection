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
    $stmt = db2_prepare($connect, $query);
    $result = db2_execute($stmt);
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
<div class="page-header" style="background: #49274a;margin-top: 0;color:white;margin-bottom:20px">
    <div class="container">
        <div class="row">
            <div class="col-md-9">
            <a href="userinfo.php" class="logo" style="float: left; text-decoration: none"><h2 style="color: white;font-size: 37px;">Safety First</h2></a>
                </div>
            
            <div class="col-md-3">
            <form action="" method="post" style="margin-top:5px;">
                <a type="button" class="btn btn-info" href="location.php" >Notification</a>

                <button name="logout" type="submit" class="btn btn-danger"><span class="glyphicon glyphicon-user"></span>Log Out</button>
            </form>
            </div>
        </div>
            </div>
      </div>   
<?php

include("header.php");

$minlat2=$_GET['lat2'];
$lat1=$_GET['lat1'];
$lon1=$_GET['lon1'];
$minlon2=$_GET['lon2'];
?>
<script>
// map with text and direction
function initMap() {
var location= new google.maps.LatLng(<?php echo $lat1; ?>,<?php echo $lon1; ?>);
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
position: new google.maps.LatLng(<?php echo $lat1; ?>,<?php echo $lon1; ?>),
map: map,
icon: accident
});
//you set your icon for each of the direction points Destination,
var marker2 = new google.maps.Marker({
position: new google.maps.LatLng(<?php echo $minlat2; ?>,<?php echo $minlon2; ?>),
map: map,
icon: help
});
directionsDisplay.setMap(map);
directionsDisplay.setPanel(document.getElementById('right-panel'));
calculateAndDisplayRoute(directionsService, directionsDisplay);
}
function calculateAndDisplayRoute(directionsService, directionsDisplay) {
directionsService.route({
origin: new google.maps.LatLng(<?php echo $lat1; ?>,<?php echo $lon1; ?>),
destination: new google.maps.LatLng(<?php echo $minlat2; ?>,<?php echo $minlon2; ?>),
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


<div class='container' >
	<h4 style="margin-bottom:30px">2nd Nearest Hospital Direction</h4>
	<div class='row'>
		<div class='col-md-8' id="direction_map" style="height:400px;">
		
		</div>
		<div class="col-md-4" id="right-panel" style="height:400px;overflow-y:scroll">
		</div>
		
	</div>
</div>

</body>
    <footer>
    <div style="background: #49274a;height: 50px;position: fixed;bottom: 0;width:100%;color:white;">
      <div class="text-center" style="position: relative;top: 40%">
        <small>&copy; 2016-2019 All rights reserved.</small>
        </div>
    </div>
    </footer>
</html>