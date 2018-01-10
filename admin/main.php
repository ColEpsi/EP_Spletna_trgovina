<?php
include('../config.php');
session_start();

try {
	$statement = $pdo->prepare("SELECT * FROM administrator WHERE ID = ?");
	$statement->bindValue(1, $_SESSION["user_ID"]);
	$statement->execute();
	$user = $statement->fetch();
} catch (PDOException $e) {
	echo "Napaka pri poizvedbi: {$e->getMessage()}";
}
$sellers = "";
try {
	$statement = $pdo->prepare("SELECT * FROM prodajalec");
	$statement->execute();
	foreach ($statement->fetchAll() as $seller) {
    $sellers .= '<tr class="clickable-row" data-id="'.$seller["ID"].'">
                            <td>'.$seller["ID"].'</td>
                            <td>'.$seller["Ime"].'</td>
														<td>'.$seller["Priimek"].'</td>
                            <td>'.$seller["Email"].'</td>
                          </tr>';
  }
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
		$statement = $pdo->prepare("INSERT INTO prodajalec (Ime, Priimek, Email, Geslo, Status) VALUES (?, ?, ?, ?, ?)");
  	$statement->bindValue(1, $name);
    $statement->bindValue(2, $surname);
    $statement->bindValue(3, $email);
    $statement->bindValue(4, $password);
    $statement->bindValue(5, $status);
  	$statement->execute();

    $message_seller = '<div class="alert alert-success col-centered" style="text-align:center" role="alert"><strong>Prodajalec je bil dodan.</strong></div>';
  } catch (PDOException $e) {
  	$message_seller = '<div class="alert alert-danger col-centered" style="text-align:center" role="alert"><strong>Napaka pri poizvedbi'.$e->getMessage().'</strong></div>';
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


		<h2 style="text-align: center;"><strong>Prodajalci:</strong></h2>
		<table id="table" class="display" cellspacing="0" width="100%">
			<thead>
					<tr>
							<th>Šifra prodajalca</th>
							<th>Ime</th>
							<th>Priimek</th>
							<th>Email</th>
					</tr>
			</thead>
			<tfoot>
					<tr>
						<th>Šifra prodajalca</th>
						<th>Ime</th>
						<th>Priimek</th>
						<th>Email</th>
					</tr>
			</tfoot>
			<tbody>
				<?php
					echo $sellers;
				?>
			</tbody>
	</table>
	<br>
	<br>
	<br>
	<br>
		<h2 style="text-align: center;"><strong>Dodajanje prodajalca:</strong></h2>
		<?php echo $message_seller; ?>
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
				<label class="col-sm-5 control-label" for="name">Geslo</label>
				<div class="col-sm-3">
					<input class="form-control" id="password" name="password" placeholder="" type="password" value="" required>
				</div>
			</div>
      <div class="form-group">
        <label class="col-sm-5 control-label" for="status">Status</label>
        <div class="col-sm-3">
          <select  name="status">
            <option value="active" selected>Active</option>
            <option value="deactivated">Deactivated</option>
          </select>
        </div>
      </div>
			<div class="button" style="text-align:center">
				<input class="btn btn-primary btn-lg" name="submit_seller" type="submit" value="Dodaj prodajalca">
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
        $('.clickable-row').click(function(){
      		var data_id = $(this).data('id');
              window.location = "seller.php?id="+data_id;
     		});

      });
	</script>
</body>
</html>
