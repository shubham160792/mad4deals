<?php
/**
 * Created by PhpStorm.
 * User: balwant
 * Date: 22/8/15
 * Time: 7:01 PM
 */
// Create new PHPExcel object
$objPHPExcel = new PHPExcel();
$objPHPExcel->getActiveSheet()->getStyle('A1:L100')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$lastWeekVideo = ($lastWeekRecords->videoCount > 0)?$lastWeekRecords->videoCount.'('.$lastWeekRecords->actualVideoCount.')':$lastWeekRecords->videoCount;
$currentWeekVideo = ($currentWeekRecords->videoCount > 0)?$currentWeekRecords->videoCount.'('.$currentWeekRecords->actualVideoCount.')':$currentWeekRecords->videoCount;
$totalVideo = ($totalRecords->videoCount > 0)?$totalRecords->videoCount.'('.$totalRecords->actualVideoCount.')':$totalRecords->videoCount;
//Write cells
$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Category Name')
    ->setCellValue('B1', date("d M Y", strtotime(\App\constants\AttributeConstants::MONDAY_LAST_WEEK))." - ".$endDate = date("d M Y", strtotime(\App\constants\AttributeConstants::SUNDAY_LAST_WEEK)))
    ->setCellValue('C1', date("d M Y", strtotime(\App\constants\AttributeConstants::MONDAY_THIS_WEEK))." - ".$endDate = date("d M Y", strtotime(\App\constants\AttributeConstants::SUNDAY_THIS_WEEK)))
    ->setCellValue('D1', 'Total Count')
    ->setCellValue('A2', 'View360')
                ->setCellValue('B2', $lastWeekRecords->views360Count)
                ->setCellValue('C2', $currentWeekRecords->views360Count)
                ->setCellValue('D2', $totalRecords->views360Count)

    ->setCellValue('A3', 'Videos')
                ->setCellValue('B3',$lastWeekVideo)
                ->setCellValue('C3',$currentWeekVideo)
                ->setCellValue('D3',$totalVideo)

    ->setCellValue('A4', 'Slideshare')
                ->setCellValue('B4', $lastWeekRecords->slideShareCount)
                ->setCellValue('C4', $currentWeekRecords->slideShareCount)
                ->setCellValue('D4', $totalRecords->slideShareCount)

    ->setCellValue('A5', 'Screenshots')
                ->setCellValue('B5', $lastWeekRecords->screenShotCount)
                ->setCellValue('C5', $currentWeekRecords->screenShotCount)
                ->setCellValue('D5', $totalRecords->screenShotCount)

    ->setCellValue('A6', 'Benchmarks')
                ->setCellValue('B6', $lastWeekRecords->benchMarkCount)
                ->setCellValue('C6', $currentWeekRecords->benchMarkCount)
                ->setCellValue('D6', $totalRecords->benchMarkCount)

    ->setCellValue('A7', 'Camera Samples')
                ->setCellValue('B7', $lastWeekRecords->cameraCount)
                ->setCellValue('C7', $currentWeekRecords->cameraCount)
                ->setCellValue('D7', $totalRecords->cameraCount);

// Set AutoSize for fields
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);

// Add new sheet
//$objPHPExcel = $objPHPExcel->createSheet(1); //Setting index when creating
$header = array( 'Id', 'Name', 'Views', '360_View', 'Video', 'Slideshare', 'Camera', 'Screenshot', 'Benchmark', 'AddDate', 'EditDate');
// Fill worksheet from values in array
$objPHPExcel->getActiveSheet()->fromArray($header, null, 'A11');
 $row = 11;
    foreach($result as $key => $value) {
       $content =array();
       $row++;
       foreach($value as $keys => $values) {
           $data= ($values == 1)?'Added':$values;
           if($values == false){
                $data = '';
           }
           $content[] = $data;
       }
       $objPHPExcel->getActiveSheet()->fromArray($content, null, 'A'.$row);
     }

// Set AutoSize for fields
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);


//set Title
$objPHPExcel->getActiveSheet()->setTitle('Detailed Summary');


header('Content-Type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename=UploadSummary.xls");
header("Pragma: no-cache");
header("Expires: 0");

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
die;
?>
