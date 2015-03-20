<!DOCTYPE html>
<html lang="en">
<head>
  <title><?=$this->e($title)?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" type="text/css" href="<?=$this->asset('assets/css/bootstrap.min.css')?>" />
  <link rel="stylesheet" type="text/css" href="<?=$this->asset('assets/css/style.min.css')?>" />
</head>
<body>
  <div class="container">
    <?=$this->section('content')?>
    <footer>
      <div class="row">
        <div class=".col-md-12">
          &copy; Andrew Ying 2015.
        </div>
      </div>
    </footer>
  </div>
</body>
</html>
