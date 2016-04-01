<?php

// Class file containing all spider scheduling configuration constants.
class ConstantUtils {
    const PROJECT_ROOT_DIRECTORY_PATH                              = "/Users/shubham/proptiger/forked/scrapy-bots/scrapyBots/";
    const BUILD_COMMAND_INITIAL                                    = "scrapy crawl ";
    const GENERIC_SPIDER                                           = "GENERIC_SPIDER";
    const AGENT_DETAILS                                            = "AGENT_DETAILS";
    const LISTINGS_DETAILS                                         = "LISTINGS_DETAILS";
    const SOURCE_MAGICBRICKS                                       = "magicbricks.com";
    const SOURCE_NN_ACRES                                          = "99acres.com";
    const SOURCE_OLX                                               = "olx.in";
    const SOURCE_QUIKR                                             = "quikr.com";
    const SOURCE_HOUSING                                           = "housing.com";
    const BUCKET                                                   = "crawled-data-production";
    const FEED_STORAGE_ROOT_DIRECTORY_IN_S3                        = "feeds/";
    const BATCH_INSERTION_SIZE                                     = 5000;
    const DEFAULT_TIMEZONE                                         = 'Asia/Kolkata';

    // constants for scraped_data table.
    const DEFAULT
    public static function getRootDir(){
        return dirname(__DIR__);
    }
} 