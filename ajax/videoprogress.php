<?php
require_once('../includes/config.php');

if (isset($_POST['user']) && isset($_POST['video'])) {
  $query = $con->prepare(
    "SELECT * FROM videoprogress WHERE username=:username AND videoId=:videoId"
  );

  $query->bindValue(":username", ($_POST['user']));
  $query->bindValue(":videoId", ($_POST['video']));
  $query->execute();

  if (!$query->fetch(PDO::FETCH_ASSOC)) {
    /* no record -> insert */
    $query = $con->prepare(
      "INSERT INTO videoprogress (username, videoId) VALUES (:username, :videoId)"
    );
    $query->bindValue(":username", ($_POST['user']));
    $query->bindValue(":videoId", ($_POST['video']));
    $query->execute();

    echo 'Video progress record inserted.';
  } else {
    /* record exists -> update */
    if (isset($_POST['progress'])) {
      $query = $con->prepare(
        "UPDATE videoprogress 
         SET progress=:progress, dateModified=NOW()
         WHERE username=:username AND videoId=:videoId"
      );

      $query->bindValue(":username", ($_POST['user']));
      $query->bindValue(":videoId", ($_POST['video']));
      $query->bindValue(":progress", ($_POST['progress']));
      $query->execute();

      echo 'Video progress updated.';
    } else {
      echo 'No progress data passed to the page.';
    }
  }
} else {
  echo 'No user, video data passed to the page.';
}
