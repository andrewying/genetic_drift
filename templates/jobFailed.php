<?php $this->layout('template', ['title' => 'Job Failed - Genetic Drift Simulator']) ?>

<div class="panel panel-danger">
  <div class="panel-heading">
    <h3 class="panel-title">Job Failed</h3>
  </div>
  <div class="panel-body">
    <p>The job you queued has failed. This is likely to be due to one of the following reasons:</p>
    <ol>
      <li><strong>The task has taken more than 5 minutes to process.</strong> To conserve resources, a job stops automatically after 5 minutes. Please try reducing the size of the population and the number of generations set. If you have legitimate research use which requires a longer processing time, please contact the administrator <a href="mailto:<?=$this->e($adminEmail)?>">here</a> and we could discuss this further.</li>
      <li><strong>An unexcepted error has occured during the job.</strong> Contact the administrator of the application <a href="mailto:<?=$this->e($adminEmail)?>">here</a> and we would look into this further.</li>
    </ol>
  </div>
</div>
