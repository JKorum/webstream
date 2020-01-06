<?php
require_once('Video.php');
require_once('Season.php');

class Entity
{

  private $con, $sqlData;

  public function __construct($con, $input)
  {
    $this->con = $con;

    /* input can be id -> to fetch data from db 
       OR already fetched data -> to store in priv var */
    if (is_array($input)) {
      $this->sqlData = $input;
    } else {

      $query = $this->con->prepare(
        "SELECT * FROM entities
         WHERE id = :id"
      );

      $query->bindValue(':id', $input);
      $query->execute();
      $this->sqlData = $query->fetch(PDO::FETCH_ASSOC);
    }
  }

  public function getCategoryId()
  {
    return $this->sqlData['categoryId'];
  }

  public function getId()
  {
    return $this->sqlData['id'];
  }

  public function getName()
  {
    return $this->sqlData['name'];
  }

  public function getThumbnail()
  {
    return $this->sqlData['thumbnail'];
  }

  public function getPreview()
  {
    return $this->sqlData['preview'];
  }

  public function getSeasons()
  {

    $query = $this->con->prepare(
      "SELECT * FROM videos 
      WHERE entityId=:id
      AND isMovie=0
      ORDER BY season, episode ASC"
    );

    $query->bindValue(':id', $this->getId());
    $query->execute();

    $seasons = array();
    $videos = array();
    $currentSeason = null;

    while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
      if ($currentSeason === null) {
        $currentSeason = $row['season'];
      } elseif ($currentSeason !== $row['season']) {
        $seasons[] = new Season($currentSeason, $videos);
        $currentSeason = $row['season'];
        $videos = array();
      }
      $videos[] = new Video($this->con, $row);
    }

    /* handle last season */
    if (count($videos) !== 0) {
      $seasons[] = new Season($currentSeason, $videos);
    }

    return $seasons;
  }
}
