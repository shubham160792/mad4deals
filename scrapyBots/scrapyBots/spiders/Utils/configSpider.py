###################################### LIST OF AVAILABLE SPIDERS #######################################################
DETAIL_PAGE_SPIDER                     = 'DETAIL_PAGE_SPIDER'
SELENIUMSPIDER                         = 'SELENIUMSPIDER'
GENERIC_SPIDER                         = 'GENERIC_SPIDER'
SPIDER_FOR_CATALOG_AND_DETAIL_PAGES    = 'SPIDER_FOR_CATALOG_AND_DETAIL_PAGES'
API_SPIDER                             = 'API_SPIDER'
WEB_SCRAPING_API_SPIDER                = 'WEB_SCRAPING_API_SPIDER'
TIMEZONE                               = 'Asia/Kolkata'
SCRAPY_LOGS_DIRECTORY                  = '/tmp/scrapyLogs/'
DEFAULT_LOGS_DIRECTORY                 = '/tmp/'
FEED_STORAGE_ROOT_DIRECTORY_LOCAL      = '/tmp/feeds'
FEED_STORAGE_ROOT_DIRECTORY_IN_S3      = 'feeds'
DIRECTORY_SEPARATOR                    = '/'
AWS_ACCESS_KEY_ID                      = ''
AWS_SECRET_ACCESS_KEY                  = ''
BUCKET                                 = 'crawled-data-testing'
NUMBER_OF_ITEMS_PER_FILE               = 2000
SPIDER_CONFIGURATION_NAME              = 'siteName'
SCRAPING_SOURCE                        = 'source'
SPIDER_LOG_FILE                        = 'SPIDER_LOG_FILE'
WEB_API_SPIDER_LOG_FILE                = 'WEB_API_SPIDER_LOG_FILE'
API_SPIDER_LOG_FILE                    = 'API_SPIDER_LOG_FILE'
SPIDER_LOG_LEVEL                       = 'DEBUG'
BATCH_ID                               = 'BATCH_ID'
MODE                                   = 'MODE'
BATCH_NAME                             = 'BATCH_NAME'
BATCH_KEY                              = 'BATCH_KEY'
STEP                                   = 'STEP'
############################################## SPIDER CONSTANTS ########################################################

ALLOWED_DOMAINS                        = 'ALLOWED_DOMAINS'
START_URLS                             = 'START_URLS'
START_URLS_WITH_LOOP                   = 'START_URLS_WITH_LOOP'
DOMAIN_NAME                            = 'DOMAIN_NAME'
LINK_EXTRACTOR_RULE                    = 'LINK_EXTRACTOR_RULE'
NEXT_PAGE_URL_XPATH                    = 'NEXT_PAGE_URL_XPATH'
LISTING_BLOCK_XPATH                    = 'LISTING_BLOCK_XPATH'
NEXT_DEPTH_PAGE_URL                    = 'NEXT_DEPTH_PAGE_URL'
FIELDS                                 = 'FIELDS'
API                                    = 'API'
API_URL                                = 'API_URL'
API_FIELDS                             = 'API_FIELDS'
API_GET_PARAM                          = 'API_GET_PARAM'
RESULT_SET                             = 'RESULT_SET'
API_LOOP                               = 'API_LOOP'
URL                                    = 'URL'
START                                  = 'start'
END                                    = 'end'
######################################### SCRAPED ITEM FIELDS LIST #####################################################

