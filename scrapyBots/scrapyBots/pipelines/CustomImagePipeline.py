from scrapy.contrib.pipeline.images import ImagesPipeline
from scrapy.exceptions import DropItem
from scrapy.http import Request
import time
class CustomPipeline(ImagesPipeline):

	def get_media_requests(self, item, info):
		for image_url in item['product_images']:
			#print image_url
			yield Request(image_url)

	def item_completed(self, results, item, info):
		image_paths = [x['path'] for ok, x in results if ok]
		thumb_image_paths = [x['path'] for ok, x in results if ok]
		if not image_paths:
			raise DropItem("Item contains no product images")
		if not thumb_image_paths:
			raise DropItem("Item contains no product thumb images")
		item['product_images_paths'] = image_paths
		return item