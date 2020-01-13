<?php

require_once('includes/config.php');
require_once('includes/classes/User.php');
require_once('includes/classes/Account.php');

if (!isset($_SESSION["userLoggedIn"])) {
  header("Location: register.php");
}
$user = new User($con, $_SESSION["userLoggedIn"]);

$firstName = isset($_POST['firstName']) ? $_POST['firstName'] : $user->getFirstName();
$secondName = isset($_POST['secondName']) ? $_POST['secondName'] : $user->getSecondName();
$email = isset($_POST['email']) ? $_POST['email'] : $user->getEmail();

$action = $_SERVER['PHP_SELF'];

if (isset($_POST['update-details'])) {
  $account = new Account($con);
  $account->updateDetails($_POST['firstName'], $_POST['secondName'], $_POST['email'], $_SESSION["userLoggedIn"]);
}

if (isset($_POST['update-password']) && $_POST['new-pass'] === $_POST['new-pass-confirm']) {

  $account = new Account($con);
  $account->updatePassword($_SESSION["userLoggedIn"], $_POST['old-pass'], $_POST['new-pass']);
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
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.2/animate.min.css">
  <script src="https://kit.fontawesome.com/df3682f87e.js" crossorigin="anonymous"></script>
</head>

<body>
  <?php
  print include 'includes/navbar.php';

  print "
    <div id='profile-forms-container' class='container'>
      <div class='row justify-content-center'>
        <div class='col-10 col-md-6 col-form'>
          <form method='POST' action='$action'>
            <h1>Update details</h1>
            <div class='form-group'>
              <input class='form-control' type='text' placeholder='First name' name='firstName' value='$firstName'>
            </div>
            <div class='form-group'>
              <input class='form-control' type='text' placeholder='Second name' name='secondName' value='$secondName'>
            </div>
            <div class='form-group'>
              <input class='form-control' type='text' placeholder='Email' name='email' value='$email'>
            </div>
            <button type='submit' class='btn btn-primary' name='update-details'>Save</button>
          </form>
        </div>
      </div>

      <div class='row justify-content-center mt-3'>
        <div class='col-10 col-md-6 col-form'>
          <form method='POST' action='$action'>
            <h1>Change password</h1>
            <div class='form-group'>
              <input class='form-control' type='password' placeholder='Old password' name='old-pass'>
            </div>
            <div class='form-group'>
              <input class='form-control' type='password' placeholder='New password' name='new-pass'>
            </div>
            <div class='form-group'>
              <input class='form-control' type='password' placeholder='Confirm new password' name='new-pass-confirm'>
            </div>
            <button type='submit' class='btn btn-primary' name='update-password'>Save</button>
          </form>
        </div>
      </div>
    </div>
  ";

  ?>

  <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
  <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
  <script src="assets/js/helpers.js"></script>

  <script>
    /* toggle navbar color */
    const navbar = document.getElementById('nav-main')
    if (navbar) {
      window.addEventListener('load', () => {
        if (window.pageYOffset > navbar.offsetHeight) {
          navbar.classList.add('nav-dark')
        }
      })
      document.addEventListener('scroll', () => {
        if (window.pageYOffset > navbar.offsetHeight) {
          if (!navbar.classList.contains('nav-dark')) {
            navbar.classList.add('nav-dark')
          }
        }
        if (window.pageYOffset === 0) {
          navbar.classList.remove('nav-dark')
        }
      })
    }
  </script>
</body>

</html>