import json
import pymongo


connection = pymongo.MongoClient("mongodb://localhost")
db=connection.scraped_data
collectionCursor = db.magicbricks
page = open("/Users/shubham/proptiger/forked/MB/MB_AGENTS_AHMEDABAD_2690.json", 'r')
parsed = json.loads(page.read())

for item in parsed:
    collectionCursor.insert(item)
