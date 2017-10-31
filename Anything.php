<?php

  $conn = new MongoClient ("mongodb://AmbiLamp:admin@ds044689.mlab.com:44689/ambilamp"); // paste your db URL
  $db = $conn->ambilamp;
  $collection = $db->AmbiLamp; // paste collection name
  $newData = array( 'num' => 131, 'val' => 57, 'time' => '8pm' );
  $collection->insert($newData);

  $cursor = $collection->find();

  echo "First loop: <br>";
  foreach ($cursor as $doc) {

    echo $doc['num'] . "<br>";

  }
  
  echo "<br>";

  $cursor = $collection->find(array('num' => 131));
  echo "Second loop: <br>";
  foreach ($cursor as $doc) {

    echo $doc['num'] . "<br>";

  }

?>
