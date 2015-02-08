<?php $this->layout('template', ['title' => 'Job Status - Genetic Drift Simulator']) ?>

  <div class="container">
    <header>
      <div class="row">
        <div class="col-md-12">
          <h1>Job Status - Genetic Drift Simulator</h1>
        </div>
      </div>
    </header>
    <div class="row">
      <div class="col-md-12">
        <p>The current status of the job is shown below. The page would update automatically. You may add this page to your bookmark and come back to retrive the result of the simulation within the following 24 hours.</p>
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
        <strong>Job Expires</strong>
      </div>
      <div class="col-md-9">
        <?=$this->e($jobExpires)?>
      </div>
    </div>
    <div class="row">
      <div class="col-md-3">
        <strong>Job Status</strong>
      </div>
      <div class="col-md-9">
        <?=$this->e($jobStatus)?>
      </div>
    </div>
  </div>
