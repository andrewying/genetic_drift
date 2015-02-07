<?php $this->layout('template', ['title' => 'Result - Genetic Drift Simulator']) ?>

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
    <?=$this->e($jobID)?>
  </div>
</div>
<div class="row">
  <div class="col-md-3">
    <strong>Job Submitted</strong>
  </div>
  <div class="col-md-9">
    <?=$this->e($jobSubmitted)?>
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
    <?=$this->e($jobPopulation)?>
  </div>
</div>
<div class="row">
  <div class="col-md-3">
    <strong>Number of Generations</strong>
  </div>
  <div class="col-md-9">
    <?=$this->e($jobGenerations)?>
  </div>
</div>
<div class="row">
  <div class="col-md-3">
    <strong>Reproduction Method</strong>
  </div>
  <div class="col-md-9">
    <?=$this->e($jobReproduction)?>
  </div>
</div>
<?php if ($this->e($jobMutation) == true): ?>
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
      <?=$this->e($mutationRate)?>
    </div>
  </div>
  <div class="row">
    <div class="col-md-3">
      <strong>Defintiion of Mutation</strong>
    </div>
    <div class="col-md-9">
      <?=$this->e($jobMutationDef)?>
    </div>
  </div>
<?php else: ?>
  <div class="row">
    <div class="col-md-3">
      <strong>Occurrence of Mutations</strong>
    </div>
    <div class="col-md-9">
      No
    </div>
  </div>
<?php endif ?>
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
    <a class="btn btn-default" onclick="window.open('graph.php?key=<?=$this->e($jobKey)?>&token=<?=$this->e($jobID)?>&time=<?=$this->e($jobSubmitted)?>&graph=allele')">Allele Frequency Graph</a>
  </div>
  <div class="col-md-3">
    <a class="btn btn-default" onclick="window.open('graph.php?key=<?=$this->e($jobKey)?>&token=<?=$this->e($jobID)?>&time=<?=$this->e($jobSubmitted)?>&graph=hetero')">Heterozygosity Frequency Graph</a>
  </div>
</div>
