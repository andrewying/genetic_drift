<?php
  header('Cache-Control: no-cache, must-revalidate');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Genetic Drift Simulator</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css" />
</head>
<body>
  <div class="container">
    <header>
      <div class="row">
        <div class=".col-md-12">
          <h1>Genetic Drift Simulator</h1>
        </div>
      </div>
    </header>
      <div class="row">
        <div class=".col-md-12">
          <p>
            A PHP script simulating <a href="http://en.wikipedia.org/wiki/Genetic_drift">genetic drift</a>. Coded by Andrew Ying using <a href="https://github.com/chrisboulton/php-resque">PHP Resque Worker</a> for queueing of jobs and <a href="http://getbootstrap.com/">Bootstrap</a> for CSS styling.
          </p>
        </div>
      </div>
      <div id="popZero" class="row" hidden>
        <div class=".col-md-12">
          <p class="bg-danger">Population size cannot be 0.</p>
        </div>
      </div>
      <div id="genZero" class="row" hidden>
        <div class=".col-md-12">
          <p class="bg-danger">Number of generations cannot be 0.</p>
        </div>
      </div>
      <div id="mutRateZero" class="row" hidden>
        <div class=".col-md-12">
          <p class="bg-danger">Mutation rate cannot be 1 mutation/0 generations.</p>
        </div>
      </div>
      <div id="mutRateNull" class="row" hidden>
        <div class=".col-md-12">
          <p class="bg-danger">Mutation rate required.</p>
        </div>
      </div>

      <form class="form-horizontal" name="queue" onsubmit="return validateForm(this);" method="get" action="submit.php">
        <div class="form-group">
          <label for="inputPopulation" class="col-md-2 control-label">Population size</label>
          <div class="col-md-10">
            <input type="number" class="form-control" id="inputPopulation" name="inputPopulation" placeholder="100" onkeyup="onInputPopulation()" required>
          </div>
        </div>
        <div class="form-group">
          <label for="inputFrequency" class="col-md-2 control-label">Initial allele frequency</label>
          <div class="col-md-10">
            <p class="form-control-static">
              <span id="inputFrequency">100</span></p>
          </div>
        </div>
        <div class="form-group">
          <label for="inputGenerations" class="col-md-2 control-label">Number of generations</label>
          <div class="col-md-10">
            <input type="number" class="form-control" id="inputGenerations" name="inputGenerations" placeholder="100" required>
          </div>
        </div>
        <div class="form-group">
          <label for="inputReproduction" class="col-md-2 control-label">Reproduction method</label>
          <div class="col-md-10">
            <select id="inputReproduction" name="inputReproduction" class="form-control">
              <option value="1">Random pairing (conserves resources)</option>
              <option value="2">Fully random</option>
            </select>
          </div>
        </div>
        <div class="form-group">
          <label for="inputMutation" class="col-md-2 control-label">Occurrence of mutations</label>
          <div class="col-md-10">
            <select id="inputMutation" name="inputMutation" class="form-control" onchange="onChangeMutation()">
              <option value="1">Yes</option>
              <option value="0">No</option>
            </select>
          </div>
        </div>
        <div id="mutationRate" class="form-group">
          <label for="inputMutationRate" class="col-md-2 control-label">Mutation rate</label>
          <div class="col-md-10">
            <div class="input-group">
              <div class="input-group-addon">1 mutation/</div>
              <input type="number" class="form-control" id="inputMutationRate" name="inputMutationRate" placeholder="100">
              <div class="input-group-addon"> generation(s)</div>
            </div>
          </div>
        </div>
        <div id="mutationDef" class="form-group">
          <label for="inputMutationDef" class="col-md-2 control-label">Mutation definition</label>
          <div class="col-md-10">
            <select id="inputMutationDef" name="inputMutationDef" class="form-control">
              <option value="1">Aggressive (Change in allele, including apperance of new allele)</option>
              <option value="2">Simplistic (Apperance of new allele which does not previously exist in the population)</option>
            </select>
          </div>
        </div>
      <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
          <button type="submit" name="submit" value="submit" class="btn btn-default">Submit</button>
        </div>
      </div>
  </div>
  <script type="text/javascript">
    function onInputPopulation() {
      var population = document.getElementById("inputPopulation").value;
      document.getElementById("inputFrequency").innerHTML = population;
    }

    function onChangeMutation() {
      var mutation = document.getElementById("inputMutation").value;

      if (mutation == 0) {
        document.getElementById("mutationRate").style.display = 'none';
        document.getElementById("mutationDef").style.display = 'none';
      }
      else {
        document.getElementById("mutationRate").style.display = 'block';
        document.getElementById("mutationDef").style.display = 'block';
      }
    }

    function validateForm(form) {
      if (form.inputPopulation.value == 0) {
        document.getElementById("popZero").style.display = 'block';
        form.inputPopulation.focus();
        return false;
      }
      else if (form.inputGenerations.value == 0) {
        document.getElementById("popZero").style.display = 'none';
        document.getElementById("genZero").style.display = 'block';
        form.inputGenerations.focus();
        return false;
      }
      else if (form.inputMutation.value == 1 && form.inputMutationRate.value == "") {
        document.getElementById("genZero").style.display = 'none';
        document.getElementById("mutRateNull").style.display = 'block';
        form.inputMutationRate.focus();
        return false;
      }
      else if (form.inputMutation.value == 1 && form.inputMutationRate.value == 0)
      {
        document.getElementById("mutRateNull").style.display = 'none';
        document.getElementById("mutRateZero").style.display = 'block';
        form.inputMutationRate.focus();
        return false;
      }
      else {
        return true;
      }
    }
  </script>
</body>
</html>
