# -*- coding: utf-8 -*-

# Scrapy settings for scrapyBots project
#
# For simplicity, this file contains only settings considered important or
# commonly used. You can find more settings consulting the documentation:
#
#     http://doc.scrapy.org/en/latest/topics/settings.html
#     http://scrapy.readthedocs.org/en/latest/topics/downloader-middleware.html
#     http://scrapy.readthedocs.org/en/latest/topics/spider-middleware.html
from spiders.Utils.configSpider import *

LOG_ENABLED=True
LOG_LEVEL=SPIDER_LOG_LEVEL
#CONCURRENT_REQUESTS_PER_DOMAIN=16
RETRY_TIMES=4


BOT_NAME = 'scrapyBots'

SPIDER_MODULES = ['scrapyBots.spiders']
NEWSPIDER_MODULE = 'scrapyBots.spiders'

# DOWNLOADER_MIDDLEWARES = {
#         'scrapy.downloadermiddleware.useragent.UserAgentMiddleware' : None,
#         'scrapyBots.middlewares.rotate_useragent.RotateUserAgentMiddleware' :400
#     }

#DOWNLOADER_MIDDLEWARES = {'scrapy_crawlera.CrawleraMiddleware': 600}
#CRAWLERA_ENABLED = True
#CRAWLERA_USER = ''
#CRAWLERA_PASS = ''

# EXTENSIONS = {
#    'scrapy.telnet.TelnetConsole': None
# }

#COOKIES_ENABLED = False

#Configure item pipelines
#See http://scrapy.readthedocs.org/en/latest/topics/item-pipeline.html
# ITEM_PIPELINES = {
#    'scrapyBots.pipelines.jsonWriterPipeline.JsonPipeline': 300,
# }

#ITEM_PIPELINES = ['scrapyBots.pipelines.CustomImagePipeline.CustomPipeline']

# IMAGES_STORE = '/Users/shubham/projects/scrapyBots/uploads'
# IMAGES_THUMBS = {
#     'thumb': (200, 200)
#     #'mobile': (320, 320),
# }
EXTENSIONS = {
    'scrapyBots.middlewares.mailSenderExtension.StatusMailer': 80
}
#STATUSMAILER_RECIPIENTS = ['marketforce-tech@proptiger.com']
STATUSMAILER_RECIPIENTS = ['xyz@gmail.com']

STATUSMAILER_COMPRESSION = 'gzip'
#STATUSMAILER_COMPRESSION = None

MAIL_HOST = 'smtp.gmail.com'
MAIL_PORT = 587
MAIL_USER = 'abc@gmail.com'
MAIL_PASS = '123'


# MONGODB_SERVER = "localhost"
# MONGODB_PORT = 27017
# MONGODB_DB = "crawling"
# MONGODB_COLLECTION = "magicbricks"

# Crawl responsibly by identifying yourself (and your website) on the user-agent
#USER_AGENT = "Mozilla/5.0 (iPhone; U; CPU like Mac OS X; en) AppleWebKit/420+ (KHTML, like Gecko) Version/3.0 Mobile/1A543a Safari/419.3"

# Configure maximum concurrent requests performed by Scrapy (default: 16)
#CONCURRENT_REQUESTS=32

# Configure a delay for requests for the same website (default: 0)
# See http://scrapy.readthedocs.org/en/latest/topics/settings.html#download-delay
# See also autothrottle settings and docs
# The download delay setting will honor only one of:
#CONCURRENT_REQUESTS_PER_DOMAIN=16
#CONCURRENT_REQUESTS_PER_IP=16

# Disable cookies (enabled by default)
#COOKIES_ENABLED=False

# Disable Telnet Console (enabled by default)
#TELNETCONSOLE_ENABLED=False

# Override the default request headers:
#DEFAULT_REQUEST_HEADERS = {
#   'Accept': 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
#   'Accept-Language': 'en',
#}

# Enable or disable spider middlewares
# See http://scrapy.readthedocs.org/en/latest/topics/spider-middleware.html
#SPIDER_MIDDLEWARES = {
#    'scrapyBots.middlewares.MyCustomSpiderMiddleware': 543,
#}

