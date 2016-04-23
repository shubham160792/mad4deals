"""
GENERIC_SPIDER crawls any website where data is not coming by ajax
"""

import scrapy
from scrapy.spiders import CrawlSpider, Rule
from scrapy.selector import HtmlXPathSelector
from scrapy.http.request import Request
from scrapy.signalmanager import SignalManager
from scrapy.xlib.pydispatch import dispatcher
from scrapy import signals
from scrapyBots.items import ScrapybotsItem
from Utils.configSpider import *
import logging
import datetime
import os
import json
import time
from Utils.uploadToS3Utils import closed_handler

#get current system date as per timezone
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

# Main spider class
class ScrapingApiSpider(CrawlSpider): #inherits from CrawlSpider class

    name                         = WEB_SCRAPING_API_SPIDER  #Name of Spider defined here
    #Rules                       = (Rule (SgmlLinkExtractor(allow=('Real',)), callback="parse", follow= True),)

    #parameters which are required as command line arguments are "siteName","siteXpath" and "detailXpath(optional)"
    #Below is the function to take above parameters mentioned in above commnent through command line
    def __init__(self, *args, **kwargs):
        super(ScrapingApiSpider, self).__init__(*args, **kwargs)
        SignalManager(dispatcher.Any).connect(
            closed_handler, signal=signals.spider_closed)
        try:
            # siteName is command line argument used to identify site specific crawling based on site specific configuration variables defined in Config File.
            self.siteName = [kwargs.get(SPIDER_CONFIGURATION_NAME)]
            if self.siteName    != [None]: #check if siteName is Not Empty
                self.siteName    = ''.join(self.siteName)


                #Returns site specific dictionary defined in configuration file on the basis of siteName passed as command line argument
                self.siteName    = eval(self.siteName)

                # ALLOWED_DOMAINS Key in dictionary is use to help the spider to identify which domains are allowed.This is optional parameter
                if ALLOWED_DOMAINS in self.siteName.keys():
                    logging.info(ALLOWED_DOMAINS+" key is present")
                    self.allowed_domains=self.siteName[ALLOWED_DOMAINS]
                else:
                    logging.warning(ALLOWED_DOMAINS+" key is not present")
                    self.allowed_domains=[]

                # DOMAIN_NAME Key in dictionary is use to specify DOMAIN_NAME of site which is crawled by spiders.
                if DOMAIN_NAME not in self.siteName.keys():
                    logging.error(DOMAIN_NAME+" KEY NOT FOUND in Site Specific Dictionary Defined in Configuration.Hence Exiting!")
                    print DOMAIN_NAME+" KEY NOT FOUND in Site Specific Dictionary Defined in Configuration.Hence Exiting!"
                    exit()
            else:
                print "siteName not provided as command line argument.Hence Exiting!"
                exit()
        except:
            logging.error("Unable to get siteName hence exiting!")
            print "Unable to get siteName hence exiting!"
            exit()

        try:
            self.batch             = [kwargs.get('batch')]
            self.batch             = ''.join(self.batch)
        except:
            logging.warn("Unable to get Batch name!")
            print "Unable to get Batch name!"


        if self.batch !=[None] and URL not in self.siteName[self.batch].keys():
            logging.error(URL+"  NOT FOUND in Site Specific Dictionary for batch.Hence Exiting!")
            print URL+"  NOT FOUND in Site Specific Dictionary for batch.Hence Exiting!"
            exit()



        # START_URLS Key is use to help the spider to provide "SEED URLS" or "CRAWL FRONTINERS" to start crawling.
        if self.siteName[MODE]==START_URLS:
            logging.info(START_URLS+" key is present")
            self.start_urls = [self.siteName[DOMAIN_NAME]+i for i in self.siteName[self.batch][START_URLS] if "http" not in i]
            self.start_urls.extend(i for i in self.siteName[self.batch][START_URLS] if "http" in i)
        elif self.siteName[MODE]==START_URLS_WITH_LOOP:
            self.start_urls=[]
            for i in range(self.siteName[self.batch][START],self.siteName[self.batch][END]+1):
                #actual url is identified from url pattern
                url=self.siteName[self.batch][URL].format(self.siteName[self.batch][BATCH_ID],i)
                #pushing actual url to start_urls array by getting actual url from url pattern
                self.start_urls.append(url)
        else:
            logging.error(START_URLS+" KEY NOT FOUND in Site Specific Dictionary.Hence Exiting!")
            print START_URLS+" KEY NOT FOUND in Site Specific Dictionary.Hence Exiting!"
            exit()

        if self.siteName == [None]:
            logging.error("SiteName not specified")
            print "SiteName not specified "
            exit()


        try:
            self.batch             = [kwargs.get('batch')]
            self.batch             = ''.join(self.batch)
        except:
            logging.warn("Unable to get Batch name!")
            print "Unable to get Batch name!"


        if self.batch !=[None] and URL not in self.siteName[self.batch].keys():
            logging.error(URL+"  NOT FOUND in Site Specific Dictionary for batch.Hence Exiting!")
            print URL+"  NOT FOUND in Site Specific Dictionary for batch.Hence Exiting!"
            exit()

        if RESULT_SET not in self.siteName[2].keys():
            logging.error(RESULT_SET+" Key Not Found in Site Specific Dictionary.Hence Exiting!")
            print RESULT_SET+" Key Not Found in Site Specific Dictionary.Hence Exiting!"
            exit()
        if FIELDS not in self.siteName[2].keys():
            logging.error(FIELDS+" Key Not Found in Site Specific Dictionary.Hence Exiting!")
            print FIELDS+" Key Not Found in Site Specific Dictionary.Hence Exiting!"
            exit()


    # Parse Function is called by spiders after getting page response
    def parse(self, response):
        # hxs variable saves html response of webpage
        hxs = HtmlXPathSelector(response)

        # responseFromUrl variable saves html response of whole listing block
        responseFromUrl = hxs.xpath(self.siteName[1][LISTING_BLOCK_XPATH])

        # Iterating over individual sections of listing block html
        for responseBlock in responseFromUrl:
            item = ScrapybotsItem()
            api_url_raw=[None]
            for Key,Value in (self.siteName[1][FIELDS]).iteritems():
                try:
                    #print "Key= "+Key+" Value= "+Value

                    # Site specific fields defined in configuration file are fetched and saved in item array
                    if Key != NEXT_DEPTH_PAGE_URL:
                        item[Key] = ''.join([i.strip('\r\n\t') for i in responseBlock.xpath(Value).extract()])
                    else:
                        api_url_raw=self.siteName[1][FIELDS][NEXT_DEPTH_PAGE_URL]
                    #item[Key] = responseBlock.xpath(Value).extract()
                except IndexError:
                    item[Key] = "No field value found on current index"
            if api_url_raw != [None] and NEXT_DEPTH_PAGE_URL in self.siteName[1][FIELDS]:
                try:
                    request_id = item[LISTING_ID].strip('chatNow_adT')
                    item[LISTING_ID]=request_id
                    api_request_url=api_url_raw.format(request_id)
                    yield scrapy.Request(api_request_url, meta={'item':item}, callback=self.parse_api)
                except:
                    logging.error("Index Error in getting details from API")
                    print "Index Error in getting details from API"
            else:
                yield item
        if NEXT_PAGE_URL_XPATH in self.siteName[1]:

            #URL join is performed in order to crawl next page url by spider
            next_page_raw_url=''.join(hxs.xpath(self.siteName[1][NEXT_PAGE_URL_XPATH]).extract())

            #checks whether next_page_url is valid or not
            if 'http' in next_page_raw_url:
                next_page_url = next_page_raw_url
            else:
                next_page_url = self.siteName[DOMAIN_NAME]+next_page_raw_url

            #check if next page url exists and valid
            if 'http' in next_page_url:
                # next page url request is performed by the spider
                yield Request(next_page_url, self.parse)
            else:
                logging.warn("Next page url is not valid")
                print "Next page url is not valid"
        else:
            logging.warn("Next page url does not exists.Hence exiting!")
            print "Next page url does not exists.Hence exiting!"



    # parse_detail function is called for crawling data from listing detail pages
    def parse_api(self,response):
        item = response.meta['item']
        responseFromAPI    =  json.loads(response.body_as_unicode())
        for responseBlock in responseFromAPI['response']['ads'][self.siteName[2][RESULT_SET]]:
            for Key, Value in self.siteName[2][FIELDS].iteritems():
                if isinstance(self.siteName[2][FIELDS][Key],dict):
                    for Keys,Values in self.siteName[2][FIELDS][Key].iteritems():
                        if Values in responseBlock[Key]:
                            item[Keys]=responseBlock[Key][Values]
                        else:
                            item[Keys]="NA"
                elif Value in responseBlock:
                    item[Key] = responseBlock[Value]
                else:
                    item[Key] = "NA"
        return item
