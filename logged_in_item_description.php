<?php
   include("config.php");
   session_start();
   $error = "";

   $id = $_GET["id"];
   $url = "location: logged_in_item_description.php?id=".$id;

   $statement = $pdo->prepare("SELECT * FROM izdelek WHERE ID = ?");
   $statement->bindValue(1, $id);
   $statement->execute();

   $product = $statement->fetch();
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
   		$basket = '<table>';
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
   												<td><form action="" method="POST"><button type="submit" name="delete" value="'.$row["ID"].'"><span id="delete" class="glyphicon glyphicon-remove-circle" style="color:red;"aria-hidden="true"></span></button></form></td>
   												<td><strong>'.$item["Ime"].'</strong></td>
   												<td><div class="amount-container""><form action="" method="POST"><input type="number" name="quantity" min="1" max="5" value="'.$row["Kolicina"].'" style="margin-right:2em;""><button type="submit" class="amount" name="amount" id="amount" value="'.$row["ID"].'"><span class="glyphicon glyphicon-refresh" style="color:blue;"aria-hidden="true"></span></button></form></div></td>
   												<td><strong>'.$price .'€</strong></td>
   											</tr>';

   				} catch (PDOException $e) {
   					echo "Napaka pri poizvedbi: {$e->getMessage()}";
   				}
   			}
   		}
   		$basket .= '</table>
   								<p style="text-align: right; font-size: 1.5em;"><strong>Skupaj: '. $basket_value.'€</strong></p>
   								<a href="checkout/review.php" class="btn btn-success btn-lg pull-right" role="button" style="margin-bottom: 1em;"><span class="glyphicon glyphicon-shopping-cart" aria-hidden="true"></span> Na blagajno</a>';
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
?>
<html>
<head>
	<meta charset="UTF-8">
	<meta content="width=device-width, initial-scale=1" name="viewport">
	<link href="mobilko_favicon.png" rel="shortcut icon" type="image/png">
	<title>Mobilko | <?php echo $product["Ime"]; ?></title>
	<link href="bootstrap/css/lumen.bootstrap.min.css" rel="stylesheet">
	<link href="css/main.css" rel="stylesheet">
  <style media="screen">
  table, th, td {
      padding: 5px;
      border: 1px solid black;
      cursor: default;
   }
   #delete:hover {
    cursor: pointer;
   }
   #basket-dp{
       min-width: 500px;
       padding: 14px 14px 0;
       overflow:hidden;
       background-color:rgba(255,255,255,.9);
   }
   #basket-dp .help-block{
       font-size:12px
   }
   #basket-dp .bottom{
       background-color:rgba(255,255,255,.9);
       border-top:1px solid #ddd;
       clear:both;
       padding:14px;
   }
   #basket-dp .form-group {
       margin-bottom: 10px;
   }
   @media(max-width:768px){
       #basket-dp{
           background-color: inherit;
           color: #fff;
       }
       #basket-dp .bottom{
           background-color: inherit;
           border-top:0 none;
       }
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
				<a class="navbar-brand" href="logged_in.php">Nazaj</a>
				<ul class="nav fixed-top navbar-nav navbar-right">
          <li class="dropdown">
						<a class="dropdown-toggle" data-toggle="dropdown" href="account.html"><b><span class="glyphicon glyphicon-user"></span> <?php  echo $user["Ime"].' '.$user["Priimek"]; ?></b> <span class="caret"></span></a>
						<ul class="dropdown-menu" id="login-dp">
							<li>
								<div class="row">
									<div class="col-md-12">
										<button class="btn btn-info btn-block" name="submit" onclick="window.location.href='orders.php'" type="submit">Naročila</button>
										<button class="btn btn-danger btn-block" name="submit" onclick="window.location.href='index.php'" type="submit">Odjava</button><br>
									</div>
								</div>
							</li>
						</ul>
					</li>
          <li class="dropdown">
						<a class="btn btn-success dropsown-toggle" data-toggle="dropdown" href="#"><span class="glyphicon glyphicon-shopping-cart" aria-hidden="true"></span><strong> Košarica</strong> <span class="badge"><?php echo $basket_value.'€'; ?></span></a>
						<ul class="dropdown-menu" id="basket-dp">
							<li>
								<div class="row">
									<div class="col-md-12">
										<?php echo $basket; ?>
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
        <div class="col-sm-12 col-md-6">
          <img src="pictures/<?php echo $product["Ime_slike"]; ?>" alt="" width="484px" height="484px">
        </div>
        <div class="col-sm-12 col-md-6">
          <div class="container-fluid" style="background-color: lightgrey; margin: 1.5em; border-radius:1em;">
            <h2 class="pull-left" style="color:black"><strong><?php echo $product["Ime"];?></strong></h2>
            <br>
            <br>
            <p><strong>Proizvajalec: <?php echo $product["Proizvajalec"]?></strong></p>
            <?php
              $basket_button = "";
              if ($product["Zaloga"] > 1) {
                echo '<h3><span class="label label-success">Na zalogi <span class="badge">'. $product["Zaloga"] .'</span></span></h3>';
              }
              else if ($product["Zaloga"] == 1) {
                echo '<h3><span class="label label-warning">Zadnji kos <span class="badge">'. $product["Zaloga"] .'</span></span></h3>';
              }
              if ($product["Zaloga"] == 0) {
                $basket_button = "disabled";
                echo '<h3><span class="label label-danger">Ni na zalogi <span class="badge">'. $product["Zaloga"] .'</span></span></h3>';
              }
             ?>
             <h3 style="color:black; margin-bottom: 0em; margin-top: 2em;"><strong>Cena:</strong></h3>
             <h2 style="color:red; margin-top: 0em;"><strong><?php echo $product["Cena"] ?>€</strong></h2>
             <form action="" method="POST">
               <button class="btn btn-success btn-lg" name="item_submit" value="<?php echo $product["ID"];?>" type="submit" style="margin-bottom: 5em"><span class="glyphicon glyphicon-shopping-cart" aria-hidden="true"></span> V košarico</button>
             </form>
          </div>
        </div>
        <div class="col-md-12">
          <div class="container-fluid">
            <h1 style="color:black"><strong>OPIS:</strong></h1>
            <div class="container-fluid" style="background-color: lightgrey; margin-bottom:3em; border-radius:1em;">
              <br>
              <p><strong><?php echo $product["Opis"]; ?></strong</p>
            </div>
          </div>
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
