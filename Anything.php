<?php

  $conn = new MongoClient (["mongodb://<dbuser>:<dbpassword>@ds044689.mlab.com:44689/ambilamp"]); // paste your db URL
  $db = $conn->[ambilamp];
  $collection = $db->[AmbiLamp]; // paste collection name
  $newData = array( 'num' => 130, 'val' => 57, 'time' => '8pm' );
  $collection->insert($newData);

?>
