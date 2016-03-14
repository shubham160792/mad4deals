<?php header('access-control-allow-origin: *');?>
<?php
include_once('../get_albums.php');
$id = $_REQUEST['id'];
$catId = $_REQUEST['catId'];
?>
<?php if($_REQUEST['source'] == 'wap'){ ?>
    <div id="albums">
        <div id="camera_samples">
            <div class="samples_images_panel">
                <?php
                $images = $galleries['albums']['Camera Samples'];
                for($i=0;$i<3;$i++){ ?>
                    <div class="img_dv" data-id="<?php echo $id; ?>" data-index="<?php echo $i; ?>" data-catId="<?php echo $catId ?>" data-category="<?php echo \Config::CAMERA_SAMPLES_CATEGORY; ?>" onclick="_gaq.push(['_trackEvent','detail-gallery-spec','camerasamples_thumb','<?php echo $galleries["pro_name"];?>::<?php echo $id; ?>::<?php echo $catId  ?>',1,false]);">
                        <img data-id="<?php echo $id; ?>" data-index="<?php echo $i; ?>" data-catId="<?php echo $catId ?>" data-category="<?php echo \Config::CAMERA_SAMPLES_CATEGORY; ?>"  src="<?php echo $images[$i]; ?>">
                    </div>
                <?php } ?>
            </div>
        </div>
        <div id="screen_shots">
            <div class="samples_images_panel">
                <?php
                $images = $galleries['albums']['Screenshots'];
                for($i=0;$i<3;$i++){ ?>
                    <div class="img_dv" data-id="<?php echo $id; ?>" data-index="<?php echo $i; ?>" data-catId="<?php echo $catId ?>" data-category="<?php echo \Config::SCREEN_SHOTS_CATEGORY; ?>" onclick="_gaq.push(['_trackEvent','detail-gallery-spec','screenshots_thumb','<?php echo $galleries["pro_name"];?>::<?php echo $id; ?>::<?php echo $catId  ?>',1,false]);">
                        <img data-id="<?php echo $id; ?>" data-index="<?php echo $i; ?>" data-catId="<?php echo $catId ?>" data-category="<?php echo \Config::SCREEN_SHOTS_CATEGORY; ?>" src="<?php echo $images[$i]; ?>">
                    </div>
                <?php } ?>
            </div>
        </div>
        <div id="benchmarks">
            <div class="samples_images_panel">
                <?php
                $images = $galleries['albums']['Benchmarks'];
                for($i=0;$i<3;$i++){ ?>
                    <div class="img_dv" data-id="<?php echo $id; ?>" data-index="<?php echo $i; ?>" data-catId="<?php echo $catId ?>" data-category="<?php echo \Config::BENCHMARK_CATEGORY; ?>" onclick="_gaq.push(['_trackEvent','detail-gallery-spec','benchmarks_thumb','<?php echo $galleries["pro_name"];?>::<?php echo $id; ?>::<?php echo $catId  ?>',1,false]);">
                        <img data-id="<?php echo $id; ?>" data-index="<?php echo $i; ?>" data-catId="<?php echo $catId ?>" data-category="<?php echo \Config::BENCHMARK_CATEGORY; ?>" src="<?php echo $images[$i]; ?>">
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
<?php }else{ ?>
    <div id="albums">
        <div id="camera_samples">
            <div class="samples_images_panel">
                <?php
                $images = $galleries['albums']['Camera Samples'];
                for($i=0;$i<count($images);$i++){ ?>
                    <div class="sample_imgs" data-id="<?php echo $id; ?>" data-index="<?php echo $i; ?>" data-catId="<?php echo $catId ?>" data-category="<?php echo \Config::CAMERA_SAMPLES_CATEGORY; ?>" onclick="_gaq.push(['_trackEvent','detail-gallery-spec','camerasamples_thumb','<?php echo $galleries["pro_name"];?>::<?php echo $id; ?>::<?php echo $catId  ?>',1,false]);">
                        <img class="thumb_img"  data-id="<?php echo $id; ?>" data-index="<?php echo $i; ?>" data-catId="<?php echo $catId ?>" data-category="<?php echo \Config::CAMERA_SAMPLES_CATEGORY; ?>"  src="<?php echo $images[$i]; ?>">
                    </div>
                <?php } ?>
            </div>
        </div>
        <div id="screen_shots">
            <div class="samples_images_panel">
                <?php
                $images = $galleries['albums']['Screenshots'];
                for($i=0;$i<count($images);$i++){ ?>
                    <div class="sample_imgs" data-id="<?php echo $id; ?>" data-index="<?php echo $i; ?>" data-catId="<?php echo $catId ?>" data-category="<?php echo \Config::SCREEN_SHOTS_CATEGORY; ?>" onclick="_gaq.push(['_trackEvent','detail-gallery-spec','screenshots_thumb','<?php echo $galleries["pro_name"];?>::<?php echo $id; ?>::<?php echo $catId  ?>',1,false]);">
                        <img class="thumb_img" data-id="<?php echo $id; ?>" data-index="<?php echo $i; ?>" data-catId="<?php echo $catId ?>" data-category="<?php echo \Config::SCREEN_SHOTS_CATEGORY; ?>" src="<?php echo $images[$i]; ?>">
                    </div>
                <?php } ?>
            </div>
        </div>
        <div id="benchmarks">
            <div class="samples_images_panel">
                <?php
                $images = $galleries['albums']['Benchmarks'];
                for($i=0;$i<count($images);$i++){ ?>
                    <div class="sample_imgs" data-id="<?php echo $id; ?>" data-index="<?php echo $i; ?>" data-catId="<?php echo $catId ?>" data-category="<?php echo \Config::BENCHMARK_CATEGORY; ?>" onclick="_gaq.push(['_trackEvent','detail-gallery-spec','benchmarks_thumb','<?php echo $galleries["pro_name"];?>::<?php echo $id; ?>::<?php echo $catId  ?>',1,false]);">
                        <img class="thumb_img" data-id="<?php echo $id; ?>" data-index="<?php echo $i; ?>" data-catId="<?php echo $catId ?>" data-category="<?php echo \Config::BENCHMARK_CATEGORY; ?>" src="<?php echo $images[$i]; ?>">
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
<?php } ?>