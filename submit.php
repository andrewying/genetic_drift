<?php
  require 'vendor/autoload.php';

  $configs = new Geneticdrift\Config;

  $templates = new League\Plates\Engine($configs->config['web_root'] . '/templates');
  $templates->loadExtension(new League\Plates\Extension\Asset($configs->config['web_root']));

  $status = $configs->appStatus();
  if (!$status) {
    header('Cache-Control: no-cache, must-revalidate');

    echo $templates->render('maintenance', ['adminEmail' => $configs->config['admin_email']]);
    exit;
  }

  try {
    if ($_POST['submit'] != 'submit') {
      throw new Exception('Invalid POST request. No form submission received.');
    }

    $error = false;

    if ($_POST['inputPopulation'] == null) {
      $error = true;
      $errorMessage = 'Please enter the desired population size.';
    }
    elseif (ctype_digit(strval($_POST['inputPopulation'])) == false) {
      $error = true;
      $errorMessage = 'The population size must be an integer.';
    }
    elseif ($_POST['inputPopulation'] <= 0) {
      $error = true;
      $errorMessage = 'The population size cannot be less than or equal to 0.';
    }
    elseif ($_POST['inputPopulation'] > 10000) {
      $error = true;
      $errorMessage = 'The population size cannot be greater than 10000.';
    }
    elseif ($_POST['inputGenerations'] == null) {
      $error = true;
      $errorMessage = 'Please enter the desired number of generations.';
    }
    elseif (ctype_digit(strval($_POST['inputGenerations'])) == false) {
      $error = true;
      $errorMessage = 'The number of generations must be an integer.';
    }
    elseif ($_POST['inputGenerations'] <= 0) {
      $error = true;
      $errorMessage = 'The number of generations cannot be less than or equal to 0.';
    }
    elseif ($_POST['inputGenerations'] > 10000) {
      $error = true;
      $errorMessage = 'The number of generations cannot be greater than 10000.';
    }
    elseif ($_POST['inputReproduction'] != 1 && $_POST['inputReproduction'] != 2) {
      throw new Exception('Invalid GET request. Invalid value for $_POST[\'inputReproduction\']');
    }
    else {
      if ($_POST['inputMutation'] == 1) {
        $mutation = true;

        if ($_POST['inputMutationRate'] == null) {
          $error = true;
          $errorMessage = 'Please enter the desired rate of mutation.';
        }
        elseif (ctype_digit(strval($_POST['inputMutationRate'])) == false) {
          $error = true;
          $errorMessage = 'The rate of mutation must be an integer.';
        }
        elseif ($_POST['inputMutationRate'] <= 0) {
          $error = true;
          $errorMessage = 'The rate of mutation cannot be less than or equal to 0.';
        }
        elseif ($_POST['inputMutationDef'] != 1 && $_POST['inputMutationDef'] != 2) {
          throw new Exception('Invalid POST request. Invalid value for $_POST[\'inputMutationDef\']');
        }
      }
      elseif ($_POST['inputMutation'] == 0) {
        $mutation = false;
        $mutationRate = INF;
      }
      else {
        throw new Exception('Invalid POST request. Invalid value for $_POST[\'inputMutation\']');
      }
    }

    if ($error == false) {
      $key = md5(uniqid(rand(), true));

      Resque::setBackend($configs->config['redis_server']['server'] . ':' . $configs->config['redis_server']['port']);

      $args = array(
        'key' => $key,
        'population' => intval($_POST['inputPopulation']),
        'generations' => intval($_POST['inputGenerations']),
        'reproduction' => $_POST['inputReproduction'],
        'mutation' => $mutation,
        'mutationRate' => intval($_POST['inputMutationRate']),
        'mutationDef' => $_POST['inputMutationDef']
      );
      $token = Resque::enqueue('gene', 'GeneticDriftSimulate', $args, true);
      $time = time();

      header('Cache-Control: no-cache, must-revalidate');
      header('Location: checkStatus.php?key=' . urlencode($key) . '&token=' . urlencode($token) . '&time=' . urlencode($time));
    }
    else {
      header('Cache-Control: no-cache, must-revalidate');
      echo $templates->render('queueForm', ['error' => true, 'errorMessage' => $errorMessage]);
    }
  }
  catch (Exception $e) {
    header('Cache-Control: no-cache, must-revalidate');
    header('HTTP/1.0 400 Bad Request');
    echo $templates->render('exception', ['showError' => true, 'message' => $e->getMessage(), 'trace' => $e->getTraceAsString(), 'adminEmail' => $configs->config['admin_email']]);
  }
