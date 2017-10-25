<!DOCTYPE html>
<html>
<head>
  <title>AmbiLamp</title>
  <link rel="stylesheet" type="text/css" href="index.css">
<!--  <link rel="stylesheet" type="text/css" href="header.css"> -->
<!--  <script src="jscolor.js"></script> -->
<body>

<?php
  include "header.php"
?>

<!-- JS COLOR PICKER -->
<input type="button" class="jscolor" id="picker" value="EFFFC9">

<!-- FORM -->
<form>
	<input type="text" name="" id="color" >
	<input type="submit" name="Set as Default" id="set_default" >

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

<script type="text/javascript" src="index.js"></script>


</body>
</html>
