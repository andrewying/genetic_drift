<?php
  class GeneticDriftSimulate
  {
    public function setUp() {
      register_shutdown_function(array($this, 'shutdown'));
      set_time_limit(300);
    }

    public function perform() {
      require dirname(__DIR__) . '/vendor/autoload.php';

      $configs = new Geneticdrift\Config;

      $key = $this->args['key'];
      $population = $this->args['population'];
      $generations = $this->args['generations'];
      $reproduction = $this->args['reproduction'];
      $mutation = $this->args['mutation'];
      $mutationRate = $this->args['mutationRate'];
      $mutationDef = $this->args['mutationDef'];

      if ($population < 1) {
        throw new Exception('Unexpected value for $this->args[\'population\']');
      }
      elseif ($generations < 1) {
        throw new Exception('Unexpected value for $this->args[\'generations\']');
      }
      elseif ($reproduction != 1 && $reproduction !=2) {
        throw new Exception('Unexpected value for $this->args[\'reproduction\']');
      }
      elseif ($mutation != true && $mutation != false) {
        throw new Exception('Unexpected value for $this->args[\'mutation\']');
      }
      elseif ($mutation == true && $mutationRate < 1) {
        throw new Exception('Unexpected value for $this->args[\'mutationRate\']');
      }
      elseif ($mutation == true && $mutationDef != 1 && $mutationDef != 2) {
        throw new Exception('Unexpected value for $this->args[\'mutationDef\']');
      }

      $individual = array();
      $individualTmp = array();
      $alleles = 2;
      $alleleFeq = array();

      $alleleFeq[0][0] = $population;
      $alleleFeq[0][1] = $population;

      $hetero = array();
      $hetero[0] = $population;

      for ($i = 0; $i < $population; $i++) {
        $individual[$i] = array(0, 1);
      }

      for ($i = 1; $i <= $generations; $i++) {
        shuffle($individual);

        for ($h = 0; $h < $alleles; $h++) {
          $alleleFeq[$i][$h] = 0;
        }

        if ($reproduction == 1) {
          for ($j = 0; $j < $population; $j = $j + 2) {
            $k = $j + 1;

            $random = array(mt_rand(0, 1), mt_rand(0, 1), mt_rand(0, 1), mt_rand(0, 1));

            $alleleFeq[$i][$individual[$j][$random[0]]]++;
            $alleleFeq[$i][$individual[$k][$random[1]]]++;
            $alleleFeq[$i][$individual[$j][$random[2]]]++;
            $alleleFeq[$i][$individual[$k][$random[3]]]++;

            $individualTmp[$j] = array($individual[$j][$random[0]], $individual[$k][$random[1]]);
            $individualTmp[$k] = array($individual[$j][$random[2]], $individual[$k][$random[3]]);
          }
        }
        else {
          for ($j = 0; $j < $population; $j++) {
            shuffle($individual);
            $random = array(mt_rand(0, 1), mt_rand(0, 1), mt_rand(0, 1), mt_rand(0, 1));

            $alleleFeq[$i][$individual[0][$random[0]]]++;
            $alleleFeq[$i][$individual[1][$random[1]]]++;

            $individualTmp[$j] = array($individual[0][$random[0]], $individual[1][$random[1]]);
          }
        }

        if ($mutation == true) {
          $mutationStatus = mt_rand(1, $mutationRate);

          if ($mutationStatus == 1) {
            if ($mutationDef == 1) {
              $individualRand = mt_rand(0, $population - 1);
              do {
                $alleleRand = mt_rand(0, 1);
              } while ($alleleFeq[$i][$individualTmp[$individualRand][$alleleRand]] == 0);

              $alleleFeq[$i][$individualTmp[$individualRand][$alleleRand]]--;

              do {
                $alleleNew = mt_rand(0, $alleles);
              } while ($individualTmp[$individualRand][$alleleRand] == $alleleNew);

              $individualTmp[$individualRand][$alleleRand] = $alleleNew;

              if ($alleleNew == $alleles) {
                $alleles++;
                $alleleFeq[$i - 1][$alleleNew] = 0;
                $alleleFeq[$i][$alleleNew] = 1;
              }
              elseif ($alleleFeq[$i][$alleleNew] == null) {
                $alleleFeq[$i][$alleleNew] = 1;
              }
              else {
                $alleleFeq[$i][$alleleNew]++;
              }
            }
            else {
              $arrayTmp = array();

              for ($x = 0; $x < $alleles; $x++) {
                if ($alleleFeq[$i][$x] == 0){
                  array_push($arrayTmp, $x);
                }
              }

              if (count($arrayTmp) == 0){
                $individualRand = mt_rand(0, $population - 1);

                do {
                  $alleleRand = mt_rand(0, 1);
                } while ($alleleFeq[$i][$individualTmp[$individualRand][$alleleRand]] == 0);

                $alleleFeq[$i][$individualTmp[$individualRand][$alleleRand]]--;

                if (!issset($alleleFeq[$i - 1][$arrayTmp[$alleleNew]])) {
                  $alleleFeq[$i - 1][$arrayTmp[$alleleNew]] = 0;
                }
                $alleleFeq[$i][$alleles] = 1;
                $individualTmp[$individualRand][$alleleRand] = $alleles;

                $alleles++;
              }
              else {
                $individualRand = mt_rand(0, $population - 1);

                do {
                  $alleleRand = mt_rand(0, 1);
                } while ($alleleFeq[$i][$individualTmp[$individualRand][$alleleRand]] == 0);

                $alleleFeq[$i][$individualTmp[$individualRand][$alleleRand]]--;

                $alleleNew = mt_rand(0, count($arrayTmp) - 1);

                if (!issset($alleleFeq[$i - 1][$arrayTmp[$alleleNew]])) {
                  $alleleFeq[$i - 1][$arrayTmp[$alleleNew]] = 0;
                }
                $alleleFeq[$i][$arrayTmp[$alleleNew]] = 1;
                $individualTmp[$individualRand][$alleleRand] = $arrayTmp[$alleleNew];
              }
            }
          }
        }

        $individual = $individualTmp;

        $hetero[$i] = 0;
        for ($j = 0; $j < $population; $j++) {
          if ($individual[$j][0] != $individual[$j][1]) {
            $hetero[$i]++;
          }
        }
      }

      $plotAllele = array();
      for ($i = 0; $i <= $generations; $i++) {
        foreach ($alleleFeq[$i] as $arrKey => $value) {
          if (!array_key_exists($arrKey, $plotAllele)) {
            $plotAllele[$arrKey] = array(
              'id' => $arrKey,
              'data' => array(),
              'label' => $arrKey + 1
            );
          }
          array_push($plotAllele[$arrKey]['data'], array($i, $value));
        }
      }

      $plotHetero = array();
      foreach ($hetero as $arrKey => $value) {
        array_push($plotHetero, array($arrKey, $value));
      }

      $array = array(
        'population' => $population,
        'generations' => $generations,
        'reproduction' => $reproduction,
        'mutation' => $mutation,
        'mutationRate' => $mutationRate,
        'mutationDef' => $mutationDef,
        'numAllele' => $alleles,
        'alleleFeq' => $plotAllele,
        'hetero' => $plotHetero
      );

      $output = json_encode($array);

      $status = file_put_contents(dirname(__DIR__) . '/logs/' . urlencode($key) . '.json', $output);

      if ($status == false) {
        throw new Exception('Failed to write to ' . $configs->config['web_root'] .  '/logs/' . urlencode($key) . '.json');
      }

      $logFile = file_exists($configs->config['web_root'] . '/logs/overall_log.json');
      $arrayLog = array(
        'key' => $key,
        'remove_time' => time() + 86400
      );

      if ($logFile == false) {
        $logContent = json_encode(array($arrayLog));
      }
      else {
        $content = file_get_contents($configs->config['web_root'] .  '/logs/overall_log.json');
        $fromLogArray = json_decode($content, true);

        array_push($fromLogArray, $arrayLog);

        $logContent = json_encode($fromLogArray);
      }

      $logStatus = file_put_contents($configs->config['web_root'] . '/logs/overall_log.json', $logContent);

      if ($logStatus === false) {
        throw new Exception('Failed to write to ' . $configs->config['web_root'] . '/logs/overall_log.json');
      }
    }

    public function shutdown() {
      $error = error_get_last();

      if ($error != null && $error['type'] == 1) {
        throw new Exception($error['message']);
      }
    }
  }
