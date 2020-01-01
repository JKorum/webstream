<?php
require_once('includes/config.php');
require_once('includes/classes/FormSanitizer.php');
require_once('includes/classes/Constants.php');
require_once('includes/classes/Account.php');

if (isset($_SESSION["userLoggedIn"])) {
  header("Location: index.php");
}

$account = new Account($con);

if (isset($_POST['submit'])) {

  $firstName = FormSanitizer::sanitizeString($_POST['firstName']);
  $lastName = FormSanitizer::sanitizeString($_POST['lastName']);
  $username = FormSanitizer::sanitizeUsername($_POST['username']);
  $email = FormSanitizer::sanitizeEmail($_POST['email']);
  $emailConfirm = FormSanitizer::sanitizeEmail($_POST['emailConfirm']);
  $password = FormSanitizer::sanitizePassword($_POST['password']);
  $passwordConfirm = FormSanitizer::sanitizePassword($_POST['passwordConfirm']);

  $success = $account->register($firstName, $lastName, $username, $email, $emailConfirm, $password, $passwordConfirm);

  if ($success) {
    $_SESSION["userLoggedIn"] = $username;
    header("Location: index.php");
  }

  /* check for validation errors */
  $firstNameVal = $account->getError(Constants::$firstNameError) ? " is-invalid" : " is-valid";
  $lastNameVal = $account->getError(Constants::$lastNameError) ? " is-invalid" : " is-valid";

  /* check username */
  $lengthVal = $account->getError(Constants::$usernameError) ? " is-invalid" : " is-valid";
  if ($lengthVal === " is-invalid") {
    $usernameVal = $lengthVal;
  } else {
    $uniqueVal = $account->getError(Constants::$usernameTaken) ? " is-invalid" : " is-valid";
    $usernameVal = $uniqueVal;
  }

  /* check email */
  $emailNotMatch = $account->getError(Constants::$emailNotMatch) ? " is-invalid" : " is-valid";
  if ($emailNotMatch === " is-invalid") {
    $emailVal = $emailNotMatch;
  } else {
    $emailNotValid = $account->getError(Constants::$emailNotValid) ? " is-invalid" : " is-valid";
    if ($emailNotValid === " is-invalid") {
      $emailVal = $emailNotValid;
    } else {
      $emailNotUnique = $account->getError(Constants::$emailNotUnique) ? " is-invalid" : " is-valid";
      $emailVal = $emailNotUnique;
    }
  }

  /* check password */
  $passwordNotMatch = $account->getError(Constants::$passwordNotMatch) ? " is-invalid" : " is-valid";
  if ($passwordNotMatch === " is-invalid") {
    $passwordVal = $passwordNotMatch;
  } else {
    $passwordLengthError = $account->getError(Constants::$passwordLengthError) ? " is-invalid" : " is-valid";
    $passwordVal = $passwordLengthError;
  }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Document</title>
  <link href="https://stackpath.bootstrapcdn.com/bootswatch/4.4.1/lumen/bootstrap.min.css" rel="stylesheet" integrity="sha384-715VMUUaOfZR3/rWXZJ9agJ/udwSXGEigabzUbJm2NR4/v5wpDy8c14yedZN6NL7" crossorigin="anonymous">
  <link rel="stylesheet" href="assets/styles/style.css">
</head>

<body>
  <div id="background" class='vh-100 bg-secondary d-flex'>
    <div class="container">
      <div class="row justify-content-center">
        <div id="col-register" class="col-sm-10 col-md-6 bg-white rounded shadow p-4">
          <img id="logo" src="https://fontmeme.com/permalink/191230/e3bd5210ce8fe0149ee201e78fdb2afd.png" alt="webstream logo">
          <hr>
          <h1 class="display-4">Register</h1>

          <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <div class="form-group">
              <input type="text" class="form-control<?php echo isset($firstNameVal) ? $firstNameVal : null; ?>" name='firstName' placeholder="First Name" value="<?php echo isset($firstName) ? $firstName : ""; ?>" required>
              <div class="valid-feedback">
                Looks great!
              </div>
              <div class="invalid-feedback">
                It should be less than 25 chars and greater than two.
              </div>
            </div>
            <div class="form-group">
              <input type="text" class="form-control<?php echo isset($lastNameVal) ? $lastNameVal : null; ?>" name='lastName' placeholder="Last Name" value="<?php echo isset($lastName) ? $lastName : ""; ?>" required>
              <div class="valid-feedback">
                Looks great!
              </div>
              <div class="invalid-feedback">
                It should be less than 25 chars and greater than two.
              </div>
            </div>
            <div class="form-group">
              <input type="text" class="form-control<?php echo isset($usernameVal) ? $usernameVal : null; ?>" name='username' placeholder="Username" value="<?php echo isset($username) ? $username : ""; ?>" required>
              <div class="valid-feedback">
                Looks great!
              </div>
              <?php if (isset($uniqueVal)) : ?>
                <div class="invalid-feedback">
                  Sorry, the username is already taken.
                </div>
              <?php else : ?>
                <div class="invalid-feedback">
                  It should be less than 50 chars and greater than two.
                </div>
              <?php endif; ?>
            </div>
            <div class="form-group">
              <input type="email" class="form-control<?php echo isset($emailVal) ? $emailVal : null; ?>" name='email' placeholder="Email" value="<?php echo isset($email) ? $email : ""; ?>" required>
              <div class="valid-feedback">
                Looks great!
              </div>
              <div class="invalid-feedback">
                <?php if (isset($emailNotUnique)) : ?>
                  Sorry, the email address is already taken.
                <?php elseif (isset($emailNotValid)) : ?>
                  Sorry, the email address is invalid.
                <?php else : ?>
                  The email addresses don't match.
                <?php endif; ?>
              </div>
            </div>
            <div class="form-group">
              <input type="email" class="form-control<?php echo isset($emailVal) ? $emailVal : null; ?>" name='emailConfirm' placeholder="Confirm Email" value="<?php echo isset($emailConfirm) ? $emailConfirm : ""; ?>" required>
            </div>
            <div class="form-group">
              <input type="password" class="form-control<?php echo isset($passwordVal) ? $passwordVal : null; ?>" name='password' placeholder="Password" value="<?php echo isset($password) ? $password : ""; ?>" required>
              <div class="valid-feedback">
                Looks great!
              </div>
              <div class="invalid-feedback">
                <?php if (isset($passwordLengthError)) : ?>
                  It should be greater than 4 chars.
                <?php else : ?>
                  The passwords don't match.
                <?php endif; ?>
              </div>
            </div>
            <div class="form-group">
              <input type="password" class="form-control<?php echo isset($passwordVal) ? $passwordVal : null; ?>" name='passwordConfirm' placeholder="Confirm Password" value="<?php echo isset($passwordConfirm) ? $passwordConfirm : ""; ?>" required>
            </div>
            <button type="submit" class="btn btn-outline-primary mr-1" name="submit">Submit</button>
            <a href="login.php" class="btn btn-outline-info">Go To Login</a>
          </form>


        </div>
      </div>
    </div>

  </div>

  <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
</body>

</html>