<?php
include('config.php');
session_start();

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
		header("location: logged_in.php");
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
		header("location: logged_in.php");
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
		header("location: logged_in.php");
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
	<title>Mobilko | Mobilniki po ugodnih cenah!</title>
	<link href="bootstrap/css/lumen.bootstrap.min.css" rel="stylesheet">
	<link href="css/main.css" rel="stylesheet">
	<style media="screen">

	{
	 -moz-box-sizing: border-box;
	 -webkit-box-sizing: border-box;
	 box-sizing: border-box;
	 margin: 0;
	 padding: 0;
 }


img {
	 max-width: 100%;
	 -moz-transition: all 0.3s;
	 -webkit-transition: all 0.3s;
	 transition: all 0.3s;
 }
img:hover {
	 -moz-transform: scale(1.1);
	 -webkit-transform: scale(1.1);
	 transform: scale(1.1);
	 cursor: pointer;
 }
 #name:hover {
 	cursor: pointer;
 }
 .thumbnail {
 	overflow: hidden;
 }
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
</head>
<body>
	<div class="container">
		<nav class="navbar sticky-topa navbar-inverse">
			<div class="container-fluid">
				<a class="navbar-brand" href="index.php"><span><img src="mobilko_favicon.png" alt="" style="max-width:9%;"></span>Mobilko</a>
				<ul class="nav fixed-top navbar-nav navbar-right">
					<li class="dropdown">
						<a class="dropdown-toggle" data-toggle="dropdown" href="account.html"><b><span class="glyphicon glyphicon-user"></span> <?php  echo $user["Ime"].' '.$user["Priimek"]; ?></b> <span class="caret"></span></a>
						<ul class="dropdown-menu" id="login-dp">
							<li>
								<div class="row">
									<div class="col-md-12">
										<button class="btn btn-info btn-block" name="submit" onclick="window.location.href='account.php'" type="submit">Račun</button>
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
		<div class="container-fluid" style="background-color: white; height: 100%; overflow:auto;">
			<h1 style="text-align: center">Dobrodošli v spletni trgovini Mobilko</h1>
			<h4 style="text-align: center">Nudimo vam veliko izbiro mobilnih telefonov po ugodnih cenah!</h4>
			<hr>
			<div class="row">
				<?php
					$statement = $pdo->prepare("SELECT * FROM izdelek");
					$statement->execute();
					foreach ($statement->fetchAll() as $row) {
						$zaloga = ' <span style="color: green; font-size: 0.6em;"><strong>Na zalogi</strong></span>';
						$zaloga_btn = "";
						if($row["Zaloga"] == 0){
							$zaloga = ' <span style="color: red; font-size: 0.6em;"><strong>Ni na zalogi</strong></span>';
							$zaloga_btn = "disabled";
						}
						echo '<div class="col-sm-6 col-md-4">
					    <div class="thumbnail" width="100%">
					      <a href="logged_in_item_description.php?id='. $row["ID"] .'"><img src="pictures/'. $row["Ime_slike"] .'" alt="..." height="242px" width="242px"></a>
					      <div class="caption">
					        <a href="logged_in_item_description.php?id='. $row["ID"] .'"><h3 id="name">'. $row["Ime"] .'</h3></a>
					        <p>
										<h3><strong>'. $row["Cena"] .'€</strong>'. $zaloga .'</h3>
										<form action="" method="POST">
											<button class="btn btn-success" name="item_submit" value="'. $row["ID"].'" type="submit"><span class="glyphicon glyphicon-shopping-cart" aria-hidden="true"></span> V košarico</button>
										</form>
									</p>
					      </div>
					    </div>
					  </div>';
					}
				 ?>
			</div>


		</div>
	</div><!-- Content will go here -->
	<script src="javascripts/jquery.min.js">
	</script>
	<script src="bootstrap/js/bootstrap.min.js">
	</script>
</body>
</html>
