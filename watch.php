<?php

require_once('includes/config.php');
require_once('includes/classes/ErrorRender.php');
require_once('includes/classes/Video.php');
require_once('includes/classes/VideoProvider.php');

if (!isset($_SESSION["userLoggedIn"])) {
  header("Location: register.php");
}

if (!isset($_GET['id'])) {
  $error = ErrorRender::show('Error: no video id provided.');
} else {

  $video = new Video($con, $_GET['id']);
  $video->incrementViews();

  $source = $video->getFilePath();
  $title = $video->getTitle();

  $nextVideo = VideoProvider::getUpNext($con, $video);
  $nextVideoEntityName = $nextVideo->getEntityName();
  $nextVideoTitle = $nextVideo->getTitle();
  $nextVideoInfo = $nextVideo->getSeasonAndEpisode();
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

  if (!isset($error)) {
    print "
    <div id='container-main' class='container-fluid p-0'>
      <div class='row m-0'>
        <div class='col-12 p-0'>
          <div class='embed-responsive embed-responsive-16by9 position-relative d-flex'>
            <div id='video-nav-back' class='animated faster'>
              <i onclick='goBack()' class='fas fa-arrow-circle-left fa-5x p-2 pl-3'></i>
              <h1 class='display-4 m-0 p-2'>$title</h1>
              <i id='replay' onclick='replayVideo()' class='fas fa-redo fa-4x p-2 pr-3 ml-auto'></i>
            </div>
            <div id='video-nav-next' class='p-3 rounded animated faster fadeIn'>              
              <h1>Up Next</h1>            
              <p class='lead'>$nextVideoTitle</p>   
              <p>$nextVideoInfo</p>                         
              <button id='play-next' type='button' class='btn btn-block'>
                <i class='fas fa-play'></i>
                Play
              </button>
            </div>
            <video id='player' controls autoplay class='embed-responsive-item'>
              <source src='$source' type='video/mp4'>              
            </video>
          </div>
        </div>
      </div>  
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
  <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
  <script src="assets/js/helpers.js"></script>
  <script>
    const btnPlayNext = document.getElementById('play-next')
    if (watchVideo && btnPlayNext) {
      const handler = watchVideo("<?php echo $nextVideo->getId(); ?>")
      btnPlayNext.addEventListener('click', handler)
    }

    /* track video progress */
    const user = "<?php echo $_SESSION["userLoggedIn"]; ?>"
    const video = "<?php echo $video->getId(); ?>"

    if (axios && user && video && user !== '' && video !== '') {
      if (trackVideoProgress) {
        /* create record in db */
        trackVideoProgress(user, video, null)
      }

      const player = document.getElementById('player')
      if (player && getVideoStart) {
        /* set video start */
        const handleCanPlay = () => {
          getVideoStart(user, video, player, handleCanPlay)
        }
        player.addEventListener('canplay', handleCanPlay)
      }

      if (player && trackVideoProgress && markVideoFinished) {
        /* update record in db */
        let interId;

        const handlePlaying = (e) => {
          interId = setInterval(() => {
            const progress = Math.round(e.target.duration) === Math.round(e.target.currentTime) ? 0 : e.target.currentTime
            trackVideoProgress(user, video, progress)
          }, 2000)
        }
        const handlePause = () => {
          clearInterval(interId)
        }

        const handleEnd = () => {
          markVideoFinished(user, video)
        }

        player.addEventListener('playing', handlePlaying)
        player.addEventListener('pause', handlePause) // run on pause and video end
        player.addEventListener('ended', handleEnd)

      }

      /* show watch next element*/
      const next = document.getElementById('video-nav-next')
      if (player && next) {
        player.addEventListener('ended', () => {
          next.style.display = 'block';
        })
      }

    }


    /* hav handler */
    const goBack = () => window.history.back()

    /* hide / show hav */
    $videoNav = document.getElementById('video-nav-back');
    if ($videoNav) {

      let id = setTimeout(() => {
        if ($videoNav.classList.contains('fadeIn')) {
          $videoNav.classList.remove('fadeIn')
        }
        if (!$videoNav.classList.contains('fadeOut')) {
          $videoNav.classList.add('fadeOut')
        }
      }, 3000)

      const handler = () => {
        clearTimeout(id)
        if ($videoNav.classList.contains('fadeOut')) {
          $videoNav.classList.remove('fadeOut')
        }
        if (!$videoNav.classList.contains('fadeIn')) {
          $videoNav.classList.add('fadeIn')
        }

        id = setTimeout(() => {
          if ($videoNav.classList.contains('fadeIn')) {
            $videoNav.classList.remove('fadeIn')
          }
          if (!$videoNav.classList.contains('fadeOut')) {
            $videoNav.classList.add('fadeOut')
          }
        }, 3000)
      }

      document.addEventListener('mousemove', handler)

    }
  </script>
</body>

</html>