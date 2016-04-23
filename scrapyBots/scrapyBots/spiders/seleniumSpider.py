from scrapy.spiders import CrawlSpider
from scrapyBots.items import ScrapybotsItem
#from scrapy.linkextractors.sgml import SgmlLinkExtractor
from selenium import selenium
from selenium import webdriver
import time
import logging
from Utils.configSpider import *
import os
import datetime


#get current system date as per indian timezone
os.environ['TZ'] = TIMEZONE
date = str(datetime.date.today())


class SeleniumSpider(CrawlSpider):
	name=SELENIUMSPIDER
	# logger6 = logging.getLogger(SELENIUMSPIDER)
	# if os.path.isdir(SCRAPY_LOGS_DIRECTORY):
	# 	fh = logging.FileHandler(SCRAPY_LOGS_DIRECTORY+SPIDER_FOR_CATALOG_AND_DETAIL_PAGES+'-'+date)
	# 	logger6.addHandler(fh)
	# 	#logger6.basicConfig(filename=SCRAPY_LOGS_DIRECTORY+SPIDER_LOG_FILE+"-"+date)
	# elif os.path.isdir(DEFAULT_LOGS_DIRECTORY):
	# 	fh = logging.FileHandler(SCRAPY_LOGS_DIRECTORY+SPIDER_FOR_CATALOG_AND_DETAIL_PAGES+'-'+date)
	# 	logger6.addHandler(fh)
	# 	#logger6.basicConfig(filename=DEFAULT_LOGS_DIRECTORY+SPIDER_LOG_FILE+"-"+date)
	# 	logger6.warn("Storing scrapy log in system's default log directory ie., directory = "+DEFAULT_LOGS_DIRECTORY+" kindly create separate directory for storing scrapy logs.ie., directory = "+SCRAPY_LOGS_DIRECTORY)
	# else:
	# 	print "Scrapy log directory doesn't exists.Please create directory to store scrapy logs.Directory = "+SCRAPY_LOGS_DIRECTORY
	# 	exit()
	allowed_domains= ["youtube.com"]
	start_urls = ['https://www.truecaller.com/in/7827675980']
	#rules = [Rule(SgmlLinkExtractor(allow=('.*?/\product.*?')),callback='parse_items',follow=True)]

	def __init__(self):
		self.driver = webdriver.Firefox()
		#self.driver = selenium("localhost", 4444, "*firefox","http://selenium.com/")
	def parse(self,response):
		self.driver.get(response.url)
		next = self.driver.find_element_by_xpath("//div[@id=\'signInGoogle\']")
		try:
			next.click()
			time.sleep(10)
			self.driver.switch_to_window(self.driver.window_handles[1])
			time.sleep(5)
			emailid=self.driver.find_element_by_id("Email")
			print "emailId="
			print emailid
			time.sleep(10)
			emailid.send_keys("shubhamsharma160792@gmail.com")

			passw=self.driver.find_element_by_id("Passwd-hidden")
			passw.send_keys("thegodfather123")


			signin=self.driver.find_element_by_id("signIn")
			signin.click()
		   # time.sleep(10)
		   # alert = self.driver.switch_to_alert()
		   # print alert
		   # next = alert.find_element_by_xpath("//div[@id=\'signInGoogle\']")
		   # next.click()
		   # time.sleep(10)
		except:
			print "exception"
			time.sleep(2)


		items = []
		print "yoho!"
		time.sleep(10)
		titles = self.driver.find_elements_by_xpath("//li[contains(@class,'channels-content-item yt-shelf-grid-item')]")
		for title in titles:
			item = ScrapybotsItem()
			item['listing_title'] =(title.find_elements_by_xpath(".//h3[@class='yt-lockup-title']/a"))[0].text
			item['listing_url'] = (title.find_elements_by_xpath(".//h3[@class='yt-lockup-title']/a"))[0].get_attribute('href')
			item['listing_views'] = "Views: "+(title.find_elements_by_xpath(".//ul[@class='yt-lockup-meta-info']/li[1]"))[0].text
			item['listing_added']="Added: "+(title.find_elements_by_xpath(".//ul[@class='yt-lockup-meta-info']/li[2]"))[0].text
			items.append(item)
		self.driver.close()
		return items
