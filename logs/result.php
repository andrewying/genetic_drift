<?php
  if ($_GET['status'] == false) {
    die("Job failed. Please contact the developer of the script at me@andrewying.com.");
  }
  elseif (!$_GET['key']) {
    die("Access denied.");
  }

  $key = urldecode($_GET['key']);

  $content = file_get_contents('logs/' . $key . '.json');
  $array = json_decode($content);

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Result - Genetic Drift Simulator</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="refresh" content="15">
  <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css" />
  <script type="text/javascript" src="assets/jquery.min.js"></script>
  <script type="text/javascript" src="assets/jquery.flot.min.js"></script>
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
        <strong>Job Status</strong>
      </div>
      <div class=".col-md-10">
        Job Completed
      </div>
    </div>
    <div class="row">
      <div class=".col-md-2">
        <strong>Population Size</strong>
      </div>
      <div class=".col-md-10">
        <?php echo $array['population']; ?>
      </div>
    </div>
    <div class="row">
      <div class=".col-md-2">
        <strong>Number of Generations</strong>
      </div>
      <div class=".col-md-10">
        <?php echo $array['generations']; ?>
      </div>
    </div>
    <?php
      if ($array['mutation'] == true){
    ?>
    <div class="row">
      <div class=".col-md-2">
        <strong>Occurrence of Mutations</strong>
      </div>
      <div class=".col-md-10">
        Yes
      </div>
    </div>
    <div class="row">
      <div class=".col-md-2">
        <strong>Mutation Rate</strong>
      </div>
      <div class=".col-md-10">
        <?php echo $array['mutationRate']; ?>
      </div>
    </div>
    <div class="row">
      <div class=".col-md-2">
        <strong>Defintiion of Mutation</strong>
      </div>
      <div class=".col-md-10">
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
      <div class=".col-md-2">
        <strong>Occurrence of Mutations</strong>
      </div>
      <div class=".col-md-10">
        No
      </div>
    </div>
    <?php
        }
    ?>
    <div class="row">
      <div class=".col-md-12">
        <h3>Graphs</h3>
      </div>
    </div>
    <div class="row">
      <div class=".col-md-12">
        <div id="chart1" style="width: 900px; height: 500px;"></div>
        <div id="legendContainer1"></div>
        <div id="tooltip1" style="position: absolute; display: none; border: 1px solid #fdd; padding: 2px; background-color: #fee; opacity: 0.80"></div>
      </div>
    </div>
    <div class="row">
      <div class=".col-md-12">
        <div id="chart2" style="width: 900px; height: 500px;"></div>
        <div id="legendContainer2"></div>
        <div id="tooltip2" style="position: absolute; display: none; border: 1px solid #fdd; padding: 2px; background-color: #fee; opacity: 0.80"></div>
      </div>
    </div>
  </div>
  <script>
  var data = new Array(<?php echo count($array['alleleFeq']); ?>);
  <?php
  for ($i = 0; $i < count($array['alleleFeq']); $i++) {
  ?>
  data[<?php echo $i; ?>] = {
    data: <?php echo json_encode($array['alleleFeq'][$i]); ?>,
    label: "Allele <?php echo $i + 1; ?>"
  };
  <?php
  }
  ?>

  var options = {
    series: {
      lines: {
        show: true
      },
      points: {
        show: false
      }
    },
    grid: {
      hoverable: true
    },
    xaxis: {
      min: 0,
      max: <?php echo $array['generations']; ?>
    },
    yaxis: {
      min: 0,
      max: <?php echo $array['population'] * 2; ?>
    },
    legend:{
      container:$("#legendContainer1"),
      noColumns: 0
    },
  };
  var plot = $.plot("#chart", data, options);

  $("#chart1").bind("plothover", function (event, pos, item) {
    if (item) {
      var x = item.datapoint[0].toFixed(0),
          y = item.datapoint[1].toFixed(0);

      $("#tooltip").html(item.series.label + " at Generation " + x + " = " + y)
      .css({top: item.pageY + 5, left: item.pageX + 5})
      .fadeIn(200);
    }
    else {
      $("#tooltip1").hide();
    }
  });
  </script>
  <script>
  var data = new Array(1);
  data[0] = {
    data: <?php echo json_encode($array['hetero']); ?>,
    label: "Number of heterozygous individuals"
  };


  var options = {
    series: {
      lines: {
        show: true
      },
      points: {
        show: false
      }
    },
    grid: {
      hoverable: true
    },
    xaxis: {
      min: 0,
      max: <?php echo $array['generations']; ?>
    },
    yaxis: {
      min: 0,
      max: <?php echo $array['population']; ?>
    },
    legend:{
      container:$("#legendContainer1"),
      noColumns: 0
    },
  };
  var plot = $.plot("#chart", data, options);

  $("#chart1").bind("plothover", function (event, pos, item) {
    if (item) {
      var x = item.datapoint[0].toFixed(0),
          y = item.datapoint[1].toFixed(0);

      $("#tooltip").html(item.series.label + " at Generation " + x + " = " + y)
      .css({top: item.pageY + 5, left: item.pageX + 5})
      .fadeIn(200);
    }
    else {
      $("#tooltip1").hide();
    }
  });
  </script>
</body>
</html>
