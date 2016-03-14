
<!doctype html>
<html class="no-js" lang="en">
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>@yield('title')</title>
	{{ HTML::style(asset(Config::get('app.url_path')."css/foundation.css")); }}
	<?php echo HTML::style(asset(Config::get('app.url_path')."css/style.css")); ?>
	<?php echo HTML::style(asset(Config::get('app.url_path')."css/dropzone.css")); ?>
	<?php echo HTML::style(asset(Config::get('app.url_path')."css/chosen.css")); ?>
	<?php echo HTML::style(asset(Config::get('app.url_path')."css/jquery-ui.css")); ?>

    @yield('style-sheets')
</head>
<body>
<script type="text/javascript">
    var js_css_url_path = "<?php echo Config::get('app.js_css_url_path'); ?>";
</script>
<div class="row full-width wrapper">
        <div class="large-12 columns content-bg">
            <section id="top-menu">
                <div class="row">
                    <div class="large-2 medium-4 small-12 columns top-part-no-padding">
                        <div class="logo-bg">
                            <div class="templ_head">Gallery</div>
                        </div>
                    </div>
                    <!-- <div class="large-10 medium-8 small-12 columns top-menu">
                        <div class="row">
                           
                        </div>
                        <div class="clearfix"></div>
                    </div> -->
                </div>
            </section>
            <div class="row">
                <div class="no-padding">
                    <div class="large-2 medium-12 small-12 columns">
                        <ul class="side-nav">
                        @include('layouts.sidebar')
                        </ul>
                    </div>
                </div>
                <div class="large-10 medium-12 small-12 columns light-grey-bg-pattern content_tab">
                    <br />
                    <div class="row">
                        <div class="large-10 columns">
                            <div class="page-name">
                                <h3 class="left">@yield('content-title')</h3>
                                <div class="clearfix"></div>
                                <span class="caption">@yield('content-caption')</span>
                            </div>
                        </div>
                    </div>
                    <section id="content">
							@yield('content')
                    </section>
                </div>
            </div>
        </div>
    </div>
<!-- 	<div class="off-canvas-wrap docs-wrap" data-offcanvas="">
		<div class="inner-wrap">
			@include('layouts.sidebar')
			<section class="main-section">
				<div class="row">
					<div class="large-12 columns">
						<div class="large-9 columns" role="content">
							
						</div>						
						<aside class="large-3 columns"></aside>
					</div>
				</div>
			</section>
			<a class="exit-off-canvas"></a>
		</div>
	</div> -->
	<script type="text/javascript">
	var ImageType = '<?php echo \App\constants\ItemType::$ImageType; ?>';
	var YoutubeType = '<?php echo \App\constants\ItemType::$YoutubeType; ?>';
	var SlideShareType = '<?php echo \App\constants\ItemType::$SlideShareType; ?>';
    </script>
	<?php echo HTML::script(asset(Config::get('app.url_path')."js/vendor/jquery.js")); ?>
	<?php echo HTML::script(asset(Config::get('app.url_path')."js/foundation.min.js")); ?>
	<?php echo HTML::script(asset(Config::get('app.url_path')."js/dropzone.js")); ?>
	<?php echo HTML::script(asset(Config::get('app.url_path')."js/vendor/modernizr.js")); ?>
	<?php echo HTML::script(asset(Config::get('app.url_path')."js/vendor/jquery-ui.min.js")); ?>
	<?php echo HTML::script(asset(Config::get('app.url_path')."js/chosen.jquery.min.js")); ?>
	<?php echo HTML::script(asset(Config::get('app.url_path')."js/common.js")); ?>

    @yield('scripts')
</body>
</html>
