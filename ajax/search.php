<?php

require_once('../includes/config.php');
require_once('../includes/classes/SearchResultsProvider.php');

if (isset($_POST['user']) && isset($_POST['term'])) {

  $srp = new SearchResultsProvider($con, $_POST['user']);
  echo $srp->getResults($_POST['term']);
} else {
  echo 'No user and term data was provided to the page.';
}
