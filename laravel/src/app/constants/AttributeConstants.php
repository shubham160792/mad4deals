<?php
/**
 * Created by PhpStorm.
 * User: balwant
 * Date: 30/7/15
 * Time: 12:42 PM
 */

namespace App\constants;


class AttributeConstants {
    const START = 0;
    const BATCHSIZE = 10;
    const VIDEO = 'video';
    const SUNDAY_THIS_WEEK ='sunday this week';
    const SUNDAY_LAST_WEEK ='sunday last week';
    const MONDAY_THIS_WEEK ='monday this week';
    const MONDAY_LAST_WEEK ='monday last week';
    const VIEW360_IMAGE_COUNT = 'view360_image_count';
    const CAMERA_SAMPLES = 'camera_samples';
    const SCREENSHOTS = 'screenshots';
    const BENCHMARKS = 'benchmarks';
    const SLIDESHARE_REVIEW_URL = 'slideshare_review_url';
    public static $PROD_ATTR_ARRAY = array('benchmarks','camera_samples','screenshots','view360_image_count','slideshare_review_url');
} 