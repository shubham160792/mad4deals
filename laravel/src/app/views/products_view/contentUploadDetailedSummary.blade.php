@extends('...layouts.master')
@section('title')
All Products Dashboard| 91Mobiles.com
@stop
@section('style-sheets')
{{ HTML::style(asset(Config::get('app.url_path')."css/jquery.dataTables.min.css")); }}
{{ HTML::style(asset(Config::get('app.url_path')."css/jquery.dataTables.min.res.css")); }}

@stop
@section('content-title')
<?php echo ucwords(str_replace('_', ' ', $category)); ?> Upload Summary | Dashboard
@stop
<head>
</head>
@section('content')
@if (Session::has('message'))
<div data-alert class="alert-box">{{ Session::get('message') }}</div>
@endif
<?php
/**
 * Created by PhpStorm.
 * User: balwant
 * Date: 21/8/15
 * Time: 12:24 PM
 */
?>

<body>
<div id="wrapper">
    <div class="container">
        <a class="tiny button" href="{{ URL::to(Config::get('app.url_path')) }}" style="float:left;">Create a Gallery</a>
        <a class="tiny button" href="{{ URL::to(Config::get('app.url_path').'allProducts/1') }}" style="float:left;margin-left:30px!important;">All Products Dashboard</a>
        <a class="tiny button" href="{{ URL::to(Config::get('app.url_path').'top200Products/1') }}" style="float:left;margin-left:30px!important;">Top 200 Products Dashboard</a>

        <div class="container">
            <table cellpadding="1" cellspacing="1" border="1" class="dataTable table table-bordered" id="example">
                <thead>
                <tr>
                    <th>SNO</th>
                    <th>ID</th>
                    <th>NAME</th>
                    <th>ADD DATE</th>
                </tr>
                </thead>
                <tbody>
                    <?php $sno = 1; foreach($records as $product) { ?>
                    <tr>
                        <td><?php echo $sno; $sno++;?></td>
                        <td><?php echo $product->id;?></td>
                        <td><?php echo $product->name;?></td>
                        <td><?php echo $product->add_date;?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
            <a class="tiny button" href="{{ URL::to(Config::get('app.url_path').'contentUploadSummary/all/overview') }}" style="float:left;margin:30px!important;">Back</a>
        </div>
    </div>
</div>
</body>
@stop
@section('scripts')
{{ HTML::script(asset(Config::get('app.url_path')."js/script.js")); }}
@stop
