<?php
require_once('Constants.php');
class Account
{
  /* to connect to database */
  private $con;

  /* to track validation errors */
  private $errors = array();

  public function __construct($con)
  {
    $this->con = $con;
  }

  public function updateDetails($fn, $ln, $em, $un)
  {
    $query = $this->con->prepare(
      "UPDATE users 
       SET firstName = :fn, secondName = :ln, email = :em
       WHERE username = :un"
    );
    $query->bindValue(':fn', $fn);
    $query->bindValue(':ln', $ln);
    $query->bindValue(':em', $em);
    $query->bindValue(':un', $un);

    $query->execute();
  }

  public function updatePassword($username, $oldPass, $newPass)
  {
    $query = $this->con->prepare(
      "SELECT * FROM users       
       WHERE userName = :un
       AND password = :pass"
    );

    $password = hash("sha512", $oldPass);

    $query->bindValue(':un', $username);
    $query->bindValue(':pass', $password);

    $query->execute();

    if ($row = $query->fetch(PDO::FETCH_ASSOC)) {
      $query = $this->con->prepare(
        "UPDATE users       
         SET password = :pass
         WHERE username = :un"
      );

      $password = hash("sha512", $newPass);
      $query->bindValue(':un', $username);
      $query->bindValue(':pass', $password);
      $query->execute();
    }
  }

  public function register($fn, $ln, $un, $em, $emc, $pw, $pwc)
  {
    $this->validateFirstName($fn);
    $this->validateLastName($ln);
    $this->validateUsername($un);
    $this->validateEmail($em, $emc);
    $this->validatePassword($pw, $pwc);

    if (empty($this->errors)) {
      return $this->insertUser($fn, $ln, $un, $em, $pw);
    }

    return false;
  }

  public function login($un, $pw)
  {
    $password = hash('sha512', $pw);

    $query = $this->con->prepare(
      "SELECT * FROM users
       WHERE userName=:un AND password=:pw"
    );

    $query->bindValue(":un", $un);
    $query->bindValue(":pw", $password);

    $query->execute();

    if ($query->rowCount() === 1) {
      return true;
    } else {
      array_push($this->errors, Constants::$loginError);
      return false;
    }
  }

  private function insertUser($fn, $ln, $un, $em, $pw)
  {
    $password = hash("sha512", $pw);

    $query = $this->con->prepare(
      "INSERT INTO users (firstName, secondName, userName, email, password) 
       VALUES (:fn, :ln, :un, :em, :pw)"
    );

    $query->bindValue(":fn", $fn);
    $query->bindValue(":ln", $ln);
    $query->bindValue(":un", $un);
    $query->bindValue(":em", $em);
    $query->bindValue(":pw", $password);

    return $query->execute();
  }

  private function validateFirstName($input)
  {
    if (strlen($input) < 2 || strlen($input) > 25) {
      array_push($this->errors, Constants::$firstNameError);
    }
  }

  private function validateLastName($input)
  {
    if (strlen($input) < 2 || strlen($input) > 25) {
      array_push($this->errors, Constants::$lastNameError);
    }
  }

  private function validateUsername($input)
  {
    if (strlen($input) < 2 || strlen($input) > 50) {
      array_push($this->errors, Constants::$usernameError);
      return;
    }

    /* use PDO instance -> prepare and execute query */
    $query = $this->con->prepare("SELECT * FROM users WHERE username=:input");
    $query->bindValue(":input", $input);
    $query->execute();

    /* check if username already in use */
    if ($query->rowCount() !== 0) {
      array_push($this->errors, Constants::$usernameTaken);
    }
  }

  private function validateEmail($email, $emailConfirm)
  {
    if ($email !== $emailConfirm) {
      array_push($this->errors, Constants::$emailNotMatch);
      return;
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      array_push($this->errors, Constants::$emailNotValid);
      return;
    }

    $query = $this->con->prepare("SELECT * FROM users WHERE email=:email");
    $query->bindValue(":email", $email);
    $query->execute();

    if ($query->rowCount() !== 0) {
      array_push($this->errors, Constants::$emailNotUnique);
    }
  }

  private function validatePassword($password, $passwordConfirm)
  {
    if ($password !== $passwordConfirm) {
      array_push($this->errors, Constants::$passwordNotMatch);
      return;
    }

    if (strlen($password) < 5) {
      array_push($this->errors, Constants::$passwordLengthError);
    }
  }

  public function getError($error)
  {
    if (in_array($error, $this->errors)) {
      return $error;
    } else {
      return null;
    }
  }
}
