<?php
require_once('includes/config.php');
require_once('includes/classes/PreviewProvider.php');
require_once('includes/classes/CategoryContainers.php');

if (!isset($_SESSION["userLoggedIn"])) {
  header("Location: register.php");
}

$username = $_SESSION["userLoggedIn"];

$preview = new PreviewProvider($con, $username);
$categories = new CategoryContainers($con, $username);

$previewHtml = $preview->createShowPreviewVideo();

$categoriesHtml = $categories->showTVShowsCategories();



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
  print include 'includes/navbar.php';
  print "
    <div id='container-main' class='container-fluid p-0'>
      <div class='row m-0'>
        <div class='col-12 p-0'>
          $previewHtml
        </div>        
      </div>
      <h1 class='mt-4 pl-1'>TV Shows</h1>
      $categoriesHtml
    </div>    
    ";

  ?>

  <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
  <script type="text/javascript" src="assets/slick/slick.min.js"></script>
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
    /* ------------------ */

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
      $('.slider-infinite').slick({
        centerPadding: 0,
        arrows: false,
        dots: true,
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