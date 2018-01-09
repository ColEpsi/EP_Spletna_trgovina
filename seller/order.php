<?php
include('../config.php');
session_start();
$order_id = $_GET["id"];
try {
	$statement = $pdo->prepare("SELECT * FROM prodajalec WHERE ID = ?");
	$statement->bindValue(1, $_SESSION["user_ID"]);
	$statement->execute();
	$user = $statement->fetch();
} catch (PDOException $e) {
	echo "Napaka pri poizvedbi: {$e->getMessage()}";
}
try {
 $statement = $pdo->prepare("SELECT * FROM nakup WHERE ID = ?");
 $statement->bindValue(1, $order_id);
 $statement->execute();
 $order = $statement->fetch();
 $html = '  <h3>Številka računa: <strong>'.$order["ID"].'</strong></h3>
            <h3>Datum naročila: <strong>'.$order["Datum"].'</strong></h3>
            <table width="100%">
             <tr>
               <th style="font-size:1.5em">Izdelek</th>
               <th style="font-size:1.5em">Količina</th>
               <th style="font-size:1.5em">Cena</th>
             </tr>';
 try {
  $statement = $pdo->prepare("SELECT * FROM izdelek_nakupa WHERE ID_nakup = ?");
  $statement->bindValue(1, $order["ID"]);
  $statement->execute();
  foreach ($statement->fetchAll() as $item) {
    try {
     $stmt = $pdo->prepare("SELECT * FROM izdelek WHERE ID = ?");
     $stmt->bindValue(1, $item["ID_izdelek"]);
     $stmt->execute();
     $description = $stmt->fetch();

     $html .= '<tr>
                 <td>'.$description["Ime"].'</td>
                 <td>'.$item["Kolicina"].'</td>
                 <td>'.$description["Cena"].'€</td>
               </tr>';
    } catch (PDOException $e) {
     echo "Napaka pri poizvedbi: {$e->getMessage()}";
    }
  }
  $html .= '<tr>
              <td></td>
              <td style="font-size:1.4em; color: red;"><strong>Skupaj</strong></td>
              <td style="font-size:1.4em; color: red;"><strong>'.$order["Cena"].'€</strong></td>
            </tr>
          </table>
          <form action="" method="post">
            <button type="submit" class="btn btn-danger pull-left btn-lg" style="margin-top:1em;" name="delete" id="delete" value="'.$order["ID"].'">Storniraj naročilo</button>
          </form>
          <form action="" method="post">
            <select class="pull-right" name="status">
              <option value="V obdelavi">V obdelavi</option>
              <option value="Potrjeno" selected>Potrjeno</option>
              <option value="V dostavi">V dostavi</option>
            </select>
            <button type="submit" class="btn btn-success pull-right btn-lg" style="margin-top:1em;" name="submit" id="submit" value="'.$order["ID"].'">Posodobi status</button>
          </form>';
 } catch (PDOException $e) {
  echo "Napaka pri poizvedbi: {$e->getMessage()}";
 }
}  catch (PDOException $e) {
 echo "Napaka pri poizvedbi: {$e->getMessage()}";
}
$error = "";
if(isset($_POST['submit'])) {
 $order_id = $_POST['submit'];
 $status = $_POST['status'];
 try {
   $statement = $pdo->prepare("UPDATE nakup SET Status = ? WHERE ID = ?");
   $statement->bindValue(1, $status);
   $statement->bindValue(2, $order_id);
   $statement->execute();
   header("location: main.php");
 } catch (PDOException $e) {
   $error = '<div class="alert alert-danger col-centered" style="text-align:center" role="alert"><strong>Podatki niso bili posodobljeni! Napaka:'.$e->getMessage().'</strong></div>';
 }
}
if(isset($_POST['delete'])) {
 $order_id = $_POST['delete'];
 try {
   $statement = $pdo->prepare("DELETE FROM nakup WHERE ID = ?");
   $statement->bindValue(1, $order_id);
   $statement->execute();
   header("location: main.php");
 } catch (PDOException $e) {
   $error = '<div class="alert alert-danger col-centered" style="text-align:center" role="alert"><strong>Podatki niso bili posodobljeni! Napaka:'.$e->getMessage().'</strong></div>';
 }
}




?>
<html>
<head>
	<meta charset="UTF-8">
	<meta content="width=device-width, initial-scale=1" name="viewport">
	<link href="../mobilko_favicon.png" rel="shortcut icon" type="image/png">
	<title>Mobilko | Mobilniki po ugodnih cenah!</title>
	<link href="../bootstrap/css/lumen.bootstrap.min.css" rel="stylesheet">
	<link href="../css/main.css" rel="stylesheet">
  <style media="screen">
  table, th, td {
      padding: 5px;
      border: 1px solid black;
      cursor: default;
   }
  </style>
</head>
<body>
	<div class="container">
		<nav class="navbar sticky-topa navbar-inverse">
			<div class="container-fluid">
				<a class="navbar-brand" href="main.php">Nazaj</a>
				<ul class="nav fixed-top navbar-nav navbar-right">
					<li class="dropdown">
						<a class="dropdown-toggle" data-toggle="dropdown" href="account.html"><b><span class="glyphicon glyphicon-user"></span> <?php  echo $user["Ime"].' '.$user["Priimek"]; ?></b> <span class="caret"></span></a>
						<ul class="dropdown-menu" id="login-dp">
							<li>
								<div class="row">
									<div class="col-md-12">
										<button class="btn btn-info btn-block" name="submit" onclick="window.location.href='account.php'" type="submit">Račun</button>
										<button class="btn btn-danger btn-block" name="submit" onclick="window.location.href='index.php'" type="submit">Odjava</button><br>
									</div>
								</div>
							</li>
						</ul>
					</li>
				</ul>
			</div>
		</nav>
		<div class="container-fluid" style="background-color: white; height: 100%; overflow:auto;">
      <?php echo $error,$html; ?>



		</div>
	</div><!-- Content will go here -->
	<script src="../javascripts/jquery.min.js">
	</script>
	<script src="../bootstrap/js/bootstrap.min.js">
	</script>
</body>
</html>
