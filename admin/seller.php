<?php
include('../config.php');
session_start();
$seller_id = $_GET["id"];
try {
	$statement = $pdo->prepare("SELECT * FROM administrator WHERE ID = ?");
	$statement->bindValue(1, $_SESSION["user_ID"]);
	$statement->execute();
	$user = $statement->fetch();
} catch (PDOException $e) {
	echo "Napaka pri poizvedbi: {$e->getMessage()}";
}
try {
	$statement = $pdo->prepare("SELECT * FROM prodajalec WHERE ID = ?");
	$statement->bindValue(1, $seller_id);
	$statement->execute();
	$seller = $statement->fetch();
} catch (PDOException $e) {
	echo "Napaka pri poizvedbi: {$e->getMessage()}";
}
$message_seller = "";
if (isset($_POST["submit_seller"])) {
  $name = $_POST['name'];
  $surname = $_POST['surname'];
  $email = $_POST['email'];
  $password = $_POST['password'];
  $status = $_POST['status'];

  try {
  	$statement = $pdo->prepare("UPDATE prodajalec SET Ime = ?, Priimek = ?, Email = ?, Geslo = ?, Status = ? WHERE ID = ?");
  	$statement->bindValue(1, $name);
    $statement->bindValue(2, $surname);
    $statement->bindValue(3, $email);
    $statement->bindValue(4, $password);
    $statement->bindValue(5, $status);
    $statement->bindValue(6, $seller_id);
  	$statement->execute();

    $message_seller = '<div class="alert alert-success col-centered" style="text-align:center" role="alert"><strong>Prodajalec je bil posodobljen.</strong></div>';
  } catch (PDOException $e) {
  	$message_seller = '<div class="alert alert-danger col-centered" style="text-align:center" role="alert"><strong>Napaka pri poizvedbi'.$e->getMessage().'</strong></div>';
  }
}
if (isset($_POST["delete_seller"])) {
  try {
    $statement = $pdo->prepare("DELETE FROM prodajalec WHERE ID = ?");
    $statement->bindValue(1, $seller_id);
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
	<title>Mobilko | Administrator</title>
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
      <h2 style="text-align: center;"><strong>Podatki o prodajalcu:</strong></h2>
      <?php echo $message_seller; ?>
  		<form class="form-horizontal" method="post" role="form">
  			<div class="form-group">
  				<label class="col-sm-5 control-label" for="name">Ime</label>
  				<div class="col-sm-3">
  					<input class="form-control" id="name" name="name" placeholder="" type="text" value="<?php echo $seller["Ime"];?>">
  				</div>
  			</div>
  			<div class="form-group">
  				<label class="col-sm-5 control-label" for="name">Priimek</label>
  				<div class="col-sm-3">
  					<input class="form-control" id="surname" name="surname" placeholder="" type="text" value="<?php echo $seller["Priimek"];?>">
  				</div>
  			</div>
  			<div class="form-group">
  				<label class="col-sm-5 control-label" for="email">Email</label>
  				<div class="col-sm-3">
  					<input class="form-control" id="email" name="email" placeholder="" type="email" value="<?php echo $seller["Email"];?>">
  				</div>
  			</div>
  			<div class="form-group">
  				<label class="col-sm-5 control-label" for="password">Geslo</label>
  				<div class="col-sm-3">
  					<input class="form-control" id="password" name="password" placeholder="" type="text" value="<?php echo $seller["Geslo"];?>">
  				</div>
  			</div>
        <div class="form-group">
  				<label class="col-sm-5 control-label" for="status">Status</label>
  				<div class="col-sm-3">
  					<?php
              if ($seller["Status"] == "active") {
                echo '<select  name="status">
                        <option value="active" selected>Active</option>
                        <option value="deactivated">Deactivated</option>
                      </select>';
              }
              else {
                echo '<select  name="status">
                        <option value="active">Active</option>
                        <option value="deactivated selected">Deactivated</option>
                      </select>';
              }
             ?>
  				</div>
  			</div>
  			<div class="button" style="text-align:center">
  				<input class="btn btn-primary btn-lg" name="submit_seller" type="submit" value="Posodobi prodajalca">
  			</div>
  		</form>
      <form action="" method="post">
        <button style="margin-left: 44%;" class="btn btn-danger btn-lg" name="delete_seller" value="<?php echo $seller_id; ?>" type="submit" style="margin-top: 1em;"><span class="glyphicon glyphicon-delete" aria-hidden="true"></span>Izbriši prodajalca</button>
      </form>


		</div>
	</div><!-- Content will go here -->
	<script src="../javascripts/jquery.min.js">
	</script>
	<script src="../bootstrap/js/bootstrap.min.js">
	</script>
</body>
</html>
