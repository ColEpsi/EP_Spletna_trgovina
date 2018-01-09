<?php
include('../config.php');
session_start();

$error = "";
if(isset($_POST['submit'])) {
	 $myemail = $_POST['email'];
	 $mypassword = $_POST['password'];
	 $statement = $pdo->prepare("SELECT * FROM prodajalec WHERE Email = ? and Geslo = ?");
	 $statement->bindValue(1, $myemail);
	 $statement->bindValue(2, $mypassword);
	 $statement->execute();

	 if ($statement->rowCount() == 1) {
		$user = $statement->fetch();
	 	$_SESSION["user_ID"] = $user["ID"];
		header("location: main.php");
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
	<link href="../mobilko_favicon.png" rel="shortcut icon" type="image/png">
	<title>Mobilko | Mobilniki po ugodnih cenah!</title>
	<link href="../bootstrap/css/lumen.bootstrap.min.css" rel="stylesheet">
	<link href="../css/main.css" rel="stylesheet">
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
				<a class="navbar-brand" href="../index.php"><span><img src="../mobilko_favicon.png" alt="" style="max-width:9%;"></span>Mobilko</a>
			</div>
		</nav>
		<div class="container-fluid" style="background-color: white; height: 100%; overflow:auto;">
			<h1 style="text-align: center">Vmesnik za prodajalce</h1>
			<h4 style="text-align: center">Pred uporabo se prijavite z uporabniškim imenom in geslom pridobljenim od administratorja strani.</h4>
			<hr>
      <?php echo $error; ?>
      <form class="form-horizontal" method="post" role="form">
        <div class="form-group">
          <label class="col-sm-5 control-label" for="email">Email</label>
          <div class="col-sm-3">
            <input class="form-control" id="email" name="email" placeholder="" type="text" value="">
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-5 control-label" for="password">Geslo</label>
          <div class="col-sm-3">
            <input class="form-control" id="password" name="password" placeholder="" type="password" value="">
          </div>
        </div>
        <div class="button" style="text-align:center">
          <input class="btn btn-primary btn-lg" name="submit" type="submit" value="Prijava">
        </div>
      </form>


		</div>
	</div><!-- Content will go here -->
	<script src="../javascripts/jquery.min.js">
	</script>
	<script src="../bootstrap/js/bootstrap.min.js">
	</script>
</body>
</html>
