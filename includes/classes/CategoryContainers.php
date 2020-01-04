<?php

require_once('EntityProvider.php');
require_once('PreviewProvider.php');

class CategoryContainers
{

  private $con, $username;

  public function __construct($con, $username)
  {
    $this->con = $con;
    $this->username = $username;
  }

  public function showAllCategories()
  {
    $query = $this->con->prepare(
      "SELECT * FROM categories"
    );

    $query->execute();

    $categoriesRowsHtml = "";
    while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
      $categoryHtml = $this->getCategoryHtml($row, null, true, true);

      /* check if category has entities in it */
      if ($categoryHtml !== null) {

        $categoryName = $row['name'];

        $categoriesRowsHtml .= "
        <div class='mt-4'>
          <h5 class='category-title pl-1'><a href='#' class='text-reset text-decoration-none'>$categoryName</a></h5>  
          <div class='slider'>$categoryHtml</div>                
        </div>
        ";
      }
    }

    return $categoriesRowsHtml;
  }

  private function getCategoryHtml($sqlData, $title, $tvShows, $movies)
  {
    $categoryId = $sqlData['id'];
    $title = $title === null ? $sqlData['name'] : $title;

    $entities = array();

    if ($tvShows && $movies) {
      $entities = EntityProvider::getEntities($this->con, $categoryId, 10);
    } elseif ($tvShows) {
      # get tv-show entities 
    } else {
      # get movie entities
    }

    /* check if category has entities in it */
    if (empty($entities)) {
      return null;
    } else {

      $entitiesHtml = '';

      $preview = new PreviewProvider($this->con, $this->username);

      foreach ($entities as $entity) {
        $entitiesHtml .= $preview->createEntityPreview($entity);
      }

      return $entitiesHtml;
    }
  }
}
