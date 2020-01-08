<?php
require_once('../includes/config.php');

if (isset($_POST['user']) && isset($_POST['video'])) {

  $query = $con->prepare(
    "SELECT progress FROM videoprogress 
     WHERE username=:username AND videoId=:videoId"
  );

  $query->bindValue(":username", $_POST['user']);
  $query->bindValue(":videoId", $_POST['video']);
  $query->execute();
  echo $query->fetchColumn();
} else {
  echo 'No user, video data provided to the page.';
}
