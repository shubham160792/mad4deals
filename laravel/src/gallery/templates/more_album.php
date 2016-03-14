<?php header('access-control-allow-origin: *');?>
<?php
include_once('../get_galleries.php');
$imageIndex = $_REQUEST['image_index'];
?>
<div class='wrap' >
    <div class='onerow topRow' id="image_view">
        <div class='col12 tab col' >
            <div id="img_view_wrap_inner">
                <div class="grid">
                    <?php if(count($galleries["galleries"][0]['all_images']) > 0 && count($galleries["galleries"][0]['all_images']) > $imageIndex) { ?>
                        <?php for($i=0;$i<count($galleries["galleries"]);$i++) {
                            ?>
                            <div class="col-1-4-album">
                                <span><?php if(strtolower($galleries["galleries"][$i]['gl_cat_name']) == 'slide share') echo "Review in Pictures"; else echo $galleries["galleries"][$i]['gl_cat_name']; ?></span>
                                <div data-id="<?php echo $galleries['id']; ?>" data-category="<?php echo strtolower($galleries["galleries"][$i]['gl_cat_name']); ?>" class="inner_img <?php if (strtolower($galleries["galleries"][$i]['gl_cat_name']) == $_REQUEST['category']) echo 'selected_category'; ?>"
                                     onclick="cat_select(this)">
                                    <?php if (strtolower($galleries["galleries"][$i]['gl_cat_name']) != 'videos' && strtolower($galleries["galleries"][$i]['gl_cat_name']) != 'view 360' && strtolower($galleries["galleries"][$i]['gl_cat_name']) != 'slide share') { ?>
                                        <img data-id="<?php echo $galleries['id']; ?>" data-category="<?php echo strtolower($galleries["galleries"][$i]['gl_cat_name']); ?>"  src="<?php echo $galleries["galleries"][$i]['all_images'][0]['small']; ?>"
                                             class="sub_img_album">
                                    <?php
                                    }elseif(strtolower($galleries["galleries"][$i]['gl_cat_name']) == 'view 360' || strtolower($galleries["galleries"][$i]['gl_cat_name']) == 'slide share'){
                                        ?>
                                        <img data-id="<?php echo $galleries['id']; ?>" data-category="<?php echo strtolower($galleries["galleries"][$i]['gl_cat_name']); ?>"  src="<?php echo $galleries["galleries"][$i]['all_videos'][0]['small']; ?>"
                                             class="sub_img_album">
                                    <?php
                                    }else{
                                        ?>
                                        <img data-id="<?php echo $galleries['id']; ?>" data-category="<?php echo strtolower($galleries["galleries"][$i]['gl_cat_name']); ?>"  src="<?php echo $galleries["galleries"][$i]['all_videos'][0]['small']; ?>"
                                             class="sub_img_album">
                                    <?php } ?>
                                </div>
                            </div>
                        <?php } ?>
                    <?php }else{
                        ?>
                    <?php
                    } ?>
                </div>
            </div>
        </div>
        <div id='closeOverlay'>✕</div>
    </div>
</div>