AGENT_NAME                               = 'AGENT_NAME'
AGENT_MOBILE                             = 'AGENT_MOBILE'
AGENT_EMAIL_ADDRESS                      = 'AGENT_EMAIL_ADDRESS'
COMPANY_NAME                             = 'COMPANY_NAME'
COMPANY_ADDRESS                          = 'COMPANY_ADDRESS'
CITY_OPERATING                           = 'CITY_OPERATING'
AREAS_OPERATING                          = 'AREAS_OPERATING'
PROFILE_PICTURE_URL                      = 'PROFILE_PICTURE_URL'
DATE_OF_REGISTRATION                     = 'DATE_OF_REGISTRATION'
SPECIALIZED_IN                           = 'SPECIALIZED_IN'
WEBSITE_LINK                             = 'WEBSITE_LINK'
LISTING_IN_SALE                          = 'LISTING_IN_SALE'
LISTING_IN_RENT                          = 'LISTING_IN_RENT'
BUILDERS_OPERATING_WITH                  = 'BUILDERS_OPERATING_WITH'
PRICE_RANGE_OF_LISTINGS                  = 'PRICE_RANGE_OF_LISTINGS'
CERTIFICATION_BY_SITE                    = 'CERTIFICATION_BY_SITE'
DATE_OF_LAST_POSTING                     = 'DATE_OF_LAST_POSTING'
LISTING_IN_RESALE                        = 'LISTING_IN_RESALE'
NUMBER_OF_EMPLOYEES_LISTED               = 'NUMBER_OF_EMPLOYEES_LISTED'
SELLER_CATEGORY                          = 'SELLER_CATEGORY'
PROPERTY_CATEGORY                        = 'PROPERTY_CATEGORY'
IMAGES_PER_LISTING                       = 'IMAGES_PER_LISTING'
VIDEOS_PER_LISTING                       = 'VIDEOS_PER_LISTING'
VIEW_360_FEATURE                         = 'VIEW_360_FEATURE'

#ADDTIONAL AGENT DETAILS ITEM FIELD
AGENT_ID                                 = 'AGENT_ID'
NUMBER_OF_LISTING                        = 'NUMBER_OF_LISTING'
AGENT_DETAILS                            = 'AGENT_DETAILS'
IS_AGENT_VERIFIED                        = 'IS_AGENT_VERIFIED'
IS_LISTING_VERIFIED                      = 'IS_LISTING_VERIFIED'
COMPANY_CONTACT_NO                       = 'COMPANY_CONTACT_NO'
#LISTING_ITEMS
LISTING_ID                               = 'LISTING_ID'
LISTING_TITLE                            = 'LISTING_TITLE'
LISTING_PRICE                            = 'LISTING_PRICE'
LISTING_POSTED_ON                        = 'LISTING_POSTED_ON'
LISTING_LOCATION                         = 'LISTING_LOCATION'
LISTING_AMENITIES                        = 'LISTING_AMENITIES'
LISTING_IMAGE_URL                        = 'LISTING_IMAGE_URL'
LISTING_TRANSACTION_TYPE                 = 'LISTING_TRANSACTION_TYPE'
PROPERTY_AGE                             = 'PROPERTY_AGE'
LISTING_CITY                             = 'LISTING_CITY'
LISTING_CATEGORY                         = 'LISTING_CATEGORY'
LISTING_DETAILS                          = 'LISTING_DETAILS'
LISTING_DESCRIPTION                      = 'LISTING_DESCRIPTION'
PROPERTY_AREA                            = 'PROPERTY_AREA'
PROPERTY_RATE_PER_SQ_FT                  = 'PROPERTY_RATE_PER_SQ_FT'
LISTING_CITY_ID                          = 'LISTING_CITY_ID'
LISTING_URL                              = 'LISTING_URL'
LATITUDE                                 = 'LATITUDE'
LONGITUDE                                = 'LONGITUDE'
ZOOM_LEVEL                               = 'ZOOM_LEVEL'
GEO_COORDINATES                          = 'GEO_COORDINATES'
LISTING_LOCALITY                         = 'LISTING_LOCALITY'
LISTING_LOCALITY_ID                      = 'LISTING_LOCALITY_ID'
FLOOR_NUMBER                             = 'FLOOR_NUMBER'
FURNISHED_STATUS                         = 'FURNISHED_STATUS'
PROPERTY_NAME                            = 'PROPERTY_NAME'
PROJECT_NAME                             = 'PROJECT_NAME'
LISTING_ADDRESS                          = 'LISTING_ADDRESS'

