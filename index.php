<?php
  require 'vendor/autoload.php';

  $configs = new Geneticdrift\Config;
  
  $templates = new League\Plates\Engine($configs->config['web_root'] . '/templates');
  $templates->loadExtension(new League\Plates\Extension\Asset($configs->config['web_root']));

  header('Cache-Control: no-cache, must-revalidate');
  echo $templates->render('queueForm', ['error' => false, 'errorMessage' => null]);
