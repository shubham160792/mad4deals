@extends('...layouts.master')
@section('title')
All Products Dashboard| 91Mobiles.com
@stop
@section('style-sheets')
{{ HTML::style(asset(Config::get('app.url_path')."css/jquery.dataTables.min.css")); }}
{{ HTML::style(asset(Config::get('app.url_path')."css/jquery.dataTables.min.res.css")); }}

@stop
@section('content-title')
All Products | Dashboard
@stop
<head>

    <style type="text/css">
        .btn
        {
            padding: 1px;
            float: left;

        }
        .DTTT_container{
            margin-top: -38px!important;
        }
        .datatables_filter{
            float:left!important;
        }
        .DTTT_button_copy{
            display:none !important;
        }
        .DTTT_button_print{
            display: none !important;
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
 * User: balwant
 * Date: 27/7/15
 * Time: 12:24 PM
 */


?>

<body>
<div id="wrapper">
    <div class="container">
    <a class="tiny button" href="{{ URL::to(Config::get('app.url_path')) }}" style="float:left;">Create a Gallery</a>
    <a class="tiny button" href="{{ URL::to(Config::get('app.url_path').'download/all') }}" style="float:left;margin-left:30px!important;" target="_blank" >Download All Products</a>
    <a class="tiny button" href="{{ URL::to(Config::get('app.url_path').'contentUploadSummary/all/overview') }}" style="float:left;margin-left:30px!important;">Content Upload Summary</a>
        <div class="container">
            <table cellpadding="1" cellspacing="1" border="1" class="table table-bordered" >
                <thead>
                <tr>
                    <th>SNo.</th>
                    <th>Pro_ID</th>
                    <th>Name</th>
                    <th>360</th>
                    <th>Youtube</th>
                    <th>Slideshare</th>
                    <th>Camera</th>
                    <th>Screenshots</th>
                    <th>Benchmarks</th>
                    <th>Add Date</th>
                    <th>Edit Date</th>
                    <th>Manage</th>
                </tr>
                </thead>
                <tbody>
                <?php $sno=(($page-1)*\App\constants\AttributeConstants::BATCHSIZE); foreach($result as $productAttr){ ?>
                <tr>
                    <td><?php echo ++$sno; ?></td>
                    <td ><?php echo $productAttr->id; ?></td>
                    <td><?php echo $productAttr->name; ?></td>
                    <td><?php echo $productAttr->views360?"Y":"N"  ?></td>
                    <td><?php echo $productAttr->video?"Y":"N"; ?></td>
                    <td><?php echo $productAttr->slideShare?"Y":"N"; ?></td>
                    <td><?php echo $productAttr->camera?"Y":"N"; ?></td>
                    <td><?php echo $productAttr->screenShot?"Y":"N"; ?></td>
                    <td><?php echo $productAttr->benchMark?"Y":"N"; ?></td>
                    <td><?php echo $productAttr->add_date; ?></td>
                    <td><?php echo $productAttr->edit_date; ?></td>

                    <td>
                        <div class="small-2 ">
                            <a target="_blank" class="tiny button" href="{{ URL::to(Config::get('app.url_path').'gallery/?id='.$productAttr->id.'&cat_id=553') }}" style="float:left;">Manage</a>
                        </div>
                    </td>
                </tr>
                <?php } ?>
                </tbody>
            </table>
            <a class="tiny button" href="{{ URL::to(Config::get('app.url_path').'allProducts/'.($page>1?$page-1:$page)) }}" style="float:left;margin-left:30px!important;">Previous</a>
            <a class="tiny button" href="{{ URL::to(Config::get('app.url_path').'allProducts/'.($page+1)) }}" style="float:left;margin-left:30px!important;">Next</a>

        </div>
    </div>
 </div>
</body>
@stop
@section('scripts')
{{ HTML::script(asset(Config::get('app.url_path')."js/script.js")); }}



<script>

    $(document).ready(function(){
        $(".alert").click(function(){

            if (!confirm("Do you want to delete")){
                return false;
            }
        });
    });
</script>

@stop
