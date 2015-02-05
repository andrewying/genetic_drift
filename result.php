<?php
  if ($_GET['status'] == 'false') {
    die('Job failed. Please contact the developer of the script at me@andrewying.com.');
  }
  elseif (!$_GET['key']) {
    die('Access denied.');
  }

  $key = urldecode($_GET['key']);
  $token = urldecode($_GET['token']);
  $time = urldecode($_GET['time']);

  $content = file_get_contents('logs/' . urlencode($key) . '.json');
  $array = json_decode($content, true);

  header('Cache-Control: no-cache, must-revalidate');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Result - Genetic Drift Simulator</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css" />
  <script type="text/javascript" src="assets/js/jquery.min.js"></script>
  <script type="text/javascript" src="assets/js/jquery.flot.min.js"></script>
</head>
<body>
  <div class="container">
    <header>
      <div class="row">
        <div class="col-md-12">
          <h1>Result - Genetic Drift Simulator</h1>
        </div>
      </div>
    </header>
    <div class="row">
      <div class="col-md-12">
        <p>The result of the stimulation could be found below.</p>
      </div>
    </div>
    <div class="row">
      <div class="col-md-3">
        <strong>Job ID</strong>
      </div>
      <div class="col-md-9">
        <?php echo $token; ?>
      </div>
    </div>
    <div class="row">
      <div class="col-md-3">
        <strong>Job Submitted</strong>
      </div>
      <div class="col-md-9">
        <?php echo date("r", $time); ?>
      </div>
    </div>
    <div class="row">
      <div class="col-md-3">
        <strong>Job Status</strong>
      </div>
      <div class="col-md-9">
        <p class="bg-success">Job Completed<p>
      </div>
    </div>
    <div class="row">
      <div class="col-md-3">
        <strong>Population Size</strong>
      </div>
      <div class="col-md-9">
        <?php echo $array['population']; ?>
      </div>
    </div>
    <div class="row">
      <div class="col-md-3">
        <strong>Number of Generations</strong>
      </div>
      <div class="col-md-9">
        <?php echo $array['generations']; ?>
      </div>
    </div>
    <div class="row">
      <div class="col-md-3">
        <strong>Reproduction Method</strong>
      </div>
      <div class="col-md-9">
        <?php
          if ($array['reproduction'] == 1) {
        ?>
        Random pairing (conserves resources)
        <?php
          }
          else {
        ?>
        Fully random
        <?php
          }
        ?>
      </div>
    </div>
    <?php
      if ($array['mutation'] == true){
    ?>
    <div class="row">
      <div class="col-md-3">
        <strong>Occurrence of Mutations</strong>
      </div>
      <div class="col-md-9">
        Yes
      </div>
    </div>
    <div class="row">
      <div class="col-md-3">
        <strong>Mutation Rate</strong>
      </div>
      <div class="col-md-9">
        <?php echo $array['mutationRate']; ?>
      </div>
    </div>
    <div class="row">
      <div class="col-md-3">
        <strong>Defintiion of Mutation</strong>
      </div>
      <div class="col-md-9">
        <?php
          if ($array['mutationDef'] == 1) {
        ?>
        Aggressive (Change in allele, including apperance of new allele)
        <?php
          }
          else {
        ?>
        Simplistic (Apperance of new allele which does not previously exist in the population)
        <?php
          }
        ?>
      </div>
    </div>
    <?php
      }
      else {
    ?>
    <div class="row">
      <div class="col-md-3">
        <strong>Occurrence of Mutations</strong>
      </div>
      <div class="col-md-9">
        No
      </div>
    </div>
    <?php
        }
    ?>
    <div class="row">
      <div class="form-group">
        <div class="col-md-offset-3 col-md-9">
          <a class="btn btn-default" href="index.php">Restart</a>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <h3>Graphs</h3>
      </div>
    </div>
    <div class="row">
      <div class="col-md-offset-3 col-md-3">
        <a class="btn btn-default" onclick="window.open('graph.php?key=<?php echo urlencode($key); ?>&token=<?php echo urlencode($token); ?>&time=<?php echo urlencode($time); ?>&graph=allele')">Allele Frequency Graph</a>
      </div>
      <div class="col-md-3">
        <a class="btn btn-default" onclick="window.open('graph.php?key=<?php echo urlencode($key); ?>&token=<?php echo urlencode($token); ?>&time=<?php echo urlencode($time); ?>&graph=hetero')">Heterozygosity Frequency Graph</a>
      </div>
    </div>
  </div>
</body>
</html>
