<!DOCTYPE html>
<html>
  <head>
    <style>
      #map {
        width: 100%;
        height: 400px;
        background-color: grey;
      }
    </style>
  </head>
  <body>
    <h3>Last Location of Vehicle</h3>
    <!--The div element for the map -->
    <div id="map"></div>
  </body>
</html>


<?php
session_start();
include("header.php");
include('Database\Database.php');
//$minlat2=$_GET['lat2'];
$lat1=$_GET['lat'];
$lon1=$_GET['lon'];
//$minlon2=$_GET['lon2'];
?>
<script>
// map with text and direction
function initMap() {
  // The location of Uluru
  var location= new google.maps.LatLng(<?php echo $lat1; ?>,<?php echo $lon1; ?>);
  // The map, centered at Uluru
  var map = new google.maps.Map(
      document.getElementById('map'), {zoom: 18, center: location});
  // The marker, positioned at Uluru
  var marker = new google.maps.Marker({position: location, map: map});
}

</script>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBK3ulqogBpz8y6XrEZWpnnsoV37TgCkc0&callback=initMap"></script>


