<?php
   include("config.php");
   session_start();
   $error = "";

   try {
   	$statement = $pdo->prepare("SELECT * FROM kupec WHERE ID = ?");
   	$statement->bindValue(1, $_SESSION["user_ID"]);
   	$statement->execute();
   	$user = $statement->fetch();
   } catch (PDOException $e) {
   	echo "Napaka pri poizvedbi: {$e->getMessage()}";
   }
   try {
   	$statement = $pdo->prepare("SELECT * FROM nakup WHERE ID_kupec = ?");
   	$statement->bindValue(1, $_SESSION["user_ID"]);
   	$statement->execute();
    if ($statement->rowCount() != 0) {
      $orders = ' <p>(Za prikaz izdelkov kliknite na vrstico)</p>
                  <table width="100%">
                  <tr>
                    <th style="font-size:1.5em">Številka naročila</th>
                    <th style="font-size:1.5em">Datum oddaje</th>
                    <th style="font-size:1.5em">Izdelki</th>
                    <th style="font-size:1.5em">Cena</th>
                    <th style="font-size:1.5em">Stanje</th>
                  </tr>';
      foreach ($statement->fetchAll() as $order) {
        $order_id = $order["ID"];
        $orders .= '<tr>
                      <td><strong>'.$order["ID"].'</strong></td>
                      <td><strong>'.$order["Datum"].'</strong></td>
                      <td>
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#'.$order["ID"].'"> Izdelki</button>
                        <div class="modal fade" id="'.$order["ID"].'" role="dialog">
                          <div class="modal-dialog">
                            <div class="modal-content">
                              <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title"><strong>Naročeni izdelki</strong></h4>
                              </div>
                              <div class="modal-body">
                                <div class="col-md-12">
                                  <table width="100%">
                                  <tr>
                                    <th>Izdelek</th>
                                    <th>Količina</th>
                                    <th>Cena</th>
                                  </tr>';

        try {
          $stmt = $pdo->prepare("SELECT * FROM izdelek_nakupa WHERE ID_nakup = ?");
          $stmt->bindValue(1, $order_id);
          $stmt->execute();
          foreach ($stmt->fetchAll() as $item) {

            try {
              $st = $pdo->prepare("SELECT * FROM izdelek WHERE ID = ?");
              $st->bindValue(1, $item["ID_izdelek"]);
              $st->execute();
              $phone = $st->fetch();
              $orders .= '<tr>
                            <td><strong>'.$phone["Ime"].'</strong></td>
                            <td><strong>'.$item["Kolicina"].'</strong></td>
                            <td><strong>'.$phone["Cena"].'€</strong></td>
                          </tr>
                          ';


            } catch (PDOException $e) {
              echo "Napaka pri poizvedbi: {$e->getMessage()}";
            }
          }
        } catch (PDOException $e) {
          echo "Napaka pri poizvedbi: {$e->getMessage()}";
        }
        $orders .= '                  </table>
                                        </div>
                                      </div>
                                      <div class="modal-footer">
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </td>
                              <td><strong>'.$order["Cena"].'€</strong></td>
                              <td><strong>'.$order["Status"].'</strong></td>
                            </tr>';

      }
      $orders .=   '</table>';
    } else {
      $orders = '<h4>Nimate preteklih nakupov</h4>';
    }
   } catch (PDOException $e) {
   	echo "Napaka pri poizvedbi: {$e->getMessage()}";
   }

?>
<html>
<head>
	<meta charset="UTF-8">
	<meta content="width=device-width, initial-scale=1" name="viewport">
	<link href="mobilko_favicon.png" rel="shortcut icon" type="image/png">
	<title>Mobilko | Pretekla naročila</title>
	<link href="bootstrap/css/lumen.bootstrap.min.css" rel="stylesheet">
	<link href="css/main.css" rel="stylesheet">
  <style media="screen">
  table, th, td {
      padding: 5px;
      border: 1px solid black;
      cursor: default;
      border-left: none;
      border-right: none;
   }
  </style>
</head>
<body style="background-color: lightblue">
	<div class="container">
		<nav class="navbar fixed-top navbar-inverse">
			<div class="container-fluid">
				<a class="navbar-brand" href="logged_in.php">Nazaj</a>
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
    <div class="container-fluid" style="background-color: white; height: 100%;">
      <div class="container-fluid" style="margin-top: 2em;">
        <h2>Pretekla naročila:</h2>
        <?php echo $orders; ?>
        </div>
      </div>
    </div>
	</div><!-- Content will go here -->
  <script src="javascripts/jquery.min.js">
  </script>
  <script src="bootstrap/js/bootstrap.min.js">
  </script>
  <script async defer src="https://www.google.com/recaptcha/api.js">
  </script>
</body>
</html>
