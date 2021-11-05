<?php
      // $connect=new mysqli("localhost","root","","paintsworld");

// $connect=new PDO("mysql:host=localhost;dbname=server1_paintsworld", "server1_paintsworld", "W1I[H$#Er8Bs");
$connect = new PDO("mysql:host=localhost;dbname=pharmacy", 'root', '');

// $connect=new PDO("mysql:host=localhost;dbname=vblpco_v6_paintsworld", 'vblpco_v6','UAFVE]YtU-7h');

if(!$connect)
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }
?>
