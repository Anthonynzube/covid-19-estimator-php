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
  $decodedData = json_decode($data);

  $reportedCases = $decodedData->reportedCases;
  $periodType =  $decodedData->periodType;
  $timeToElapse =  $decodedData->timeToElapse;
  $population =  $decodedData->population;
  $totalHospitalBeds =  $decodedData->totalHospitalBeds;
  $avgDailyIncomeInUSD = $decodedData->region->avgDailyIncomeInUSD;

  if ($timeToElapse > 0 && $periodType == "days")
  {
    $timeToElapse = $timeToElapse;

  }elseif ($timeToElapse > 0 && $periodType == "weeks")
  {
    $timeToElapse = $timeToElapse * 7;
    
  }elseif ($timeToElapse > 0 && $periodType == "months")
  {
    $timeToElapse = $timeToElapse * 30;
  }

  $factor = 2**floor($timeToElapse/3);

  $currentlyInfected = $reportedCases * 10;
  $infectionsByRequestedTime = $currentlyInfected * $factor; 
  $severeCasesByRequestedTime = round(0.15 * $infectionsByRequestedTime, 0);
  $hospitalBedsByRequestedTime = round((0.35 * $totalHospitalBeds) - $severeCasesByRequestedTime, 0);
  $casesForICUByRequestedTime = round(0.05 * $infectionsByRequestedTime, 0);
  $casesForVentilatorsByRequestedTime = round(0.02 * $infectionsByRequestedTime, 0);
  $dollarsInFlight = round(($infectionsByRequestedTime * 0.65 * $avgDailyIncomeInUSD * 30), 0);

  $impact['currentlyInfected'] = $currentlyInfected;
  $impact['infectionsByRequestedTime'] = $infectionsByRequestedTime;
  $impact['severeCasesByRequestedTime'] = $severeCasesByRequestedTime;
  $impact['hospitalBedsByRequestedTime'] = $hospitalBedsByRequestedTime;
  $impact['casesForICUByRequestedTime'] = $casesForICUByRequestedTime;
  $impact['casesForVentilatorsByRequestedTime'] = $casesForVentilatorsByRequestedTime;
  $impact['dollarsInFlight'] = $dollarsInFlight;

  $currentlyInfected;
  $infectionsByRequestedTime;

  $currentlyInfected = $reportedCases * 50;
  $infectionsByRequestedTime = $currentlyInfected * $factor; 
  $severeCasesByRequestedTime = round(0.15 * $infectionsByRequestedTime, 0);
  $hospitalBedsByRequestedTime = round((0.35 * $totalHospitalBeds) - $severeCasesByRequestedTime, 0);
  $casesForICUByRequestedTime = round(0.05 * $infectionsByRequestedTime, 0);
  $casesForVentilatorsByRequestedTime = round(0.02 * $infectionsByRequestedTime, 0);
  $dollarsInFlight = round(($infectionsByRequestedTime * 0.65 * $avgDailyIncomeInUSD * 30), 0);

  $severeImpact['currentlyInfected'] = $currentlyInfected;
  $severeImpact['infectionsByRequestedTime'] = $infectionsByRequestedTime;
  $severeImpact['severeCasesByRequestedTime'] = $severeCasesByRequestedTime;
  $severeImpact['hospitalBedsByRequestedTime'] = $hospitalBedsByRequestedTime;
  $severeImpact['casesForICUByRequestedTime'] = $casesForICUByRequestedTime;
  $severeImpact['casesForVentilatorsByRequestedTime'] = $casesForVentilatorsByRequestedTime;
  $severeImpact['dollarsInFlight'] = $dollarsInFlight;


  $data = array(
    "data" => $decodedData,
    "impact" => $impact,
    "severeImpact" => $severeImpact
  );

  $data = json_encode($data);
  
  echo $data;
}

covid19ImpactEstimator($data);