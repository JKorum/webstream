<?php
require_once('Entity.php');
require_once('EntityProvider.php');
require_once('VideoProvider.php');
require_once('Video.php');

class PreviewProvider
{

  private $con, $username;

  public function __construct($con, $username)
  {
    $this->con = $con;
    $this->username = $username;
  }

  public function createCategoryPreviewVideo($categoryId)
  {
    $entitiesArray = EntityProvider::getEntities($this->con, $categoryId, 1, null);
    if (count($entitiesArray) === 0) {
      return null;
    }

    return $this->createPreviewVideo($entitiesArray[0]);
  }

  public function createShowPreviewVideo()
  {
    $entitiesArray = EntityProvider::getShowsEntities($this->con, null, 1);
    if (count($entitiesArray) === 0) {
      return null;
    }

    return $this->createPreviewVideo($entitiesArray[0]);
  }

  public function createMoviePreviewVideo()
  {
    $entitiesArray = EntityProvider::getMoviesEntities($this->con, null, 1);
    if (count($entitiesArray) === 0) {
      return null;
    }

    return $this->createPreviewVideo($entitiesArray[0]);
  }

  public function createPreviewVideo($entity)
  {
    if ($entity === null) {
      $entity = $this->getRandomEntity();
    }

    $id = $entity->getId();
    $name = $entity->getName();
    $thumbnail = $entity->getThumbnail();
    $preview = $entity->getPreview();

    $videoId = VideoProvider::getEntityVideoId($this->con, $id, $this->username);
    $video = new Video($this->con, $videoId);
    $subtitle = $video->getSeasonAndEpisode();

    $isInProgress = $video->isInProgress($this->username) ? 'Resume' : 'Play';


    # alternative to obj tag: <img id='img-preview' class='img-fluid' src='$thumbnail' hidden>

    return "<div class='embed-responsive embed-responsive-16by9 position-relative d-flex'> 
              <video id='video-preview' autoplay muted class='embed-responsive-item'>
                <source src='$preview' type='video/mp4'>
                Sorry, your browser doesn't support embedded videos.
              </video>
              <object id='img-preview' data='$thumbnail' hidden></object>
              <div class='overlay-preview position-absolute d-flex align-items-center'>
                <div class='preview-details pl-3'>
                  <h1 class='display-4'>$name</h1>
                  <p class='lead'>$subtitle</p>
                  <div class='preview-buttons'>
                    <button 
                      id='btn-play'
                      class='btn mt-0 mr-1'
                      type='button'
                      data-video='$videoId'
                    >
                      <i class='far fa-play-circle pr-1'></i>
                      $isInProgress
                    </button>
                    <button 
                      id='btn-mute'
                      class='btn mt-0'    
                      type='button'                  
                    >
                      <i id='icon-mute' class='fas fa-volume-mute pr-1'></i>
                      <span id='btn-mute-text'>Muted</span>
                    </button>                    
                  </div>
                </div>
              </div>
            </div>";
  }

  public function createEntityPreview($entity)
  {
    $id = $entity->getId();
    $thumbnail = $entity->getThumbnail();
    $name = $entity->getName();

    return "
    <div class='slider-img-container'>
      <a href='entity.php?id=$id'><img src='$thumbnail' class='slider-img'></a>
    </div>
    ";
  }

  private function getRandomEntity()
  {
    $entitiesArray = EntityProvider::getEntities($this->con, null, 1, null);
    return $entitiesArray[0];
  }
}
