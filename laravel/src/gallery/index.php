<?php
//ini_set("display_errors", "ON");
//error_reporting(E_ALL);
require __DIR__.'/config/'.$_SERVER['environment'].'/config.php';
require 'utils/Connection.php';
require 'classes/Gallery.php';
$obj = new classes\Gallery();
$category_id=$_GET['category_id'];
$gallery_count=(int)$_GET['count'];
$page=(int)$_GET['page'];
if($page != 0)
{
$page=$page-1;
}
$page=($page*20);
$val=$obj -> getGalleryData($category_id,$gallery_count,$page);
$val=json_decode($val);
?>
<head>
	<title>Gallery | 91mobiles.com</title>
	<script src="js/jquery.min.js" type="text/javascript" charset="utf-8"></script>
	<script src="js/jquery.babylongrid.js" type="text/javascript" charset="utf-8"></script>
	<link rel="stylesheet" href="css/babylongrid-default.css">
	<link rel="stylesheet" href="css/gallery_dashboard.css">
</head>
<body style="background: #D2D2D2;">	
	<div id="babylongrid2">
		<?php
		for($i=0;$i<count($val);$i++) 
		{
			?>
			<ul class="gallery clearfix">
				<article>
					<div class="h3 title"><?php echo $val[$i] -> name; ?> </div>
					<a  href=<?php echo "http://www.91-img.com/gallery_images_uploads/".$val[$i] ->thumb_img_url.".".$val[$i] ->thumb_img_extension; ?> rel="prettyPhoto"><center><img style="max-width:100px;" src=<?php echo "http://www.91-img.com/gallery_images_uploads/".$val[$i] ->thumb_img_url.".".$val[$i] ->thumb_img_extension; ?>  >
					</a>
					<div class="desc">
						<?php if($val[$i] -> description != NULL )
						{
							echo "Description: ".$val[$i] -> description; 
						}
						?> 
					</div>
				</center>
			</article>
		</ul>
		<?php
		}
		?>	
	</div>
</body>
<script>
	(function($){

		$('#babylongrid').babylongrid({
			firstToRight: true
		});

		$('#babylongrid2').babylongrid({
			scheme: [
			{
				minWidth: 960,
				columns: 3
			},
			{
				minWidth: 500,
				columns: 2
			},
			{
				minWidth: 0,
				columns: 1
			}
			]
		});

		$('#babylongrid3').babylongrid({
			display: 'tower'
		});

		$('#babylongrid4').babylongrid({
			display: 'city'

		});
				//$('#babylongrid4').trigger('babylongrid:resize');
			}(jQuery));
</script>
</body>
</html>

