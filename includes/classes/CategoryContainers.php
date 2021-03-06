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

  public function showCategory($categoryId, $title = null, $exclude)
  {
    $query = $this->con->prepare(
      "SELECT * FROM categories
       WHERE id=:categoryId"
    );

    $query->bindValue(':categoryId', $categoryId);
    $query->execute();

    $categoriesRowsHtml = "";

    $row = $query->fetch(PDO::FETCH_ASSOC);
    $categoryHtml = $this->getCategoryHtml($row, $title, true, true, $exclude);

    /* check if category has entities in it */
    if ($categoryHtml !== null) {

      $title = $title === null ? $row['name'] : $title;

      $categoriesRowsHtml .= "
        <div class='mt-4'>
          <h5 class='category-title pl-1' style='pointer-events:none;'>$title</h5>         
          <div class='slider-infinite'>$categoryHtml</div>                
        </div>
      ";
    }

    return $categoriesRowsHtml;
  }

  public function showAllCategories()
  {
    $query = $this->con->prepare(
      "SELECT * FROM categories"
    );

    $query->execute();

    $categoriesRowsHtml = "";
    while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
      $categoryHtml = $this->getCategoryHtml($row, null, true, true, null);

      /* check if category has entities in it */
      if ($categoryHtml !== null) {

        $categoryName = $row['name'];
        $id = $row['id'];

        $categoriesRowsHtml .= "
        <div class='mt-4'>
          <h5 class='category-title pl-1'><a href='category.php?id=$id' class='text-reset text-decoration-none'>$categoryName</a></h5>  
          <div class='slider-infinite'>$categoryHtml</div>                
        </div>
        ";
      }
    }

    return $categoriesRowsHtml;
  }

  public function showTVShowsCategories()
  {
    $query = $this->con->prepare(
      "SELECT * FROM categories"
    );

    $query->execute();

    $categoriesRowsHtml = "";
    while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
      $categoryHtml = $this->getCategoryHtml($row, null, true, false, null);

      /* check if category has entities in it */
      if ($categoryHtml !== null) {

        $categoryName = $row['name'];
        $id = $row['id'];

        $categoriesRowsHtml .= "
        <div class='mt-4'>
          <h5 class='category-title pl-1'><a href='category.php?id=$id' class='text-reset text-decoration-none'>$categoryName</a></h5>  
          <div class='slider-infinite'>$categoryHtml</div>                
        </div>
        ";
      }
    }

    return $categoriesRowsHtml;
  }

  public function showMoviesCategories()
  {
    $query = $this->con->prepare(
      "SELECT * FROM categories"
    );

    $query->execute();

    $categoriesRowsHtml = "";
    while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
      $categoryHtml = $this->getCategoryHtml($row, null, false, true, null);

      /* check if category has entities in it */
      if ($categoryHtml !== null) {

        $categoryName = $row['name'];
        $id = $row['id'];

        $categoriesRowsHtml .= "
        <div class='mt-4'>
          <h5 class='category-title pl-1'><a href='category.php?id=$id' class='text-reset text-decoration-none'>$categoryName</a></h5>  
          <div class='slider-infinite'>$categoryHtml</div>                
        </div>
        ";
      }
    }

    return $categoriesRowsHtml;
  }

  private function getCategoryHtml($sqlData, $title, $tvShows, $movies, $exclude)
  {
    $categoryId = $sqlData['id'];
    // $title = $title === null ? $sqlData['name'] : $title;

    $entities = array();

    if ($tvShows && $movies) {
      $entities = EntityProvider::getEntities($this->con, $categoryId, 10, $exclude);
    } elseif ($tvShows) {
      $entities = EntityProvider::getShowsEntities($this->con, $categoryId, 10);
    } else {
      $entities = EntityProvider::getMoviesEntities($this->con, $categoryId, 10);
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
