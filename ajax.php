<?php
include('config.php');
session_start();
echo "SEM TU NOT";
if (!empty($_GET)) {
  $item_id = $_GET['product_id:'];
  try {
    $statement = $pdo->prepare("INSERT INTO kosarica (ID_kupec, ID_izdelek) VALUES (?, ?)");
    $statement->bindValue(1, $_SESSION["user_ID"]);
    $statement->bindValue(2, $item_id);
    $statement->execute();
    header("location: logged_in.php");
  } catch (PDOException $e) {
    echo "Napaka pri poizvedbi: {$e->getMessage()}";
  }
?>
