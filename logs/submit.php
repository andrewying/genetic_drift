<?php
  require 'vendor/autoload.php';

  if ($_GET["submit"] != "submit") {
    die ("Unauthorised access.");
  }
  if ($_GET["inputMutation"] == 1) {
    $mutation = true;
  }
  else {
    $mutation = flase;
  }

  $key = microtime();

  $args = array(
        'key' => $key,
        'population' => $_GET["inputPopulation"],
        'generations' => $_GET["inputGenerations"],
        'mutation' => $mutation,
        'mutationRate' => $_GET["inputMutationRate"],
        'mutationDef' => $_GET["inputMutationDef"]
  );
  $token = Resque::enqueue('gene', 'GeneticDriftSimulate', $args, true);
  $time = time();

  header("Location:checkStatus.php?key=" . urlencode($key) . "&token=" . urlencode($token) . "&time=" . urlencode($time));