PROJECT_ID                               = 'PROJECT_ID'
LINK                                     = 'LINK'
PRICE_RANGE                              = 'PRICE_RANGE'
IMAGE_URL                                = 'IMAGE_URL'
BUILDER_ID                               = 'BUILDER_ID'
BUILDING_ID                              = 'BUILDING_ID'
START_DATE                               = 'START_DATE'
POSSESSION_DATE                          = 'POSSESSION_DATE'
PROPERTY_COUNT                           = 'PROPERTY_COUNT'
POSSESSION_TYPE                          = 'POSSESSION_TYPE'
LOGO                                     = 'LOGO'
BUILDER_NAME                             = 'BUILDER_NAME'
MIN_AREA                                 = 'MIN_AREA'
MAX_AREA                                 = 'MAX_AREA'
MIN_BEDROOM                              = 'MIN_BEDROOM'
MAX_BEDROOM                              = 'MAX_BEDROOM'
BUILDER_URL                              = 'BUILDER_URL'
IMAGES_COUNT                             = 'IMAGES_COUNT'
VIDEOS_COUNT                             = 'VIDEOS_COUNT'
FLOOR_IMAGES_COUNT                       = 'FLOOR_IMAGES_COUNT'
CONSTRUCTION_IMAGE_COUNT                 = 'CONSTRUCTION_IMAGE_COUNT'
YEAR_OF_ESTABLISHMENT                    = 'YEAR_OF_ESTABLISHMENT'
MIN_PRICE                                = 'MIN_PRICE'
MAX_PRICE                                = 'MAX_PRICE'
LISTING_STATUS                           = 'LISTING_STATUS'
IMPRESSIONS                              = 'IMPRESSIONS'
CTR                                      = 'CTR'
VIDEO_URL                                = 'VIDEO_URL'
PROJECT_URL                              = 'PROJECT_URL'
LOCALITY                                 = 'LOCALITY'
CITY                                     = 'CITY'
FLOORS                                   = 'FLOORS'
TOTAL_AREA                               = 'TOTAL_AREA'
OPEN_AREA                                = 'OPEN_AREA'
BHK_CONFIG                               = 'BHK_CONFIG'
CARPET_AREA                              = 'CARPET_AREA'
DESCRIPTION                              = 'DESCRIPTION'
BANK_APPROVALS                           = 'BANK_APPROVALS'
VIEWS_COUNT                              = 'VIEWS_COUNT'
PRICE_APPRECIATION                       = 'PRICE_APPRECIATION'
FIRST_APPRECIATION_DATE                  = 'FIRST_APPRECIATION_DATE'
FLOOR_PLANS_COUNT                        = 'FLOOR_PLANS_COUNT'
TOWER_AND_UNITS                          = 'TOWER_AND_UNITS'
PROJECT_SPECIFICATIONS                   = 'PROJECT_SPECIFICATIONS'
RES_OR_COM                               = 'RES_OR_COM'
PROJECT_ADDRESS                          = 'PROJECT_ADDRESS'
BASIC_AMENITIES                          = 'BASIC_AMENITIES'
LIFESTYLE_AMENITIES                      = 'LIFESTYLE_AMENITIES'
BROCHURE_LINK                            = 'BROCHURE_LINK'
PROJECT_RATING                           = 'PROJECT_RATING'
YEARS_OF_EXPERIENCE                      = 'YEARS_OF_EXPERIENCE'
TOTAL_PROJECTS                           = 'TOTAL_PROJECTS'
ONGOING_PROJECTS                         = 'ONGOING_PROJECTS'
BUILDER_LOGO                             = 'BUILDER_LOGO'
BUILDER_DESCRIPTION                      = 'BUILDER_DESCRIPTION'
PRICE_WITH_AREA                          = 'PRICE_WITH_AREA'
BEDROOMS                                 = 'BEDROOMS'
FLOORING                                 = 'FLOORING_SPECIFICATIONS'
FITTING                                  = 'FITTING_SPECIFICATIONS'
WALLS                                    = 'WALLS_SPECIFICATIONS'
################################## SOURCE SPECIFIC SPIDER CONFIGURATION STARTS HERE ####################################

#################################### MB CRAWLING CONFIGURATION BLOCK STARTS HERE #######################################


# MB WEB SCRAPING SPIDER CONFIGURATION

# MB AGENTS LISTING PAGES
MB_AGENTS = {}
MB_AGENTS[ALLOWED_DOMAINS]=["magicbricks.com"]
MB_AGENTS[START_URLS] =["/Real-estate-property-agents/agent-in-Gurgaon/Page-1"]
MB_AGENTS[DOMAIN_NAME]="http://www.magicbricks.com"

