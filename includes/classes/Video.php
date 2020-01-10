<?php

require_once('Entity.php');

class Video
{

  private $con, $sqlData, $entity;

  /* input id or entity  */
  public function __construct($con, $input)
  {
    $this->con = $con;
    $this->sqlData = $input;

    if (is_array($input)) {
      $this->sqlData = $input;
    } else {

      $query = $this->con->prepare(
        "SELECT * FROM videos
         WHERE id=:id"
      );

      $query->bindValue(':id', $input);
      $query->execute();

      $this->sqlData = $query->fetch(PDO::FETCH_ASSOC);
    }

    $this->entity = new Entity($this->con, $this->sqlData['entityId']);
  }

  public function getId()
  {
    return $this->sqlData['id'];
  }

  public function getTitle()
  {
    return $this->sqlData['title'];
  }

  public function getDescription()
  {
    return $this->sqlData['description'];
  }

  public function getFilePath()
  {
    return $this->sqlData['filePath'];
  }

  public function getThumbnail()
  {
    return $this->entity->getThumbnail();
  }

  public function getEpisodeNumber()
  {
    return $this->sqlData['episode'];
  }

  public function getEntityId()
  {
    return $this->entity->getId();
  }

  public function getEntityName()
  {
    return $this->entity->getName();
  }

  public function getSeasonNumber()
  {
    return $this->sqlData['season'];
  }

  public function incrementViews()
  {
    $query = $this->con->prepare(
      "UPDATE videos SET views=views+1 WHERE id=:id"
    );
    $query->bindValue(":id", $this->getId());
    $query->execute();
  }

  public function isMovie()
  {
    return $this->sqlData['isMovie'] == 1;
  }

  public function getSeasonAndEpisode()
  {
    if (!$this->isMovie()) {
      $season = $this->getSeasonNumber();
      $episode = $this->getEpisodeNumber();

      return "Season $season, episode $episode";
    }
  }

  public function isInProgress($username)
  {
    $query = $this->con->prepare(
      "SELECT * FROM videoprogress
       WHERE videoId = :videoId 
       AND username = :username"
    );
    $query->bindValue(":videoId", $this->sqlData['id']);
    $query->bindValue(":username", $username);
    $query->execute();

    return $query->fetch(PDO::FETCH_ASSOC);
  }

  public function isFinished($username)
  {
    $query = $this->con->prepare(
      "SELECT * FROM videoprogress
       WHERE videoId = :videoId 
       AND username = :username
       AND finished = 1"
    );
    $query->bindValue(":videoId", $this->sqlData['id']);
    $query->bindValue(":username", $username);
    $query->execute();

    return $query->fetch(PDO::FETCH_ASSOC);
  }
}
