<!doctype html>
<html>
<head>
    <title>@yield('title')</title>
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/jquery.dataTables.min.css">
</head>
<body>
<div class="container">
    <header> @include('layout.header') </header>
    <div class="sidebar"> @include('layout.sidebar') </div>
    <div class="contents"> @yield('content') </div>
    <footer> @include('layout.footer') </footer>
</div>
<script type="text/javascript" src="js/jquery.js"></script>

<!-- Bootstrap Core JavaScript -->
<script src="js/bootstrap.min.js"></script>

<script type="text/javascript" src="js/jquery.dataTables.min.js"></script>
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

<!-- Menu Toggle Script -->
<script>
    $("#menu-toggle").click(function(e) {
        e.preventDefault();
        $("#wrapper").toggleClass("toggled");
    });
</script>
</body>
</html>