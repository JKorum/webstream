<?php
require_once('Video.php');

class VideoProvider
{

  public static function getUpNext($con, $currentVideo)
  {
    $query = $con->prepare(
      "SELECT * FROM videos 
       WHERE entityId=:entityId
       AND id!=:id
       AND ((season=:season AND episode>:episode) OR season>:season)
       ORDER BY season, episode ASC
       LIMIT 1"
    );

    $query->bindValue(":entityId", $currentVideo->getEntityId());
    $query->bindValue(":id", $currentVideo->getId());
    $query->bindValue(":season", $currentVideo->getSeasonNumber());
    $query->bindValue(":episode", $currentVideo->getEpisodeNumber());

    $query->execute();

    $row = $query->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
      /* if no episodes left in entity -> get random video */
      $query = $con->prepare(
        "SELECT * FROM videos
         WHERE season<=1 AND episode<=1
         AND id!=:videoId
         ORDER BY views DESC
         LIMIT 1"
      );

      $query->bindValue(":videoId", $currentVideo->getId());
      $query->execute();
      $row = $query->fetch(PDO::FETCH_ASSOC);
    }

    return new Video($con, $row);
  }

  public static function getEntityVideoId($con, $entityId, $username)
  {
    /* user did watch the show */
    $query = $con->prepare(
      "SELECT videoId FROM videoprogress
       INNER JOIN videos
       ON videoprogress.videoId = videos.id
       WHERE videos.entityId = :entityId
       AND videoprogress.username = :username
       ORDER BY videoprogress.dateModified DESC
       LIMIT 1"
    );

    $query->bindValue(':entityId', $entityId);
    $query->bindValue(':username', $username);
    $query->execute();

    $row = $query->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
      /* user didn't watch the show yet */
      $query = $con->prepare(
        "SELECT id FROM videos
         WHERE entityId = :entityId
         ORDER BY season, episode ASC
         LIMIT 1"
      );
      $query->bindValue(':entityId', $entityId);
      $query->execute();
      $row = $query->fetch(PDO::FETCH_ASSOC);

      return $row['id'];
    } else {
      return $row['videoId'];
    }
  }
}
