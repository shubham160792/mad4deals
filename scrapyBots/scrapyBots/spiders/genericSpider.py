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
class GenericSpiderClass(CrawlSpider):  # inherits from CrawlSpider

    name                         = GENERIC_SPIDER   #Name of Spider
    #Rules                       = (Rule (SgmlLinkExtractor(allow=('Real',)), callback="parse", follow= True),)

    #parameters which are required as command line arguments are "siteName" and "depth"
    #Below is the function to take above parameters mentioned in above commnent as command line arguments
    def __init__(self, *args, **kwargs):
        super(GenericSpiderClass, self).__init__(*args, **kwargs)
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
            self.depth             = [kwargs.get('depth')]
            if self.depth         != [None]:
                self.depth         = ''.join(self.depth)
                self.depth         = eval(self.depth)

                # validation for depth parameters are done below
                if not isinstance( self.depth,int):
                    logging.error("CRAWLING DEPTH VALUE MUST BE POSITIVE INTEGER")
                    print "CRAWLING DEPTH VALUE MUST BE POSITIVE INTEGER"
                    exit()
                elif self.depth < 1:
                    logging.error("CRAWLING DEPTH MUST BE GREATER THAN OR EQUAL TO 1")
                    print "CRAWLING DEPTH MUST BE GREATER THAN OR EQUAL TO 1"
                    exit()
                else:
                    for i in range(1,self.depth+1):
                        if i not in self.siteName:
                            logging.error("Configuration settings at depth= "+i+" are not found in site specific dictionary")
                            print "Configuration settings at current depth= "+i+" are not found in site specific dictionary"
                            exit()
                        elif FIELDS not in self.siteName[i]:
                            logging.error(FIELDS+" Key not found at depth= "+i+" level.hence exiting!")
                            print FIELDS+" Key not found at depth= "+i+" level.hence exiting!"
                            exit()
            else:
                logging.error("Crawling Depth parameter must be provided in order to run spider.Hence exiting!")
                print "Crawling Depth parameter must be provided in order to run spider.Hence exiting!"
                exit()

        except:
            logging.error("Exception in getting depth related settings from site specific dictionary.Hence exiting!")
            print "Exception in getting depth related settings from site specific dictionary.Hence exiting!"
            exit()
        try:
            self.batch             = [kwargs.get('batch')]
            self.batch             = ''.join(self.batch)
        except:
            logging.warn("Unable to get Batch name!")
            print "Unable to get Batch name!"


        if self.siteName == [None] or self.depth == [None] or self.batch == [None]:
            logging.error("Either SiteName not specified || Crawling Depth not specified || Batch name not provided.")
            print "Either SiteName not specified || Crawling Depth not specified || Batch name not provided."
            exit()

        # START_URLS Key is use to help the spider to provide "SEED URLS" or "CRAWL FRONTINERS" to start crawling.
        if self.siteName[MODE] == START_URLS:
            logging.info(START_URLS+" key is present")

            # check is performed below in order to check whether domain name is present in start_urls or not

            #if domain name is not present then prepending domain name to start_urls in which domain name is not present
            self.start_urls = [self.siteName[DOMAIN_NAME]+i for i in self.siteName[self.batch][START_URLS] if "http" not in i]

            #if domain name is already present in urls then appending those urls in start_urls array without prepending them with domain name.
            self.start_urls.extend(i for i in self.siteName[self.batch][START_URLS] if "http" in i)
            #self.start_urls = self.siteName[START_URLS]

        #checks whether start_urls is provided with some pattern on which loop can be performed in order to get all start_urls
        elif self.siteName[MODE] == START_URLS_WITH_LOOP:
            self.start_urls=[]
            if STEP not in self.siteName[self.batch]:
                for i in range(self.siteName[self.batch][START],self.siteName[self.batch][END]+1):
                    #actual url is identified from url pattern
                    url=self.siteName[URL].format(self.siteName[self.batch][BATCH_ID],i)
                    #pushing actual url to start_urls array by getting actual url from url pattern
                    if 'http' not in url:
                        url=self.siteName[DOMAIN_NAME]+url
                    self.start_urls.append(url)
            else:
                for i in range(self.siteName[self.batch][START],self.siteName[self.batch][END]+1,self.siteName[self.batch][STEP]):
                    #actual url is identified from url pattern
                    url=self.siteName[URL].format(self.siteName[self.batch][BATCH_ID],i)
                    #pushing actual url to start_urls array by getting actual url from url pattern
                    if 'http' not in url:
                        url=self.siteName[DOMAIN_NAME]+url
                    self.start_urls.append(url)
            print self.start_urls
            #time.sleep(20)
        else:
            logging.error("Neither '"+START_URLS+"' nor '"+START_URLS_WITH_LOOP+"' Keys are found in Site Specific Dictionary as per the siteName provided.Hence Exiting!")
            print "Neither '"+START_URLS+"' nor '"+START_URLS_WITH_LOOP+"' Keys are found in Site Specific Dictionary as per the siteName provided.Hence Exiting!"
            exit()

    # Initially parse Function is called by spiders after getting page response from START_URLS
    def parse(self, response):

        # hxs variable saves html response of webpage
        hxs = HtmlXPathSelector(response)
        # responseFromUrl variable saves html response of whole listing block
        try:
            responseFromUrl = hxs.xpath(self.siteName[1][LISTING_BLOCK_XPATH])
        except:
            logging.error("Error in getting html response from xpath= "+LISTING_BLOCK_XPATH+" for depth-1")
            print "Error in getting html response from xpath= "+LISTING_BLOCK_XPATH+" for depth-1"
            exit()

        # Iterating over individual sections of listing block html
        for responseBlock in responseFromUrl:
            item = ScrapybotsItem()
            for Key,Value in (self.siteName[1][FIELDS]).iteritems():
                try:

                    #print "Key= "+Key+" Value= "+Value
                    # Site specific fields defined in configuration file are fetched and saved in item array
                    if Key != NEXT_DEPTH_PAGE_URL:
                        #item[Key] = ''.join([i.strip(' \r\n\t') for i in responseBlock.xpath(Value).extract()])
                        item[Key] = responseBlock.xpath(Value).extract()
                    else:
                        next_depth_page_raw_url=''.join(responseBlock.xpath(Value).extract()[0])
                        #print next_depth_page_raw_url
                except:
                    item[Key] = "NA"
            if NEXT_DEPTH_PAGE_URL in self.siteName[1][FIELDS] and next_depth_page_raw_url != '':
                try:

                    # Check if domain already exist in detail page url
                    if 'http' in next_depth_page_raw_url:
                        next_depth_page_url   = next_depth_page_raw_url
                    else:
                        next_depth_page_url   = self.siteName[DOMAIN_NAME]+next_depth_page_raw_url
                    #print next_depth_page_url
                    yield scrapy.Request(next_depth_page_url, meta={'item':item,'next_depth':2},callback=self.parse_depth)
                except:
                    logging.error("Index Error in getting depth level 2 page url")
                    print "Index error in getting depth level 2 page url "
                    exit()
            else:
                yield item
        #URL join is performed in order to crawl next page url by spider
        if NEXT_PAGE_URL_XPATH in self.siteName[1]:

            next_page_raw_url=''.join(hxs.xpath(self.siteName[1][NEXT_PAGE_URL_XPATH]).extract()[0])
            if 'http' in next_page_raw_url:
                next_page_url = next_page_raw_url
            else:
                next_page_url = self.siteName[DOMAIN_NAME]+next_page_raw_url

            #check if next page url valid or not
            if 'http' in next_page_url:

                # next page url request is performed by the spider here
                yield Request(next_page_url, self.parse)
            else:
                logging.warn("NEXT_PAGE_URL not found from xpath provided")
                print "NEXT_PAGE_URL not found from xpath provided"

        else:
            logging.warn("NEXT_PAGE_URL_XPATH key not present at depth level 1")
            print "NEXT_PAGE_URL_XPATH key not present at depth level 1"

    # parse_detail function is called for crawling data from listing detail pages
    def parse_depth(self,response):
        item = response.meta['item']
        current_depth = response.meta['next_depth']

        #print current_depth
        #print item
        # Check is performed whether LISTING_BLOCK_XPATH Field is present at current depth
        if LISTING_BLOCK_XPATH in self.siteName[current_depth]:

            responseFromUrl = response.xpath(self.siteName[current_depth][LISTING_BLOCK_XPATH]).extract()

            for responseBlock in responseFromUrl:

                for Key,Value in (self.siteName[current_depth][FIELDS]).iteritems():
                    try:

                        # print "Key= "+Key+" and Value= "+Value
                        # Site specific fields defined in configuration file are fetched from detail pages and saved in item array
                        if Key != NEXT_DEPTH_PAGE_URL and current_depth <= self.depth:
                            #item[Key] = ''.join([i.strip(' \r\n\t') for i in responseBlock.xpath(Value).extract()])
                            item[Key] = responseBlock.xpath(Value).extract()
                        else:
                            next_depth_page_raw_url=''.join(responseBlock.xpath(Value).extract())
                    except:
                        logging.error("Index Error Occurred for Key= "+Key+" Value= "+Value)
                        #item[Key] = "NA"

                if self.siteName[current_depth][FIELDS] != [None] and next_depth_page_raw_url !='':
                    try:
                        # Check if domain already exist in detail page url
                        if 'http' in next_depth_page_raw_url:
                            next_depth_page_url   = next_depth_page_raw_url
                        else:
                            next_depth_page_url   = self.siteName[DOMAIN_NAME]+next_depth_page_raw_url

                        yield scrapy.Request(next_depth_page_url, meta={'item':item,'next_depth':current_depth+1},callback=self.parse_depth)
                    except:
                        logging.error("Index Error in getting next depth page url")
                        print "Index error in getting next depth page url "
                        exit()
                else:
                    yield item

            next_page_raw_url=''.join(response.xpath(self.siteName[current_depth][NEXT_PAGE_URL_XPATH]).extract())
            if next_page_raw_url != '':
                if 'http' in next_page_raw_url:
                    next_page_url = next_page_raw_url
                else:
                    next_page_url = self.siteName[DOMAIN_NAME]+next_page_raw_url

                #check if next page url exists
                if next_page_url:
                    # next page url request is performed by the spider here
                    yield Request(next_page_url, self.parse_depth)
            else:
                logging.warn("next page url not present at depth= "+current_depth)
                print  "next page url not present at depth= "+current_depth

        else:
            # Check if fields are defined which needs to be scraped from current depth
            if self.siteName[current_depth][FIELDS] != [None]:

                # Iterating over loop to get predefind field values crawling at current level
                for Key,Value in (self.siteName[current_depth][FIELDS]).iteritems():
                    try:
                        #print "Key= "+Key+" and Value= "+Value

                        # Check is performed in order to prevent NEXT_DEPTH_PAGE_URL field to store in item array
                        if Key != NEXT_DEPTH_PAGE_URL and current_depth <= self.depth:

                            # Site specific fields defined in configuration file are fetched from current depth level page and saved in item array
                            #item[Key] = ''.join([i.strip(' \r\n\t') for i in response.xpath(Value).extract()])
                            item[Key] = response.xpath(Value).extract()
                        else:

                            # Extracting next_depth_page_url in order to go to next depth level
                            next_depth_page_raw_url=''.join(response.xpath(Value).extract())
                    except:
                        logging.error("Index Error Occurred for Key= "+Key+" Value= "+Value)
                        #exit()


                if current_depth < self.depth and NEXT_DEPTH_PAGE_URL in self.siteName[current_depth][FIELDS] and next_depth_page_raw_url !='':
                    try:
                        # Check if domain already exist in next depth page url
                        if 'http' in next_depth_page_raw_url:
                            next_depth_page_url   = next_depth_page_raw_url
                        else:
                            next_depth_page_url   = self.siteName[DOMAIN_NAME]+next_depth_page_raw_url

                        yield scrapy.Request(next_depth_page_url, meta={'item':item,'next_depth':current_depth+1},callback=self.parse_depth)
                    except:
                        logging.error("Index Error in getting next depth page url")
                        print "Index error in getting next depth page url "
                else:
                    yield item
            else:
                yield item
