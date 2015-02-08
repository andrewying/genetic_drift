<?php
  error_reporting(-1);
  require 'vendor/autoload.php';

  $templates = new League\Plates\Engine(dirname(__FILE__) . '/templates');

  header('Cache-Control: no-cache, must-revalidate');
  echo $templates->render('queueForm', ['error' => false, 'errorMessage' => null]);
