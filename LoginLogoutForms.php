<?php

	session_start();

	$errorMessage = "";

	if(array_key_exists("logout", $_GET)) {

		unset($_SESSION);
		setcookie("id", "", time() - 60*60);
		$_COOKIE["id"] = "";

	} else if ((array_key_exists("id", $_SESSION) AND $_SESSION['id']) OR (array_key_exists("id", $_COOKIE) AND $_COOKIE['id'])) {
		header("Location: loggedinpage.php");
	}

	$link = mysqli_connect("shareddb-m.hosting.stackcp.net", "secretdi-3835d62d", "02k8cxuct6", "secretdi-3835d62d");

	if (mysqli_connect_error()) {
		die ("There was an error connecting to the database.");
	}

	if (array_key_exists('email', $_POST) OR array_key_exists('password', $_POST)) {

		if ($_POST['email'] == '') {
			$errorMessage .= "<p>Email is required.</p>";

		}	else if ($_POST['password'] == '') {
				$errorMessage .= "<p>Password is required.</p>";

			}

			if ($errorMessage != "") {

					$errorMessage = "<p>There were error(s) in your form:</p>".$errorMessage;

			}			else {

				if ($_POST['signUp'] == 1) {

					$query = "SELECT id FROM `users` WHERE email = '".mysqli_real_escape_string($link, $_POST['email'])."' LIMIT 1";
					$result = mysqli_query($link, $query);
					if (mysqli_num_rows($result) > 0) {
						$errorMessage = "<p>That email adress is taken.</p>";

					} else {

							$query = "INSERT INTO `users` (`email`, `password`) VALUES ('".mysqli_real_escape_string($link, $_POST['email'])."', '".mysqli_real_escape_string($link, $_POST['password'])."')";

							if ($result = mysqli_query($link, $query)) {

								$query = "UPDATE `users` SET password = '".md5(md5(mysqli_insert_id($link)).$_POST['password'])."' WHERE id = ".mysqli_insert_id($link)." LIMIT 1";
								mysqli_query($link, $query);

								$_SESSION['id'] = mysqli_insert_id($link);

									if (isset($_POST['staySignedIn']) && $_POST['staySignedIn'] == 'yes') {
										setcookie("id", mysqli_insert_id($link), time() + 60*60*24*365);
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

							if (isset($_POST['staySignedIn']) && $_POST['staySignedIn'] == 'yes') {
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

<html>

	<body>
		<div id="error"><?php echo $errorMessage; ?></div>

		<form method="post">
				<input name ="email" type="email" placeholder = "Your email">
				<input name ="password" type = "password" placeholder = "Password">
				<input type="checkbox" name="staySignedIn" value="yes">
				<input type="hidden" name="signUp" value="1">
				<input type="submit" value="Sign Up!">
			</form>

		 <form method="post">
				<input name ="email" type="text" placeholder = "Your email">
				<input name ="password" type = "password" placeholder = "Password">
				<input type="checkbox" name="stayLoggedIn" value="">
				<input type="hidden" name="signUp" value="0">
				<input type="submit" value="Log In!">
			</form>
		</body>
	</html>
