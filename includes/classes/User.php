<?php

class User
{

  private $con, $username;

  public function __construct($con, $username)
  {
    $this->con = $con;

    $query = $this->con->prepare(
      "SELECT * FROM users
       WHERE userName = :username"
    );

    $query->bindValue(':username', $username);
    $query->execute();

    $this->username = $query->fetch(PDO::FETCH_ASSOC);
  }

  public function getFirstName()
  {
    return $this->username['firstName'];
  }

  public function getSecondName()
  {
    return $this->username['secondName'];
  }

  public function getEmail()
  {
    return $this->username['email'];
  }
}
