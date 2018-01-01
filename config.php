<?php
  $host = 'localhost';
  $user = 'root';
  $password = '';
  $dbname = 'ep_spletna_trgovina';

  //nastavi DSN
  $dsn = 'mysql:host='. $host . ';dbname='. $dbname;

  //ustvari PDO instanco
  $pdo = new PDO($dsn, $user, $password);

 ?>
