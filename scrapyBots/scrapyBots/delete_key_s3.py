from boto.s3.connection import Key, Bucket,S3Connection
from spiders.Utils.configSpider import *

conn = S3Connection(AWS_ACCESS_KEY_ID, AWS_SECRET_ACCESS_KEY)

b = Bucket(conn, BUCKET)
k = Key(b)

for x in b.list():
    #k.key = '/'+filename
    print x
#b.delete_key(k)