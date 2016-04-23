import datetime
import os
from boto.s3.key import Key
from boto.s3.connection import S3Connection
from os import walk
import time
from configSpider import *
#get current system date as per indian timezone
os.environ['TZ'] = TIMEZONE
timestamp =int(time.time() )
timestamp = str(timestamp)
timestamp = timestamp[:-2]

#Get current date
date=str(datetime.date.today())

def closed_handler(spider):    # Invoked on successful completion of spider run
        source=spider.settings.get(SCRAPING_SOURCE)    #crawling source
        FEED_STORAGE_DIRECTORY_IN_S3 = FEED_STORAGE_ROOT_DIRECTORY_IN_S3+DIRECTORY_SEPARATOR+date+DIRECTORY_SEPARATOR+source+DIRECTORY_SEPARATOR
        FEED_LOCAL_STORAGE = FEED_STORAGE_ROOT_DIRECTORY_LOCAL+DIRECTORY_SEPARATOR+date+DIRECTORY_SEPARATOR+source+DIRECTORY_SEPARATOR
        files = []
        filtered_files=[]
        for (dirpath, dirnames, filenames) in walk(FEED_LOCAL_STORAGE):
            files.extend(filenames)
        for file in files:
            if str(timestamp) in file:
                filtered_files.append(file)
        print filtered_files
        conn = S3Connection(AWS_ACCESS_KEY_ID, AWS_SECRET_ACCESS_KEY)
        print "connection made"
        b = conn.get_bucket(BUCKET)
        print "Gotten reference to bucket named " + BUCKET
        for file in filtered_files:
            print file
            feed_uri_s3 = FEED_STORAGE_DIRECTORY_IN_S3+file
            #uploadfile = sys.argv[1]
            feed_uri_local = FEED_LOCAL_STORAGE+file
            print "Uploading " + feed_uri_local
            k = Key(b)
            k.key = feed_uri_s3
            k.set_contents_from_filename(feed_uri_local)
            print "Uploaded file"
            if os.path.exists(feed_uri_local):
                os.remove(feed_uri_local)
