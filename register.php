<?php
   include("config.php");
   session_start();

   $message = "";
   if(isset($_POST['submit']) && $_POST['g-recaptcha-response']!="") {
      //reCAPTCHA
      $secret = '6Lcd3jEUAAAAAGRmFMGmYPwyQZnST2vOY9E26gtt';

      $url = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$_POST['g-recaptcha-response']);
      $result = json_decode($url);
      if ($result->success) {
        $myname = $_POST['name'];
        $mysurname = $_POST['surname'];
        $myemail = $_POST['email'];
        $myaddress = $_POST['address'];
        $myphone = $_POST['phone'];
        $mypassword = $_POST['password'];
        $mypassword_confirm = $_POST['password_confirm'];

       if(strlen($myname) == 0 || strlen($mysurname) == 0 || strlen($myemail) == 0 ||
          strlen($mypassword) == 0){
         $message = '<div class="alert alert-danger col-centered" style="text-align:center" role="alert"><strong>Napaka:</strong> Vnesti morate vsa polja!</div>';
       }
        else if ($mypassword != $mypassword_confirm){
          $message = '<div class="alert alert-danger col-centered" style="text-align:center" role="alert"><strong>Napaka:</strong> Gesli se ne ujemata.</div>';
        }
       else{
        $_SESSION['password'] = $mypassword;

        try {
          $statement = $pdo->prepare("SELECT * FROM kupec WHERE Email = ?");
          $statement->bindValue(1, $myemail);
          $statement->execute();

          if ($statement->rowCount() != 0) {
            $message = '<div class="alert alert-danger col-centered" style="text-align:center" role="alert"><strong>Napaka:</strong> Uporabnik s tem E-Mail naslovom že obstaja.</div>';
          }
          else {
            try {
              $statement = $pdo->prepare("INSERT INTO kupec (Ime, Priimek, Email, Naslov, Telefonska_stevilka, Password) VALUES
              (?, ?, ?, ?, ?, ?)");
              $statement->bindValue(1, $myname);
              $statement->bindValue(2, $mysurname);
              $statement->bindValue(3, $myemail);
              $statement->bindValue(4, $myaddress);
              $statement->bindValue(5, $myphone);
              $statement->bindValue(6, $mypassword);
              $statement->execute();
              $message = '<div class="alert alert-success col-centered" style="text-align:center" role="alert"><strong>Registracija je bila uspešna</strong></div>';
              //header("location: register_success.php");
            } catch (PDOException $e) {
              $message = "Napaka pri vnašanju: {$e->getMessage()}";
            }
          }
        } catch (PDOException $e) {
          echo "Napaka pri poizvedbi: {$e->getMessage()}";
        }
      }
     }
   }

   $error = "";
   if(isset($_POST['login'])) {
      $myusername = $_POST['uname'];
      $mypassword = $_POST['pass'];
      try {
        $statement = $pdo->prepare("SELECT * FROM kupec WHERE Email = ? and Password = ?");
        $statement->bindValue(1, $myusername);
        $statement->bindValue(2, $mypassword);
        $statement->execute();
        if ($statement->rowCount() == 1) {
          $_SESSION['email'] = $myemail;
          $_SESSION['password'] = $mypassword;
          header("location: logged_in.php");
        }
      } catch (PDOException $e) {
          $error = "Napačen email naslov ali geslo!<br><br> <script> dropdown() </script>";
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
				<a class="navbar-brand" href="index.php">Začetna stran</a>
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
												<label class="sr-only" for="InputEmail">Email naslov</label> <input class="form-control" id="InputEmail" name="uname" placeholder="Email naslov" required="" type="username">
											</div>
											<div class="form-group">
												<label class="sr-only" for="InputPassword">Geslo</label> <input class="form-control" id="exampleInputPassword2" name="pass" placeholder="Geslo" required="" type="password">
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
				</ul>
			</div>
		</nav>
		<div class="container-fluid" style="background-color: white">
			<div>
				<h2 style="text-align:center">Prosimo, vnesite vaše podatke:</h2>
        <?php echo $message;
				      ?><br>
				<form class="form-horizontal" method="post" role="form">
					<div class="form-group">
						<label class="col-sm-5 control-label" for="name">Ime</label>
						<div class="col-sm-3">
							<input class="form-control" id="name" name="name" placeholder="" type="text" value="">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-5 control-label" for="name">Priimek</label>
						<div class="col-sm-3">
							<input class="form-control" id="surname" name="surname" placeholder="" type="text" value="">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-5 control-label" for="email">Email</label>
						<div class="col-sm-3">
							<input class="form-control" id="email" name="email" placeholder="" type="email" value="">
						</div>
					</div>
          <div class="form-group">
						<label class="col-sm-5 control-label" for="address">Naslov</label>
						<div class="col-sm-3">
							<input class="form-control" id="address" name="address" placeholder="" type="text" value="">
						</div>
					</div>
          <div class="form-group">
						<label class="col-sm-5 control-label" for="phone">Telefon</label>
						<div class="col-sm-3">
							<input class="form-control" id="phone" name="phone" placeholder="" type="text" value="">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-5 control-label" for="name">Geslo</label>
						<div class="col-sm-3">
							<input class="form-control" id="password" name="password" placeholder="" type="password" value="">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-5 control-label" for="name">Potrditev Gesla</label>
						<div class="col-sm-3">
							<input class="form-control" id="password_confirm" name="password_confirm" placeholder="" type="password" value="">
						</div>
					</div>
					<div>
						<div class="g-recaptcha" data-sitekey="6Lcd3jEUAAAAAAXzL4QGQvYSiEcWzkrzz7P2_4m9" style="position: absolute; left: 50%; margin-left: -125px;"></div>
					</div><br>
					<br>
					<br>
					<br>
					<br>
					<br>
					<div class="button" style="text-align:center">
						<input class="btn btn-primary btn-lg" name="submit" type="submit" value="Registracija">
					</div>
				</form>
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
