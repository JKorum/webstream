<?php
require_once('includes/config.php');
require_once('includes/classes/PreviewProvider.php');
require_once('includes/classes/CategoryContainers.php');
require_once('includes/classes/Entity.php');
require_once('includes/classes/ErrorRender.php');
require_once('includes/classes/SeasonProvider.php');

if (!isset($_SESSION["userLoggedIn"])) {
  header("Location: register.php");
}

if (!isset($_GET['id'])) {
  $error = ErrorRender::show('Error: no entity id provided.');
} else {

  $entityId = $_GET['id'];
  $entity = new Entity($con, $entityId);

  $username = $_SESSION["userLoggedIn"];

  $preview = new PreviewProvider($con, $username);
  $previewHtml = $preview->createPreviewVideo($entity);

  $provider = new SeasonProvider($con, $username);
  $seasonsHtml = $provider->create($entity) ? $provider->create($entity) : '';

  $category = new CategoryContainers($con, $username);
  $suggestToWatchHtml = $category->showCategory(
    $entity->getCategoryId(),
    "You might also like",
    $entity->getId()
  );
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

  <link rel="stylesheet" type="text/css" href="assets/slick/slick.css" />
  <link rel="stylesheet" type="text/css" href="assets/slick/slick-theme.css" />

  <script src="https://kit.fontawesome.com/df3682f87e.js" crossorigin="anonymous"></script>

</head>

<body>
  <?php

  if (!isset($error)) {
    print "
    <div id='container-main' class='container-fluid p-0'>
      <div class='row m-0'>
        <div class='col-12 p-0'>
          $previewHtml
        </div>
      </div>
      $seasonsHtml
      $suggestToWatchHtml      
    </div>    
    ";
  } else {
    print "
    <div id='container-main' class='container-fluid p-0 error-wrapper'>
      $error
    </div>    
    ";
  }

  ?>

  <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
  <script type="text/javascript" src="assets/slick/slick.min.js"></script>
  <script src="assets/js/helpers.js"></script>

  <script>
    const btnPlay = document.getElementById('btn-play');
    if (btnPlay && watchVideo) {
      const id = btnPlay.dataset.video
      const handler = watchVideo(id)
      btnPlay.addEventListener('click', handler)
    }

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
  <script>
    $(document).ready(function() {
      $('.slider').slick({
        centerPadding: 0,
        arrows: false,
        dots: true,
        infinite: false,
        slidesToShow: 2,
        slidesToScroll: 1,
        mobileFirst: true,
        responsive: [{
            breakpoint: 768,
            settings: {
              slidesToShow: 3
            }
          },
          {
            breakpoint: 992,
            settings: {
              slidesToShow: 4
            }
          },
          {
            breakpoint: 1200,
            settings: {
              slidesToShow: 5
            }
          },
          {
            breakpoint: 1424,
            settings: {
              slidesToShow: 6
            }
          }
        ]
      })
      $('.slider-infinite').slick({
        centerPadding: 0,
        arrows: false,
        dots: true,
        infinite: true,
        slidesToShow: 2,
        slidesToScroll: 1,
        mobileFirst: true,
        responsive: [{
            breakpoint: 768,
            settings: {
              slidesToShow: 3
            }
          },
          {
            breakpoint: 992,
            settings: {
              slidesToShow: 4
            }
          },
          {
            breakpoint: 1200,
            settings: {
              slidesToShow: 5
            }
          },
          {
            breakpoint: 1424,
            settings: {
              slidesToShow: 6
            }
          }
        ]
      })
    })
  </script>
</body>

</html>