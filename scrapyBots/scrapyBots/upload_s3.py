from boto.s3.key import Key
from boto.s3.connection import S3Connection
from spiders.Utils.configSpider import *
from os import walk
import datetime

DATE=datetime.datetime.now().strftime("%Y-%m-%d")
FEED_LOCAL_STORAGE = FEED_STORAGE_ROOT_DIRECTORY_LOCAL+DATE+'/'+'99acres.com/'
files = []
for (dirpath, dirnames, filenames) in walk(FEED_LOCAL_STORAGE):
    files.extend(filenames)
    break
print files
print len(files)

conn = S3Connection(AWS_ACCESS_KEY_ID, AWS_SECRET_ACCESS_KEY)
print "connection made"

b = conn.get_bucket(BUCKET)
print "Gotten reference to bucket named " + BUCKET
FEED_STORAGE_DIRECTORY_IN_S3 = FEED_STORAGE_ROOT_DIRECTORY_IN_S3+DATE+'/'+"99acres.com"+'/'
FEED_LOCAL_STORAGE = FEED_STORAGE_ROOT_DIRECTORY_LOCAL+DATE+'/'+"99acres.com"+'/'
for file in files:
    print file
    feed_uri_s3 = FEED_STORAGE_DIRECTORY_IN_S3+file
    feed_uri_local = FEED_LOCAL_STORAGE+file
    print "Uploading " + feed_uri_local
    k = Key(b)
    k.key = feed_uri_s3
    k.set_contents_from_filename(feed_uri_local)
    print "Uploaded file"
