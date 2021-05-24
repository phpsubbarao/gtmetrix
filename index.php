<?php

require 'vendor/autoload.php';
ini_set('max_execution_time', 0);
date_default_timezone_set("Asia/Calcutta");   //India time (GMT+5:30)

use LightningStudio\GTMetrixClient\GTMetrixClient;
use LightningStudio\GTMetrixClient\GTMetrixTest;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;



$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$sheet->setCellValue('A1','URL');
$sheet->setCellValue('B1', 'Score');
$sheet->setCellValue('C1', 'Date');

$str = file_get_contents('list.json');
$json = json_decode($str, true); // decode the JSON into an associative array


foreach ($json as $key => $value) {


$keys = $key + 2; 

$score = get_gtmetrix_data($value);


$sheet->setCellValue('A'.$keys, $value);
$sheet->setCellValue('B'.$keys, $score['pagespeedscore']);
$sheet->setCellValue('C'.$keys, date('Y-m-d H:i:s'));
$writer = new Xlsx($spreadsheet);
$writer->save('gtmetrix_page_speed_'.date('Y_m_d').'.xlsx');
    flush();
    ob_flush();
    sleep(1);

}


function get_gtmetrix_data($url){
$client = new GTMetrixClient();
$client->setUsername('phpsubbarao@gmail.com');
$client->setAPIKey('xxxxxxx');


$test = $client->startTest($url);
 
//Wait for result
while ($test->getState() != GTMetrixTest::STATE_COMPLETED &&
    $test->getState() != GTMetrixTest::STATE_ERROR) {
    $data= $client->getTestStatus($test);
   
    sleep(5);
}

return $data;
}
