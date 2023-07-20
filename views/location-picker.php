<?php 
    session_start();

    $route = '';
    if(isset($_GET['redirect']))
    {
        $route = $_GET['redirect'];
    }
    else 
    {
        require_once('./error/access-error.html');
        die();
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Location Picker</title>
        <link rel="stylesheet" href="../static/css/location-picker.css">
        <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
        <link href="https://api.mapbox.com/mapbox-gl-js/v2.7.0/mapbox-gl.css" rel="stylesheet">
    	<script src="https://api.mapbox.com/mapbox-gl-js/v2.7.0/mapbox-gl.js"></script>
        <script>
            var longlat = [];
        </script>
    </head>
    <body>
        <div class="map" id="map"></div>

        <div class="modal" id="modal">
            <?php require_once "./modal/lp/arm.php"; ?>
        </div>
        <script src="../static/js/map/location-picker.js"></script>
    </body>
</html>