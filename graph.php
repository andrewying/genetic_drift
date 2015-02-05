<?php
  if (!$_GET['key']) {
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
  <title>Graph - Genetic Drift Simulator</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <script type="text/javascript" src="assets/js/jquery.min.js"></script>
  <script type="text/javascript" src="assets/js/jquery.flot.min.js"></script>
</head>
<body>
  <div id="chart" style="width: 900px; height: 500px;"></div>
  <div id="legendContainer"></div>
  <div id="tooltip" style="position: absolute; display: none; border: 1px solid #fdd; padding: 2px; background-color: #fee; opacity: 0.80"></div>
  <script>
  <?php
  if ($_GET['graph'] == 'allele') {
  ?>
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
      container:$("#legendContainer"),
      noColumns: 0
    },
  };
  var plot = $.plot("#chart", data, options);

  $("#chart").bind("plothover", function (event, pos, item) {
    if (item) {
      var x = item.datapoint[0].toFixed(0),
          y = item.datapoint[1].toFixed(0);

      $("#tooltip").html(item.series.label + " at Generation " + x + " = " + y)
      .css({top: item.pageY + 5, left: item.pageX + 5})
      .fadeIn(200);
    }
    else {
      $("#tooltip").hide();
    }
  });
  <?php
  }
  elseif ($_GET['graph'] == "hetero") {
    ?>
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
      container:$("#legendContainer"),
      noColumns: 0
    },
  };
  var plot = $.plot("#chart", data, options);

  $("#chart").bind("plothover", function (event, pos, item) {
    if (item) {
      var x = item.datapoint[0].toFixed(0),
          y = item.datapoint[1].toFixed(0);

      $("#tooltip").html(item.series.label + " at Generation " + x + " = " + y)
      .css({top: item.pageY + 5, left: item.pageX + 5})
      .fadeIn(200);
    }
    else {
      $("#tooltip").hide();
    }
  });
  <?php
  }
  else {
    die('Access denied.');
  }
  ?>
  </script>
</body>
</html>
