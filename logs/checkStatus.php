<?php
  require 'vendor/autoload.php';

  if (!$_GET["key"]) {
    die ("Unauthorised access.");
  }

  $key = urldecode($_GET["key"]);
  $token = urldecode($_GET["token"]);
  $time = urldecode($_GET["time"]);
  $expireTime = $time + 86400;

  $status = new Resque_Job_Status(urldecode($_GET["token"]));
  $statusMessage = $status->get();

  if ($statusMessage == Resque_Job_Status::STATUS_COMPLETE) {
    header("Location:result.php?key=" . urlencode($key) . "&time=" . urlencode($time));
  }
  elseif ($statusMessage == Resque_Job_Status::STATUS_FAILED) {
    header("Location:result.php?status=false");
  }
  elseif ($statusMessage == false) {
    die("Token invalid.");
  }
  else {
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Job Status - Genetic Drift Simulator</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="refresh" content="15">
  <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css" />
</head>
<body>
  <div class="container">
    <header>
      <div class="row">
        <div class=".col-md-12">
          <h1>Job Status - Genetic Drift Simulator</h1>
        </div>
      </div>
    </header>
    <div class="row">
      <div class=".col-md-12">
        <p>The current status of the job is shown below. The page would automatically update every 15 seconds. You may add this page to your bookmark and come back to retrive the result of the simulation within the following 24 hours.</p>
      </div>
    </div>
    <div class="row">
      <div class=".col-md-2">
        <strong>Job ID</strong>
      </div>
      <div class=".col-md-10">
        <?php echo $token; ?>
      </div>
    </div>
    <div class="row">
      <div class=".col-md-2">
        <strong>Job Submitted</strong>
      </div>
      <div class=".col-md-10">
        <?php echo date("r", $time); ?>
      </div>
    </div>
    <div class="row">
      <div class=".col-md-2">
        <strong>Job Expires</strong>
      </div>
      <div class=".col-md-10">
        <?php echo date("r", $expireTime); ?>
      </div>
    </div>
    <div class="row">
      <div class=".col-md-2">
        <strong>Job Status</strong>
      </div>
      <div class=".col-md-10">
        <?php if ($statusMessage == Resque_Job_Status::STATUS_WAITING) { ?>
        <p class="bg-info">Job Queued</p>
        <?php } elseif ($statusMessage == Resque_Job_Status::STATUS_RUNNING) { ?>
        <p class="bg-success">Job Running</p>
        <?php } ?>
      </div>
    </div>
  </div>
</body>
</html>
<?php
  }
