<?php
include('../config.php');
session_start();

try {
	$statement = $pdo->prepare("SELECT * FROM prodajalec WHERE ID = ?");
	$statement->bindValue(1, $_SESSION["user_ID"]);
	$statement->execute();
	$user = $statement->fetch();
} catch (PDOException $e) {
	echo "Napaka pri poizvedbi: {$e->getMessage()}";
}
$submitted_orders = "";
try {
	$statement = $pdo->prepare("SELECT * FROM nakup WHERE NOT Status = ?");
	$statement->bindValue(1, "V dostavi");
	$statement->execute();
	foreach ($statement->fetchAll() as $order) {
    $submitted_orders .= '<tr class="clickable-row" data-id="'.$order["ID"].'">
                            <td>'.$order["ID"].'</td>
                            <td>'.$order["Datum"].'</td>
                            <td>'.$order["Status"].'</td>
                          </tr>';
  }
} catch (PDOException $e) {
	echo "Napaka pri poizvedbi: {$e->getMessage()}";
}
$products = "";
try {
	$statement = $pdo->prepare("SELECT * FROM izdelek");
	$statement->execute();
	foreach ($statement->fetchAll() as $product) {
    $products .= '<tr class="clickable-product" data-id="'.$product["ID"].'">
                            <td>'.$product["ID"].'</td>
                            <td>'.$product["Ime"].'</td>
                            <td>'.$product["Proizvajalec"].'</td>
														<td>'.$product["Cena"].'</td>
														<td>'.$product["Status"].'</td>
                          </tr>';
  }
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
		$statement = $pdo->prepare("INSERT INTO izdelek (Ime, Cena, Opis, Proizvajalec, Operacijski_sistem, Velikost_zaslona, Ime_slike, Status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
		$statement->bindValue(1, $name);
	  $statement->bindValue(2, $price);
	  $statement->bindValue(3, $description);
	  $statement->bindValue(4, $brand);
	  $statement->bindValue(5, $os);
	  $statement->bindValue(6, $screen);
	  $statement->bindValue(7, $_FILES["image"]["name"]);
		$statement->bindValue(8, $status);
		$statement->execute();

	  if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
	      $message = '<div class="alert alert-success col-centered" style="text-align:center" role="alert"><strong>Izdelek je bil dodan.</strong></div>';
	  } else {
	    $message = '<div class="alert alert-danger col-centered" style="text-align:center" role="alert"><strong>Izdelek ni bil dodan.</strong></div>';
	  }
	} catch (PDOException $e) {
	  $message = '<div class="alert alert-danger col-centered" style="text-align:center" role="alert"><strong>Napaka pri poizvedbi'.$e->getMessage().'</strong></div>';
	}
}
$buyers = "";
try {
	$statement = $pdo->prepare("SELECT * FROM kupec");
	$statement->execute();
	foreach ($statement->fetchAll() as $buyer) {
    $buyers .= '<tr class="clickable-buyer" data-id="'.$buyer["ID"].'">
                            <td>'.$buyer["ID"].'</td>
                            <td>'.$buyer["Ime"].'</td>
														<td>'.$buyer["Priimek"].'</td>
                            <td>'.$buyer["Email"].'</td>
                          </tr>';
  }
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
		$statement = $pdo->prepare("INSERT INTO kupec (Ime, Priimek, Email, Naslov, Telefonska_stevilka, Password) VALUES (?, ?, ?, ?, ?, ?)");
  	$statement->bindValue(1, $name);
    $statement->bindValue(2, $surname);
    $statement->bindValue(3, $email);
    $statement->bindValue(4, $address);
    $statement->bindValue(5, $phone);
    $statement->bindValue(6, $password);
  	$statement->execute();

    $message_buyer = '<div class="alert alert-success col-centered" style="text-align:center" role="alert"><strong>Uporabnik je bil dodan.</strong></div>';
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
  <link href="https://cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css" rel="stylesheet">
  <link href="https://cdn.datatables.net/select/1.2.2/css/select.dataTables.min.css" rel="stylesheet">
	<link href="../css/main.css" rel="stylesheet">
</head>
<body>
	<div class="container">
		<nav class="navbar sticky-topa navbar-inverse">
			<div class="container-fluid">
				<a class="navbar-brand" href="index.php"><span><img src="../mobilko_favicon.png" alt="" style="max-width:9%;"></span>Mobilko</a>
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
      <h2 style="text-align: center;"><strong>Neobdelani nakupi:</strong></h2>
      <table id="table" class="display" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>Številka nakupa</th>
                <th>Datum oddaje</th>
                <th>Status</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
              <th>Številka nakupa</th>
              <th>Datum oddaje</th>
              <th>Status</th>
            </tr>
        </tfoot>
        <tbody>
          <?php
            echo $submitted_orders;
          ?>
        </tbody>
    </table>
		<br>
		<br>
		<br>
		<br>
		<hr>
		<h2 style="text-align: center;"><strong>Izdelki:</strong></h2>
		<table id="table-products" class="display" cellspacing="0" width="100%">
			<thead>
					<tr>
							<th>Šifra izdelka</th>
							<th>Naziv</th>
							<th>Proizvajalec</th>
							<th>Cena</th>
							<th>Status</th>
					</tr>
			</thead>
			<tfoot>
					<tr>
						<th>Šifra izdelka</th>
						<th>Naziv</th>
						<th>Proizvajalec</th>
						<th>Cena</th>
						<th>Status</th>
					</tr>
			</tfoot>
			<tbody>
				<?php
					echo $products;
				?>
			</tbody>
	</table>
	<br>
	<br>
	<br>
	<br>
		<h2 style="text-align: center;"><strong>Dodajanje izdelka:</strong></h2>
		<?php echo $message; ?>
		<form class="form-horizontal" method="post" action="" enctype="multipart/form-data">
			<div class="form-group">
				<label class="col-sm-5 control-label" for="name">Naziv</label>
				<div class="col-sm-3">
					<input class="form-control" id="name" name="name" placeholder="" type="text" value="">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-5 control-label" for="price">Cena</label>
				<div class="col-sm-3">
					<input class="form-control" id="price" name="price" placeholder="" type="text" value="">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-5 control-label" for="description">Opis</label>
				<div class="col-sm-3">
					<textarea class="form-control" rows="5" id="description" name="description"></textarea>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-5 control-label" for="brand">Proizvajalec</label>
				<div class="col-sm-3">
					<input class="form-control" id="brand" name="brand" placeholder="" type="text" value="">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-5 control-label" for="os">Operacijski sistem</label>
				<div class="col-sm-3">
					<input class="form-control" id="os" name="os" placeholder="" type="text" value="">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-5 control-label" for="screen">Velikost Zaslona</label>
				<div class="col-sm-3">
					<input class="form-control" id="screen" name="screen" placeholder="" type="text" value="">
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
						<option value="Aktiven" selected>Aktiven</option>
						<option value="Neaktiven">Neaktiven</option>
					</select>
				</div>
			</div>
			<div class="button" style="text-align:center">
				<input class="btn btn-primary btn-lg" name="submit" type="submit" value="Dodaj izdelek">
			</div>
		</form>
		<br>
		<br>
		<br>
		<br>
		<hr>
		<h2 style="text-align: center;"><strong>Uporabniki:</strong></h2>
		<table id="table-buyers" class="display" cellspacing="0" width="100%">
			<thead>
					<tr>
							<th>Šifra uporabnika</th>
							<th>Ime</th>
							<th>Priimek</th>
							<th>Email</th>
					</tr>
			</thead>
			<tfoot>
					<tr>
						<th>Šifra uporabnika</th>
						<th>Ime</th>
						<th>Priimek</th>
						<th>Email</th>
					</tr>
			</tfoot>
			<tbody>
				<?php
					echo $buyers;
				?>
			</tbody>
	</table>
	<br>
	<br>
	<br>
	<br>
		<h2 style="text-align: center;"><strong>Dodajanje uporabnika:</strong></h2>
		<?php echo $message_buyer; ?>
		<form class="form-horizontal" method="post" role="form">
			<div class="form-group">
				<label class="col-sm-5 control-label" for="name">Ime</label>
				<div class="col-sm-3">
					<input class="form-control" id="name" name="name" placeholder="" type="text" value="" required>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-5 control-label" for="name">Priimek</label>
				<div class="col-sm-3">
					<input class="form-control" id="surname" name="surname" placeholder="" type="text" value="" required>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-5 control-label" for="email">Email</label>
				<div class="col-sm-3">
					<input class="form-control" id="email" name="email" placeholder="" type="email" value="" required>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-5 control-label" for="address">Naslov</label>
				<div class="col-sm-3">
					<input class="form-control" id="address" name="address" placeholder="" type="text" value="" required>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-5 control-label" for="phone">Telefon</label>
				<div class="col-sm-3">
					<input class="form-control" id="phone" name="phone" placeholder="" type="text" value="" required>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-5 control-label" for="name">Geslo</label>
				<div class="col-sm-3">
					<input class="form-control" id="password" name="password" placeholder="" type="password" value="" required>
				</div>
			</div>
			<div class="button" style="text-align:center">
				<input class="btn btn-primary btn-lg" name="submit_buyer" type="submit" value="Dodaj uporabnika">
			</div>
		</form>


		</div>
	</div><!-- Content will go here -->
	<script src="../javascripts/jquery.min.js">
	</script>
	<script src="../bootstrap/js/bootstrap.min.js">
	</script>
  <script src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/select/1.2.2/js/dataTables.select.min.js"></script>
  <script>
      $(document).ready(function(){
        $('#table').DataTable({
          columnDefs: [{
              orderable: true,
              targets:   0
          }],
          order: [[1, 'asc']]
        });
        $('#table-products').DataTable({
          columnDefs: [{
              orderable: true,
              targets:   0
          }],
          order: [[0, 'asc']]
        });
				$('#table-buyers').DataTable({
          columnDefs: [{
              orderable: true,
              targets:   0
          }],
          order: [[0, 'asc']]
        });
        $('.clickable-row').click(function(){
      		var data_id = $(this).data('id');
              window.location = "order.php?id="+data_id;
     		});
				$('.clickable-product').click(function(){
      		var data_id = $(this).data('id');
              window.location = "product.php?id="+data_id;
     		});
				$('.clickable-buyer').click(function(){
      		var data_id = $(this).data('id');
              window.location = "buyer.php?id="+data_id;
     		});
      });
	</script>
</body>
</html>
