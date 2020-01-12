<?php

require_once('EntityProvider.php');

class SearchResultsProvider
{
  private $con, $username;

  public function __construct($con, $username)
  {
    $this->con = $con;
    $this->username = $username;
  }

  public function getResults($term)
  {

    $results = EntityProvider::getSearchEntities($this->con, $term);
    if (count($results) > 0) {
      return $this->createResultsHtml($results);
    }
  }

  private function createResultsHtml($results)
  {

    $html = '';

    foreach ($results as $result) {
      $id = $result->getId();
      $thumbnail = $result->getThumbnail();

      $html .= "
        <div class='entity-searched ml-0 mr-1 mt-1 mb-0 animated fadeIn faster'>
          <a href='entity.php?id=$id'>
            <img src='$thumbnail' class='entity-img'>
          </a>
        </div>
      ";
    }

    return $html;
  }
}
