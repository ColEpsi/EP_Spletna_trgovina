<?php
include('config.php');
session_start();

try {
	$statement = $pdo->prepare("SELECT * FROM kupec WHERE ID = ?");
	$statement->bindValue(1, $_SESSION["user_ID"]);
	$statement->execute();
	$user = $statement->fetch();
} catch (PDOException $e) {
	echo "Napaka pri poizvedbi: {$e->getMessage()}";
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
										<button class="btn btn-info btn-block" name="submit" onclick="window.location.href='#'" type="submit">Račun</button>
										<button class="btn btn-info btn-block" name="submit" onclick="window.location.href='#'" type="submit">Naročila</button>
										<button class="btn btn-info btn-block" name="submit" onclick="window.location.href='#'" type="submit">Naslovi</button>
										<button class="btn btn-danger btn-block" name="submit" onclick="window.location.href='index.php'" type="submit">Odjava</button><br>
									</div>
								</div>
							</li>
						</ul>
					</li>
					<li class="dropdown">
						<a class="btn btn-success dropsown-toggle" data-toggle="dropdown" href="#"><span class="glyphicon glyphicon-shopping-cart" aria-hidden="true"></span><strong> Košarica</strong> <span class="badge">0€</span></a>
						<ul class="dropdown-menu" id="login-dp">
							<li>
								<div class="row">
									<div class="col-md-12">
										<p style="text-align:center"><strong>Vaša košarica je prazna.</strong></p>
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
										<a href="#" class="btn btn-success '. $zaloga_btn .'" role="button"><span class="glyphicon glyphicon-shopping-cart" aria-hidden="true"></span> V košarico</a>
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
