<?php
  session_start();
  include("connection.php");

  if (isset($_POST["content"])) {
    $query = "UPDATE `users` SET diary = '".mysqli_real_escape_string($link, $_POST["content"])."' WHERE id = ".mysqli_real_escape_string($link, $_SESSION['id'])." LIMIT 1";
    mysqli_query($link, $query);
  };
?>
