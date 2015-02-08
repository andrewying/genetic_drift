<?php
  require 'vendor/autoload.php';

  try {
    if (!$_GET['key']) {
      throw new Exception('Invalid GET request. No data received.');
    }

    $key = urldecode($_GET['key']);
    $token = urldecode($_GET['token']);
    $time = urldecode($_GET['time']);
    $expireTime = $time + 86400;

    $status = new Resque_Job_Status(urldecode($_GET['token']));
    $statusMessage = $status->get();

    if ($statusMessage == Resque_Job_Status::STATUS_COMPLETE) {
      header('Cache-Control: no-cache, must-revalidate');
      header('Location:result.php?key=' . urlencode($key) . '&token=' . urlencode($token) . '&time=' . urlencode($time));
    }
    elseif ($statusMessage == Resque_Job_Status::STATUS_FAILED) {
      header('Cache-Control: no-cache, must-revalidate');
      header('Location:result.php?status=false');
    }
    elseif ($statusMessage == false) {
      throw new Exception('Invalid token supplied.');
    }
    else {
      if ($statusMessage == Resque_Job_Status::STATUS_WAITING) {
        $jobStatus = 'Job Queued';
      }
      else {
        $jobStatus = 'Job Running';
      }

      $templates = new League\Plates\Engine(dirname(__FILE__) . '/templates');

      header('Cache-Control: no-cache, must-revalidate');
      header('Refresh: 5');
      echo $templates->render('checkStatus', ['jobID' => $token, 'jobSubmitted' => date('r', $time), 'jobExpires' => date('r', $expireTime), 'jobStatus' => $jobStatus]);
    }
  }
  catch (Exception $e) {
    $templates = new League\Plates\Engine(dirname(__FILE__) . '/templates');

    header('Cache-Control: no-cache, must-revalidate');
    echo $templates->render('exception', ['showError' => true, 'message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
  }
