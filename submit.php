<?php
  require 'vendor/autoload.php';

  try {
    if ($_GET['submit'] != 'submit') {
      throw new Exception('Invalid GET request. No form submission received.');
    }

    $error = false;

    if ($_GET['inputPopulation'] == null) {
      $error = true;
      $errorMessage = 'Please enter the desired population size.';
    }
    elseif (ctype_digit(strval($_GET['inputPopulation'])) == false) {
      $error = true;
      $errorMessage = 'The population size must be an integer.';
    }
    elseif ($_GET['inputPopulation'] <= 0) {
      $error = true;
      $errorMessage = 'The population size cannot be less than or equal to 0.';
    }
    elseif ($_GET['inputGenerations'] == null) {
      $error = true;
      $errorMessage = 'Please enter the desired number of generations.';
    }
    elseif (ctype_digit(strval($_GET['inputGenerations'])) == false) {
      $error = true;
      $errorMessage = 'The number of generations must be an integer.';
    }
    elseif ($_GET['inputGenerations'] <= 0) {
      $error = true;
      $errorMessage = 'The number of generations cannot be less than or equal to 0.';
    }
    elseif ($_GET['inputReproduction'] != 1 && $_GET['inputReproduction'] != 2) {
      throw new Exception('Invalid GET request. Invalid value for $_GET[\'inputReproduction\']');
    }
    else {
      if ($_GET['inputMutation'] == 1) {
        $mutation = true;

        if ($_GET['inputMutationRate'] == null) {
          $error = true;
          $errorMessage = 'Please enter the desired rate of mutation.';
        }
        elseif (ctype_digit(strval($_GET['inputMutationRate'])) == false) {
          $error = true;
          $errorMessage = 'The rate of mutation must be an integer.';
        }
        elseif ($_GET['inputMutationRate'] <= 0) {
          $error = true;
          $errorMessage = 'The rate of mutation cannot be less than or equal to 0.';
        }
        elseif ($_GET['inputMutationDef'] != 1 && $_GET['inputMutationDef'] != 2) {
          throw new Exception('Invalid GET request. Invalid value for $_GET[\'inputMutationDef\']');
        }
      }
      elseif ($_GET['inputMutation'] == 0) {
        $mutation = false;
        $mutationRate = INF;
      }
      else {
        throw new Exception('Invalid GET request. Invalid value for $_GET[\'inputMutation\']');
      }
    }

    if ($error == false) {
      $key = md5(uniqid(rand(), true));

      $args = array(
        'key' => $key,
        'population' => $_GET['inputPopulation'],
        'generations' => $_GET['inputGenerations'],
        'reproduction' => $_GET['inputReproduction'],
        'mutation' => $mutation,
        'mutationRate' => $_GET['inputMutationRate'],
        'mutationDef' => $_GET['inputMutationDef']
      );
      $token = Resque::enqueue('gene', 'GeneticDriftSimulate', $args, true);
      $time = time();

      header('Cache-Control: no-cache, must-revalidate');
      header('Location: checkStatus.php?key=' . urlencode($key) . '&token=' . urlencode($token) . '&time=' . urlencode($time));
    }
    else {
      $templates = new League\Plates\Engine(dirname(__FILE__) . '/templates');

      header('Cache-Control: no-cache, must-revalidate');
      echo $templates->render('queueForm', ['error' => true, 'errorMessage' => $errorMessage]);
    }
  }
  catch (Exception $e) {
    $templates = new League\Plates\Engine(dirname(__FILE__) . '/templates');

    header('Cache-Control: no-cache, must-revalidate');
    header('HTTP/1.0 400 Bad Request');
    echo $templates->render('exception', ['showError' => true, 'message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
  }
