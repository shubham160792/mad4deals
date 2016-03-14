<?php
/**
 * Created by PhpStorm.
 * User: balwant
 * Date: 28/7/15
 * Time: 7:01 PM
 */
$header = array( 'Id', 'Name', 'Views', '360_View', 'Video', 'Slideshare', 'Camera', 'Screenshot', 'Benchmark', 'AddDate', 'EditDate');


// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

// Fill worksheet from values in array
$objPHPExcel->getActiveSheet()->fromArray($header, null, 'A1');
 $row = 1;
    foreach($result as $key => $value) {
       $content =array();
       $row++;
       foreach($value as $keys => $values) {
           $data= ($values == 1)?'Y':$values;
           if($values == false){
                $data = 'N';
           }
           $content[] = $data;
       }
       $objPHPExcel->getActiveSheet()->fromArray($content, null, 'A'.$row);
     }

// Set AutoSize for name and email fields
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

header('Content-Type: application/vnd.ms-excel');
if($page == 'top200') {
    $objPHPExcel->getActiveSheet()->setTitle('Top200Products');
   header("Content-Disposition: attachment; filename=top200Products.xls");
} elseif ($page == 'all') {
    $objPHPExcel->getActiveSheet()->setTitle('AllProducts');
   header("Content-Disposition: attachment; filename=allProducts.xls");
}
header("Pragma: no-cache");
header("Expires: 0");

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
die;
?>
