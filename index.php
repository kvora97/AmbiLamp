<!DOCTYPE html>
<html>
<head>
  <title>AmbiLamp</title>
  <link rel="stylesheet" type="text/css" href="assets/css/index.css">
<!--  <link rel="stylesheet" type="text/css" href="header.css"> -->
<script src="jscolor.js"></script>
<body>

<?php
  
  include "GPIO.php";
  include "header.php";

  $default_color = "EFFFC9";

  $db = connectMongo();

  $color_data = $db->color;

//  $color = "EFFFC9";

  if (isset($_POST['set_default'])) {

    $num_entries = $color_data->count();
    $color_dict = array('color' => $_POST['color'], 'entry' => $num_entries +1);
    $color_data->insert($color_dict);  
  }

  $colorCursor = $color_data->find()->sort(array('entry' => -1))->limit(1);

  foreach($colorCursor as $doc) {
    $color = $doc['color']; 
    // change the initialized default color to the one specified in the database
  }

  if (isset($_POST['set_color'])) {
    $color = $_POST['color'];
  }

  /*if (isset($_POST['set_default'])) {
    $color_data->insert($_POST['color']);
  }*/

  /* BEGIN LED CODE */
  /********************************************************
  * Use the LED schematic in Challenge 2, LED Circuit
  * to complete these constructor lines.
  ********************************************************/
  $red = new GPIO(22, "out",4);
  $green = new GPIO(27, "out",3);
  $blue = new GPIO(17, "out",1);
  $colorArray = $color.str_split();
  /*********************************************************
  * Our colors are in hexadecimal - that is, come in the
  * form #------ where each dash is a character in the set
  * {0 1 2 3 4 5 6 7 8 9 a b c d e f}, which is the number
  * system in base 16. The RGB LED accepts values 0-255 for
  * each of the three colors. Conveniently, 255 is the
  * largest decimal value of two hexademical digits. That
  * is, #FF = (15 * 16^1) + (15 * 16^0) = 255. Thus, in a
  * hex color such as #BAD94D, the red PWM value is
  * respresented by #BA, green by #D9, and blue by #4D.
  * The str_split() function above turns our color string
  * into an array of characters (e.g. [B, A, D, 9, 4, D])
  * and we pwm_write() red with the decimal value of #BA in
  * the line below. Follow this reasoning to complete the
  * pwm_write()inputs for green and blue.
  ********************************************************/
  $red->pwm_write(hexdec($colorArray[0].$colorArray[1]));
  $green->pwm_write(hexdec($colorArray[2].$colorArray[3]));
  $blue->pwm_write(hexdec($colorArray[4].$colorArray[5]));
  /* END LED CODE */

  
  /* BEGIN SOUND DATA PARSING */

  // pre-fill arrays with 0
  $hourSums = array_fill(0, 24, 0);
  $hourCounts = array_fill(0, 24, 0);

  $sound_data = $db->sound;
  $soundCursor = $sound_data->find()->sort(array('entry' => -1))->limit(24);
  
  // create sums for the readings from each hour, and 
  // the number of readings for that hour
  foreach ($soundCursor as $doc) {

    $time = split('[-:]', $doc['time'])[3];
    
    $hourCounts[$time] = $hourCounts[$time] + 1;
    $hourSums[$time] = $hourSums[$time] + $doc['audio'];

  }
  

  // parse these arrays to create arrays of averages by hour
  $soundMin = 1000;
  $soundMax = 0;
  $soundDataDay = '[';
  $soundDataNight = '[';

  for($i = 0; $i < 24; $i = $i + 1) {

    //calculate the average
    $hourSums[$i] = $hourSums[$i]/$hourCounts[$i];

    // update the max value
    if ((float)$hourSums[$i] > $soundMax) {
      $soundMax = (float)$hourSums[$i];
    }

    // update the min value
    if ((float)$hourSums[$i] < $soundMin) {
      $soundMin = (float)$hourSums[$i];
    }

    // determine whether to add the value to daytime array 
    // or to the night time array
    if ($i < 12) {
      $soundDataDay = $soundDataDay . (float)$hourSums[$i] . ",";
    }
    else {
      $soundDataNight = $soundDataNight . (float)$hourSums[$i] . ",";
    }
  }

 
  $soundDataDay = trim($soundDataDay, ",");
  $soundDataDay = $soundDataDay . "]";
 
  $soundDataNight = trim($soundDataNight, ",");
  $soundDataNight = $soundDataNight . "]";

//  $soundMin = trim($soundMin, ",");
//  $soundMin = $soundMin . "]";

//  $soundMax = trim($soundMax, ",");
//  $soundMax = $soundMax . "]";

  echo "<script>";
  echo "var soundDataDay = " . $soundDataDay . ";";
  echo "var soundDataNight = " . $soundDataNight . ";";
  echo "var soundMin = " . $soundMin . ";";
  echo "var soundMax = " . $soundMax . ";";
  echo "</script>";



