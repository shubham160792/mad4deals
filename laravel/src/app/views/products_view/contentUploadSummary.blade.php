@extends('...layouts.master')
@section('title')
All Products Dashboard| 91Mobiles.com
@stop
@section('style-sheets')
{{ HTML::style(asset(Config::get('app.url_path')."css/jquery.dataTables.min.css")); }}
{{ HTML::style(asset(Config::get('app.url_path')."css/jquery.dataTables.min.res.css")); }}

@stop
@section('content-title')
Content Upload Summary | Dashboard
@stop
<head>

    <style type="text/css">
        tr{
            height: 70px;
        }
    </style>


</head>
@section('content')
@if (Session::has('message'))
<div data-alert class="alert-box">{{ Session::get('message') }}</div>
@endif
<?php
/**
 * Created by PhpStorm.
 * User: shubham
 * Date: 8/7/15
 * Time: 12:24 PM
 */
?>

<body>
<div id="wrapper">
    <div class="container">
        <a class="tiny button" href="{{ URL::to(Config::get('app.url_path')) }}" style="float:left;">Create a Gallery</a>
        <a class="tiny button" href="{{ URL::to(Config::get('app.url_path').'allProducts/1') }}" style="float:left;margin-left:30px!important;">All Products Dashboard</a>
        <a class="tiny button" href="{{ URL::to(Config::get('app.url_path').'top200Products/1') }}" style="float:left;margin-left:30px!important;">Top 200 Products Dashboard</a>
        <a class="tiny button" href="{{ URL::to(Config::get('app.url_path').'download/summary') }}" style="float:left;margin-left:30px!important;">Download Summary Report</a>

        <div class="container">
            <table cellpadding="1" cellspacing="1" border="1" class="dataTable table table-bordered" id="example">
                <thead>
                <tr>
                    <th>Category Name</th>
                    <th><?php echo date("d M Y", strtotime(\App\constants\AttributeConstants::MONDAY_LAST_WEEK))." - ".$endDate = date("d M Y", strtotime(\App\constants\AttributeConstants::SUNDAY_LAST_WEEK));?></th>
                    <th><?php echo date("d M Y", strtotime(\App\constants\AttributeConstants::MONDAY_THIS_WEEK))." - ".$endDate = date("d M Y", strtotime(\App\constants\AttributeConstants::SUNDAY_THIS_WEEK));?></th>
                    <th>Total Count</th>
                </tr>
                </thead>
                <tbody>
                    <tr>
                        <th>View360</th>
                        <td><?php echo $lastWeekRecords->views360Count;
                               if($lastWeekRecords->views360Count > 0) {?>
                                <a class="tiny button" style="float: right" href="{{ URL::to(Config::get('app.url_path').'contentUploadSummary/lastWeek/view360_image_count') }}">Details</a>
                                <?php } ?>
                        </td>
                        <td><?php echo $currentWeekRecords->views360Count;
                        if($currentWeekRecords->views360Count > 0) {?>
                             <a class="tiny button" style="float: right" href="{{ URL::to(Config::get('app.url_path').'contentUploadSummary/thisWeek/view360_image_count') }}">Details</a>
                             <?php } ?>
                         </td>
                        <td><?php echo $totalRecords->views360Count; ?></td>

                    </tr>
                    <tr>
                        <th>Videos</th>
                        <td><?php echo $lastWeekRecords->videoCount;echo ($lastWeekRecords->videoCount != 0)?"(".$lastWeekRecords->actualVideoCount.")":"";
                         if($lastWeekRecords->videoCount > 0) {?>
                            <a class="tiny button" style="float: right" href="{{ URL::to(Config::get('app.url_path').'contentUploadSummary/lastWeek/video') }}">Details</a>
                            <?php } ?>
                        </td>
                        <td><?php echo $currentWeekRecords->videoCount;echo ($currentWeekRecords->videoCount != 0)? "(".$currentWeekRecords->actualVideoCount.")":"";
                        if($currentWeekRecords->videoCount > 0) {?>
                            <a class="tiny button" style="float: right" href="{{ URL::to(Config::get('app.url_path').'contentUploadSummary/thisWeek/video') }}">Details</a>
                            <?php } ?>
                        </td>
                        <td><?php echo $totalRecords->videoCount;echo ($totalRecords->videoCount != 0)? "(".$totalRecords->actualVideoCount.")" :""; ?></td>
                    </tr>
                    <tr>
                        <th>Slideshare</th>
                        <td><?php echo $lastWeekRecords->slideShareCount;
                        if($lastWeekRecords->slideShareCount > 0) {?>
                            <a class="tiny button" style="float: right" href="{{ URL::to(Config::get('app.url_path').'contentUploadSummary/lastWeek/slideshare_review_url') }}">Details</a>
                            <?php } ?>
                        </td>
                        <td><?php echo $currentWeekRecords->slideShareCount;
                        if($currentWeekRecords->slideShareCount > 0) {?>
                            <a class="tiny button" style="float: right" style="float: right" href="{{ URL::to(Config::get('app.url_path').'contentUploadSummary/thisWeek/slideshare_review_url') }}">Details</a>
                            <?php } ?>
                        </td>
                        <td><?php echo $totalRecords->slideShareCount; ?></td>
                    </tr>
                    <tr>
                        <th>Screenshots</th>
                        <td><?php echo $lastWeekRecords->screenShotCount;
                        if($lastWeekRecords->screenShotCount > 0) {?>
                            <a class="tiny button" style="float: right" href="{{ URL::to(Config::get('app.url_path').'contentUploadSummary/lastWeek/screenshots') }}">Details</a>
                            <?php } ?>
                        </td>
                        <td><?php echo $currentWeekRecords->screenShotCount;
                        if($currentWeekRecords->screenShotCount > 0) {?>
                            <a class="tiny button" style="float: right" href="{{ URL::to(Config::get('app.url_path').'contentUploadSummary/thisWeek/screenshots') }}">Details</a>
                            <?php } ?>
                        </td>
                        <td><?php echo $totalRecords->screenShotCount; ?></td>
                    </tr>
                    <tr>
                        <th>Benchmarks</th>
                        <td><?php echo $lastWeekRecords->benchMarkCount;
                        if($lastWeekRecords->benchMarkCount > 0) {?>
                            <a class="tiny button" style="float: right" href="{{ URL::to(Config::get('app.url_path').'contentUploadSummary/lastWeek/benchmarks') }}">Details</a>
                            <?php } ?>
                        </td>
                        <td><?php echo $currentWeekRecords->benchMarkCount;
                        if($currentWeekRecords->benchMarkCount > 0) {?>
                            <a class="tiny button" style="float: right" href="{{ URL::to(Config::get('app.url_path').'contentUploadSummary/thisWeek/benchmarks') }}">Details</a>
                            <?php } ?>
                        </td>
                        <td><?php echo $totalRecords->benchMarkCount; ?></td>
                    </tr>
                    <tr>
                        <th>Camera Samples</th>
                        <td><?php echo $lastWeekRecords->cameraCount;
                        if($lastWeekRecords->cameraCount > 0) {?>
                            <a class="tiny button" style="float: right" href="{{ URL::to(Config::get('app.url_path').'contentUploadSummary/lastWeek/camera_samples') }}">Details</a>
                            <?php } ?>
                        </td>
                        <td><?php echo $currentWeekRecords->cameraCount;
                        if($currentWeekRecords->cameraCount > 0) {?>
                            <a class="tiny button" style="float: right" href="{{ URL::to(Config::get('app.url_path').'contentUploadSummary/thisWeek/camera_samples') }}">Details</a>
                            <?php } ?>
                        </td>
                        <td><?php echo $totalRecords->cameraCount; ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
@stop
@section('scripts')
{{ HTML::script(asset(Config::get('app.url_path')."js/script.js")); }}
@stop
