"""
GENERIC_SPIDER crawls any website where data is not coming by ajax
"""

import scrapy
from scrapy.spiders import CrawlSpider, Rule
from scrapy.selector import HtmlXPathSelector
from scrapy.http.request import Request
from scrapyBots.items import ScrapybotsItem
from scrapy.signalmanager import SignalManager
from scrapy.xlib.pydispatch import dispatcher
from scrapy import signals
from Utils.configSpider import *
from Utils.uploadToS3Utils import closed_handler
import logging
import datetime
import os
import time

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

# Main spider class
class GenericSpiderForCatalogAndDetailPagesClass(CrawlSpider):

    name                         = SPIDER_FOR_CATALOG_AND_DETAIL_PAGES   #Name of Spider defined here
    #Rules                       = (Rule (SgmlLinkExtractor(allow=('Real',)), callback="parse", follow= True),)

    #parameters which are required as command line arguments are "siteName","siteXpath" and "detailXpath(optional)"
    #Below is the function to take above parameters mentioned in above commnent through command line
    def __init__(self, *args, **kwargs):
        super(GenericSpiderForCatalogAndDetailPagesClass, self).__init__(*args, **kwargs)
        #SignalManager(dispatcher.Any).connect(
        #    closed_handler, signal=signals.spider_closed)
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

                # START_URLS Key is use to help the spider to provide "SEED URLS" or "CRAWL FRONTINERS" to start crawling.
                if START_URLS in self.siteName.keys():
                    logging.info(START_URLS+" key is present")
                    self.start_urls = self.siteName[START_URLS]
                    self.start_urls = [self.siteName[DOMAIN_NAME]+i for i in self.siteName[START_URLS] if "http" not in i]
                    self.start_urls.extend(i for i in self.siteName[START_URLS] if "http" in i)
                else:
                    logging.error(START_URLS+" KEY NOT FOUND in Site Specific Dictionary.Hence Exiting!")
                    print START_URLS+" KEY NOT FOUND in Site Specific Dictionary.Hence Exiting!"
                    exit()



                # # LISTING_NEXT_PAGE_URL_XPATH Key in dictionary helps spider to jump to next catalog page for crawling
                # if NEXT_PAGE_URL_XPATH not in self.siteName[1][FIELDS].keys():
                #     logging.error(NEXT_PAGE_URL_XPATH+" KEY NOT FOUND in Site Specific Dictionary Defined in Configuration.Hence Exiting!")
                #     print NEXT_PAGE_URL_XPATH+" KEY NOT FOUND in Site Specific Dictionary Defined in Configuration.Hence Exiting!"
                #     exit()
                #
                # # LISTING_BLOCK_XPATH Key in dictionary helps spider to extract block of information from catalog pages.
                # if LISTING_BLOCK_XPATH not in self.siteName[1][FIELDS].keys():
                #     logging.error(LISTING_BLOCK_XPATH+" KEY NOT FOUND in Site Specific Dictionary Defined in Configuration.Hence Exiting!")
                #     print LISTING_BLOCK_XPATH+" KEY NOT FOUND in Site Specific Dictionary Defined in Configuration.Hence Exiting!"
                #     exit()

            else:
                print "siteName not provided as command line argument.Hence Exiting!"
                exit()
        except:
            logging.error("Unable to get siteName hence exiting!")
            print "Unable to get siteName hence exiting!"
            exit()

        if self.siteName == [None]:
            logging.error("Either SiteName not specified")
            print "Either SiteName not specified "
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
            next_depth_page_url_xpath=None
            for Key,Value in (self.siteName[1][FIELDS]).iteritems():
                try:
                    #print "Key= "+Key+" Value= "+Value

                    # Site specific fields defined in configuration file are fetched and saved in item array
                    if Key != NEXT_DEPTH_PAGE_URL:
                        item[Key] = [i.strip('\r\n\t') for i in responseBlock.xpath(Value).extract()]
                    else:
                        next_depth_page_url_xpath=responseBlock.xpath(Value).extract()
                    #item[Key] = responseBlock.xpath(Value).extract()
                except IndexError:
                    item[Key] = "No field value found on current index"
            if next_depth_page_url_xpath != [None] and NEXT_DEPTH_PAGE_URL in self.siteName[1][FIELDS]:
                try:
                    # Check if domain already exist in detail page url
                    if 'http' in next_depth_page_url_xpath[0]:
                        detail_page_url   = next_depth_page_url_xpath[0]
                    else:
                        detail_page_url   = self.siteName[DOMAIN_NAME]+next_depth_page_url_xpath[0]
                except:
                    logging.error("Index Error in getting detail page")
                    print "Index error in detail_page_url"
                    exit()
                if 'http' in detail_page_url:
                    yield scrapy.Request(detail_page_url, meta={'item':item}, callback=self.parse_detail)
                else:
                    logging.error("Not valid detail page url.URL= "+detail_page_url)
                    print "Not valid detail page url.URL= "+detail_page_url
                    exit()
            else:
                yield item

        #URL join is performed in order to crawl next page url by spider
        try:
            next_page_url_xpath=hxs.xpath(self.siteName[1][NEXT_PAGE_URL_XPATH]).extract()[0]
            if 'http' in next_page_url_xpath:
                next_page_url = next_page_url_xpath
            else:
                next_page_url = self.siteName[DOMAIN_NAME]+next_page_url_xpath
        except:
            logging.error("Index List out of range for next_page_url_field")
            print "Index List out of range"
            exit()

        #check if next page url exists and valid
        if 'http' in next_page_url:

            # next page url request is performed by the spider here
            yield Request(next_page_url, self.parse)

    # parse_detail function is called for crawling data from listing detail pages
    def parse_detail(self,response):
        item = response.meta['item']
        for Key,Value in (self.siteName[2][FIELDS]).iteritems():
                try:

                    # Site specific fields defined in configuration file are fetched from detail pages and saved in item array
                    item[Key] = response.xpath(Value).extract()
                except:
                    logging.error("Index Error Occurred for Key= "+Key+" Value= "+Value)
                    #item[Key] = "Index Error Occurred"
        return item
