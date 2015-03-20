<?php
  require 'vendor/autoload.php';

  $configs = new Geneticdrift\Config;

  $templates = new League\Plates\Engine($configs->config['web_root'] . '/templates');
  $templates->loadExtension(new League\Plates\Extension\Asset($configs->config['web_root']));

  try {
    if (!$_GET['key']) {
      throw new Exception('Invalid GET request. No data received.');
    }

    $key = $_GET['key'];

    $content = file_get_contents('logs/' . urlencode($key) . '.json');
    if ($content == false) {
      throw new Exception('Invalid key supplied.');
    }

    header('X-Accel-Redirect: /logs/' . urlencode($key) . '.json');
    header('Content-type: application/octet-stream');
    header('Content-Disposition: attachment; filename="gd_raw.json"');
  }
  catch (Exception $e) {
    header('Cache-Control: no-cache, must-revalidate');
    echo $templates->render('exception', ['showError' => true, 'message' => $e->getMessage(), 'trace' => $e->getTraceAsString(), 'adminEmail' => $configs->config['admin_email']]);
  }
