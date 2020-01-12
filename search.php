<?php
require_once('includes/config.php');

if (!isset($_SESSION["userLoggedIn"])) {
  header("Location: register.php");
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
    <div id='container-main' class='container-fluid p-0'>
      <div id='search-container' class='row m-0'>
        <div class='col-10 col-md-6'>
          <div class='input-group input-group-lg'>
          <input 
            id='search-field'
            type='text' 
            class='form-control' 
            placeholder='Find something to watch...'
            autofocus
          >
        </div>         
        </div>
      </div>   
      <div class='row m-0 mt-3'>
        <div class='col'>
          <div id='search-results' class='d-flex flex-wrap'></div>
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
    /* handle search */
    const search = document.getElementById('search-field')
    const results = document.getElementById('search-results')
    let timer = null

    if (search && results && axios && searchEntities) {

      const handler = e => {
        if (timer) {
          clearTimeout(timer)
        }
        if (e.target.value !== '') {
          timer = setTimeout(() => {
            searchEntities(
              "<?php echo $_SESSION["userLoggedIn"]; ?>",
              e.target.value,
              results
            )
          }, 500)
        } else {
          results.innerHTML = ''
        }
      }

      search.addEventListener('keyup', handler)
    }
  </script>
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