<?php
   include("../config.php");
   session_start();
   $error = "";
   $credentials_reset = "";
   $password_reset = "";
   $user = null;
   try {
   	$statement = $pdo->prepare("SELECT * FROM administrator WHERE ID = ?");
   	$statement->bindValue(1, $_SESSION["user_ID"]);
   	$statement->execute();
   	$user = $statement->fetch();
   } catch (PDOException $e) {
   	echo "Napaka pri poizvedbi: {$e->getMessage()}";
   }
   if(isset($_POST['submit'])) {
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    try {
      $statement = $pdo->prepare("UPDATE administrator SET Ime = ?, Priimek = ? WHERE ID = ?");
      $statement->bindValue(1, $name);
      $statement->bindValue(2, $surname);
      $statement->bindValue(3, $_SESSION["user_ID"]);
      $statement->execute();
      $credentials_reset = '<div class="alert alert-success col-centered" style="text-align:center" role="alert"><strong>Podatki so bili uspešno posodobljeni.</strong></div>';
    } catch (PDOException $e) {
      $credentials_reset = '<div class="alert alert-danger col-centered" style="text-align:center" role="alert"><strong>Podatki niso bili posodobljeni! Napaka:'.$e->getMessage().'</strong></div>';
    }
   }
   if(isset($_POST['password_reset'])) {
    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];
    $password_confirm = $_POST['password_confirm'];
    if ($old_password == $user["Geslo"]) {
      if ($new_password == $password_confirm) {
        try {
          $statement = $pdo->prepare("UPDATE administrator SET Geslo = ? WHERE ID = ?");
          $statement->bindValue(1, $new_password);
          $statement->bindValue(2, $_SESSION["user_ID"]);
          $statement->execute();
          $password_reset = '<div class="alert alert-success col-centered" style="text-align:center" role="alert"><strong>Geslo je bilo uspešno posodobljeno.</strong></div>';
        } catch (PDOException $e) {
          $password_reset = '<div class="alert alert-danger col-centered" style="text-align:center" role="alert"><strong>Podatki niso bili posodobljeni! Napaka:'.$e->getMessage().'</strong></div>';
        }
      }
      else {
        $password_reset = '<div class="alert alert-danger col-centered" style="text-align:center" role="alert"><strong>Vnešeni gesli se ne ujemata.</strong></div>';
      }
    }
    else {
      $password_reset = '<div class="alert alert-danger col-centered" style="text-align:center" role="alert"><strong>Vnešeno staro geslo ni pravilno</strong></div>';
    }
   }

?>
<html>
<head>
	<meta charset="UTF-8">
	<meta content="width=device-width, initial-scale=1" name="viewport">
	<link href="../mobilko_favicon.png" rel="shortcut icon" type="image/png">
	<title>Mobilko | Administrator nastavitve računa</title>
	<link href="../bootstrap/css/lumen.bootstrap.min.css" rel="stylesheet">
	<link href="../css/main.css" rel="stylesheet">

</head>
<body style="background-color: lightblue">
	<div class="container">
		<nav class="navbar fixed-top navbar-inverse">
			<div class="container-fluid">
				<a class="navbar-brand" href="main.php">Nazaj</a>
				<ul class="nav fixed-top navbar-nav navbar-right">
          <li class="dropdown">
						<a class="dropdown-toggle" data-toggle="dropdown" href="account.html"><b><span class="glyphicon glyphicon-user"></span> <?php  echo $user["Ime"].' '.$user["Priimek"]; ?></b> <span class="caret"></span></a>
						<ul class="dropdown-menu" id="login-dp">
							<li>
								<div class="row">
									<div class="col-md-12">
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
        <?php echo $credentials_reset; ?>
        <h2 style="text-align:center;"><strong>Nastavitve računa:</strong></h2>
        <form class="form-horizontal" method="post" role="form">
					<div class="form-group">
						<label class="col-sm-5 control-label" for="name">Ime</label>
						<div class="col-sm-3">
							<input class="form-control" id="name" name="name" placeholder="" type="text" value="<?php echo $user["Ime"];?>">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-5 control-label" for="name">Priimek</label>
						<div class="col-sm-3">
							<input class="form-control" id="surname" name="surname" placeholder="" type="text" value="<?php echo $user["Priimek"];?>">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-5 control-label" for="email">Email</label>
						<div class="col-sm-3">
							<input class="form-control" id="email" name="email" placeholder="" type="email" value="<?php echo $user["Email"];?>" disabled>
						</div>
					</div>
          <div class="button" style="text-align:center">
						<input class="btn btn-success btn-lg" name="submit" type="submit" value="Posodobi podatke">
					</div>
        </form>
        <br>
        <?php echo $password_reset; ?>
        <h2 style="text-align:center;"><strong>Spremeni geslo:</strong></h2>
        <form class="form-horizontal" method="post" role="form">
          <div class="form-group">
						<label class="col-sm-5 control-label" for="old_password">Staro geslo</label>
						<div class="col-sm-3">
							<input class="form-control" id="password" name="old_password" placeholder="" type="password" value="">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-5 control-label" for="new_password">Novo geslo</label>
						<div class="col-sm-3">
							<input class="form-control" id="password" name="new_password" placeholder="" type="password" value="">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-5 control-label" for="password_confirm">Potrditev Gesla</label>
						<div class="col-sm-3">
							<input class="form-control" id="password_confirm" name="password_confirm" placeholder="" type="password" value="">
						</div>
					</div>
					<div class="button" style="text-align:center">
						<input class="btn btn-success btn-lg" name="password_reset" type="submit" value="Posodobi geslo">
					</div>
				</form>

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
