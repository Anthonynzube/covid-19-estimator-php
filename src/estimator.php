<?php
$url = 'data.json';
$data = file_get_contents($url);

function covid19ImpactEstimator($data)
{
  $decodedData = json_decode($data, true);

  $reportedCases = $decodedData['reportedCases'];
  $periodType =  $decodedData['periodType'];
  $timeToElapse =  $decodedData['timeToElapse'];
  $population =  $decodedData['population'];
  $totalHospitalBeds =  $decodedData['totalHospitalBeds'];
  $avgDailyIncomeInUSD = $decodedData['region']['avgDailyIncomeInUSD'];



  $currentlyInfected = $reportedCases * 10;
  $infectionsByRequestedTime = $currentlyInfected * 512; 
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
  $infectionsByRequestedTime = $currentlyInfected * 512;
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