# Enable or disable downloader middlewares
# See http://scrapy.readthedocs.org/en/latest/topics/downloader-middleware.html
#DOWNLOADER_MIDDLEWARES = {
#    'scrapyBots.middlewares.MyCustomDownloaderMiddleware': 543,
#}

# Enable or disable extensions
# See http://scrapy.readthedocs.org/en/latest/topics/extensions.html
#EXTENSIONS = {
#    'scrapy.telnet.TelnetConsole': None,
#}

# Configure item pipelines
# See http://scrapy.readthedocs.org/en/latest/topics/item-pipeline.html
#ITEM_PIPELINES = {
#    'scrapyBots.pipelines.SomePipeline': 300,
#}

# Enable and configure the AutoThrottle extension (disabled by default)
# See http://doc.scrapy.org/en/latest/topics/autothrottle.html
# NOTE: AutoThrottle will honour the standard settings for concurrency and delay
#AUTOTHROTTLE_ENABLED=True
# The initial download delay
#AUTOTHROTTLE_START_DELAY=5
# The maximum download delay to be set in case of high latencies
#AUTOTHROTTLE_MAX_DELAY=60
# Enable showing throttling stats for every response received:
#AUTOTHROTTLE_DEBUG=False

# Enable and configure HTTP caching (disabled by default)
# See http://scrapy.readthedocs.org/en/latest/topics/downloader-middleware.html#httpcache-middleware-settings
#HTTPCACHE_ENABLED=True
#HTTPCACHE_EXPIRATION_SECS=0
#HTTPCACHE_DIR='httpcache'
#HTTPCACHE_IGNORE_HTTP_CODES=[]
#HTTPCACHE_STORAGE='scrapy.extensions.httpcache.FilesystemCacheStorage'

#FEED_EXPORT_FIELDS = ["AGENT_ID","AGENT_NAME","AGENT_MOBILE","AGENT_EMAIL_ADDRESS","PROFILE_PICTURE_URL","CITY_OPERATING","LISTING_IN_RENT","LISTING_IN_SALE","COMPANY_NAME","COMPANY_ADDRESS","COMPANY_CONTACT_NO"]
#FEED_URI="\'Info_%s.csv\'  % time.strftime(\"%Y-%m-%d-%H.%M.%S\")"
#FEED_URI="file:///Users/shubham/proptiger/forked/export.csv"
#FEED_EXPORT_FIELDS = ["AGENT_ID","AGENT_NAME","AGENT_MOBILE","AGENT_EMAIL_ADDRESS","PROFILE_PICTURE_URL","CITY_OPERATING","LISTING_IN_RENT","LISTING_IN_SALE","COMPANY_NAME","COMPANY_ADDRESS","COMPANY_CONTACT_NO"]

#FEED_EXPORT_FIELDS = ["AGENT_ID","AGENT_NAME","AREAS_OPERATING","CERTIFICATION_BY_SITE","NUMBER_OF_LISTING","SPECIALIZED_IN","COMPANY_NAME","DATE_OF_REGISTRATION"]

#FEED_EXPORT_FIELDS = ["AGENT_ID","AGENT_NAME","AGENT_MOBILE","COMPANY_NAME","LISTING_ID","LISTING_CATEGORY","LISTING_PRICE","LISTING_TRANSACTION_TYPE","SELLER_CATEGORY","PROPERTY_CATEGORY","PROPERTY_AGE","LISTING_IMAGE_URL","LISTING_LOCATION","LISTING_CITY"]
#FEED_EXPORT_FIELDS = ["LISTING_ID","LISTING_TITLE","LISTING_PRICE","LISTING_IMAGE_URL","IMAGES_PER_LISTING","LISTING_LOCATION","SELLER_CATEGORY","AGENT_ID","AGENT_NAME","AGENT_MOBILE","CERTIFICATION_BY_SITE","COMPANY_NAME","COMPANY_CONTACT_NO"]

#CLOSESPIDER_ITEMCOUNT=10000
#CLOSESPIDER_TIMEOUT=1000
CLOSESPIDER_PAGECOUNT = 100000
#CLOSESPIDER_ERRORCOUNT=1000
#FEED_URI = 's3://crawled-data-testing/feeds/%(siteName)s/%(time)s.json'
FEED_FORMAT = 'json'
