<?php
include('config.php');
session_start();


?>
<html>
<head>
	<meta charset="UTF-8">
	<meta content="width=device-width, initial-scale=1" name="viewport">
	<link href="mobilko_favicon.png" rel="shortcut icon" type="image/png">
	<title>Mobilko | Mobilniki po ugodnih cenah!</title>
	<link href="bootstrap/css/lumen.bootstrap.min.css" rel="stylesheet">
	<link href="css/main.css" rel="stylesheet">
</head>
<body>
	<div class="container">
		<nav class="navbar sticky-topa navbar-inverse">
			<div class="container-fluid">
				<a class="navbar-brand" href="index.php"><span><img src="mobilko_favicon.png" alt="" style="max-width:9%;"></span>Mobilko</a>
				<ul class="nav fixed-top navbar-nav navbar-right">
					<li>
						<a href="register.php"><span class="glyphicon glyphicon-user"></span> Registracija</a>
					</li>
					<li class="dropdown" id="dropdown">
						<a class="dropdown-toggle" data-toggle="dropdown" href="#"><b><span class="glyphicon glyphicon-log-in"></span> Prijava</b> <span class="caret"></span></a>
						<ul class="dropdown-menu" id="login-dp">
							<li>
								<div class="row">
									<div class="col-md-12">
										<form accept-charset="UTF-8" action="" class="form" id="login-nav" method="post" name="login-nav">
											<div style="color:red">
												<?php echo $error?>
											</div>
											<div class="form-group">
												<label class="sr-only" for="InputEmail">Email naslov</label> <input class="form-control" id="InputEmail" name="uname" placeholder="Email naslov" required="" type="username">
											</div>
											<div class="form-group">
												<label class="sr-only" for="InputPassword">Geslo</label> <input class="form-control" id="exampleInputPassword2" name="pass" placeholder="Geslo" required="" type="password">
												<div class="help-block text-right">
													<a href="password_reset.php">Pozabljeno geslo?</a>
												</div>
											</div>
											<div class="form-group">
												<button class="btn btn-primary btn-block" id="prijava" name="submit" type="submit">Prijava</button>
											</div>
										</form>
									</div>
									<div class="bottom text-center">
										Nov uporabnik? <a href="register.php"><b>Registracija</b></a>
									</div>
								</div>
							</li>
						</ul>
					</li>
					<li>
						<a class="btn btn-success" href="#"><span class="glyphicon glyphicon-shopping-cart" aria-hidden="true"></span><strong> Košarica</strong> <span class="badge">0€</span></a>
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
					    <div class="thumbnail">
					      <img src="pictures/'. $row["Ime_slike"] .'" alt="..." height="242px" width="242px">
					      <div class="caption">
					        <h3>'. $row["Ime"] .'</h3>
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