# Magic Bricks Depth Level 1 configuration
MB_AGENTS[1]={}
MB_AGENTS[1][NEXT_PAGE_URL_XPATH]="//span[@class='pageNos']/a[last()]/@href"
MB_AGENTS[1][LISTING_BLOCK_XPATH]="//div[@class='srpBlock']"
MB_AGENTS[1][FIELDS]={}
MB_AGENTS[1][FIELDS][NEXT_DEPTH_PAGE_URL]=".//span[@class=\'seeProDetail\']/a/@href"
MB_AGENTS[1][FIELDS][AGENT_NAME]=".//p[@class=\'proGroup\']/text()"
MB_AGENTS[1][FIELDS][AGENT_ID]=".//div[@class=\'srpBlockLeftBtn\']/ul/li[1]/@id"
MB_AGENTS[1][FIELDS][COMPANY_NAME]=".//p[@class=\'proHeading\']/strong/text()"
MB_AGENTS[1][FIELDS][AREAS_OPERATING]=".//div[@class=\'proDetailLine\'][3]/span/@title"
MB_AGENTS[1][FIELDS][DATE_OF_REGISTRATION]=".//div[@class=\'proDetailLine\'][4]/span/text()"
MB_AGENTS[1][FIELDS][CERTIFICATION_BY_SITE]=".//div[@class=\'proAgent\']/span/text()"
MB_AGENTS[1][FIELDS][NUMBER_OF_LISTING]=".//div[@class=\'proDetailLine\'][2]/span/text()"
MB_AGENTS[1][FIELDS][PROFILE_PICTURE_URL]=".//div[@class=\'porpertyImages\']/a/img/@data-original"
MB_AGENTS[1][FIELDS][SPECIALIZED_IN]=".//div[@class=\'proDetailLine\'][1]/span/text()"

# Magic Bricks Depth Level 2 configuration
MB_AGENTS[2]={}
MB_AGENTS[2][FIELDS]={}
MB_AGENTS[2][FIELDS][COMPANY_ADDRESS]="//div[@class=\'localityOtherInfo\']/ul/li[last()]/div[2]/text()"
MB_AGENTS[2][FIELDS][WEBSITE_LINK] ="//div[@class=\'localityOtherInfo\']/ul/li[last()-1]/div/a/text()"
MB_AGENTS[2][FIELDS][LISTING_IN_SALE] ="//div[@class=\'localityOtherInfo\']/ul/li[3]/div/text()"
MB_AGENTS[2][FIELDS][LISTING_IN_RENT] ="//div[@class=\'localityOtherInfo\']/ul/li[4]/div/text()"



# MAGIC BRICKS LISTING DETAILS Configuration
MB_LISTINGS = {}
MB_LISTINGS[ALLOWED_DOMAINS]=["magicbricks.com"]
MB_LISTINGS[START_URLS]=["/property-for-sale/residential-real-estate?cityName=Bangalore"]
MB_LISTINGS[DOMAIN_NAME]="http://www.magicbricks.com"
MB_LISTINGS[1]={}
MB_LISTINGS[1][NEXT_PAGE_URL_XPATH]="//span[@class='pageNos']/a[last()]/@href"
MB_LISTINGS[1][LISTING_BLOCK_XPATH]="//div[contains(@class,\'srpBlock\') and contains(@class,\'srpContentImageWrap\')]"
MB_LISTINGS[1][FIELDS]={}
#MB_LISTINGS[1][FIELDS][NEXT_DEPTH_PAGE_URL]=".//span[@class=\'seeProDetail\']/a/@href"
MB_LISTINGS[1][FIELDS][AGENT_NAME]=".//div[@class=\'agentDetail\']/a/text()"
MB_LISTINGS[1][FIELDS][AGENT_ID]=".//a[@class='contactBtn agentBtn']/@id"
MB_LISTINGS[1][FIELDS][LISTING_POSTED_ON]=".//span[@class=\'proPostedBy\']/text()"
MB_LISTINGS[1][FIELDS][IMAGES_PER_LISTING]=".//div[@class=\'propertyImageCount\']/text()"
MB_LISTINGS[1][FIELDS][LISTING_PRICE]=".//span[@class=\'proPriceField\']/text()"
MB_LISTINGS[1][FIELDS][LISTING_LOCATION]=".//span[@class=\'localityFirst\']/text()"
MB_LISTINGS[1][FIELDS][LISTING_TRANSACTION_TYPE]=".//div[@class=\'proDetailLine\'][2]/text()"
MB_LISTINGS[1][FIELDS][LISTING_AMENITIES]=".//div[@class=\'amenitiesListing\']/ul//li/text()"



