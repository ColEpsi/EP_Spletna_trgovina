<?php
include('../config.php');
session_start();

$error = "";
if(isset($_POST['submit'])) {
	 $myemail = $_POST['email'];
	 $mypassword = $_POST['password'];
	 $statement = $pdo->prepare("SELECT * FROM administrator WHERE Email = ? and Geslo = ?");
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
	<title>Mobilko | Administrator</title>
	<link href="../bootstrap/css/lumen.bootstrap.min.css" rel="stylesheet">
	<link href="../css/main.css" rel="stylesheet">
</head>
<body>
	<div class="container">
		<nav class="navbar sticky-topa navbar-inverse">
			<div class="container-fluid">
				<a class="navbar-brand" href="../index.php"><span><img src="../mobilko_favicon.png" alt="" style="max-width:9%;"></span>Mobilko</a>
			</div>
		</nav>
		<div class="container-fluid" style="background-color: white; height: 100%; overflow:auto;">
			<h1 style="text-align: center">Vmesnik za administratorja strani</h1>
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
