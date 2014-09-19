<?php
  $connect=mysql_connect("localhost","atraders_jd","password242") or die("Unable to Connect");
  mysql_select_db("atraders_angular_test") or die("Could not open the db");
  $showtablequery="SHOW TABLES FROM atraders_angular_test";
  $query_result=mysql_query($showtablequery);
  while($showtablerow = mysql_fetch_array($query_result))
  {
    echo $showtablerow[0]." ";
  }
?>
