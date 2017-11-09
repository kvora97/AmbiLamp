<!DOCTYPE html>
<html>
<head>
	<title>AmbiLamp</title>
	<link rel="stylesheet" type="text/css" href="assets/css/details.css">
</head>
<body>

<?php
  
  include "header.php";

  /* Connect to MongoDB */
  $db = connectMongo();
  $sounds = $db->sound;
  $temperatures = $db->temp;
  $soundCursor = $sounds->find()->sort(array('entry' => -1))->limit(24);
  $temperatureCursor = $temperatures->find()->sort(array('entry' => -1))->limit(24);

  /* Parse temperature data */
  /* We need to form a strinf representation of two 
  /* arrays, which will become x-y pairs for a line chart
  /* This is because Charts.js will need an array, which we
  /* we will probably provide by assigning this string to JS
  /* variable. It will make more sense in a minute */

  $temperatureX = "[";
  $temperatureData = "[";

  foreach ($temperatureCursor as $doc) {

    $time = split('[ ]', $doc['time']);
    $temperatureX = $temperatureX . "'" . $time[1] . "',";
    $temperatureData = $temperatureData . $doc['val'] . ",";
  }

  /*
  $temperatureX = trim($temperatureX, ",");
  $temperatureX = $temperatureX . "]";

  $temperatureData = trim($temperatureData, ",");
  $temperatureData = $temperatureData . "]";
*/
  /* end of temperature parse */

  /* assign arrays to JS variables */
  /*echo "<script>";
  echo "var temperatureData = " . $temperatureData . ";";
  echo "var temperatureX = " . $temperatureX . ";";
  echo "</script>";
*/

?>

<!-- BUTTONS AND CANVASES -->
<input type="button" id="temp-bin" class="btn" value="View Temperature Charts" onclick="drawTemp()">
<canvas id="temp-chart-long" class="chart" width="900" height="350" hidden></canvas>
<input type="button" id="sound-bin" class="btn" value="View Sound Charts" onclick="drawSound()">
<canvas id="sound-chart-long" class="chart" width="900" height="350" hidden></canvas>

<script type="text/javascript" src="assets/js/details.js"></script>

<!-- TABLES -->
<div id="tables-container">
  <div class="table">
    <table id="temp-table">
      <tr>
        <th>Time</th>
        <th>Temperature</th>
      </tr>
    
      <?php

      foreach ($temperatureCursor as $doc) 
      {
        echo "<tr>";
          echo "<td>".$doc['time']."</td>";
          echo "<td>".$doc['val']."</td>";
        echo "</tr>";
      }

      ?>
    </table>
  </div>
</div>


<div id="tables-container">
  <div class="table">
    <table id="sound-table">
      <tr>
        <th>Time</th>
        <th>Sound</th>
      </tr>
    
      <?php

      foreach ($soundCursor as $doc) 
      {
        echo "<tr>";
          echo "<td>".$doc['time']."</td>";
          echo "<td>".$doc['val']."</td>";
        echo "</tr>";
      }

      ?>
    </table>
  </div>
</div>


<!--

<div id="tables-container">

	<div class="table">
		<table id="temp-table" >
			<ul>
			<tr class="topcell">
			    <th>Company</th>
			    <th>Contact</th>
			    <th>Country</th>
			  </tr>
			  <tr>
			    <td>Alfreds Futterkiste</td>
			    <td>Maria Anders</td>
			    <td>Germany</td>
			  </tr>
			  <tr>
			    <td>Centro comercial Moctezuma</td>
			    <td>Francisco Chang</td>
			    <td>Mexico</td>
			  </tr>
			  <tr>
			    <td>Ernst Handel</td>
			    <td>Roland Mendel</td>
			    <td>Austria</td>
			  </tr>
			  <tr>
			    <td>Island Trading</td>
			    <td>Helen Bennett</td>
			    <td>UK</td>
			  </tr>
			  <tr>
			    <td>Laughing Bacchus Winecellars</td>
			    <td>Yoshi Tannamuri</td>
			    <td>Canada</td>
			  </tr>
			  <tr>
			    <td>Magazzini Alimentari Riuniti</td>
			    <td>Giovanni Rovelli</td>
			    <td>Italy</td>
			  </tr>
			</ul>
		</table>
	</div>

	<div class="table">
		<table id="sound-table" >
		<ul>
			<tr class="topcell">
			    <th>Company</th>
			    <th>Contact</th>
			    <th>Country</th>
			  </tr>
			  <tr>
			    <td>Alfreds Futterkiste</td>
			    <td>Maria Anders</td>
			    <td>Germany</td>
			  </tr>
			  <tr>
			    <td>Centro comercial Moctezuma</td>
			    <td>Francisco Chang</td>
			    <td>Mexico</td>
			  </tr>
			  <tr>
			    <td>Ernst Handel</td>
			    <td>Roland Mendel</td>
			    <td>Austria</td>
			  </tr>
			  <tr>
			    <td>Island Trading</td>
			    <td>Helen Bennett</td>
			    <td>UK</td>
			  </tr>
			  <tr>
			    <td>Laughing Bacchus Winecellars</td>
			    <td>Yoshi Tannamuri</td>
			    <td>Canada</td>
			  </tr>
			  <tr>
			    <td>Magazzini Alimentari Riuniti</td>
			    <td>Giovanni Rovelli</td>
			    <td>Italy</td>
			  </tr>
		</ul>
		</table>
	</div>
</div>


-->

</body>
</html>

