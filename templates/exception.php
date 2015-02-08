<?php $this->layout('template', ['title' => 'Application Error - Genetic Drift Simulator']) ?>

<div class="row bg-danger">
  <div class=".col-md-12">
    <h1>Application Error</h1>
  </div>
</div>
<div class="row bg-danger">
  <div class=".col-md-12">
    <p>An error has occured when processing your request. Please try again later. If the error persists, contact the administrator of the application <a href="mailto:<?=$this->e($adminEmail)?>">here</a>.</p>
    <?php if ($this->e($showError) == true): ?>
      <pre class=".pre-scrollable">
        <p><strong>The following error message is for the administrator's use only.</strong></p>
        <p>Message: <?=$this->e($message)?><br />Trace: <?=$this->e($trace)?></p></pre>
    <?php endif ?>
  </div>
</div>
