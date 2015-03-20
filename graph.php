<?php
  require 'vendor/autoload.php';

  $configs = new Geneticdrift\Config;

  $templates = new League\Plates\Engine($configs->config['web_root'] . '/templates');
  $templates->loadExtension(new League\Plates\Extension\Asset($configs->config['web_root']));

  try {
    if (!$_GET['key']) {
      throw new Exception('Invalid GET request. No data received.');
    }

    $key = urldecode($_GET['key']);
    $token = urldecode($_GET['token']);
    $graph = urldecode($_GET['graph']);

    $content = file_get_contents('logs/' . urlencode($key) . '.json');
    if ($content == false) {
      throw new Exception('Invalid key supplied.');
    }

    $array = json_decode($content, true);

    header('Cache-Control: no-cache, must-revalidate');
    if ($graph == 'allele') {
      echo $templates->render('graph', ['graph' => 'allele', 'numAllele' => $array['numAllele'], 'dataArray' => $array['alleleFeq'], 'graphXAxis' => $array['generations'], 'graphYAxis' => $array['population'] * 2]);
    }
    elseif ($graph == 'hetero') {
      echo $templates->render('graph', ['graph' => 'hetero', 'numAllele' => $array['numAllele'], 'dataArray' => $array['hetero'], 'graphXAxis' => $array['generations'], 'graphYAxis' => $array['population']]);
    }
    else {
      throw new Exception ('Invalid GET request. Invalid value for $_GET[\'graph\']');
    }
  }
  catch (Exception $e) {
    header('Cache-Control: no-cache, must-revalidate');
    echo $templates->render('exception', ['showError' => true, 'message' => $e->getMessage(), 'trace' => $e->getTraceAsString(), 'adminEmail' => $configs->config['admin_email']]);
  }
