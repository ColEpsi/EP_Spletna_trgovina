<?php
include('config.php');
session_start();

$error = "";
if(isset($_POST['submit'])) {
	 $myemail = $_POST['email'];
	 $mypassword = $_POST['password'];
	 $statement = $pdo->prepare("SELECT * FROM kupec WHERE Email = ? and Password = ?");
	 $statement->bindValue(1, $myemail);
	 $statement->bindValue(2, $mypassword);
	 $statement->execute();

	 if ($statement->rowCount() == 1) {
		$user = $statement->fetch();
	 	$_SESSION["user_ID"] = $user["ID"];
		header("location: logged_in.php");
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
												<label class="sr-only" for="InputEmail">Email naslov</label> <input class="form-control" id="InputEmail" name="email" placeholder="Email naslov" required type="email">
											</div>
											<div class="form-group">
												<label class="sr-only" for="InputPassword">Geslo</label> <input class="form-control" id="exampleInputPassword2" name="password" placeholder="Geslo" required type="password">
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
					<li class="dropdown">
						<a class="btn btn-success dropsown-toggle" data-toggle="dropdown" href="#"><span class="glyphicon glyphicon-shopping-cart" aria-hidden="true"></span><strong> Košarica</strong> <span class="badge">0€</span></a>
						<ul class="dropdown-menu" id="login-dp">
							<li>
								<div class="row">
									<div class="col-md-12">
										<p style="text-align:center; color: red;"><strong>Pred dodajanjem izdelkov se prijavite!</strong></p>
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
						if ($row["Status"] == "Aktiven") {
							$zaloga = ' <span style="color: green; font-size: 0.6em;"><strong>Na zalogi</strong></span>';
							$zaloga_btn = "";
							if($row["Zaloga"] == 0){
								$zaloga = ' <span style="color: red; font-size: 0.6em;"><strong>Ni na zalogi</strong></span>';
								$zaloga_btn = "disabled";
							}
							echo '<div class="col-sm-6 col-md-4">
						    <div class="thumbnail" width="100%">
						      <a href="item_description.php?id='. $row["ID"] .'"><img src="pictures/'. $row["Ime_slike"] .'" alt="..." height="242px" width="242px"></a>
						      <div class="caption">
						        <a href="item_description.php?id='. $row["ID"] .'"><h3 id="name">'. $row["Ime"] .'</h3></a>
						        <p>
											<h3><strong>'. $row["Cena"] .'€</strong>'. $zaloga .'</h3>
											<button type="button" class="btn btn-success" data-toggle="modal" data-target="#login-popup"><span class="glyphicon glyphicon-shopping-cart" aria-hidden="true"></span> V košarico</button>
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
																		<button class="btn btn-primary btn-block" id="prijava" name="submit" type="submit">Prijava</button>
																	</div>
																</form>
															</div>
										        </div>
										        <div class="modal-footer">
										        </div>
										      </div>

										    </div>
										  </div>
										</p>
						      </div>
						    </div>
						  </div>';
						}
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
