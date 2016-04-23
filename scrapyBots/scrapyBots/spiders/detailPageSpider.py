"""
GENERIC_SPIDER crawls any website at any depth level where data is not coming by ajax
"""
#All required libraries imported below

import scrapy
from scrapy.spiders import CrawlSpider, Rule
from scrapy.selector import HtmlXPathSelector
from scrapy.http.request import Request
from scrapyBots.items import ScrapybotsItem
from scrapy.signalmanager import SignalManager
from scrapy.xlib.pydispatch import dispatcher
from scrapy import signals
from Utils.configSpider import *
import logging
import os
import time
import datetime
from Utils.uploadToS3Utils import closed_handler

#get current system date as per indian timezone
os.environ['TZ'] = TIMEZONE
date = str(datetime.date.today())
timestamp =int(time.time() )
timestamp=str(timestamp)

# Checks whether Scrapy's Log Directory exists or not

log_directory=SCRAPY_LOGS_DIRECTORY+DIRECTORY_SEPARATOR+date+DIRECTORY_SEPARATOR
if not os.path.exists(log_directory):
    os.makedirs(log_directory)

if os.path.isdir(log_directory):
    logging.basicConfig(filename=log_directory+SPIDER_LOG_FILE+"-"+timestamp)
else:
    print "Default log directory doesn't exists.Please create log directory to store scrapy logs.Directory name which needs to be created = "+SCRAPY_LOGS_DIRECTORY
    exit()


# Main Spider class file starts below
class DetailPageSpider(CrawlSpider):  # inherits from CrawlSpider

    name                         = DETAIL_PAGE_SPIDER   #Name of Spider
    #Rules                       = (Rule (SgmlLinkExtractor(allow=('Real',)), callback="parse", follow= True),)

    #parameters which are required as command line arguments are "siteName" and "depth"
    #Below is the function to take above parameters mentioned in above commnent as command line arguments
    def __init__(self, *args, **kwargs):
        super(DetailPageSpider, self).__init__(*args, **kwargs)
        SignalManager(dispatcher.Any).connect(
            closed_handler, signal=signals.spider_closed)
        try:

            # siteName is command line argument used to identify site specific crawling based on site specific configuration dictionary defined in Config File.
            self.siteName = [kwargs.get(SPIDER_CONFIGURATION_NAME)]
            if self.siteName    != [None]: #check if siteName is Not Empty
                self.siteName    = ''.join(self.siteName)

                #Returns site specific dictionary defined in configuration file on the basis of siteName passed as command line argument
                self.siteName    = eval(self.siteName)

                # ALLOWED_DOMAINS Key in dictionary is use to help the spider to identify which domains are allowed.This is optional parameter
                if ALLOWED_DOMAINS in self.siteName.keys():
                    logging.info(ALLOWED_DOMAINS+" key is present in dictionary")
                    self.allowed_domains=self.siteName[ALLOWED_DOMAINS]
                else:
                    logging.warning(ALLOWED_DOMAINS+" key is not present in dictionary")
                    self.allowed_domains=[]


                # DOMAIN_NAME Key in dictionary is use to specify DOMAIN_NAME of site which will be crawled by spider.
                if DOMAIN_NAME not in self.siteName.keys():
                    logging.error(DOMAIN_NAME+" KEY NOT FOUND in Site Specific Dictionary Defined in Configuration.Hence Exiting!")
                    print DOMAIN_NAME+" KEY NOT FOUND in Site Specific Dictionary Defined in Configuration.Hence Exiting!"
                    exit()

            else:
                logging.error("siteName not provided as command line argument.Hence Exiting!")
                print "siteName not provided as command line argument.Hence Exiting!"
                exit()
        except:
            logging.error("Exception in getting essential spider configuration as per siteName provided. Hence exiting!")
            print "Exception in getting essential spider configuration as per siteName provided. Hence exiting!"
            exit()

        try:
            self.start_urls             = kwargs.get('pageUrl').split(',')
        except:
            logging.warn("Unable to get pageUrl !")
            print "Unable to get pageUrl !"



    # Initially parse Function is called by spiders after getting page response from START_URLS
    def parse(self, response):

        # hxs variable saves html response of webpage
        hxs = HtmlXPathSelector(response)
        item = ScrapybotsItem()
        current_depth = 2

        # responseFromUrl variable saves html response of whole listing block
        try:
            responseFromUrl = hxs.xpath(self.siteName[1][LISTING_BLOCK_XPATH])
        except:
            logging.error("Error in getting html response from xpath= "+LISTING_BLOCK_XPATH+" for depth-1")
            print "Error in getting html response from xpath= "+LISTING_BLOCK_XPATH+" for depth-1"
            exit()

        #print current_depth
        #print item
        # Check is performed whether LISTING_BLOCK_XPATH Field is present at current depth
        if self.siteName[current_depth][FIELDS] != [None]:

            # Iterating over loop to get predefind field values crawling at current level
            for Key,Value in (self.siteName[current_depth][FIELDS]).iteritems():
                try:
                    #print "Key= "+Key+" and Value= "+Value
                    # Check is performed in order to prevent NEXT_DEPTH_PAGE_URL field to store in item array
                    item[Key] = hxs.xpath(Value).extract()
                except:
                    logging.error("Index Error Occurred for Key= "+Key+" Value= "+Value)
            yield item
        else:
            yield item