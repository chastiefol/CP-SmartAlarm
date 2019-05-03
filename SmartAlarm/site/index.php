<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>SmartAlarm</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
    <link rel="icon" type="image/png" href="https://img.icons8.com/metro/26/000000/alarm-clock.png" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
    <?php
    if(isset($_GET["node"])){
        $json = file_get_contents($_GET["node"]);
        $device = json_decode($json)[0];
        $alarms = json_decode($json)[1];
        if(isset($_GET["delClock"])){
            $xml = file_get_contents($_GET["node"] . "/del/alarm?i=".$_GET["delClock"]);
            header('Location: '.$_SERVER['SCRIPT_NAME'].'/?node='.$_GET["node"]);
        }
        if(isset($_GET["setClock"])){
            $xml = file_get_contents($_GET["node"] . "/set/alarm?h=".$_GET["h"]."&m=".$_GET["m"]);
            header('Location: '.$_SERVER['SCRIPT_NAME'].'/?node='.$_GET["node"]);
        }
    }
    ?>
</head>

<body onload="startTime()">
    <nav class="navbar navbar-expand-sm navbar-light bg-light">
        <a class="navbar-brand"><img src="https://img.icons8.com/metro/26/000000/alarm-clock.png" width="30" height="30" class  ="d-inline-block align-top" alt="logo icon">
            SmartAlarm
        </a>
        <a class="nav-link" href="./">
            New Connection
        </a>
    </nav>
    <div class="row mt-3">
        <div class="col-8 mx-auto">
            
            <?php 
            if(!isset($_GET["node"])) {
                echo '
                    <div class="card p-5" id="connector">
                        <div class="contain mx-auto">
                            <h1 class="text-center">Connect to device</h1>
                            <form>
                                <input class="form-control col-12" name="node" placeholder="192.168.1.35, your.ddns.com" required>
                                <input type="submit" class="btn btn-primary col-12 mx-auto m-1 p-2">
                            </form>
                            <div id="example">
                                <?php print_r($obj ? $obj : ""); ?>
                            </div>
                        </div>
                    </div>';
            }else{
                printf('
                    <div class="card p-5" id="device">
                        <div class="contain mx-auto">
                            <h1 id="txt" class="text-center"></h1>
                            <h3 class="text-center">Device name : %s</h3>
                            <h5 class="text-center">Device Address : %s</h5>
                            <h5 class="text-center mt-5">Choose your time:</h5>
                            <form class="row" >
                                <div class="mx-auto p-2 col-8 row">
                                    <input class="form-control col-6" type="number" name="h" min="0" max="23" required %s>
                                    <input class="form-control col-6" type="number" name="m" min="0" max="59" required %s>
                                    <input type="hidden" name="node" value="'.$_GET["node"].'">
                                </div>
                                <input class="btn btn-primary col-8 mx-auto m-1 p-2" type="submit" name="setClock" value="%s" %s>
                            </form>
                        </div>
                    </div>', $device->name, $_GET["node"], $alarms->count < 10 ? "" : "disabled", $alarms->count < 10 ? "" : "disabled", $alarms->count < 10 ? "Set alarm" : "Alarm is full.", $alarms->count < 10 ? "" : "disabled"); ?>
                <div class="card p-5 mt-3" id="alarm">
                        <div class="contain mx-auto col-12 mx-auto">
                            <h3 class="text-center">Alarm</h3>
                        </div>
                        <div class="contain mx-auto col-12">
                            <p>Volume</p>
                            <div class="slidecontainer">
                                <input type="range" min="1" max="100" value="50" class="slider" id="volume-control">
                            </div>
                        </div>
                        <div class="contain mx-auto col-12">
                            <p>Pitch</p>
                            <div class="slidecontainer">
                                <input type="range" min="1" max="100" value="50" class="slider" id="pitch-control">
                            </div>
                        </div>
                        <div class="contain mx-auto col-12">
                            <ul class="list-group col-12 mx-auto">
                <?php $i = 0;
                foreach($alarms->alarms as $value){
                    printf('<li class="list-group-item"><span class="col-10 d-inline-block">%02d : %02d</span> <a href="./?node=%s&delClock=%d" role="button" class="btn btn-outline-danger ml-0">Delete</a></li>', $value->hour, $value->minute, $_GET["node"], $i);
                    $i = $i + 1;
                }
                            
                echo '      </ul>
                        </div>
                    </div>';
            };
            ?>
        </div>
    </div>
</body>
<script>
function httpGet(Url)
{
    var xmlHttp = new XMLHttpRequest();
    xmlHttp.open( "GET", Url, false );
    xmlHttp.send( null );
    return xmlHttp.responseText;
}
function startTime() {
  var today = new Date();
  var h = today.getHours();
  var m = today.getMinutes();
  var s = today.getSeconds();
  m = checkTime(m);
  s = checkTime(s);
  document.getElementById('txt').innerHTML =
  h + ":" + m + ":" + s;
  var t = setTimeout(startTime, 500);
}
function checkTime(i) {
  if (i < 10) {i = "0" + i};  // add zero in front of numbers < 10
  return i;
}
</script>

<style>
.slidecontainer {
  width: 100%; /* Width of the outside container */
}

/* The slider itself */
.slider {
  -webkit-appearance: none;  /* Override default CSS styles */
  appearance: none;
  width: 100%; /* Full-width */
  height: 25px; /* Specified height */
  background: #d3d3d3; /* Grey background */
  outline: none; /* Remove outline */
  opacity: 0.7; /* Set transparency (for mouse-over effects on hover) */
  -webkit-transition: .2s; /* 0.2 seconds transition on hover */
  transition: opacity .2s;
}

/* Mouse-over effects */
.slider:hover {
  opacity: 1; /* Fully shown on mouse-over */
}

/* The slider handle (use -webkit- (Chrome, Opera, Safari, Edge) and -moz- (Firefox) to override default look) */ 
.slider::-webkit-slider-thumb {
  -webkit-appearance: none; /* Override default look */
  appearance: none;
  width: 25px; /* Set a specific slider handle width */
  height: 25px; /* Slider handle height */
  background: #4CAF50; /* Green background */
  cursor: pointer; /* Cursor on hover */
}

.slider::-moz-range-thumb {
  width: 25px; /* Set a specific slider handle width */
  height: 25px; /* Slider handle height */
  background: #4CAF50; /* Green background */
  cursor: pointer; /* Cursor on hover */
}
</style>

<script>

    var volume_slider = document.getElementById("volume-control");
    var pitch_slider = document.getElementById("pitch-control");

    volume_slider.onchange = function() {
        console.log("volume");
        console.log(this.value);
        $.ajax({
            url: "<?php echo $_GET["node"] ?>/set/volume?i="+this.value,
            dataType: "jsonp",
            success: function (data) {
                console.log(data);
            }

        });
    }
    pitch_slider.onchange = function() {
        console.log("Pitch");
        console.log(this.value);
        $.ajax({
            url: "<?php echo $_GET["node"] ?>/set/pitch?i="+this.value,
            dataType: "jsonp",
            success: function (data) {
                console.log(data);
            }

        })
    }

</script>

</html>