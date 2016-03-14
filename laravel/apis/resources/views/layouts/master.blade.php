<html>
<head>
    <style>
        body {
            font-family: 'helvetica';
        }

        .fa-btn {
            margin-right: 6px;
        }
    </style>
    <title>@yield('title')</title>
    {!!Html::style('//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css')!!}
    {!!Html::style('css/jquery.dataTables.min.css')!!}
    {!!Html::style('css/simple-sidebar.css')!!}
</head>
<body>
<div class="container">
    <header> @include('layouts.header') </header>
    <div style="float: left;width:18%;">
        @section('sidebar')
            @include('layouts.sidebar')
        @show
    </div>
    <div style="float: right;width:82%">
        @yield('content')
    </div>
</div>
{!!Html::script('js/jquery.js')!!}
{!!Html::script('js/jquery.dataTables.min.js')!!}
<script type="text/javascript">
    $(document).ready(function(){
        $('#dt').DataTable({
            responsive: true,
            "bPaginate": false
        });
        $(".alerts").click(function(){
            if (!confirm("Do you want to delete")){
                return false;
            }
        });
    });

</script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
</body>
</html>