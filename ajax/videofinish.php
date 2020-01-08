<?php
require_once('../includes/config.php');

if (isset($_POST['user']) && isset($_POST['video'])) {

  $query = $con->prepare(
    "UPDATE videoprogress 
       SET progress=0, finished=1
       WHERE username=:username AND videoId=:videoId"
  );

  $query->bindValue(":username", $_POST['user']);
  $query->bindValue(":videoId", $_POST['video']);
  $query->execute();

  echo 'The video is marked as finished.';
} else {
  echo 'No user, video data provided to the page.';
}