################################## QUIKR CRAWLING CONFIGURATION BLOCK STARTS HERE ######################################
#
QUIKR={}
QUIKR[DOMAIN_NAME]="http://www.quikr.com"
QUIKR[MODE]=START_URLS_WITH_LOOP
QUIKR['DELHI']={}
QUIKR['DELHI']={URL:'{0}?page={1}',BATCH_ID:'http://delhi.quikr.com/Real-Estate/w663',START:1,END:10}


QUIKR[1]={}
QUIKR[1][LISTING_BLOCK_XPATH]="//div[contains(@class,\'snb_entire_ad\')]"
QUIKR[1][NEXT_PAGE_URL_XPATH]="//div[@class=\'innernnpagination\']/ul/li[last()]/a/@href"
QUIKR[1][FIELDS]={}
QUIKR[1][FIELDS][NEXT_DEPTH_PAGE_URL]="http://services.quikr.com/api?method=getAd&opf=json&adId={0}&version=1.6&secCode=zXcv80386Mdp1hs0q7o0p9uiLZV37TdF"
#QUIKR[1][FIELDS][LISTING_TITLE]=".//a[contains(@class,\'adttllnk\')]/@title"
#QUIKR[1][FIELDS][LISTING_PRICE]=".//div[@class=\'snb_price_tag\']/text()"
QUIKR[1][FIELDS][LISTING_POSTED_ON]=".//font[@class=\'snb_date\']/text()"
QUIKR[1][FIELDS][IMAGES_PER_LISTING]=".//div[@class=\'multi_img_icon\']/text()"
QUIKR[1][FIELDS][LISTING_ID]=".//div[contains(@class,\'cht-sm-wrapp\')]/@id"
QUIKR[1][FIELDS][LISTING_IMAGE_URL]=".//div[@class=\'quikrsnb_listboxphoto\']/img/@data-original"


QUIKR[2]={}
QUIKR[2][RESULT_SET]='ad'
QUIKR[2][FIELDS]={}
QUIKR[2][FIELDS][AGENT_MOBILE]="mobile"
QUIKR[2][FIELDS][AGENT_EMAIL_ADDRESS]="txtemail"
QUIKR[2][FIELDS][LISTING_TITLE]='title'
QUIKR[2][FIELDS][LISTING_LOCATION]="location"
QUIKR[2][FIELDS][IS_AGENT_VERIFIED]="isVerifiedNo"
QUIKR[2][FIELDS][LISTING_CATEGORY]="isPaidAd"
QUIKR[2][FIELDS][LISTING_PRICE]='priceTag'

#QUIKR[2][FIELDS][LISTING_DESCRIPTION]="description"
QUIKR[2][FIELDS]['city']={}
QUIKR[2][FIELDS]['city'][LISTING_CITY]="name"
QUIKR[2][FIELDS]['subcategory']={}
QUIKR[2][FIELDS]['subcategory'][LISTING_DETAILS]="name"
QUIKR[2][FIELDS][SELLER_CATEGORY]="limitedAttributes"
QUIKR[2][FIELDS][LISTING_URL]="adURL"




### IREF CRAWLING CONFIGURATION STARTS HERE ###

IREF={}
IREF[ALLOWED_DOMAINS]=["indianrealestateforum.com"]
IREF[DOMAIN_NAME]="https://www.indianrealestateforum.com"
IREF[MODE]=START_URLS
IREF['GURGAON']={START_URLS:["/f-gurgaon-real-estate-28.html/"]}

IREF['LAWS']={START_URLS:["/f-indian-property-laws-39.html/"]}

IREF['DELHI']={START_URLS:["/f-delhi-real-estate-19.html/"]}

IREF['NOIDA']={START_URLS:["/f-noida-real-estate-83.html/"]}

IREF['MUMBAI']={START_URLS:["/f-mumbai-real-estate-20.html/"]}

IREF['PUNE']={START_URLS:["/f-pune-real-estate-25.html/"]}

IREF['BANGALORE']={START_URLS:["/f-bangalore-real-estate-21.html/"]}

IREF['CHENNAI']={START_URLS:["/f-chennai-real-estate-24.html/"]}

IREF['KOLKATA']={START_URLS:["/f-kolkata-real-estate-27.html/"]}

IREF['GREATER_NOIDA']={START_URLS:["/f-greater-noida-real-estate-84.html/"]}

IREF['FARIDABAD']={START_URLS:["/f-faridabad-real-estate-95.html/"]}

