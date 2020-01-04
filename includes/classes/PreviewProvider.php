<?php
require_once('Entity.php');
require_once('EntityProvider.php');

class PreviewProvider
{

  private $con, $username;

  public function __construct($con, $username)
  {
    $this->con = $con;
    $this->username = $username;
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

    # TODO: add preview subtitle
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
                  <div class='preview-buttons'>
                    <button 
                      class='btn mt-0 mr-1'
                      type='button'
                    >
                      <i class='far fa-play-circle pr-1'></i>
                      Play
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
    $entitiesArray = EntityProvider::getEntities($this->con, null, 1);
    return $entitiesArray[0];
  }
}
