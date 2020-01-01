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
  $username = FormSanitizer::sanitizeUsername($_POST['username']);
  $password = FormSanitizer::sanitizePassword($_POST['password']);

  $success = $account->login($username, $password);
  if ($success) {
    $_SESSION["userLoggedIn"] = $username;
    header("Location: index.php");
  } else {
    $loginError = $account->getError(Constants::$loginError);
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
          <h1 class="display-4">Login</h1>

          <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">

            <div class="form-group">
              <input type="text" class="form-control<?php echo isset($loginError) ? ' is-invalid' : null; ?>" name='username' placeholder="Username" value="<?php echo isset($username) ? $username : ""; ?>" required>
              <div class="invalid-feedback">
                Sorry, login is failed.
              </div>
            </div>
            <div class="form-group">
              <input type="password" class="form-control<?php echo isset($loginError) ? ' is-invalid' : null; ?>" name='password' placeholder="Password" value="<?php echo isset($password) ? $password : ""; ?>" required>
            </div>
            <button type="submit" class="btn btn-outline-primary mr-1" name="submit">Submit</button>
            <a href="register.php" class="btn btn-outline-info">Go To Register</a>
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