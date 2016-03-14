<?php header('access-control-allow-origin: *');?>
<?php
include_once('../get_galleries.php');
$imageIndex = $_REQUEST['image_index'];
$videosAvailable = false;
?>


<div class="bigproductname">
    <span class="detailsnew"><?php echo $galleries["pro_name"]; ?> <font class="upcoming_laptops_subtitle vsmallf">
        </font>
    </span>
</div>
<div class="module-card1">
    <div class="card-body1 multiple-dod1 c-oscroll1">
        <?php
        if(count($galleries["galleries"]) > 1){
            $tabItems = array();
            for ($i = 0; $i < count($galleries["galleries"]); $i++) {
                array_push($tabItems,$galleries["galleries"] );
            }
            $galleryArray = array();
            $sortedTabItems = array();
            foreach ($tabItems[0] as $key => $tab) {

                if(strtolower($tab['gl_cat_name']) == 'design'){
                    $sortedTabItems[0] = $tab;
                }
                if(strtolower($tab['gl_cat_name']) == 'view 360'){
                    $sortedTabItems[1] = $tab;
                }
                if(strtolower($tab['gl_cat_name']) == 'videos'){
                    $sortedTabItems[2] = $tab;
                }
                if(strtolower($tab['gl_cat_name']) == 'camera samples'){
                    $sortedTabItems[3] = $tab;
                }
                if(strtolower($tab['gl_cat_name']) == 'screenshots'){
                    $sortedTabItems[4] = $tab;
                }
                if(strtolower($tab['gl_cat_name']) == 'benchmarks'){
                    $sortedTabItems[5] = $tab;
                }
                if(strtolower($tab['gl_cat_name']) == 'slide share'){
                    $sortedTabItems[6] = $tab;
                }

            }
            ksort($sortedTabItems);
            $aliasArray = $sortedTabItems;

                foreach ($sortedTabItems as $galleryItems){
                if (isset($galleryItems)) {
                    array_push($galleryArray,strtolower($galleryItems['gl_cat_name'] ));
                    ?>
                    <a onclick="cat_select(this);" class="item1 dod-block1 inner_img <?php if (strtolower($galleryItems['gl_cat_name']) == $_REQUEST['category']) echo "active"; ?>"
                       data-id="<?php echo $galleries['id']; ?>" data-catid="<?php echo $galleries['pro_cat_id']; ?>"
                       data-category="<?php echo strtolower($galleryItems['gl_cat_name']); ?>"
                       href="javascript:void(0);"><?php if (strtolower($galleryItems['gl_cat_name']) == 'screenshots') {
                            echo 'UI ' . $galleryItems['gl_cat_name'];
                        } else {
                            echo $galleryItems['gl_cat_name'];
                        } ?></a>
                    <?php
                }
            }
        }
        ?>
    </div>
</div>

