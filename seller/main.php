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
      <h2>Neobdelani nakupi:</h2>
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
              window.location = "order.php?id="+data_id;
     		});
      });
	</script>
</body>
</html>
