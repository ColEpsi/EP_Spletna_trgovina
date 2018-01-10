<?php
include('../config.php');
session_start();
$buyer_id = $_GET["id"];
try {
	$statement = $pdo->prepare("SELECT * FROM prodajalec WHERE ID = ?");
	$statement->bindValue(1, $_SESSION["user_ID"]);
	$statement->execute();
	$user = $statement->fetch();
} catch (PDOException $e) {
	echo "Napaka pri poizvedbi: {$e->getMessage()}";
}
try {
	$statement = $pdo->prepare("SELECT * FROM kupec WHERE ID = ?");
	$statement->bindValue(1, $buyer_id);
	$statement->execute();
	$buyer = $statement->fetch();
} catch (PDOException $e) {
	echo "Napaka pri poizvedbi: {$e->getMessage()}";
}
$message_buyer = "";
if (isset($_POST["submit_buyer"])) {
  $name = $_POST['name'];
  $surname = $_POST['surname'];
  $email = $_POST['email'];
  $address = $_POST['address'];
  $phone = $_POST['phone'];
  $password = $_POST['password'];
  try {
  	$statement = $pdo->prepare("UPDATE kupec SET Ime = ?, Priimek = ?, Email = ?, Naslov = ?, Telefonska_stevilka = ?, Password = ? WHERE ID = ?");
  	$statement->bindValue(1, $name);
    $statement->bindValue(2, $surname);
    $statement->bindValue(3, $email);
    $statement->bindValue(4, $address);
    $statement->bindValue(5, $phone);
    $statement->bindValue(6, $password);
    $statement->bindValue(7, $buyer_id);
  	$statement->execute();

    $message_buyer = '<div class="alert alert-success col-centered" style="text-align:center" role="alert"><strong>Uporabnik je bil posodobljen.</strong></div>';
  } catch (PDOException $e) {
  	$message_buyer = '<div class="alert alert-danger col-centered" style="text-align:center" role="alert"><strong>Napaka pri poizvedbi'.$e->getMessage().'</strong></div>';
  }
}
if (isset($_POST["delete_buyer"])) {
  try {
    $statement = $pdo->prepare("DELETE FROM kupec WHERE ID = ?");
    $statement->bindValue(1, $buyer_id);
    $statement->execute();
    echo '<script type="text/javascript">alert("Uporabnik je bil izbrisan");</script>';
    header("location: main.php");
  } catch (PDOException $e) {
    $message_buyer = '<div class="alert alert-danger col-centered" style="text-align:center" role="alert"><strong>Napaka pri poizvedbi'.$e->getMessage().'</strong></div>';
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
      <h2 style="text-align: center;"><strong>Podatki o uporabniku:</strong></h2>
      <?php echo $message_buyer; ?>
  		<form class="form-horizontal" method="post" role="form">
  			<div class="form-group">
  				<label class="col-sm-5 control-label" for="name">Ime</label>
  				<div class="col-sm-3">
  					<input class="form-control" id="name" name="name" placeholder="" type="text" value="<?php echo $buyer["Ime"];?>">
  				</div>
  			</div>
  			<div class="form-group">
  				<label class="col-sm-5 control-label" for="name">Priimek</label>
  				<div class="col-sm-3">
  					<input class="form-control" id="surname" name="surname" placeholder="" type="text" value="<?php echo $buyer["Priimek"];?>">
  				</div>
  			</div>
  			<div class="form-group">
  				<label class="col-sm-5 control-label" for="email">Email</label>
  				<div class="col-sm-3">
  					<input class="form-control" id="email" name="email" placeholder="" type="email" value="<?php echo $buyer["Email"];?>">
  				</div>
  			</div>
  			<div class="form-group">
  				<label class="col-sm-5 control-label" for="address">Naslov</label>
  				<div class="col-sm-3">
  					<input class="form-control" id="address" name="address" placeholder="" type="text" value="<?php echo $buyer["Naslov"];?>">
  				</div>
  			</div>
  			<div class="form-group">
  				<label class="col-sm-5 control-label" for="phone">Telefon</label>
  				<div class="col-sm-3">
  					<input class="form-control" id="phone" name="phone" placeholder="" type="text" value="<?php echo $buyer["Telefonska_stevilka"];?>">
  				</div>
  			</div>
  			<div class="form-group">
  				<label class="col-sm-5 control-label" for="name">Geslo</label>
  				<div class="col-sm-3">
  					<input class="form-control" id="password" name="password" placeholder="" type="text" value="<?php echo $buyer["Password"];?>">
  				</div>
  			</div>
  			<div class="button" style="text-align:center">
  				<input class="btn btn-primary btn-lg" name="submit_buyer" type="submit" value="Posodobi uporabnika">
  			</div>
  		</form>
      <form action="" method="post">
        <button style="margin-left: 44%;" class="btn btn-danger btn-lg" name="delete_buyer" value="<?php echo $buyer_id; ?>" type="submit" style="margin-top: 1em;"><span class="glyphicon glyphicon-delete" aria-hidden="true"></span>Izbriši Uporabnika</button>
      </form>


		</div>
	</div><!-- Content will go here -->
	<script src="../javascripts/jquery.min.js">
	</script>
	<script src="../bootstrap/js/bootstrap.min.js">
	</script>
</body>
</html>