<div class="clr"></div>
<div class='wrap' >
    <div class='onerow topRow' id="image_view">
        <div class='col12 tab col' >

            <div id='img_view_wrap_inner' class='thumb'>
                <?php if(count($galleries["galleries"]) > 0) {
                    $more_images = array();
                    for ($i = 0; $i < count($galleries["galleries"]); $i++) {
                        if (strtolower($galleries["galleries"][$i]['gl_cat_name']) == $_REQUEST['category']) {
                            $caption = $galleries["galleries"][$i]['all_images'][$imageIndex]['caption'];
                            for ($j = 0; $j < count($galleries["galleries"][$i]['all_images']); $j++) {
                                $more_images[$j]['url'] = $galleries["galleries"][$i]['all_images'][$j]['large'];
                                $more_images[$j]['caption'] = $galleries["galleries"][$i]['all_images'][$j]['caption'];
                                $more_images[$j]['name'] = $galleries["pro_name"];
                                $more_images[$j]['thumb_url'] = $galleries["galleries"][$i]['all_images'][$j]['thumb'];
                                $more_images[$j]['active'] = '';
                                $more_images[$j]['is_video'] = 0;
                                if ($imageIndex == $j) {
                                    $more_images[$j]['active'] = 'active';
                                }
                            }
                            for ($k = $j, $l = 0; $l < count($galleries["galleries"][$i]['all_videos']); $k++, $l++) {
                                $more_images[$k]['url'] = $galleries["galleries"][$i]['all_videos'][$l]['large'];
                                $more_images[$k]['caption'] = $galleries["galleries"][$i]['all_videos'][$l]['caption'];
                                $more_images[$k]['name'] = $galleries["pro_name"];
                                $more_images[$k]['thumb_url'] = $galleries["galleries"][$i]['all_videos'][$l]['thumb'];
                                $more_images[$k]['active'] = '';
                                $more_images[$k]['is_video'] = 1;
                                if ($imageIndex == $l && $j == 0) {
                                    $more_images[$k]['active'] = 'active';
                                }
                            }
                            ?>
                            <?php if (count($galleries["galleries"][$i]['all_images']) == 0) { ?>
                                <input type='hidden' id='current_url' value='<?php echo $galleries["galleries"][$i]['all_videos'][$imageIndex]['large']; ?>'/>
                                <input type='hidden' id='current_gallery' value='<?php echo $galleries["galleries"][$i]['id'] ?>'/>
                                <img class="main loader" src="http://img.91mobiles.com/image_gallery/gallery/images/713.GIF" style="visibility: visible;">
                                <?php if($_REQUEST['category'] == 'view 360' || $_REQUEST['category'] == 'slide share'){ ?>
                                    <iframe  id='videoIframe'  scrolling="no" style="height: 98%;width: 100%;visibility: hidden;" src='<?php echo str_replace('watch?v=', 'embed/', $galleries["galleries"][$i]['all_videos'][$imageIndex]['large']); ?>'
                                             frameborder='0' allowfullscreen=''></iframe>
                                <?php }else{?>
                                    <iframe  id='videoIframe'  scrolling="no" style="height: 98%;width: 100%;visibility: hidden;" src='<?php echo str_replace('watch?v=', 'embed/', $galleries["galleries"][$i]['all_videos'][$imageIndex]['large']); ?>'
                                             frameborder='0' allowfullscreen=''></iframe>
                                <?php }?>
                                <a class='next'>›</a><a class='prev'>‹</a>
                            <?php } else { ?>
                                <input type='hidden' id='current_url' value='<?php echo $galleries["galleries"][$i]['all_images'][$imageIndex]['large']; ?>'/>
                                <input type='hidden' id='current_gallery' value='<?php echo $galleries["galleries"][$i]['id'] ?>'/>
                                <img class='main' title='<?php echo $galleries["pro_name"]; ?>'
                                     src='<?php echo $galleries["galleries"][$i]['all_images'][$imageIndex]['large']; ?>'/>
                                <a class='next'>›</a><a class='prev'>‹</a>
                            <?php } ?>
                            <?php
                        }
                    }
                }else{
                    ?>
                    <input type='hidden' id='current_url' value='<?php echo $_POST['image_url']; ?>' />
                    <input type='hidden' id='current_gallery' value='<?php echo $_POST['gallery_id']; ?>' />
                    <img class='main' title='<?php echo $_POST['name']; ?>' src='<?php echo $_POST['image_url']; ?>'/>
                    <?php
                }
                ?>
                <div id="caption_line" class="photocaption"><?php if(isset($caption)) echo $caption;else echo $_POST['caption']; ?></div>
                <div class="grid" style="display: none;">
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
                                    <?php }
                                    if (strtolower($galleries["galleries"][$i]['gl_cat_name']) == 'videos') {
                                        $videosAvailable = true;
                                        $videoThumbUrl = $galleries["galleries"][$i]['all_videos'][0]['small'];
                                    }

                                    ?>
                                </div>
                            </div>
                        <?php } ?>
                    <?php }else{
                        ?>
                        <?php
                    } ?>
                </div>
            </div>
            <?php
            if(in_array($_REQUEST['category'], $galleryArray)){
                $count = count($galleryArray);
                $key = array_search($_REQUEST['category'], $galleryArray);
                $categoryNext = $galleryArray[$key+1];
                $nextAlbumThumb = $aliasArray[$key+2]['all_images'][0]['small'];
                if($categoryNext == 'videos' || $categoryNext == 'slide share' || $categoryNext == 'view 360' ) {
                    $nextAlbumThumb = $aliasArray[$key+2]['all_videos'][0]['small'];
                }
                if($_REQUEST['category'] == 'slide share') {
                    $nextAlbumThumb = $aliasArray[0]['all_images'][0]['small'];
                }

                $key = ($key == 0)?$count-1:$key-1 ;
                foreach( $aliasArray as $gallery ){
                    if(strtolower($gallery['gl_cat_name']) == strtolower($galleryArray[$key])){
                        $categoryPrev = strtolower($gallery['gl_cat_name']);
                        if($categoryPrev == 'videos' || $categoryPrev == 'slide share' || $categoryPrev == 'view 360' ) {
                            $prevCount = count($gallery['all_videos'])-1;
                            $prevAlbumThumb = $gallery['all_videos'][$prevCount]['small'];
                        }
                        else {
                            $prevCount = count($gallery['all_images'])-1;
                            $prevAlbumThumb = $gallery['all_images'][$prevCount]['small'];
                        }
                    }
                }



            }
           ?>
            <div class='onerow thumb_nav'>
                <div class='thumb_move_right'></div>
                <div class='thumb_move_left'></div>
                <div class='thumbnail_row'>
                    <div class='img_wrap_link blank_thumb' ></div>
                    <?php if(count($galleries['galleries']) > 1){ ?>

                        <a class="img_wrap_link more_album" data-id="<?php echo $_REQUEST['id']; ?>" data-catId="<?php echo $_REQUEST['catId'];?>" data-category="<?php echo $_REQUEST['category']; ?>">
                            <div  data-id="<?php echo $_REQUEST['id']; ?>" data-catId="<?php echo $_REQUEST['catId']; ?>" data-category="<?php echo $_REQUEST['category'];?>" class="img_wrap " style="background-image:url(<?php echo \Config::viewMoreImage; ?>)"></div>
                        </a>
                        <?php if(isset($categoryPrev) && $_REQUEST['category'] != 'design') {?>
                        <a onclick="cat_select(this);" class="img_wrap_link inner_img prev_image" data-id="<?php echo $_REQUEST['id']; ?>" data-catId="<?php echo $_REQUEST['catId']; ?>" data-category="<?php echo $categoryPrev;?>" data-index="<?php echo $prevCount;?>" style="display: none;">
                            <div  data-id="<?php echo $_REQUEST['id']; ?>" data-catId="<?php echo $_REQUEST['catId']; ?>" data-category="<?php echo $categoryPrev;?>" class="img_wrap" style="background-image:url(<?php echo $prevAlbumThumb; ?>)"></div>
                        </a>
                    <?php } }?>
                    <?php
                    if(isset($more_images) && count($more_images) > 0){
                        foreach ($more_images as $url => $image) {
                            $thumb_url = !empty($image['thumb_url']) ? $image['thumb_url'] : $image['url'];
                            echo "<a  class='img_wrap_link' data-video='".$image['is_video']."' data-url='".$image['url']."' data-info='".json_encode($image)."' href='javascript:void(0);'> <div class='img_wrap ".$image['active']."'><img class='thumb_img' src='$thumb_url'></div></a>";
                        }
                    }else{
                        foreach ($_POST['more_images'] as $url => $image) {
                            $thumb_url = !empty($image['thumb_url']) ? $image['thumb_url'] : $image['url'];
                            echo "<a  class='img_wrap_link' data-url='".$image['url']."' data-info='".json_encode($image)."' href='javascript:void(0);'> <div class='img_wrap ".$image['active']."' style='background-image:url(".$thumb_url.")' ></div></a>";
                        }
                    }
                    ?>


                        <?php if(isset($categoryNext)) {?>

                        <a onclick="cat_select(this);" class="img_wrap_link inner_img" data-id="<?php echo $_REQUEST['id']; ?>" data-catId="<?php echo $_REQUEST['catId'];?>" data-category="<?php echo $categoryNext;?>" style="display: none;">
                            <div  data-id="<?php echo $_REQUEST['id'];?>" data-catId="<?php echo $_REQUEST['catId']; ?>" data-category="<?php echo $categoryNext;?>" class="img_wrap" style="background-image:url(<?php echo $nextAlbumThumb; ?>)"></div>
                        </a>
                       <?php } ?>
                    <div class='img_wrap_link blank_thumb' ></div>
                </div>
            </div>
        </div>
        <div id='closeOverlay'>✕</div>
    </div>
</div>

