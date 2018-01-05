<?php
   include("../config.php");
   session_start();
   $error = "";

   $url = "location: review.php";

   $date = date("Y-m-d H:i:s");
   $basket_value = 0.0;

   function delete_outdated_basket($basket_id){
   	try {
   		$statement = $pdo->prepare("DELETE FROM kosarica WHERE ID = ?");
   		$statement->bindValue(1, $basket_id);
   		$statement->execute();
   	} catch (PDOException $e) {
   		echo "Napaka pri poizvedbi: {$e->getMessage()}";
   	}
   }
   try {
   	$statement = $pdo->prepare("SELECT * FROM kupec WHERE ID = ?");
   	$statement->bindValue(1, $_SESSION["user_ID"]);
   	$statement->execute();
   	$user = $statement->fetch();
   } catch (PDOException $e) {
   	echo "Napaka pri poizvedbi: {$e->getMessage()}";
   }
   try {
   	$statement = $pdo->prepare("SELECT * FROM kosarica WHERE ID_kupec = ?");
   	$statement->bindValue(1, $_SESSION["user_ID"]);
   	$statement->execute();
   	if ($statement->rowCount() == 0) {
   		$basket = '<p style="text-align:center"><strong>Vaša košarica je prazna.</strong></p>';
   	}
   	else {
   		$basket = ' <h1>Povzetek nakupa:</h1>
                  <table width="100%">
                  <tr>
                    <th></th>
                    <th></th>
                    <th>Izdelek</th>
                    <th>Cena</th>
                    <th>Količina</th>
                    <th>Skupaj</th>
                  </tr>';
   		$time_elapsed = '86400';
   		foreach ($statement->fetchAll() as $row) {
   			$time_past = strtotime($row["datum"]) + $time_elapsed;
   			if ($time_past < $date) {
   				delete_outdated_basket($row["ID"]);
   			}
   			else {
   				try {
   					$statement_item = $pdo->prepare("SELECT * FROM izdelek WHERE ID = ?");
   					$statement_item->bindValue(1, $row["ID_izdelek"]);
   					$statement_item->execute();
   					$item = $statement_item->fetch();
   					$price = $row["Kolicina"] * $item["Cena"];
   					$basket_value += $price;
   					$basket .= '<tr>
   												<td><form action="" method="POST"><button type="submit" class="btn btn-danger" name="delete" value="'.$row["ID"].'">Odstrani</button></form></td>
                          <td><img src="../pictures/'.$item["Ime_slike"].'" alt="" width="120px" height="120px"></td>
   												<td><strong>'.$item["Ime"].'</strong></td>
                          <td><strong>'.$item["Cena"] .'€</strong></td>
   												<td><div class="amount-container""><form action="" method="POST"><input type="number" name="quantity" min="1" max="5" value="'.$row["Kolicina"].'" style="margin-right:2em;""><button type="submit" class="amount" name="amount" id="amount" value="'.$row["ID"].'"><span class="glyphicon glyphicon-refresh" style="color:blue;"aria-hidden="true"></span></button></form></div></td>
   												<td><strong>'.$price .'€</strong></td>
   											</tr>';

   				} catch (PDOException $e) {
   					echo "Napaka pri poizvedbi: {$e->getMessage()}";
   				}
   			}
   		}
      $shipping_cost = 0.0;
      if ($basket_value < 570.0) {
        $shipping_cost = 5.49;
      }
      $total = $basket_value + $shipping_cost;
   		$basket .= '<tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td><p style=" font-size: 1.5em;"><strong>Skupaj:</strong></p></td>
                    <td><p style=" font-size: 1.5em;"><strong>'.$basket_value .'€</strong></p></td>
                  </tr>
                  <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td><p style=" font-size: 1em;"><strong>Dostava:</strong></p></td>
                    <td><strong>'.$shipping_cost.'€</strong></td>
                  </tr>
                  <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td><p style="color:red; font-size: 2em;"><strong>Skupaj za plačilo:</strong></p></td>
                    <td><p style="color:red; font-size: 2em;"><strong>'.$total.'€</strong></p></td>
                  </tr>
                  </table>
                  <form action="" method="POST">
                    <button class="btn btn-success btn-lg pull-right" name="order_submit" value="'. $_SESSION["user_ID"].'" type="submit" style="margin-top: 1em;"><span class="glyphicon glyphicon-check" aria-hidden="true"></span> Potrdi nakup</button>
                  </form>';
   	}
   } catch (PDOException $e) {
   	echo "Napaka pri poizvedbi: {$e->getMessage()}";
   }
   if(isset($_POST['item_submit'])) {
   	$item_id = $_POST['item_submit'];
   	try {
     		$statement = $pdo->prepare("INSERT INTO kosarica (ID_kupec, ID_izdelek) VALUES (?, ?)");
     		$statement->bindValue(1, $_SESSION["user_ID"]);
     		$statement->bindValue(2, $item_id);
     		$statement->execute();
     		header($url);
     	} catch (PDOException $e) {
     		echo "Napaka pri poizvedbi: {$e->getMessage()}";
     	}
     }
     if(isset($_POST['delete'])) {
     	$item_id = $_POST['delete'];
     	try {
     		$statement = $pdo->prepare("DELETE FROM kosarica WHERE ID = ?");
     		$statement->bindValue(1, $item_id);
     		$statement->execute();
     		header($url);
     	} catch (PDOException $e) {
     		echo "Napaka pri poizvedbi: {$e->getMessage()}";
     	}
     }
     if(isset($_POST['amount'])) {
     	$item_id = $_POST['amount'];
     	$quantity = $_POST['quantity'];
     	try {
     		$statement = $pdo->prepare("UPDATE kosarica SET Kolicina = ? WHERE ID = ?");
     		$statement->bindValue(1, $quantity);
     		$statement->bindValue(2, $item_id);
     		$statement->execute();
        header($url);
     	} catch (PDOException $e) {
     		echo "Napaka pri poizvedbi: {$e->getMessage()}";
     	}
     }
     if(isset($_POST['order_submit'])) {
     	$buyer_id = $_POST['order_submit'];
      try {
     		$statement = $pdo->prepare("INSERT INTO nakup (ID_kupec, Cena, Status) VALUES (?, ?, ?)");
     		$statement->bindValue(1, $buyer_id);
        $statement->bindValue(2, $total);
        $statement->bindValue(3, "V obdelavi");
     		$statement->execute();
        try {
       		$statement = $pdo->prepare("SELECT LAST_INSERT_ID()");
       		$statement->execute();
          $result = $statement->fetch();
          try {
         		$statement = $pdo->prepare("SELECT * FROM kosarica WHERE ID_kupec = ?");
            $statement->bindValue(1, $buyer_id);
         		$statement->execute();
            foreach ($statement->fetchAll() as $row) {
              $stmt = $pdo->prepare("INSERT INTO izdelek_nakupa (ID_nakup, ID_izdelek, Kolicina) VALUES (?, ?, ?)");
              $stmt->bindValue(1, $result["LAST_INSERT_ID()"]);
              $stmt->bindValue(2, $row["ID_izdelek"]);
              $stmt->bindValue(3, $row["Kolicina"]);
           		$stmt->execute();
            }
            try {
           		$statement = $pdo->prepare("DELETE FROM kosarica WHERE ID_kupec = ?");
              $statement->bindValue(1, $buyer_id);
           		$statement->execute();
              $basket = '<div class="jumbotron">
                            <h1>Naročilo uspešno oddano!</h1>
                            <p>Zahvaljujemo se vam za oddano naročilo. Obdelali ga bomo v najkrajšem možnem času.</p>
                            <p><a class="btn btn-primary btn-lg" href="#" role="button">Pregled naročil</a></p>
                        </div>';
           	} catch (PDOException $e) {
           		echo "Napaka pri poizvedbi: {$e->getMessage()}";
           	}
         	} catch (PDOException $e) {
         		echo "Napaka pri poizvedbi: {$e->getMessage()}";
         	}
       	} catch (PDOException $e) {
       		echo "Napaka pri poizvedbi: {$e->getMessage()}";
       	}
     	} catch (PDOException $e) {
     		echo "Napaka pri poizvedbi: {$e->getMessage()}";
     	}
     }
