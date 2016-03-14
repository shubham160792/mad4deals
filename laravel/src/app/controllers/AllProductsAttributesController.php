<?php
use \App\constants\AttributeConstants;
class AllProductsAttributesController extends \BaseController {




    public function index($page)
    {


        try
        {
            if (Auth::check())
            {

                $start =AttributeConstants::START;
                $size = AttributeConstants::BATCHSIZE;
                if($page > 0) {
                    $start = (($page-1)*$size);
                }
                $Products = new AllProductAttributes();
                $result = $Products->getProductIds($start,$size);
                return View::make('products_view.allProducts')
                    ->with('result', $result)
                    ->with('page', $page);

            }
            else
            {
                return View::make(Config::get('app.url_path').'login');
            }

        }
        catch(\Exception $e){

            Log::error("error occurs while getting details of all category");
            Log::error($e -> getMessage());

        }
    }


    public function top200Index($page)
    {


        try
        { 
            if (Auth::check())
            {

                $start =AttributeConstants::START;
                $size = AttributeConstants::BATCHSIZE;
                if($page > 0) {
                    $start = (($page-1)*$size);
                }
                $Products = new Top200Products();
                $result = $Products->getTop200Products($start,$size);
                
                return View::make('products_view.top200Products')
                    ->with('result', $result)
                    ->with('page', $page);

            }
            else
            {
                return View::make(Config::get('app.url_path').'login');
            }

        }
        catch(\Exception $e){

            Log::error("error occurs while getting details of all category");
            Log::error($e -> getMessage());

        }
    }

public function download($page)
    {


        try
        { 
            if (Auth::check()) {
                $result = array();
                if ($page == 'summary') {
                    $Products = new AllProductAttributes();
                    $result = $Products->getLastWeekRecordDetailsDownload();
                    $lastWeekRecords = $Products->getLastWeekRecords();
                    $currentWeekRecords = $Products->getCurrentWeekRecords();
                    $totalRecords = $Products->getAllRecords();
                    return View::make('products_view.downloadSummary')
                        ->with('result', $result)
                        ->with('lastWeekRecords', $lastWeekRecords)
                        ->with('currentWeekRecords', $currentWeekRecords)
                        ->with('totalRecords', $totalRecords);
                } else {
                    if ($page == 'top200') {
                        $Products = new Top200Products();
                        $result = $Products->getTop200Products(0, 200);
                    } elseif ($page == 'all') {
                        $Products = new AllProductAttributes();
                        $result = $Products->getProductIds(0, 200, 1);
                    }
                    $result = json_encode($result);
                    $result = json_decode($result, true);
                    return View::make('products_view.download')
                        ->with('result', $result)
                        ->with('page', $page);

                 }
            }
            else
            {
                return View::make(Config::get('app.url_path').'login');
            }

        }
        catch(\Exception $e){

            Log::error("error occurs while getting details of all category");
            Log::error($e -> getMessage());

        }
    }

    public function contentSummary($week,$page)
    {
        try
        {
            if (Auth::check())
            {
                $Products = new AllProductAttributes();
                if($page == 'overview') {
                    $lastWeekRecords = $Products->getLastWeekRecords();
                    $currentWeekRecords = $Products->getCurrentWeekRecords();
                    $totalRecords = $Products->getAllRecords();
                    return View::make('products_view.contentUploadSummary')
                        ->with('lastWeekRecords', $lastWeekRecords)
                        ->with('currentWeekRecords', $currentWeekRecords)
                        ->with('totalRecords', $totalRecords);
                } elseif ($page != 'overview') {
                    if($week == 'lastWeek') {
                        $records = $Products->getLastWeekRecordsDetailsByCategory($page);

                    } elseif ($week == 'thisWeek') {
                        $records = $Products->getCurrentWeekRecordsDetailsByCategory($page);
                    }
                    return View::make('products_view.contentUploadDetailedSummary')
                        ->with('records', $records)
                        ->with('category', $page);
                }

            }
            else
            {
                return View::make(Config::get('app.url_path').'login');
            }

        }
        catch(\Exception $e){

            Log::error("error occurs while getting details of all category");
            Log::error($e -> getMessage());

        }
    }
}