IREF['GENERAL_DISCUSSION']={START_URLS:["/f-general-real-estate-discussion-15.html/"]}

IREF['HOME_LOAN']={START_URLS:["/f-home-loans-india-36.html/"]}

IREF['NRI']={START_URLS:["/f-nri-real-estate-32.html/"]}

IREF['VASTU']={START_URLS:["/f-vastu-home-improvement-235.html/"]}

IREF[1]={}
IREF[1][NEXT_PAGE_URL_XPATH]="//div[contains(@class,\'pagenav\')]/table/tr/td[last()-1]//@href"
IREF[1][LISTING_BLOCK_XPATH]="//table[@id=\'threadslist\']/tbody[last()]/tr"
IREF[1][FIELDS]={}
IREF[1][FIELDS][NEXT_DEPTH_PAGE_URL]=".//td/div/a[last()]/@href"
IREF[1][FIELDS]['FORUM_NAME']="//div[@class=\'cty_thrd_head\']/div/a[last()]/text()"
IREF[1][FIELDS]['THREAD_TITLE']=".//td/div/a[last()]/text()"
IREF[1][FIELDS]['LAST_POSTED_IN_THREAD']=".//span[@class=\'date\']/text()"
IREF[1][FIELDS]['REPLIES_IN_THREAD']=".//td/a/text()"
IREF[1][FIELDS]['TOTAL_VIEWS']=".//td[last()]/text()"

IREF[2]={}
IREF[2][FIELDS]={}
IREF[2][FIELDS]['FIRST_POST_DATE']="//table[contains(@class,\'showThreadLegacy\')][1]/tr/td[1]/text()"
IREF[2][FIELDS]['FIRST_POST_HEADING']="(//div[contains(@class,\'tl_title\')]/strong/text())[1]"
IREF[2][FIELDS]['FIRST_POST_CONTENT']="(//div[@class=\'tl_message\'])[1]/div/text()"
#"FORUM_NAME,THREAD_TITLE,LAST_POSTED_IN_THREAD,REPLIES_IN_THREAD,TOTAL_VIEWS,FIRST_POST_DATE,FIRST_POST_HEADING,FIRST_POST_CONTENT"
### IREF CRAWLING CONFIGURATION ENDS HERE ###

#### MB FORUMS STARTS HERE####

MB_FORUM={}
MB_FORUM[URL]="/Real-Estate-Forum/{0}/Page-{1}"
MB_FORUM[ALLOWED_DOMAINS]=["magicbricks.com"]
MB_FORUM[DOMAIN_NAME]="http://www.magicbricks.com"
MB_FORUM[MODE]=START_URLS_WITH_LOOP
MB_FORUM['QNA']={BATCH_ID:"popular-questions-answers",START:1,END:2105}
MB_FORUM[1]={}
#MB_FORUM[1][NEXT_PAGE_URL_XPATH]="//div[@id=\'paginate-slider2\']/a[last()]/@href"
MB_FORUM[1][LISTING_BLOCK_XPATH]="//div[@class=\'qAns-MBox\']"
MB_FORUM[1][FIELDS]={}
MB_FORUM[1][FIELDS]['FORUM_QUESTION']=".//div[@class=\'qBox\']/p[contains(@class,\'first\')]/a/strong/text()"
MB_FORUM[1][FIELDS]['FORUM_ANSWER']=".//div[@class=\'ansBox\']/p[contains(@class,\'first\')]/text()"
MB_FORUM[1][FIELDS]['DATE']=".//p[contains(@class,\'third\')]/text()"
MB_FORUM[1][FIELDS]['FORUM_SECTION']=".//div[@class=\'qBox\']/p[contains(@class,\'third\')]/a[2]/text()"
MB_FORUM[1][FIELDS]['CITY']=".//div[@class=\'qBox\']/p[contains(@class,\'third\')]/a[3]/text()"

#"FORUM_SECTION,CITY,FORUM_QUESTION,FORUM_ANSWER,DATE"

#### MB FORUMS ENDS HERE####


### NN FORUM STARTS HERE ###

