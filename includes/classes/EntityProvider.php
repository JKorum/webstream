<?php
require_once('Entity.php');

class EntityProvider
{

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
}
