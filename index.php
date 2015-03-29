<?php
  require 'vendor/autoload.php';

  $configs = new Geneticdrift\Config;

  $templates = new League\Plates\Engine($configs->config['web_root'] . '/templates');
  $templates->loadExtension(new League\Plates\Extension\Asset($configs->config['web_root']));

  header('Cache-Control: no-cache, must-revalidate');

  $status = $configs->appStatus();
  if (!$status) {
    echo $templates->render('maintenance', ['adminEmail' => $configs->config['admin_email']]);
    exit;
  }
  
  echo $templates->render('queueForm', ['error' => false, 'errorMessage' => null]);