NN_FORUM={}
NN_FORUM[URL]="{0}-page-{1}"
NN_FORUM[ALLOWED_DOMAINS]=["99acres.com"]
NN_FORUM[DOMAIN_NAME]="http://www.99acres.com"
NN_FORUM[MODE]=START_URLS_WITH_LOOP
NN_FORUM['QNA']={BATCH_ID:"/ask-real-estate-property-qna-popular-questions",START:1,END:2105}
NN_FORUM[1]={}
#NN_FORUM[1][NEXT_PAGE_URL_XPATH]="//div[@id=\'paginataionPlace2\']/a[last()]/@href"
NN_FORUM[1][LISTING_BLOCK_XPATH]="//div[@class=\'mt10\']"
NN_FORUM[1][FIELDS]={}
NN_FORUM[1][FIELDS]['REPLIES_IN_THREAD']=".//div[contains(@class,\'askLStat\')]/div[contains(@class,\'askL1\')]/text()"
NN_FORUM[1][FIELDS]['TOTAL_VIEWS']=".//div[contains(@class,\'askLStat\')]/div[contains(@class,\'show\')]/text()"
#NN_FORUM[1][FIELDS]['THREAD_TITLE_COMPLETE']=".//a[starts-with(@id,\'completeRelatedQuesDiv\')]/text()"
NN_FORUM[1][FIELDS]['THREAD_TITLE']=".//div[contains(@class,\'askRDet\')]/a[1]/text()"
NN_FORUM[1][FIELDS]['COMPLETE_THREAD']=".//a[starts-with(@id,\'completeRelatedQuesDiv\')]/text()"
NN_FORUM[1][FIELDS]['DATE']=".//div[contains(@class,\'askRDet\')]/div[contains(@class,\'p5\')]/span/text()"
NN_FORUM[1][FIELDS]['FORUM_SECTION']=".//div[contains(@class,\'askRDet\')]/div[contains(@class,\'p5\')]/span/a[1]/text()"
NN_FORUM[1][FIELDS]['CITY']=".//div[contains(@class,\'askRDet\')]/div[contains(@class,\'p5\')]/span/a[last()]/text()"

#"FORUM_SECTION,CITY,THREAD_TITLE,COMPLETE_THREAD,REPLIES_IN_THREAD,TOTAL_VIEWS,DATE"
### NN FORUM ENDS HERE ###
################################## SOURCE SPECIFIC SPIDER CONFIGURATION ENDS HERE ####################################

#Flipkart Configuration starts here
FK={}
FK[URL]="{0}{1}"
FK[ALLOWED_DOMAINS]=["flipkart.com"]
FK[DOMAIN_NAME]="http://www.flipkart.com"
FK[MODE]=START_URLS_WITH_LOOP
FK['SHOES']={BATCH_ID:"/mens-footwear/sports-shoes/pr?sid=osp,cil,1cu&start=",START:1,END:60,STEP:15}
FK['MOBILES']={BATCH_ID:"/mobiles/pr?sid=tyy,4io&otracker=ch_vn_mobile_filter_Top%20Brands_All&start=",START:1,END:90,STEP:15}
FK[1]={}
FK[1][LISTING_BLOCK_XPATH]="//div[contains(@class,\'product-unit\')]"
FK[1][FIELDS]={}
FK[1][FIELDS][NEXT_DEPTH_PAGE_URL]=".//a[@data-tracking-id=\'prd_title\']/@href"
FK[1][FIELDS]['product_title']=".//a[@data-tracking-id=\'prd_title\']/@title"
FK[1][FIELDS]['product_price']=".//div[contains(@class,\'pu-final\')]/span/text()"
FK[1][FIELDS]['product_mrp']=".//div[contains(@class,\'pu-discount\')]/span[@class=\'pu-old\']/text()"
FK[1][FIELDS]['discount_percentage']=".//div[contains(@class,\'pu-discount\')]/span[@class=\'pu-off-per\']/text()"
FK[1][FIELDS]['ratings_count']=".//div[@class=\'pu-rating\']/text()"
FK[1][FIELDS]['product_rating']=".//div[@class=\'pu-rating\']/div/@title"
FK[1][FIELDS]['product_url']=".//a[@data-tracking-id=\'prd_title\']/@href"
#FK[1][FIELDS]['thumb_images']=".//a[contains(@class,\'pu-image\')]/@data-images"