// }   <---   NOT SURE IF THIS BRACKET NEEDS TO BE HERE ????
  
  /* BEGIN TEMP DATA PARSING */

  // pre-fill arrays with 0
  $hourtempSums = array_fill(0, 24, 0);
  $hourtempCounts = array_fill(0, 24, 0);

  $temp_data = $db->temp;
  $tempCursor = $temp_data->find()->sort(array('entry' => -1))->limit(24);
  //$tempCursor = $temp_data->find();
 
  // create sums for the readings from each hour, and 
  // the number of readings for that hour
  foreach ($tempCursor as $doc) {

    $time = split('[-:]', $doc['time'])[3];
    
    $hourtempCounts[$time] = $hourtempCounts[$time] + 1;
    $hourtempSums[$time] = $hourtempSums[$time] + $doc['val'];
  }

  // parse these arrays to create arrays of averages by hour
  $tempMin = 1000;
  $tempMax = 0;
  $tempDataDay = '[';
  $tempDataNight = '[';

  for($i = 0; $i < 24; $i = $i + 1) {

    //calculate the average
    $hourtempSums[$i] = $hourtempSums[$i]/$hourtempCounts[$i];

    // update the max value
    if ((float)$hourtempSums[$i] > $tempMax) {
      $tempMax = (float)$hourtempSums[$i];
    }

    // update the min value
    if ((float)$hourtempSums[$i] < $tempMin) {
      $tempMin = (float)$hourtempSums[$i];
    }

    // determine whether to add the value to daytime array 
    // or to the night time array
    if ($i < 12) {
      $tempDataDay = $tempDataDay . (float)$hourtempSums[$i] . ",";
    }
    else {
      $tempDataNight = $tempDataNight . (float)$hourtempSums[$i] . ",";
    }
  }

  $tempDataDay = trim($tempDataDay, ",");
  $tempDataDay = $tempDataDay . "]";
 
  $tempDataNight = trim($tempDataNight, ",");
  $tempDataNight = $tempDataNight . "]";

//  $tempMin = trim($tempMin, ",");
//  $tempMin = $tempMin . "]";

//  $tempMax = trim($tempMax, ",");
//  $tempMax = $tempMax . "]";


  echo "<script>";
  echo "var tempDataDay = " . $tempDataDay . ";";
  echo "var tempDataNight = " . $tempDataNight . ";";
  echo "var tempMin = " . $tempMin . ";";
  echo "var tempMax = " . $tempMax . ";";
  echo "</script>";

  echo $tempDataDay;
  echo $tempDataNight;
  echo $tempMin . ",";
  echo $tempMax;
?>

<!-- JS COLOR PICKER -->
<input type="button" class="jscolor" id="picker" onchange="update(this.jscolor)" onfocusout="apply()" value=<?php echo "'" . $color . "'"; ?> >

<!-- FORM -->
<form method="POST">
  <input type="text" name="color" id="color" >
	<input type="submit" id="smt" name="set_color"  hidden>
	<input type="submit" value="Set as Default" id="set_default" name="set_default" >
</form>

<!-- CHARTS -->
<div id="charts-container" >
	<canvas id="temp-chart" class="chart" width="550" height="350"></canvas>
	<canvas id="sound-chart" class="chart" width="550" height="350"></canvas>
</div>


<!-- ABOUT -->

<div id="about" >

	<h1>About</h1>

	<p>
		Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed sed congue diam. Mauris ultrices feugiat metus, vel maximus lacus lacinia luctus. Nulla non rutrum sapien. Morbi varius scelerisque aliquet. Quisque sit amet auctor tellus, vel placerat orci. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Aliquam bibendum eleifend mauris ut finibus. Ut non lectus quis ligula volutpat accumsan at a eros. Morbi varius nulla velit, in scelerisque sem bibendum nec. Proin mollis in massa in finibus. Ut tellus nulla, semper vitae venenatis ut, convallis quis dolor.</p>

		<p>

	Aliquam erat volutpat. Ut cursus nisl a ligula consectetur posuere vitae nec lorem. Praesent aliquet nisi fermentum quam commodo congue. Mauris euismod libero at convallis sodales. Quisque elementum, ante eu consequat vulputate, libero orci dapibus enim, nec imperdiet tellus tellus eu arcu. Donec efficitur tincidunt lacus, eu sagittis augue tempus in. Integer tristique massa sem, et interdum nisi facilisis blandit. Duis feugiat porttitor magna, nec mollis arcu interdum vel. In laoreet ultrices condimentum. Nullam sodales leo non risus lacinia, a rutrum nibh iaculis. Nam gravida quam elit, a suscipit arcu congue eget. Sed mollis dui vel metus iaculis, a convallis orci sagittis.</p>

	<p>
	Morbi varius nulla et urna tristique, non elementum mauris tincidunt. Sed vestibulum nunc vitae augue scelerisque, at pellentesque nisl ultricies. Ut at felis elit. Mauris lacus purus, commodo feugiat tincidunt sed, tempor aliquet nunc. Mauris ligula lorem, pharetra ut nunc sit amet, lacinia fermentum neque. Curabitur sapien risus, dignissim ac aliquet non, ornare eget nibh. In molestie sollicitudin enim. Nullam a massa et nibh rutrum egestas sed mollis orci.</p>



	<p>
	Cras scelerisque suscipit est tincidunt viverra. Ut lobortis ullamcorper lectus ac mattis. Proin ac lobortis ex. Donec sit amet lorem risus. Morbi sit amet mollis ligula. Phasellus euismod justo et elit malesuada elementum. Nunc vehicula imperdiet pretium. Donec lacinia, arcu ut porttitor convallis, velit nunc porta nunc, ut egestas purus odio vitae nisl. Vivamus accumsan rhoncus nibh ut maximus. Duis arcu tortor, congue in sagittis vitae, hendrerit a leo.</p>

	<p>
	Donec sem ante, consequat id quam et, efficitur aliquam sapien. Aliquam cursus velit eget convallis mollis. Cras consequat augue ac euismod varius. Integer porta justo eget tortor interdum bibendum. Proin nunc turpis, blandit nec iaculis sed, gravida et augue. Duis non nisl elit. Integer non enim dictum, ultrices ligula nec, tristique nulla. Mauris hendrerit ipsum a fermentum ultrices. Morbi id lacinia dui.
	</p>

</div>

<script type="text/javascript" src="assets/js/index.js"></script>


</body>
</html>
