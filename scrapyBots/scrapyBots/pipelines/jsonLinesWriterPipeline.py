# Define your item pipelines here
#
# Don't forget to add your pipeline to the ITEM_PIPELINES setting
# See: http://doc.scrapy.org/topics/item-pipeline.html

from scrapy import signals
from scrapy import log
from scrapy.contrib.exporter import JsonLinesItemExporter

class JsonLinePipeline(object):
    def __init__(self):
        self.files = {}
        self.fileid = 0
        self.counter=1
        self.filetype = ".jl"

    @classmethod
    def from_crawler(cls, crawler):
        pipeline = cls()
        crawler.signals.connect(pipeline.spider_opened, signals.spider_opened)
        crawler.signals.connect(pipeline.spider_closed, signals.spider_closed)
        return pipeline

    def spider_opened(self, spider):
        file = open(str(self.fileid) + self.filetype, 'w+b')
        self.files[spider] = file
        self.exporter = JsonLinesItemExporter(file)
        self.exporter.start_exporting()

    def spider_closed(self, spider):
        self.exporter.finish_exporting()
        file = self.files.pop(spider)
        file.close()

    def process_item(self, item, spider):
        self.counter = self.counter +1
        print self.counter
        # creates a separate file if items exceeds the max limit
        if self.counter % 10 == 0:
            self.spider_closed(spider)
            self.fileid = self.fileid + 1
            self.spider_opened(spider)
        self.exporter.export_item(item)
        return item