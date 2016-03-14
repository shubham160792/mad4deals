<div id="wrapper">

    <!-- Sidebar -->
    <div id="sidebar-wrapper">
        <ul class="sidebar-nav">
            <li class="sidebar-brand">
                <a href="#">
                    Navigation Panel
                </a>
            </li>
            <li>
                <a href="{{ URL::to('categories') }}">Categories</a>
            </li>
            <li>
                <a href="{{ URL::to('subcategories') }}">Subcategories</a>
            </li>
            <li>
                <a href="{{ URL::to('stores') }}">Stores</a>
            </li>
            <li>
                <a href="{{ URL::to('storeUrls') }}">Store Product Url Controller</a>
            </li>
            <li>
                <a href="{{ URL::to('products') }}">Products</a>
            </li>
        </ul>
    </div>
    <!-- /#sidebar-wrapper -->

</div>
<!-- /#wrapper -->
