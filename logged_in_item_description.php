<?php
   include("config.php");
   session_start();
   $error = "";

   $id = $_GET["id"];

   $statement = $pdo->prepare("SELECT * FROM izdelek WHERE ID = ?");
   $statement->bindValue(1, $id);
   $statement->execute();

   $item = $statement->fetch();

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
	<title>Mobilko | Registracija</title>
	<link href="bootstrap/css/lumen.bootstrap.min.css" rel="stylesheet">
	<link href="css/main.css" rel="stylesheet">
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
    <div class="container-fluid" style="background-color: white; height: 100%;">
      <div class="container-fluid" style="margin-top: 2em;">
        <div class="col-sm-12 col-md-6">
          <img src="pictures/<?php echo $item["Ime_slike"]; ?>" alt="" width="484px" height="484px">
        </div>
        <div class="col-sm-12 col-md-6">
          <div class="container-fluid" style="background-color: lightgrey; margin: 1.5em; border-radius:1em;">
            <h2 class="pull-left" style="color:black"><strong><?php echo $item["Ime"];?></strong></h2>
            <br>
            <br>
            <p><strong>Proizvajalec: <?php echo $item["Proizvajalec"]?></strong></p>
            <?php
              $basket_button = "";
              if ($item["Zaloga"] > 1) {
                echo '<h3><span class="label label-success">Na zalogi <span class="badge">'. $item["Zaloga"] .'</span></span></h3>';
              }
              else if ($item["Zaloga"] == 1) {
                echo '<h3><span class="label label-warning">Zadnji kos <span class="badge">'. $item["Zaloga"] .'</span></span></h3>';
              }
              if ($item["Zaloga"] == 0) {
                $basket_button = "disabled";
                echo '<h3><span class="label label-danger">Ni na zalogi <span class="badge">'. $item["Zaloga"] .'</span></span></h3>';
              }
             ?>
             <h3 style="color:black; margin-bottom: 0em; margin-top: 2em;"><strong>Cena:</strong></h3>
             <h2 style="color:red; margin-top: 0em;"><strong><?php echo $item["Cena"] ?>€</strong></h2>
             <a href="#" class="btn btn-success btn-lg <?php echo $basket_button ?>" role="button" style="margin-bottom: 5em"><span class="glyphicon glyphicon-shopping-cart" aria-hidden="true"></span> V košarico</a>
          </div>
        </div>
        <div class="col-md-12">
          <div class="container-fluid">
            <h1 style="color:black"><strong>OPIS:</strong></h1>
            <div class="container-fluid" style="background-color: lightgrey; margin-bottom:3em; border-radius:1em;">
              <br>
              <p><strong><?php echo $item["Opis"]; ?></strong</p>
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
