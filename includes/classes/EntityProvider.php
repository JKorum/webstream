<?php
require_once('Entity.php');

class EntityProvider
{
  public static function getSearchEntities($con, $term)
  {
    $query = $con->prepare(
      "SELECT * FROM entities 
       WHERE name LIKE CONCAT('%', :term, '%')
       LIMIT 30"
    );

    $query->bindValue(':term', $term);
    $query->execute();

    $result = array();
    while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
      $result[] = new Entity($con, $row);
    }
    return $result;
  }

  public static function getEntities($con, $categoryId, $limit, $exclude)
  {

    $sql = "SELECT * FROM entities ";

    if ($categoryId !== null) {
      $sql .= "WHERE categoryId = :categoryId ";
    }

    if ($categoryId !== null && $exclude !== null) {
      $sql .= "AND id != :entityId ";
    }

    if ($categoryId === null && $exclude !== null) {
      $sql .= "WHERE id != :entityId ";
    }

    $sql .= "ORDER BY RAND() LIMIT :limit";

    $query = $con->prepare($sql);

    if ($categoryId !== null) {
      $query->bindValue(':categoryId', $categoryId);
    }

    if ($exclude !== null) {
      $query->bindValue(':entityId', $exclude);
    }

    $query->bindValue(':limit', $limit, PDO::PARAM_INT);
    $query->execute();

    $result = array();

    while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
      $result[] = new Entity($con, $row);
    }

    return $result;
  }

  public static function getShowsEntities($con, $categoryId, $limit)
  {
    $sql = 'SELECT DISTINCT (entities.id) FROM entities
            INNER JOIN videos ON entities.id = videos.entityId
            WHERE videos.isMovie = 0 ';

    if ($categoryId !== null) {
      $sql .= 'AND entities.categoryId = :categoryId ';
    }

    $sql .= 'ORDER BY RAND() LIMIT :limit';

    $query = $con->prepare($sql);

    if ($categoryId !== null) {
      $query->bindValue(':categoryId', $categoryId);
    }
    $query->bindValue(':limit', $limit, PDO::PARAM_INT);

    $query->execute();

    $result = array();

    while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
      $result[] = new Entity($con, $row['id']);
    }

    return $result;
  }

  public static function getMoviesEntities($con, $categoryId, $limit)
  {
    $sql = 'SELECT DISTINCT (entities.id) FROM entities
            INNER JOIN videos ON entities.id = videos.entityId
            WHERE videos.isMovie = 1 ';

    if ($categoryId !== null) {
      $sql .= 'AND entities.categoryId = :categoryId ';
    }

    $sql .= 'ORDER BY RAND() LIMIT :limit';

    $query = $con->prepare($sql);

    if ($categoryId !== null) {
      $query->bindValue(':categoryId', $categoryId);
    }
    $query->bindValue(':limit', $limit, PDO::PARAM_INT);

    $query->execute();

    $result = array();

    while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
      $result[] = new Entity($con, $row['id']);
    }

    return $result;
  }
}
