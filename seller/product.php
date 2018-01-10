<?php
include('../config.php');
session_start();
$product_id = $_GET["id"];
try {
	$statement = $pdo->prepare("SELECT * FROM prodajalec WHERE ID = ?");
	$statement->bindValue(1, $_SESSION["user_ID"]);
	$statement->execute();
	$user = $statement->fetch();
} catch (PDOException $e) {
	echo "Napaka pri poizvedbi: {$e->getMessage()}";
}
try {
	$statement = $pdo->prepare("SELECT * FROM izdelek WHERE ID = ?");
	$statement->bindValue(1, $product_id);
	$statement->execute();
	$product = $statement->fetch();
} catch (PDOException $e) {
	echo "Napaka pri poizvedbi: {$e->getMessage()}";
}
$message = "";
if (isset($_POST["submit"])) {
	$target_dir = "../pictures/";
	$target_file = $target_dir . basename($_FILES["image"]["name"]);
	$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

	$name = $_POST['name'];
	$price = $_POST['price'];
	$description = $_POST['description'];
	$brand = $_POST['brand'];
	$os = $_POST['os'];
	$screen = $_POST['screen'];
	$status = $_POST['status'];
	try {
    $statement = $pdo->prepare("UPDATE izdelek SET Ime = ?, Cena = ?, Opis = ?, Proizvajalec = ?, Operacijski_sistem = ?, Velikost_zaslona = ?, Ime_slike = ?, Status = ? WHERE ID = ?");
		$statement->bindValue(1, $name);
	  $statement->bindValue(2, $price);
	  $statement->bindValue(3, $description);
	  $statement->bindValue(4, $brand);
	  $statement->bindValue(5, $os);
	  $statement->bindValue(6, $screen);
	  $statement->bindValue(7, $_FILES["image"]["name"]);
		$statement->bindValue(8, $status);
    $statement->bindValue(9, $product_id);
		$statement->execute();

	  if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
	      $message = '<div class="alert alert-success col-centered" style="text-align:center" role="alert"><strong>Izdelek je bil posodobljen.</strong></div>';
	  } else {
	    $message = '<div class="alert alert-danger col-centered" style="text-align:center" role="alert"><strong>Izdelek ni bil posodobljen.</strong></div>';
	  }
	} catch (PDOException $e) {
	  $message = '<div class="alert alert-danger col-centered" style="text-align:center" role="alert"><strong>Napaka pri poizvedbi'.$e->getMessage().'</strong></div>';
	}
}
if (isset($_POST["product_delete"])) {
  try {
    $statement = $pdo->prepare("DELETE FROM izdelek WHERE ID = ?");
    $statement->bindValue(1, $product_id);
    $statement->execute();
    echo '<script type="text/javascript">alert("Izdelek je bil izbrisan");</script>';
    header("location: main.php");
  } catch (PDOException $e) {
    $message = '<div class="alert alert-danger col-centered" style="text-align:center" role="alert"><strong>Napaka pri poizvedbi'.$e->getMessage().'</strong></div>';
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
      <h2 style="text-align: center;"><strong>Podatki o izdelku:</strong></h2>
  		<?php echo $message; ?>
  		<form class="form-horizontal" method="post" action="" enctype="multipart/form-data">
  			<div class="form-group">
  				<label class="col-sm-5 control-label" for="name">Naziv</label>
  				<div class="col-sm-3">
  					<input class="form-control" id="name" name="name" placeholder="" type="text" value="<?php echo $product["Ime"]?>">
  				</div>
  			</div>
  			<div class="form-group">
  				<label class="col-sm-5 control-label" for="price">Cena</label>
  				<div class="col-sm-3">
  					<input class="form-control" id="price" name="price" placeholder="" type="text" value="<?php echo $product["Cena"]?>">
  				</div>
  			</div>
  			<div class="form-group">
  				<label class="col-sm-5 control-label" for="description">Opis</label>
  				<div class="col-sm-3">
  					<textarea class="form-control" rows="5" id="description" name="description"><?php echo $product["Opis"]?></textarea>
  				</div>
  			</div>
  			<div class="form-group">
  				<label class="col-sm-5 control-label" for="brand">Proizvajalec</label>
  				<div class="col-sm-3">
  					<input class="form-control" id="brand" name="brand" placeholder="" type="text" value="<?php echo $product["Proizvajalec"]?>">
  				</div>
  			</div>
  			<div class="form-group">
  				<label class="col-sm-5 control-label" for="os">Operacijski sistem</label>
  				<div class="col-sm-3">
  					<input class="form-control" id="os" name="os" placeholder="" type="text" value="<?php echo $product["Operacijski_sistem"]?>">
  				</div>
  			</div>
  			<div class="form-group">
  				<label class="col-sm-5 control-label" for="screen">Velikost Zaslona</label>
  				<div class="col-sm-3">
  					<input class="form-control" id="screen" name="screen" placeholder="" type="text" value="<?php echo $product["Velikost_zaslona"]?>">
  				</div>
  			</div>
  			<div class="form-group">
  				<label class="col-sm-5 control-label" for="image">Naloži sliko</label>
  				<div class="col-sm-3">
  					<input id="image" type="file" name="image">
  				</div>
  			</div>
  			<div class="form-group">
  				<label class="col-sm-5 control-label" for="status">Status</label>
  				<div class="col-sm-3">
  					<select name="status">
              <?php
                if ($product["Status"] == "Aktiven") {
                  echo '<option value="Aktiven" selected>Aktiven</option>
      						      <option value="Neaktiven">Neaktiven</option>';
                }
                else {
                  echo '<option value="Aktiven">Aktiven</option>
      						      <option value="Neaktiven" selected>Neaktiven</option>';
                }
              ?>
  					</select>
  				</div>
  			</div>
  			<div class="button" style="text-align:center">
  				<input class="btn btn-primary btn-lg" name="submit" type="submit" value="Posodobi">
  			</div>
  		</form>
      <form action="" method="post">
        <button style="margin-left: 44%;" class="btn btn-danger btn-lg" name="product_delete" value="<?php echo $product_id; ?>" type="submit" style="margin-top: 1em;"><span class="glyphicon glyphicon-delete" aria-hidden="true"></span> Izbriši izdelek</button>
      </form>


		</div>
	</div><!-- Content will go here -->
	<script src="../javascripts/jquery.min.js">
	</script>
	<script src="../bootstrap/js/bootstrap.min.js">
	</script>
</body>
</html>
