<?php
  require 'vendor/autoload.php';

  $configs = new Geneticdrift\Config;

  $templates = new League\Plates\Engine($configs->config['web_root'] . '/templates');
  $templates->loadExtension(new League\Plates\Extension\Asset($configs->config['web_root']));

  try {
    if ($_GET['status'] == 'false') {
      throw new Exception('Job failed.');
    }
    elseif (!$_GET['key']) {
      throw new Exception('Invalid GET request. No data received.');
    }

    $key = urldecode($_GET['key']);
    $token = urldecode($_GET['token']);
    $time = urldecode($_GET['time']);

    $content = file_get_contents('logs/' . urlencode($key) . '.json');
    if ($content == false) {
      throw new Exception('Invalid key supplied.');
    }

    $array = json_decode($content, true);
    if ($array['reproduction'] == 1){
      $reproduction = 'Random pairing (conserves resources)';
    }
    elseif ($array['reproduction'] == 2){
      $reproduction = 'Fully random';
    }
    else {
      throw new Exception('Data compromised.');
    }

    if ($array['mutationDef'] == 1) {
      $mutationDef = 'Aggressive (Change in allele, including apperance of new allele)';
    }
    elseif ($array['mutationDef'] == 2) {
      $mutationDef = 'Simplistic (Apperance of new allele which does not previously exist in the population)';
    }
    else {
      throw new Exception('Data compromised.');
    }

    if ($array['mutationRate'] == 1) {
      $mutationRate = '1 mutation/' . $array['mutationRate'] . ' generation';
    }
    else {
      $mutationRate = '1 mutation/' . $array['mutationRate'] . ' generations';
    }

    header('Cache-Control: no-cache, must-revalidate');
    echo $templates->render('result', ['jobID' => $token, 'jobKey'=> $key, 'jobSubmitted' => date('r', $time), 'jobPopulation' => $array['population'], 'jobGenerations' => $array['generations'], 'jobReproduction' => $reproduction, 'jobMutation' => $array['mutation'], 'jobMutationRate' => $mutationRate, 'jobMutationDef' => $mutationDef]);
  }
  catch (Exception $e) {
    header('Cache-Control: no-cache, must-revalidate');
    echo $templates->render('exception', ['showError' => true, 'message' => $e->getMessage(), 'trace' => $e->getTraceAsString(), 'adminEmail' => $configs->config['admin_email']]);
  }
