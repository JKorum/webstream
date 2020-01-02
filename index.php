<?php
require_once('includes/config.php');
require_once('includes/classes/PreviewProvider.php');

if (!isset($_SESSION["userLoggedIn"])) {
  header("Location: register.php");
}

$username = $_SESSION["userLoggedIn"];

$preview = new PreviewProvider($con, $username);


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
  <script src="https://kit.fontawesome.com/df3682f87e.js" crossorigin="anonymous"></script>
</head>

<body>
  <div class="container-fluid p-0">
    <?php echo $preview->createPreviewVideo(null); ?>
  </div>

  <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
  <script>
    const btnMute = document.getElementById('btn-mute')
    const video = document.getElementById('video-preview')
    const icon = document.getElementById('icon-mute')
    const text = document.getElementById('btn-mute-text')

    const img = document.getElementById('img-preview')

    if (btnMute && video && icon && text) {
      function handleClick(e) {
        video.muted = !video.muted

        icon.classList.toggle('fa-volume-mute')
        icon.classList.toggle('fa-volume-up')

        if (text.innerText.toLowerCase() === 'muted') {
          text.innerText = 'Unmuted'
        } else {
          text.innerText = 'Muted'
        }
      }

      btnMute.addEventListener('click', handleClick)
    }

    if (video && img) {
      function handleVideoStop() {
        video.hidden = !video.hidden
        img.hidden = !img.hidden

      }

      video.addEventListener('ended', handleVideoStop)

    }
  </script>
</body>

</html>