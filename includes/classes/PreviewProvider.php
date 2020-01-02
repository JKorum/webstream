<?php
require_once('Entity.php');

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
                <div>                
              </div>
            </div>";
  }



  private function getRandomEntity()
  {
    /* retrieve rows in random order */
    $query = $this->con->prepare(
      "SELECT * FROM entities 
       ORDER BY RAND() LIMIT 1"
    );

    $query->execute();

    /* return row as associative array */
    $row = $query->fetch(PDO::FETCH_ASSOC);

    return new Entity($this->con, $row);
  }
}
