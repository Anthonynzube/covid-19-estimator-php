<?php

$data = '{
    "region": {
        "name": "Africa",
        "avgAge": 19.7,
        "avgDailyIncomeInUSD": 5,
        "avgDailyIncomePopulation": 0.71
    },
    "periodType": "days",
    "timeToElapse": 58,
    "reportedCases": 674,
    "population": 66622705,
    "totalHospitalBeds": 1380614
}';

function covid19ImpactEstimator($data)
{
  if (!empty($data))
  {
    
    $timeToElapse = $data['timeToElapse'];
    $periodType = $data['periodType'];
    $reportedCases = $data['reportedCases'];
    $population =  $data['population'];
    $totalHospitalBeds =  $data['totalHospitalBeds'];
    $avgDailyIncomeInUSD = $data['region']['avgDailyIncomeInUSD'];
    $avgDailyIncomePopulation = $data['region']['avgDailyIncomePopulation'];

    $impactCurrentlyInfected = $data['reportedCases'] * 10;
    $severeImpactCurrentlyInfected = $data['reportedCases'] * 50;

    if ($periodType === 'days')
    {
      $timeToElapse = $timeToElapse;
      $factor = 2**(int)($timeToElapse/3);
      $impactInfectionsByRequestedTime = $impactCurrentlyInfected * $factor;
      $severeImpactInfectionsByRequestedTime = $severeImpactCurrentlyInfected * $factor;
    }elseif ($timeToElapse === 'weeks')
    {
      $timeToElapse = $timeToElapse * 7;
      $factor = 2**(int)(timeToElapse/3);
      $impactInfectionsByRequestedTime = $impactCurrentlyInfected * $factor;
      $severeImpactInfectionsByRequestedTime = $severeImpactCurrentlyInfected * $factor;
    }elseif ($timeToElapse === 'months')
    {
      $timeToElapse = $timeToElapse * 30;
      $factor = 2**(int)(timeToElapse/3);
      $impactInfectionsByRequestedTime = $impactCurrentlyInfected * $factor;
      $severeImpactInfectionsByRequestedTime = $severeImpactCurrentlyInfected * $factor;
    }else
    {
      return "period Type must be days, weeks or months";
    }

    $impactSevereCasesByRequestedTime = (int)(0.15 * $impactInfectionsByRequestedTime);
    $severeImpactSevereCasesByRequestedTime = (int)(0.15 * $severeImpactInfectionsByRequestedTime);

    $impactHospitalBedsByRequestedTime = (int)((0.35 * $totalHospitalBeds) - $impactSevereCasesByRequestedTime);
    $severeImpactHospitalBedsByRequestedTime = (int)((0.35 * $totalHospitalBeds) - $severeImpactSevereCasesByRequestedTime);

    $impactCasesForICUByRequestedTime = (int)(0.05 * $impactInfectionsByRequestedTime);
    $severeImpactCasesForICUByRequestedTime = (int)(0.05 * $severeImpactInfectionsByRequestedTime);

    $impactCasesForVentilatorsByRequestedTime = (int)(0.02 * $impactInfectionsByRequestedTime);
    $severeImpactCasesForVentilatorsByRequestedTime = (int)(0.02 * $severeImpactInfectionsByRequestedTime);

    $impactDollarsInFlight = (int)(($impactInfectionsByRequestedTime * avgDailyIncomePopulation * $avgDailyIncomeInUSD) / 30);
    $severeImpactDollarsInFlight = (int)(($severeImpactInfectionsByRequestedTime * avgDailyIncomePopulation * $avgDailyIncomeInUSD) / 30);

    $data = array(
      "data" => $data,
      "impact" => array(
        'currentlyInfected' => $impactCurrentlyInfected,
        'infectionsByRequestedTime' => $impactInfectionsByRequestedTime,
        'severeCasesByRequestedTime' => $impactSevereCasesByRequestedTime,
        'hospitalBedsByRequestedTime' => $impactHospitalBedsByRequestedTime,
        'casesForICUByRequestedTime' => $impactCasesForICUByRequestedTime,
        'casesForVentilatorsByRequestedTime' => $impactCasesForVentilatorsByRequestedTime,
        'dollarsInFlight' => $impactDollarsInFlight
      ),
      "severeImpact" => array(
        'currentlyInfected' => $severeImpactCurrentlyInfected,
        'infectionsByRequestedTime' => $severeImpactInfectionsByRequestedTime,
        'severeCasesByRequestedTime' => $severeImpactSevereCasesByRequestedTime,
        'hospitalBedsByRequestedTime' => $severeImpactHospitalBedsByRequestedTime,
        'casesForICUByRequestedTime' => $severeImpactCasesForICUByRequestedTime,
        'casesForVentilatorsByRequestedTime' => $severeImpactCasesForVentilatorsByRequestedTime,
        'dollarsInFlight' => $severeImpactDollarsInFlight
      )
    );
    
    return $data;
  } else {
    return "Data should not be empty";
  }


}

covid19ImpactEstimator($data);