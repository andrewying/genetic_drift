<?php $this->layout('template', ['title' => 'Application Error - Genetic Drift Simulator']) ?>

<div class="panel panel-danger">
  <div class="panel-heading">
    <h3 class="panel-title">Application Error</h3>
  </div>
  <div class="panel-body">
    <p>An error has occured when processing your request. Please try again later. If the error persists, contact the administrator of the application <a href="mailto:<?=$this->e($adminEmail)?>">here</a>.</p>
    <?php if ($this->e($showError) == true): ?>
      <pre class=".pre-scrollable"><p><strong>The following error message is for the administrator's use only.</strong></p><p>Message: <?=$this->e($message)?><br />Trace:<br /><?=$this->e($trace)?></p></pre>
    <?php endif ?>
  </div>
</div>
