# Define your item pipelines here
#
# Don't forget to add your pipeline to the ITEM_PIPELINES setting
# See: http://doc.scrapy.org/topics/item-pipeline.html

from scrapy import signals
from scrapy.exporters import JsonItemExporter
from ..spiders.Utils.configSpider import *
import datetime
import os.path
import time

#get current system date as per timezone
os.environ['TZ'] = TIMEZONE
date = str(datetime.date.today())
timestamp =int(time.time() )
timestamp=str(timestamp)
class JsonPipeline(object):
    def __init__(self):
        self.files = {}
        self.fileid = 0
        self.item_scraped_count = 0
        self.filetype = ".json"


    @classmethod
    def from_crawler(cls, crawler):
        pipeline = cls()
        crawler.signals.connect(pipeline.spider_opened, signals.spider_opened)
        crawler.signals.connect(pipeline.spider_closed, signals.spider_closed)
        return pipeline

    def spider_opened(self, spider):
        source=spider.settings.get(SCRAPING_SOURCE)
        if source != None:
            feedStorageDirectory=FEED_STORAGE_ROOT_DIRECTORY_LOCAL+DIRECTORY_SEPARATOR+date+DIRECTORY_SEPARATOR+source+DIRECTORY_SEPARATOR
            feed_uri=feedStorageDirectory+timestamp+"_"+str(self.fileid)+self.filetype
            if not os.path.exists(os.path.dirname(feed_uri)):
                os.makedirs(os.path.dirname(feed_uri))
            file = open(feed_uri,'w+b')
            self.files[spider] = file
            self.exporter = JsonItemExporter(file)
            self.exporter.start_exporting()
        else:
            pass

    def spider_closed(self, spider):
        self.exporter.finish_exporting()
        file = self.files.pop(spider)
        file.close()

    def process_item(self, item, spider):
        # creates a separate file if items exceeds the max limit
        if self.item_scraped_count % NUMBER_OF_ITEMS_PER_FILE +1 == True and self.item_scraped_count > 0:
            self.spider_closed(spider)
            self.fileid = self.fileid + 1
            self.spider_opened(spider)
        self.exporter.export_item(item)
        self.item_scraped_count = self.item_scraped_count +1
        return item