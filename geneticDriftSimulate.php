<?php
  class GeneticDriftSimulate
  {
    public function perform () {
      $key = $this->args['key'];
      $population = $this->args['population'];
      $generations = $this->args['generations'];
      $reproduction = $this->args['reproduction'];
      $mutation = $this->args['mutation'];
      $mutationRate = $this->args['mutationRate'];
      $mutationDef = $this->args['mutationDef'];

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

        if ($mutation) {
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
                  $alleleRand = mt_rand(0, $alleles - 1);
                } while ($alleleFeq[$i][$individualTmp[$individualRand][$alleleRand]] == 0);
                $alleleFeq[$i][$individualTmp[$individualRand][$alleleRand]]--;

                $alleleFeq[$i][$alleles] = 1;
                $individualTmp[$individualRand][$alleleRand] = $alleles;

                $alleles++;
              }
              else {
                $individualRand = mt_rand(0, $population - 1);
                do {
                  $alleleRand = mt_rand(0, $alleles - 1);
                } while ($alleleFeq[$i][$individualTmp[$individualRand][$alleleRand]] == 0);
                $alleleFeq[$i][$individualTmp[$individualRand][$alleleRand]]--;

                $alleleNew = mt_rand(0, count($arrayTmp) - 1);

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
            $plotAllele[$arrKey] = array();
          }
          array_push($plotAllele[$arrKey], array($i, $value));
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
        'alleleFeq' => $plotAllele,
        'hetero' => $plotHetero
      );

      $output = json_encode($array);

      $status = file_put_contents("logs/" . urlencode($key) . ".json", $output);

      if ($status == false) {
        throw new Exception("Job failed.");
      }
    }
  }
