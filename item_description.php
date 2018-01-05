<?php
   include("config.php");
   session_start();

   $id = $_GET["id"];

   $statement = $pdo->prepare("SELECT * FROM izdelek WHERE ID = ?");
   $statement->bindValue(1, $id);
   $statement->execute();

   $item = $statement->fetch();

   $error = "";
   if(isset($_POST['login'])) {
   	 $myemail = $_POST['email'];
   	 $mypassword = $_POST['password'];
   	 $statemen = $pdo->prepare("SELECT * FROM kupec WHERE Email = ? and Password = ?");
   	 $statemen->bindValue(1, $myemail);
   	 $statemen->bindValue(2, $mypassword);
   	 $statemen->execute();

   	 if ($statemen->rowCount() == 1) {
   		$user = $statemen->fetch();
   	 	$_SESSION["user_ID"] = $user["ID"];
      $path = 'location: logged_in_item_description.php?id='. $id;
   		header($path);
   	 }
   	 else {
   	 	$error = '<div class="alert alert-danger" role="alert">
     							<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
     							<span class="sr-only">Error:</span>
     							Vnešeni podatki niso pravilni
   						 </div>';
   	 }
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
				<a class="navbar-brand" href="index.php">Nazaj</a>
				<ul class="nav fixed-top navbar-nav navbar-right">
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
												<label class="sr-only" for="InputEmail">Email naslov</label> <input class="form-control" id="InputEmail" name="email" placeholder="Email naslov" required type="email">
											</div>
											<div class="form-group">
												<label class="sr-only" for="InputPassword">Geslo</label> <input class="form-control" id="exampleInputPassword2" name="password" placeholder="Geslo" required type="password">
												<div class="help-block text-right">
													<a href="password_reset.php">Pozabljeno geslo?</a>
												</div>
											</div>
											<div class="form-group">
												<button class="btn btn-primary btn-block" id="prijava" name="login" type="submit">Prijava</button>
											</div>
										</form>
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
                    <?php // TODO: Posodabljanje kosarice ?>
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
             <a href="#" class="btn btn-success btn-lg <?php echo $basket_button ?>" role="button" style="margin-bottom: 5em" data-toggle="modal" data-target="#login-popup"><span class="glyphicon glyphicon-shopping-cart" aria-hidden="true"></span> V košarico</a>
             <div class="modal fade" id="login-popup" role="dialog">
               <div class="modal-dialog">
                 <div class="modal-content">
                   <div class="modal-header">
                     <button type="button" class="close" data-dismiss="modal">&times;</button>
                     <h4 class="modal-title"><strong>Pred dodajanjem izdelkov v košarico se prijavite.</strong></h4>
                   </div>
                   <div class="modal-body">
                     <div class="col-md-12">
                       <form accept-charset="UTF-8" action="" class="form" id="login-nav" method="post" name="login-nav">
                         <div style="color:red">
                           <?php echo $error?>
                         </div>
                         <div class="form-group">
                           <label class="sr-only" for="InputEmail">Email naslov</label> <input class="form-control" id="InputEmail" name="email" placeholder="Email naslov" required type="email">
                         </div>
                         <div class="form-group">
                           <label class="sr-only" for="InputPassword">Geslo</label> <input class="form-control" id="exampleInputPassword2" name="password" placeholder="Geslo" required type="password">
                           <div class="help-block text-right">
                             <a href="password_reset.php">Pozabljeno geslo?</a>
                           </div>
                         </div>
                         <div class="form-group">
                           <button class="btn btn-primary btn-block" id="prijava" name="login" type="submit">Prijava</button>
                         </div>
                       </form>
                     </div>
                   </div>
                   <div class="modal-footer">
                   </div>
                 </div>

               </div>
             </div>
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
