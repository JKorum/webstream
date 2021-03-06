<?php

class SeasonProvider
{

  private $con, $username;

  public function __construct($con, $username)
  {
    $this->con = $con;
    $this->username = $username;
  }

  public function create($entity)
  {
    $seasons = $entity->getSeasons();

    if (count($seasons) === 0) {
      return null;
    }

    $seasonsRowsHtml = '';

    foreach ($seasons as $season) {
      $seasonNumber = $season->getSeasonNumber();
      $seasonVideos = $season->getVideos();

      $videosHtml = '';

      foreach ($seasonVideos as $video) {
        $id = $video->getId();
        $title = $video->getTitle();
        $description = $video->getDescription();
        $filePath = $video->getFilePath();
        $thumbnail = $video->getThumbnail();
        $episodeNumber = $video->getEpisodeNumber();

        $isFinished = $video->isFinished($this->username);
        $isFinished = $isFinished ? "<i class='fas fa-check-circle finished pr-1'></i>" : "";

        $videosHtml .= "
          <div class='slider-img-container'>
            <a href='watch.php?id=$id'><img src='$thumbnail' class='slider-img'></a>
            <div class='episode-info d-flex justify-content-between align-items-center'>
              <p class='p-0 pl-1 m-0'>
                $episodeNumber. $title
              </p>
              $isFinished
            </div>
          </div>
        ";
      }

      $seasonsRowsHtml .= "
        <div class='mt-4'>
          <h5 class='category-title pl-1'>Season $seasonNumber</h5>  
          <div class='slider'>$videosHtml</div>                
        </div>
      ";
    }

    return $seasonsRowsHtml;
  }
}
