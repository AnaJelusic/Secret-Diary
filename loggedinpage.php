<?php
	session_start();
	$savedDiaryContent = "";
	include("connection.php");
	if (array_key_exists("id", $_COOKIE)) {
		$_SESSION['id'] = $_COOKIE['id'];
	}
	if (array_key_exists("id", $_SESSION)) {
		$query = "SELECT `diary` FROM `users` WHERE id = ".mysqli_real_escape_string($link, $_SESSION['id'])." LIMIT 1";
		$row = mysqli_fetch_array(mysqli_query($link, $query));
		if(isset($row)) {
			$savedDiaryContent = $row['diary'];
		}
	} 	else {
				header("Location: index.php");
	}

include("header.php");
?>

			<nav class="navbar navbar-fixed-top navbar-expand-lg navbar-light bg-light justify-content-between">
				<span class="navbar-brand mb-0 h1">Secret Diary</span>
				<a href="index.php?logout=1" class="btn btn-outline-success" type="button">Log out</a>
			</nav>

			<div id="diaryContainer" class="form-group container-fluid justify-content-center">
				<textarea name="diaryContent" rows="36" cols="180" class="form-control col-md-10 offset-md-1"><?php echo "$savedDiaryContent"; ?></textarea>
			</div>

			<script>
			$(document).ready(function() {
				$("textarea").keyup(function() {
					var textTyped = $("textarea").val();
					if (textTyped !== "") {
						$.ajax ({
							url: "updatedatabase.php",
							method: "POST",
							data: {content: textTyped},
							dataType: "text",
							success: function(data) {

							},
							error: function(xhr, ajaxOptions, thrownError) {
								alert(thrownError);
							}
						});
					}
				})
			});
			</script>

		<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
	</body>

</html>