?>
<html>
<head>
	<meta charset="UTF-8">
	<meta content="width=device-width, initial-scale=1" name="viewport">
	<link href="../mobilko_favicon.png" rel="shortcut icon" type="image/png">
	<title>Mobilko | Povzetek naročila</title>
	<link href="../bootstrap/css/lumen.bootstrap.min.css" rel="stylesheet">
	<link href="../css/main.css" rel="stylesheet">
  <style media="screen">
  table, th, td {
      padding: 5px;
      border: 1px solid black;
      cursor: default;
      border-left: none;
      border-right: none;
   }
   #delete:hover {
    cursor: pointer;
   }

   .amount-container {
     display: inline-block;
     position:relative;
   }
   .amount {
    position: absolute;
     top: 0;
     left: 50px;
     transition: left 0.2s;
   }
   .show {
     left: 5px;
   }
  </style>
	<script type="text/javascript">
	 function dropdown(){
	   $('#dropdown').addClass('open');
	 }

	</script>
</head>
<body style="background-color: lightblue">
	<div class="container">
		<nav class="navbar fixed-top navbar-inverse">
			<div class="container-fluid">
				<a class="navbar-brand" href="../logged_in.php">Nazaj</a>
				<ul class="nav fixed-top navbar-nav navbar-right">
          <li class="dropdown">
						<a class="dropdown-toggle" data-toggle="dropdown" href="account.html"><b><span class="glyphicon glyphicon-user"></span> <?php  echo $user["Ime"].' '.$user["Priimek"]; ?></b> <span class="caret"></span></a>
						<ul class="dropdown-menu" id="login-dp">
							<li>
								<div class="row">
									<div class="col-md-12">
										<button class="btn btn-info btn-block" name="submit" onclick="window.location.href='#'" type="submit">Račun</button>
										<button class="btn btn-info btn-block" name="submit" onclick="window.location.href='#'" type="submit">Naročila</button>
										<button class="btn btn-info btn-block" name="submit" onclick="window.location.href='#'" type="submit">Naslovi</button>
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
        <?php echo $basket; ?>
        </div>
      </div>
    </div>
	</div><!-- Content will go here -->
	<script src="../javascripts/jquery.min.js">
	</script>
	<script src="../bootstrap/js/bootstrap.min.js">
	</script>
	<script async defer src="https://www.google.com/recaptcha/api.js">
	</script>
</body>
</html>