FK[2]={}
FK[2][FIELDS]={}
FK[2][FIELDS]['product_images']="//img[contains(@class,\'productImage\')]/@data-zoomimage"
FK[2][FIELDS]['review_title']="(//div[@class=\'rightCol\']/p[@class=\'review-title\'])[1]/text()"
FK[2][FIELDS]['review']="(//div[@class=\'rightCol\']/span[@class=\'review-text\'])[1]/text()"
FK[2][FIELDS]['review_posted_date']="(//p[@class=\'review-date\'])[1]/text()"
FK[2][FIELDS]['shipping_charges']="//div[@class=\'default-shipping-charge\']/span/text()"
FK[2][FIELDS]['seller_name']="//a[@class=\'seller-name\']/text()"
FK[2][FIELDS]['seller_rating']="(//span[contains(@class,\'rating-out-of-five\')])[1]/text()"
FK[2][FIELDS]['available_sizes']="//div[@class=\'selector-boxes\']/@data-selectorvalue"
FK[2][FIELDS]['gender']="//td[contains(text(),'Ideal')]/following-sibling::td/text()"
FK[2][FIELDS]['product_subcategory']="//td[contains(text(),'Occasion')]/following-sibling::td/text()"
FK[2][FIELDS]['is_cod_available']="//div[@class=\'cash-on-delivery\']/text()"
FK[2][FIELDS]['return_time']="//span[@class=\'return-text\']/b/text()"
FK[2][FIELDS]['delivery_time']="(//div[@class=\'delivery\']/ul/li)[1]/text()"
#Flipkart Configuration ends here



#Amazon Configuration starts here
AMAZON={}
AMAZON[URL]="{0}{1}"
AMAZON[ALLOWED_DOMAINS]=["amazon.in"]
AMAZON[DOMAIN_NAME]="http://www.amazon.in"
AMAZON[MODE]=START_URLS_WITH_LOOP
AMAZON['SHOES']={BATCH_ID:"/mens-footwear/pr?sid=osp%2Ccil&otracker=clp_mens-footwear_CategoryLinksModule_0-2_catergorylinks_1_SportsShoes&start=",START:1,END:30,STEP:15}
AMAZON['LAPTOPS']={BATCH_ID:"/s/ref=lp_1375424031_pg_2?rh=n%3A976392031%2Cn%3A%21976393031%2Cn%3A1375424031&page=",START:1,END:10,STEP:1}
AMAZON[1]={}
AMAZON[1][LISTING_BLOCK_XPATH]="//div[@class=\'s-item-container\']"
AMAZON[1][FIELDS]={}
AMAZON[1][FIELDS][NEXT_DEPTH_PAGE_URL]=".//a[contains(@class,\'s-access-detail-page\')]/@href"
AMAZON[1][FIELDS]['product_title']=".//h2[contains(@class,\'s-access-title\')]/text()"
AMAZON[1][FIELDS]['product_price']=".//span[contains(@class,\'s-price\')]/text()"
AMAZON[1][FIELDS]['product_mrp']=".//span[contains(@class,\'a-text-strike\')]/text()"
#AMAZON[1][FIELDS]['discount_percentage']=".//span[@class=\'a-size-small a-color-price\']/text()"
AMAZON[1][FIELDS]['ratings_count']=".//a[@class=\'a-size-small a-link-normal a-text-normal\']/text()"
AMAZON[1][FIELDS]['product_rating']=".//span[@class=\'a-icon-alt\']/text()"
#AMAZON[1][FIELDS]['thumb_images']=".//img[contains(@class,\'s-access-image\')]/@src"




AMAZON[2]={}
AMAZON[2][FIELDS]={}
AMAZON[2][FIELDS]['discount_percentage']="//tr[@id=\'dealprice_savings\']/td/text()"
AMAZON[2][FIELDS]['review_title']="//div[@id=\'revMHRL\']/span[@class=\'a-size-base a-text-bold\']/text()"
AMAZON[2][FIELDS]['seller_name']="//div[@id=\'merchant-info\']/a/text()"
AMAZON[2][FIELDS]['seller_rating']="//div[@id=\'merchant-info\']/text()"
AMAZON[2][FIELDS]['ratings_count']="//span[@id=\'acrCustomerReviewText\']/text()"
#Amazon Configuration ends here



############################################## mysql db constants ######################################################
MYSQL_HOST  = "127.0.0.1"       # mysql host
MYSQL_USER  = "root"			# mysql username
MYSQL_PASS  = "123"			    # mysql password
MYSQL_DB    = "TEST"		    # mysql database name
MYSQL_TABLE = "crawling"		# mysql table name
########################################################################################################################
