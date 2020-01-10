<?php

return "
<nav id='nav-main' class='navbar navbar-expand align-items-center fixed-top border-0'>
  <a class='navbar-brand' href='index.php'>
    <img id='logo' src='https://fontmeme.com/permalink/191230/e3bd5210ce8fe0149ee201e78fdb2afd.png' alt='webstream logo'>
  </a>
  <button class='navbar-toggler' type='button' data-toggle='collapse' data-target='#navbarContent' aria-controls='navbarContent' aria-expanded='false' aria-label='Toggle navigation'>
    <span class='navbar-toggler-icon'></span>
  </button>
  <div class='collapse navbar-collapse' id='navbarContent'>
    <ul class='navbar-nav mr-auto'>
      <li class='nav-item'>
        <a class='nav-link' href='index.php'>Home</a>
      </li>
      <li class='nav-item'>
        <a class='nav-link' href='shows.php'>TV Shows</a>
      </li>
      <li class='nav-item'>
        <a class='nav-link' href='movies.php'>Movies</a>
      </li>
    </ul>
    <ul class='navbar-nav'>
      <li class='nav-item'>
        <a class='nav-link' href='search.php'>
          <i class='fas fa-search fa-lg'></i>
        </a>
      </li>
      <li class='nav-item'>
        <a class='nav-link' href='profile.php'>
          <i class='fas fa-user fa-lg'></i>
        </a>
      </li>
    </ul>
  </div>    
</nav>";
