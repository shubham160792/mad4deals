"""
API SPIDER is responsible to fetch data from API.

"""


import scrapy
from scrapy.spiders import Spider,BaseSpider
from scrapyBots.items import ScrapybotsItem
from scrapy.spiders import Request
from scrapy.signalmanager import SignalManager
from scrapy.xlib.pydispatch import dispatcher
from scrapy import signals
import json
from Utils.configSpider import *
import logging
import datetime
import os
import time
from Utils.uploadToS3Utils import closed_handler




#get current system date as per indian timezone
os.environ['TZ'] = TIMEZONE
date=str(datetime.date.today())
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


class APISpider(Spider):
    dictionary={}
    name = 'genericAPISpider'
    def __init__(self, *args, **kwargs):
        super(APISpider, self).__init__(*args, **kwargs)
        SignalManager(dispatcher.Any).connect(
            closed_handler, signal=signals.spider_closed)
        try:
            self.siteName           = [kwargs.get(SPIDER_CONFIGURATION_NAME)]
            self.siteName           = ''.join(self.siteName)
            #Returns site specific dictionary defined in configuration file on the basis of siteName passed as command line argument
            self.siteName    = eval(self.siteName)
        except:
            logging.error("Unable to get Site Name. Hence exiting!")
            print "Unable to get Site Name. Hence exiting!"
            exit()

        try:
            self.batch             = [kwargs.get('batch')]
            self.batch             = ''.join(self.batch)
        except:
            logging.warn("Unable to get Batch name!")
            print "Unable to get Batch name!"


        if self.batch !=[None] and API_URL not in self.siteName.keys():
            logging.error(API_URL+"  NOT FOUND in Site Specific Dictionary for batch.Hence Exiting!")
            print API_URL+"  NOT FOUND in Site Specific Dictionary for batch.Hence Exiting!"
            exit()

        if RESULT_SET not in self.siteName.keys():
            logging.error(RESULT_SET+" Key Not Found in Site Specific Dictionary.Hence Exiting!")
            print RESULT_SET+" Key Not Found in Site Specific Dictionary.Hence Exiting!"
            exit()
        if API_FIELDS not in self.siteName.keys():
            logging.error(API_FIELDS+" Key Not Found in Site Specific Dictionary.Hence Exiting!")
            print API_FIELDS+" Key Not Found in Site Specific Dictionary.Hence Exiting!"
            exit()

    def start_requests(self):
        try:

            #if domain name is not present then prepending domain name to start_urls in which domain name is not present
            #self.start_urls = [self.siteName[DOMAIN_NAME]+i for i in self.siteName[API_URL] if "http" not in i]

            #if domain name is already present in urls then appending those urls in start_urls array without prepending them with domain name.
            #self.start_urls.extend(i for i in self.siteName[API_URL] if "http" in i)
            self.start_urls=[self.siteName[API_URL]]

            for api_url in self.start_urls:
                if  self.siteName[MODE] == API_GET_PARAM:
                    for get_param in self.siteName[self.batch][API_GET_PARAM]:
                        api_url_with_get_param=api_url.format(get_param)
                        yield Request(api_url_with_get_param,callback=self.parse_api)
                elif self.siteName[MODE] == API_LOOP:
                    for i in range(self.siteName[self.batch][START],self.siteName[self.batch][END]+1):
                        api_url_with_get_param=api_url.format(self.siteName[self.batch][BATCH_ID],i)
                        yield Request(api_url_with_get_param,callback=self.parse_api)
                else:
                    yield Request(api_url,callback=self.parse_api)
        except:
            logging.error("Exception in generating start_urls")
            print "Exception in generating start_urls"
            exit()


    def parse_api(self,response):
        responseFromAPI    =  json.loads(response.body_as_unicode())
        items              =  []
        for responseBlock in responseFromAPI[self.siteName[RESULT_SET]]['hits']:
            item               =  ScrapybotsItem()
            for Key, Value in self.siteName[API_FIELDS].iteritems():
                if isinstance(self.siteName[API_FIELDS][Key],dict):
                    for Keys,Values in self.siteName[API_FIELDS][Key].iteritems():
                        if Values in responseBlock[Key]:
                            item[Keys]=responseBlock[Key][Values]
                        else:
                            item[Keys]="NA"
                elif Value in responseBlock:
                    item[Key] = responseBlock[Value]
                else:
                    item[Key] = "NA"
            api_request_url=self.siteName[NEXT_DEPTH_PAGE_URL].format(item[LISTING_ID])
            print "api_request_url="+api_request_url
            yield scrapy.Request(api_request_url, meta={'item':item},callback=self.parse_detail)
        #yield item

    def getDetailedDictionary(self,d):
        for key, value in d.iteritems():
            if isinstance(value, dict):
                if not all(k in value for k in ('id', 'name','gid')):
                    self.getDetailedDictionary(value)
            elif isinstance(value,list):
                for v in value:
                    if isinstance(v,dict):
                        self.getDetailedDictionary(v)
            else:
                #print "{0} : {1}".format(key, value)
                if key not in self.dictionary:
                    self.dictionary[key]=value
        return self.dictionary


    def parse_detail(self,response):
        item = response.meta['item']
        responseFromAPI=json.loads(response.body_as_unicode())
        result=self.getDetailedDictionary(responseFromAPI)
        for Key,Value in self.siteName[2][API_FIELDS].iteritems():
            if Value in result:
                item[Key]=result[Value]
            else:
                item[Key]="NA"
        self.dictionary={}
        yield item

