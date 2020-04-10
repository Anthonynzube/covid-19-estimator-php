<?php

// $data = '{
//     "region": {
//         "name": "Africa",
//         "avgAge": 19.7,
//         "avgDailyIncomeInUSD": 5,
//         "avgDailyIncomePopulation": 0.71
//     },
//     "periodType": "days",
//     "timeToElapse": 58,
//     "reportedCases": 674,
//     "population": 66622705,
//     "totalHospitalBeds": 1380614
// }';

function covid19ImpactEstimator($data)
{
  $decodedData = json_decode($data);

  $timeToElapse = $decodedData->timeToElapse;
  $periodType = $decodedData->periodType;
  $reportedCases = $decodedData->reportedCases;
  $population =  $decodedData->population;
  $totalHospitalBeds =  $decodedData->totalHospitalBeds;
  $avgDailyIncomeInUSD = $decodedData->region->avgDailyIncomeInUSD;

  $impactCurrentlyInfected = $decodedData->reportedCases * 10;
  $severeImpactCurrentlyInfected = $decodedData->reportedCases * 50;

  if ($periodType === 'days')
  {
    $timeToElapse = $timeToElapse;
    $factor = 2**floor($timeToElapse/3);
    $impactInfectionsByRequestedTime = $impactCurrentlyInfected * $factor;
    $severeImpactInfectionsByRequestedTime = $severeImpactCurrentlyInfected * $factor;
  }elseif ($timeToElapse === 'weeks')
  {
    $timeToElapse = $timeToElapse * 7;
    $factor = 2**floor(timeToElapse/3);
    $impactInfectionsByRequestedTime = $impactCurrentlyInfected * $factor;
    $severeImpactInfectionsByRequestedTime = $severeImpactCurrentlyInfected * $factor;
  }elseif ($timeToElapse === 'months')
  {
    $timeToElapse = $timeToElapse * 30;
    $factor = 2**floor(timeToElapse/3);
    $impactInfectionsByRequestedTime = $impactCurrentlyInfected * $factor;
    $severeImpactInfectionsByRequestedTime = $severeImpactCurrentlyInfected * $factor;
  }else
  {
    "period Type must be days, weeks or months";
  }

  $impactSevereCasesByRequestedTime = floor(0.15 * $impactInfectionsByRequestedTime);
  $severeImpactSevereCasesByRequestedTime = floor(0.15 * $severeImpactInfectionsByRequestedTime);

  $impactHospitalBedsByRequestedTime = floor((0.35 * $totalHospitalBeds) - $impactSevereCasesByRequestedTime);
  $severeImpactHospitalBedsByRequestedTime = floor((0.35 * $totalHospitalBeds) - $severeImpactSevereCasesByRequestedTime);

  $impactCasesForICUByRequestedTime = floor(0.05 * $impactInfectionsByRequestedTime);
  $severeImpactCasesForICUByRequestedTime = floor(0.05 * $severeImpactInfectionsByRequestedTime);

  $impactCasesForVentilatorsByRequestedTime = floor(0.02 * $impactInfectionsByRequestedTime);
  $severeImpactCasesForVentilatorsByRequestedTime = floor(0.02 * $severeImpactInfectionsByRequestedTime);

  $impactDollarsInFlight = floor(($impactInfectionsByRequestedTime * 0.65 * $avgDailyIncomeInUSD * 30));
  $severeImpactDollarsInFlight = floor(($severeImpactInfectionsByRequestedTime * 0.65 * $avgDailyIncomeInUSD * 30));

  $data = array(
    "data" => $decodedData,
    "impact" => array(
      'CurrentlyInfected' => $impactCurrentlyInfected,
      'InfectionsByRequestedTime' => $impactInfectionsByRequestedTime,
      'SevereCasesByRequestedTime' => $impactSevereCasesByRequestedTime,
      'HospitalBedsByRequestedTime' => $impactHospitalBedsByRequestedTime,
      'CasesForICUByRequestedTime' => $impactCasesForICUByRequestedTime,
      'CasesForVentilatorsByRequestedTime' => $impactCasesForVentilatorsByRequestedTime,
      'DollarsInFlight' => $impactDollarsInFlight
    ),
    "severeImpact" => array(
      'CurrentlyInfected' => $severeImpactCurrentlyInfected,
      'InfectionsByRequestedTime' => $severeImpactInfectionsByRequestedTime,
      'SevereCasesByRequestedTime' => $severeImpactSevereCasesByRequestedTime,
      'HospitalBedsByRequestedTime' => $severeImpactHospitalBedsByRequestedTime,
      'CasesForICUByRequestedTime' => $severeImpactCasesForICUByRequestedTime,
      'CasesForVentilatorsByRequestedTime' => $severeImpactCasesForVentilatorsByRequestedTime,
      'DollarsInFlight' => $severeImpactDollarsInFlight
    )
  );

  $data = json_encode($data);
  
  return $data;
}

// covid19ImpactEstimator($data);