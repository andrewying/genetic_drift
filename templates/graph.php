<?php $this->layout('template', ['title' => 'Graph - Genetic Drift Simulator']) ?>

<?php $this->start('header') ?>
  <script type="text/javascript" src="assets/js/jquery.min.js"></script>
  <script type="text/javascript" src="assets/js/jquery.flot.min.js"></script>
<?php $this->end() ?>

<?php $this->start('content') ?>
  <div id="chart" style="width: 900px; height: 500px;"></div>
  <div id="legendContainer"></div>
  <div id="tooltip" style="position: absolute; display: none; border: 1px solid #fdd; padding: 2px; background-color: #fee; opacity: 0.80"></div>
  <script>
  <?php if ($this->e($graph) == 'allele'): ?>
    var data = new Array(<?=$this->e($numAllele)?>);
    <?php foreach ($this->e($dataArray) as $data): ?>
      data[<?=$data->id?>] = {
        data: <?=$data->data?>,
        label: "Allele <?=$data->label?>"
      };
      <?php endforeach ?>

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
          max: <?=$this->e($graphXAxis)?>
        },
        yaxis: {
          min: 0,
          max: <?=$this->e($graphYAxis)?>
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
  <?php else if ($this->e($graph) == "hetero"): ?>
    var data = new Array(1);
    data[0] = {
      data: <?=$this->e($data)?>,
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
        max: <?=$this->e($graphXAxis)?>
      },
      yaxis: {
        min: 0,
        max: <?=$this->e($graphYAxis)?>
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
  <?php endif ?>
  </script>
<?php $this->end() ?>
