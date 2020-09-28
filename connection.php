<?php
    $link = mysqli_connect("shareddb-m.hosting.stackcp.net", "secretdi-3835d62d", "02k8cxuct6", "secretdi-3835d62d");
    mysqli_set_charset($link, "utf8");
    if (mysqli_connect_error()) {
      die ("There was an error connecting to the database.");
    }
?>
