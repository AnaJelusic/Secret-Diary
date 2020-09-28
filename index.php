<?php
	session_start();
	$errorMessage = "";

	if(array_key_exists("logout", $_GET)) {
		unset($_SESSION['id']);
		setcookie("id", "", time() - 60*60);
		$_COOKIE["id"] = "";
	} else if ((array_key_exists("id", $_SESSION) AND $_SESSION['id']) OR (array_key_exists("id", $_COOKIE) AND $_COOKIE['id'])) {
			header("Location: loggedinpage.php");
		}

		include("connection.php");

	if (array_key_exists('email', $_POST) OR array_key_exists('password', $_POST)) {
		if ($_POST['email'] == '') {
			$errorMessage .= "<p>Email is required.</p>";
		}	else if ($_POST['password'] == '') {
				$errorMessage .= "<p>Password is required.</p>";
			}
			if ($errorMessage != "") {
					$errorMessage = "<p>There were error(s) in your form:</p>".$errorMessage;
			}	else {
				if ($_POST['signUp'] == '1') {
					$query = "SELECT id FROM `users` WHERE email = '".mysqli_real_escape_string($link, $_POST['email'])."' LIMIT 1";
					$result = mysqli_query($link, $query);
					if (mysqli_num_rows($result) > 0) {
						$errorMessage = "<p>That email adress is taken.</p>";
					} else {
							$query = "INSERT INTO `users` (`email`, `password`) VALUES ('".mysqli_real_escape_string($link, $_POST['email'])."', '".mysqli_real_escape_string($link, $_POST['password'])."')";
							$result = mysqli_query($link, $query);
							$_SESSION['id'] = mysqli_insert_id($link);

							if (isset($result)) {
								$query = "UPDATE `users` SET password = '".md5(md5(mysqli_insert_id($link)).$_POST['password'])."' WHERE id = ".mysqli_insert_id($link)." LIMIT 1";
								mysqli_query($link, $query);

								if (isset($_POST['staySignedIn']) == 'yes') {
										setcookie("id", $_SESSION['id'], time() + 60*60*24*365);
									}
								header("Location: loggedinpage.php");
							}
						}
					} else {

					$query = "SELECT * FROM `users` WHERE email = '".mysqli_real_escape_string($link, $_POST['email'])."'";
					$result = mysqli_query($link, $query);
					$row = mysqli_fetch_array($result);

					if (isset($row)) {
						$hashedPassword = md5(md5($row['id']).$_POST['password']);
						if ($hashedPassword == $row['password']) {
							$_SESSION['id'] = $row['id'];

							if (isset($_POST['staySignedIn']) == 'yes') {
								setcookie("id", $row['id'], time() + 60*60*24*365);
								}
							header("Location: loggedinpage.php");

							} else {
								$errorMessage = "<p>That email/password combination could not be found.</p>";
							}
						} else {
							$errorMessage = "<p>That email/password combination could not be found.</p>";
							}
						}
					}
				}
?>

<?php include("header.php"); ?>

	  <div id="homePageContainer" class="container">
			<div class="row">
				<div class="col-md-12 my-auto ">
					<h1>Secret Diary</h1>
					<p><strong>Store your thoughts permanently and securely.</strong></p>
				</div>

				<div id="errorDiv" class="<?php if ($errorMessage != "") {echo "alert alert-danger";}?>"><?php echo $errorMessage;?></div>
			 </div>

			 <div id="signUpForm">
			   <p>Intrested? Sign up now.</p>
			  	<form method="post">
				  	<div class="form-group">
					  	<input name ="email" class="form-control" type="email" placeholder = "Your email">
				  	</div>
				  	<div class="form-group">
					    <input name ="password" class="form-control" type = "password" placeholder = "Password">
				    </div>
			  	  <div class="form-group">
				      <input class="form-check-input" type="checkbox" name="staySignedIn" id="staySignedIn" value="yes">
					  	<label class="form-check-label" for="staySignedIn">Stay logged in.</label>
					    <input type="hidden" name="signUp" value="1">
				    </div>
				    <div class="form-group">
				  		<input type="submit" name="submit" class="btn btn-success" value="Sign Up!">
				    </div>
			  	 </form>
					 <button type="button" class="btn btn-link toggleForms">Already have an account? Log in!</button>
			 </div>

       <div id="logInForm">
				 <p>Log in using your username and password.</p>
			   <form method="post">
			  		<div class="form-group">
								<input name ="email" class="form-control" type="text" placeholder = "Your email">
				  	</div>
	          <div class="form-group">
								<input name ="password" class="form-control" type = "password" placeholder = "Password">
				  	</div>
				  	<div class="form-group">
								<input class="form-check-input" type="checkbox" name="staySignedIn" id="staySignedIn" value="yes">
								<label class="form-check-label" for="staySignedIn">Stay logged in.</label>
								<input type="hidden" name="signUp" value="0">
				  	</div>
				  	<div class="form-group">
								<input type="submit" name="submit" class="btn btn-success" value="Log In!">
				  	</div>
					</form>
					<button type="button" class="btn btn-link toggleForms">Don't have an account? Sign up!</button>
			 	</div>
			<div>
		</div>
	 </div>

	<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
 	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
 	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

 	<script>
 	  $(document).ready(function(){
 	    $(".toggleForms").click(function(){
 	      $("#signUpForm").toggle();
 	      $("#logInForm").toggle();
 	    })
 	  });
 	</script>
 	</body>
 	</html>
