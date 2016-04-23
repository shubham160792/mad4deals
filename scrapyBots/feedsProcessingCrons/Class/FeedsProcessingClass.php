<?php

use Aws\S3\S3Client;
class FeedsProcessingClass{
    public $con;
    public $res;
    public $query;
    public $result;


    public function __construct()
    {
        $this->logger = LoggerFactory::getInstance(__CLASS__);
        $this->insertedRecordCounter=1;
        $this->batchSize=100;
    }

    //PostGreSql Connection
    private function PostgreSQL_connect(){
        static $con;
        $conn_string = "";
        if(!isset($con)){
            $con = pg_connect($conn_string);
        }
        return $con;
    }

    //MY SQL DB_CONNECTION
    private function sql_db_connect() {
        static $con;
        if(!isset($con)) {
            $config = parse_ini_file('config/config.local.ini');
            $con = mysqli_connect($config['host'],$config['username'],$config['password'],$config['dbname']);
        }
        if($con === false) {
            return mysqli_connect_error(); 
        }
        return $con;
    }


    /**
     * function saveMagicbricksAgentsRecord insert records from magicbricks agents S3 feed to redshift and returns number of records inserted successfully
     * @param  array $inputData dataArray from feed
     * @param string $crawlType for ex. listing_details or agent_details
     * @param string $source for ex. magicbricks.com
     * @param string $date date for ex. 2015-10-05
     * @return  integer
     */
    public function saveMagicbricksAgentsRecord($inputData,$crawlType,$source,$date){
        $con = $this->PostgreSQL_connect();
        if (!isset($con)){
            $this->logger->error("PostgreSQL Connection not set hence exiting");
            return null;
        }
        if(isset($this->insertedRecordCounter)){
            $this->insertedRecordCounter=1;
        }
        try {
            $batchSize = ConstantUtils::BATCH_INSERTION_SIZE;
            $this->logger->debug("Batch insertion size=".$batchSize);
            $qry = "INSERT INTO " . ConstantUtils::TABLE_SCRAPED_DATA . "(" . ConstantUtils::COLUMN_SCRAPED_DATA_CRAWL_TYPE . "," . ConstantUtils::COLUMN_SCRAPED_DATA_SOURCE . "," . ConstantUtils::COLUMN_SCRAPED_DATA_LATEST_CRAWL . "," . ConstantUtils::COLUMN_SCRAPED_DATA_DATE . "," . ConstantUtils::COLUMN_SCRAPED_DATA_AGENT_ID . "," . ConstantUtils::COLUMN_SCRAPED_DATA_AGENT_NAME . "," . ConstantUtils::COLUMN_SCRAPED_DATA_AGENT_MOBILE . "," . ConstantUtils::COLUMN_SCRAPED_DATA_AGENT_EMAIL_ADDRESS . "," . ConstantUtils::COLUMN_SCRAPED_DATA_IMAGE_URL . "," . ConstantUtils::COLUMN_SCRAPED_DATA_CITY_OPERATING . "," . ConstantUtils::COLUMN_SCRAPED_DATA_LISTINGS_IN_RENT . "," . ConstantUtils::COLUMN_SCRAPED_DATA_LISTINGS_IN_SALE . "," . ConstantUtils::COLUMN_SCRAPED_DATA_COMPANY_NAME . "," . ConstantUtils::COLUMN_SCRAPED_DATA_COMPANY_ADDRESS . "," . ConstantUtils::COLUMN_SCRAPED_DATA_COMPANY_CONTACT_NO . "," . ConstantUtils::COLUMN_SCRAPED_DATA_DATE_OF_REGISTRATION . ") VALUES";
            for ($i = 0; $i < count($inputData); $i++) {
                $latest_crawl = true;
                $AGENT_ID = pg_escape_string($con, $inputData[$i][ConstantUtils::AGENT_ID]);
                $AGENT_NAME = pg_escape_string($con, $inputData[$i][ConstantUtils::AGENT_NAME]);
                $AGENT_MOBILE = pg_escape_string($con, $inputData[$i][ConstantUtils::AGENT_MOBILE]);
                $AGENT_EMAIL_ADDRESS = pg_escape_string($con, $inputData[$i][ConstantUtils::AGENT_EMAIL_ADDRESS]);
                $PROFILE_PICTURE_URL = pg_escape_string($con, $inputData[$i][ConstantUtils::PROFILE_PICTURE_URL]);
                $CITY_OPERATING = pg_escape_string($con, $inputData[$i][ConstantUtils::CITY_OPERATING]);
                $LISTING_IN_RENT = pg_escape_string($con, $inputData[$i][ConstantUtils::LISTING_IN_RENT]);
                $LISTING_IN_SALE = pg_escape_string($con, $inputData[$i][ConstantUtils::LISTING_IN_SALE]);
                $COMPANY_NAME = pg_escape_string($con, $inputData[$i][ConstantUtils::COMPANY_NAME]);
                $COMPANY_ADDRESS = pg_escape_string($con, $inputData[$i][ConstantUtils::COMPANY_ADDRESS]);
                $COMPANY_CONTACT_NO = pg_escape_string($con, $inputData[$i][ConstantUtils::COMPANY_CONTACT_NO]);
                $DATE_OF_REGISTRATION = pg_escape_string($con, $inputData[$i][ConstantUtils::DATE_OF_REGISTRATION]);

                $batchSizeCounter = ($i + 1) % $batchSize;
                if ($batchSizeCounter != 0) {
                    if ($i != 0) {
                        $qry .= ",";
                    }
                } else {
                    $qry = rtrim($qry, ',');
                    echo $qry;
                    $res = pg_query($con, $qry);
                    $res = pg_result_status($res);
                    if ($res == 1) {
                        $this->insertedRecordCounter += $batchSize;
                    }
                    $qry = "INSERT INTO " . ConstantUtils::TABLE_SCRAPED_DATA . "(" . ConstantUtils::COLUMN_SCRAPED_DATA_CRAWL_TYPE . "," . ConstantUtils::COLUMN_SCRAPED_DATA_SOURCE . "," . ConstantUtils::COLUMN_SCRAPED_DATA_LATEST_CRAWL . "," . ConstantUtils::COLUMN_SCRAPED_DATA_DATE . "," . ConstantUtils::COLUMN_SCRAPED_DATA_AGENT_ID . "," . ConstantUtils::COLUMN_SCRAPED_DATA_AGENT_NAME . "," . ConstantUtils::COLUMN_SCRAPED_DATA_AGENT_MOBILE . "," . ConstantUtils::COLUMN_SCRAPED_DATA_AGENT_EMAIL_ADDRESS . "," . ConstantUtils::COLUMN_SCRAPED_DATA_IMAGE_URL . "," . ConstantUtils::COLUMN_SCRAPED_DATA_CITY_OPERATING . "," . ConstantUtils::COLUMN_SCRAPED_DATA_LISTINGS_IN_RENT . "," . ConstantUtils::COLUMN_SCRAPED_DATA_LISTINGS_IN_SALE . "," . ConstantUtils::COLUMN_SCRAPED_DATA_COMPANY_NAME . "," . ConstantUtils::COLUMN_SCRAPED_DATA_COMPANY_ADDRESS . "," . ConstantUtils::COLUMN_SCRAPED_DATA_COMPANY_CONTACT_NO . "," . ConstantUtils::COLUMN_SCRAPED_DATA_DATE_OF_REGISTRATION . ") VALUES";
                }
                $qry .= "('$crawlType','$source','$latest_crawl','$date','$AGENT_ID','$AGENT_NAME','$AGENT_MOBILE','$AGENT_EMAIL_ADDRESS','$PROFILE_PICTURE_URL','$CITY_OPERATING','$LISTING_IN_RENT','$LISTING_IN_SALE','$COMPANY_NAME','$COMPANY_ADDRESS','$COMPANY_CONTACT_NO','$DATE_OF_REGISTRATION') ";
            }
            $qry = rtrim($qry, ',');
            echo $qry;
            $res = pg_query($con, $qry);
            $res = pg_result_status($res);
            if ($res == 1) {
                $this->insertedRecordCounter += $batchSizeCounter - 1;
                $this->insertedRecordCounter;
            }
            return $this->insertedRecordCounter;
        }catch(\Exception $exp){
            $this->logger->error("Exception in saving magicbricks agents data into redshift" ,$exp);
            return null;
        }
    }

    public function truncate($string, $length) {
        return (strlen($string) > $length) ?substr($string, 0, $length): $string;
    }

    /**
     * function saveMagicbricksListingsRecord insert records from magicbricks listings S3 feed to redshift and returns number of records inserted successfully
     * @param  array $inputData dataArray from feed
     * @param string $crawlType for ex. listing_details or agent_details
     * @param string $source for ex. magicbricks.com
     * @param string $date date for ex. 2015-10-05
     * @return  integer
     */
    public function saveMagicbricksListingsRecord($inputData,$crawlType,$source,$date){
        $con = $this->PostgreSQL_connect();
        if (!isset($con)){
            $this->logger->error("PostgreSQL Connection not set hence exiting");
            return null;
        }
        if(isset($this->insertedRecordCounter)){
            $this->insertedRecordCounter=1;
        }
        try {
            $batchSize = ConstantUtils::BATCH_INSERTION_SIZE;
            $this->logger->debug("Batch insertion size=".$batchSize);
            $qry = "INSERT INTO " . ConstantUtils::TABLE_SCRAPED_DATA . "(" . ConstantUtils::COLUMN_SCRAPED_DATA_CRAWL_TYPE . "," . ConstantUtils::COLUMN_SCRAPED_DATA_SOURCE . "," . ConstantUtils::COLUMN_SCRAPED_DATA_LATEST_CRAWL . "," . ConstantUtils::COLUMN_SCRAPED_DATA_DATE . "," . ConstantUtils::COLUMN_SCRAPED_DATA_AGENT_ID . "," . ConstantUtils::COLUMN_SCRAPED_DATA_AGENT_NAME . "," . ConstantUtils::COLUMN_SCRAPED_DATA_AGENT_MOBILE . "," . ConstantUtils::COLUMN_SCRAPED_DATA_LISTING_ID . "," . ConstantUtils::COLUMN_SCRAPED_DATA_LISTING_PRICE. "," . ConstantUtils::COLUMN_SCRAPED_DATA_LISTING_TRANSACTION_TYPE . "," . ConstantUtils::COLUMN_SCRAPED_DATA_LISTING_LOCATION . "," . ConstantUtils::COLUMN_SCRAPED_DATA_LISTING_CITY . "," . ConstantUtils::COLUMN_SCRAPED_DATA_LISTING_CATEGORY . "," . ConstantUtils::COLUMN_SCRAPED_DATA_PROPERTY_CATEGORY . "," . ConstantUtils::COLUMN_SCRAPED_DATA_PROPERTY_AGE . "," . ConstantUtils::COLUMN_SCRAPED_DATA_SELLER_CATEGORY . "," . ConstantUtils::COLUMN_SCRAPED_DATA_COMPANY_NAME . "," . ConstantUtils::COLUMN_SCRAPED_DATA_COMPANY_CONTACT_NO . "," . ConstantUtils::COLUMN_SCRAPED_DATA_PROPERTY_AREA . "," . ConstantUtils::COLUMN_SCRAPED_DATA_PROPERTY_RATE_PER_SQ_FT . "," . ConstantUtils::COLUMN_SCRAPED_DATA_IS_VERIFIED . "," . ConstantUtils::LATITUDE . "," . ConstantUtils::LONGITUDE.",".ConstantUtils::COLUMN_SCRAPED_DATA_LISTING_LOCALITY.",".ConstantUtils::COLUMN_SCRAPED_DATA_LISTING_LOCALITY_ID . ") VALUES";
            for ($i = 0; $i < count($inputData); $i++) {
                $latest_crawl = true;
                $AGENT_ID = pg_escape_string($con, $inputData[$i][ConstantUtils::AGENT_ID]);
                $AGENT_NAME = pg_escape_string($con, $inputData[$i][ConstantUtils::AGENT_NAME]);
                $AGENT_MOBILE = pg_escape_string($con, $inputData[$i][ConstantUtils::AGENT_MOBILE]);
                $LISTING_ID = pg_escape_string($con, $inputData[$i][ConstantUtils::LISTING_ID]);
                $LISTING_PRICE = pg_escape_string($con, $inputData[$i][ConstantUtils::LISTING_PRICE]);
                #$IMAGE_URL = pg_escape_string($con, $inputData[$i][ConstantUtils::LISTING_IMAGE_URL]);
                $LISTING_CATEGORY = pg_escape_string($con, $inputData[$i][ConstantUtils::LISTING_CATEGORY]);
                $PROPERTY_CATEGORY = pg_escape_string($con, $inputData[$i][ConstantUtils::PROPERTY_CATEGORY]);
                $SELLER_CATEGORY = pg_escape_string($con, $inputData[$i][ConstantUtils::SELLER_CATEGORY]);
                $PROPERTY_AGE = pg_escape_string($con, $inputData[$i][ConstantUtils::PROPERTY_AGE]);
                $COMPANY_NAME = pg_escape_string($con, $inputData[$i][ConstantUtils::COMPANY_NAME]);
                $LISTING_TRANSACTION_TYPE = pg_escape_string($con, $inputData[$i][ConstantUtils::LISTING_TRANSACTION_TYPE]);
                $LISTING_LOCATION = pg_escape_string($con, $inputData[$i][ConstantUtils::LISTING_LOCATION]);
                $LISTING_CITY = pg_escape_string($con, $inputData[$i][ConstantUtils::LISTING_CITY]);
                $COMPANY_CONTACT_NO = pg_escape_string($con, $inputData[$i][ConstantUtils::COMPANY_CONTACT_NO]);
                $PROPERTY_AREA = pg_escape_string($con, $inputData[$i][ConstantUtils::PROPERTY_AREA]);
                $PROPERTY_RATE_PER_SQ_FT = pg_escape_string($con, $inputData[$i][ConstantUtils::PROPERTY_RATE_PER_SQ_FT]);
                $IS_AGENT_VERIFIED = pg_escape_string($con, $inputData[$i][ConstantUtils::IS_AGENT_VERIFIED]);
                $LATITUDE = pg_escape_string($con, $inputData[$i][ConstantUtils::LATITUDE]);
                $LONGITUDE = pg_escape_string($con, $inputData[$i][ConstantUtils::LONGITUDE]);
                $LISTING_LOCALITY = pg_escape_string($con,$inputData[$i][ConstantUtils::LISTING_LOCALITY]);
                $LISTING_LOCALITY_ID = pg_escape_string($con,$inputData[$i][ConstantUtils::LISTING_LOCALITY_ID]);

                $AGENT_ID=$this->truncate($AGENT_ID,100);
                $AGENT_NAME=$this->truncate($AGENT_NAME,100);
                $AGENT_MOBILE=$this->truncate($AGENT_MOBILE,100);
                $LISTING_ID=$this->truncate($LISTING_ID,100);
                $LISTING_PRICE=$this->truncate($LISTING_PRICE,100);
                $LISTING_CATEGORY=$this->truncate($LISTING_CATEGORY,100);
                $PROPERTY_CATEGORY=$this->truncate($PROPERTY_CATEGORY,100);
                $SELLER_CATEGORY=$this->truncate($SELLER_CATEGORY,100);
                $PROPERTY_AGE=$this->truncate($PROPERTY_AGE,100);
                $COMPANY_NAME=$this->truncate($COMPANY_NAME,150);
                $LISTING_TRANSACTION_TYPE=$this->truncate($LISTING_TRANSACTION_TYPE,100);
                $LISTING_LOCATION=$this->truncate($LISTING_LOCATION,200);
                $LISTING_CITY=$this->truncate($LISTING_CITY,100);
                $COMPANY_CONTACT_NO=$this->truncate($COMPANY_CONTACT_NO,100);
                $PROPERTY_AREA=$this->truncate($PROPERTY_AREA,50);
                $PROPERTY_RATE_PER_SQ_FT=$this->truncate($PROPERTY_RATE_PER_SQ_FT,50);
                $IS_AGENT_VERIFIED=$this->truncate($IS_AGENT_VERIFIED,50);
                $LATITUDE=$this->truncate($LATITUDE,50);
                $LONGITUDE=$this->truncate($LONGITUDE,50);
                $LISTING_LOCALITY=$this->truncate($LISTING_LOCALITY,200);
                $LISTING_LOCALITY_ID=$this->truncate($LISTING_LOCALITY_ID,50);

                if(!is_numeric($LATITUDE)){
                    $LATITUDE=0;
                }
                if(!is_numeric($LONGITUDE)){
                    $LONGITUDE=0;
                }
                $batchSizeCounter = ($i + 1) % $batchSize;
                if ($batchSizeCounter != 0) {
                    if ($i != 0) {
                        $qry .= ",";
                    }
                } else {
                    $qry = rtrim($qry, ',');
                    echo $qry;
                    $res = pg_query($con, $qry);
                    $res = pg_result_status($res);
                    if ($res == 1) {
                        $this->insertedRecordCounter += $batchSize;
                    }
                    $qry = "INSERT INTO " . ConstantUtils::TABLE_SCRAPED_DATA . "(" . ConstantUtils::COLUMN_SCRAPED_DATA_CRAWL_TYPE . "," . ConstantUtils::COLUMN_SCRAPED_DATA_SOURCE . "," . ConstantUtils::COLUMN_SCRAPED_DATA_LATEST_CRAWL . "," . ConstantUtils::COLUMN_SCRAPED_DATA_DATE . "," . ConstantUtils::COLUMN_SCRAPED_DATA_AGENT_ID . "," . ConstantUtils::COLUMN_SCRAPED_DATA_AGENT_NAME . "," . ConstantUtils::COLUMN_SCRAPED_DATA_AGENT_MOBILE . "," . ConstantUtils::COLUMN_SCRAPED_DATA_LISTING_ID . "," . ConstantUtils::COLUMN_SCRAPED_DATA_LISTING_PRICE . "," . ConstantUtils::COLUMN_SCRAPED_DATA_LISTING_TRANSACTION_TYPE . "," . ConstantUtils::COLUMN_SCRAPED_DATA_LISTING_LOCATION . "," . ConstantUtils::COLUMN_SCRAPED_DATA_LISTING_CITY . "," . ConstantUtils::COLUMN_SCRAPED_DATA_LISTING_CATEGORY . "," . ConstantUtils::COLUMN_SCRAPED_DATA_PROPERTY_CATEGORY . "," . ConstantUtils::COLUMN_SCRAPED_DATA_PROPERTY_AGE . "," . ConstantUtils::COLUMN_SCRAPED_DATA_SELLER_CATEGORY . "," . ConstantUtils::COLUMN_SCRAPED_DATA_COMPANY_NAME . "," . ConstantUtils::COLUMN_SCRAPED_DATA_COMPANY_CONTACT_NO . "," . ConstantUtils::COLUMN_SCRAPED_DATA_PROPERTY_AREA . "," . ConstantUtils::COLUMN_SCRAPED_DATA_PROPERTY_RATE_PER_SQ_FT . "," . ConstantUtils::COLUMN_SCRAPED_DATA_IS_VERIFIED . "," . ConstantUtils::LATITUDE . "," . ConstantUtils::LONGITUDE.",".ConstantUtils::COLUMN_SCRAPED_DATA_LISTING_LOCALITY.",".ConstantUtils::COLUMN_SCRAPED_DATA_LISTING_LOCALITY_ID  . ") VALUES";
                }
                $qry .= "('$crawlType','$source','$latest_crawl','$date','$AGENT_ID','$AGENT_NAME','$AGENT_MOBILE','$LISTING_ID','$LISTING_PRICE','$LISTING_TRANSACTION_TYPE','$LISTING_LOCATION','$LISTING_CITY','$LISTING_CATEGORY','$PROPERTY_CATEGORY','$PROPERTY_AGE','$SELLER_CATEGORY','$COMPANY_NAME','$COMPANY_CONTACT_NO','$PROPERTY_AREA','$PROPERTY_RATE_PER_SQ_FT','$IS_AGENT_VERIFIED','$LATITUDE','$LONGITUDE','$LISTING_LOCALITY','$LISTING_LOCALITY_ID') ";
            }
            $qry = rtrim($qry, ',');
            echo $qry;
            $res = pg_query($con, $qry);
            $res = pg_result_status($res);
            if ($res == 1) {
                $this->insertedRecordCounter += $batchSizeCounter - 1;
                $this->insertedRecordCounter;
            }
            return $this->insertedRecordCounter;
        }catch(\Exception $exp){
            $this->logger->error("Exception in saving magicbricks listing data into redshift" ,$exp);
            return null;
        }
    }


    /**
     * function saveOlxLisitngsRecord insert records from olx listings S3 feed to redshift and returns number of records inserted successfully
     * @param  array $inputData dataArray from feed
     * @param string $crawlType for ex. listing_details or agent_details
     * @param string $source for ex. olx.in
     * @param string $date date for ex. 2015-10-05
     * @return  integer
     */
    public function saveOlxLisitngsRecord($inputData,$crawlType,$source,$date){
        $con = $this->PostgreSQL_connect();
        if (!isset($con)){
            $this->logger->error("PostgreSQL Connection not set hence exiting");
            return null;
        }
        if(isset($this->insertedRecordCounter)){
            $this->insertedRecordCounter=1;
        }
        try {
            $batchSize = ConstantUtils::BATCH_INSERTION_SIZE;
            $this->logger->debug("Batch insertion size=".$batchSize);
            $qry = "INSERT INTO " . ConstantUtils::TABLE_SCRAPED_DATA . "(" . ConstantUtils::COLUMN_SCRAPED_DATA_CRAWL_TYPE . "," . ConstantUtils::COLUMN_SCRAPED_DATA_SOURCE . "," . ConstantUtils::COLUMN_SCRAPED_DATA_LATEST_CRAWL . "," . ConstantUtils::COLUMN_SCRAPED_DATA_DATE . "," . ConstantUtils::COLUMN_SCRAPED_DATA_AGENT_ID . "," . ConstantUtils::COLUMN_SCRAPED_DATA_AGENT_NAME . "," . ConstantUtils::COLUMN_SCRAPED_DATA_AGENT_MOBILE . "," . ConstantUtils::COLUMN_SCRAPED_DATA_LISTING_ID . "," . ConstantUtils::COLUMN_SCRAPED_DATA_LISTING_TITLE . "," . ConstantUtils::COLUMN_SCRAPED_DATA_LISTING_PRICE . "," . ConstantUtils::COLUMN_SCRAPED_DATA_LISTING_POSTED_ON . "," . ConstantUtils::COLUMN_SCRAPED_DATA_PROPERTY_CATEGORY . "," . ConstantUtils::COLUMN_SCRAPED_DATA_LISTING_TRANSACTION_TYPE . "," . ConstantUtils::COLUMN_SCRAPED_DATA_LISTING_LOCATION . "," . ConstantUtils::COLUMN_SCRAPED_DATA_LISTING_CITY . "," . ConstantUtils::COLUMN_SCRAPED_DATA_IMAGES_PER_LISTING . "," . ConstantUtils::COLUMN_SCRAPED_DATA_LISTING_URL . ") VALUES";
            for ($i = 0; $i < count($inputData); $i++) {
                $latest_crawl = true;
                $AGENT_ID = pg_escape_string($con, $inputData[$i][ConstantUtils::AGENT_ID]);
                $AGENT_NAME = pg_escape_string($con, $inputData[$i][ConstantUtils::AGENT_NAME]);
                $AGENT_MOBILE = pg_escape_string($con, $inputData[$i][ConstantUtils::AGENT_MOBILE]);
                $LISTING_ID = pg_escape_string($con, $inputData[$i][ConstantUtils::LISTING_ID]);
                $LISTING_TITLE = pg_escape_string($con, $inputData[$i][ConstantUtils::LISTING_TITLE]);
                $LISTING_PRICE = pg_escape_string($con, $inputData[$i][ConstantUtils::LISTING_PRICE]);
                $LISTING_POSTED_ON = pg_escape_string($con, $inputData[$i][ConstantUtils::LISTING_POSTED_ON]);
                $LISTING_TRANSACTION_TYPE = pg_escape_string($con, $inputData[$i][ConstantUtils::LISTING_TRANSACTION_TYPE]);
                $LISTING_LOCATION = pg_escape_string($con, $inputData[$i][ConstantUtils::LISTING_LOCATION]);
                $LISTING_CITY = pg_escape_string($con, $inputData[$i][ConstantUtils::LISTING_CITY]);
                $IMAGES_PER_LISTING = pg_escape_string($con, $inputData[$i][ConstantUtils::IMAGES_PER_LISTING]);
                #$LISTING_DESCRIPTION = pg_escape_string($con, $inputData[$i][ConstantUtils::LISTING_DESCRIPTION]);
                $LISTING_URL = pg_escape_string($con, $inputData[$i][ConstantUtils::LISTING_URL]);

                $removeUnwantedCharacters = array(" ", ",", "Added", "at", "added", "At", '\t', 'on');
                $LISTING_POSTED_ON = ltrim(str_replace($removeUnwantedCharacters, "", $LISTING_POSTED_ON));

                $arr = explode('»', $LISTING_TRANSACTION_TYPE);
                if (count($arr) > 1) {
                    $PROPERTY_CATEGORY = ucfirst(trim($arr[0]));
                    $LISTING_TRANSACTION_TYPE = ucfirst($arr[1]);
                } else {
                    $PROPERTY_CATEGORY = "NA";
                    $LISTING_TRANSACTION_TYPE = "NA";
                }

                $batchSizeCounter = ($i + 1) % $batchSize;
                if ($batchSizeCounter != 0) {
                    if ($i != 0) {
                        $qry .= ",";
                    }
                } else {
                    $qry = rtrim($qry, ',');
                    echo $qry;
                    $res = pg_query($con, $qry);
                    $res = pg_result_status($res);
                    if ($res == 1) {
                        $this->insertedRecordCounter += $batchSize;
                    }
                    $qry = "INSERT INTO " . ConstantUtils::TABLE_SCRAPED_DATA . "(" . ConstantUtils::COLUMN_SCRAPED_DATA_CRAWL_TYPE . "," . ConstantUtils::COLUMN_SCRAPED_DATA_SOURCE . "," . ConstantUtils::COLUMN_SCRAPED_DATA_LATEST_CRAWL . "," . ConstantUtils::COLUMN_SCRAPED_DATA_DATE . "," . ConstantUtils::COLUMN_SCRAPED_DATA_AGENT_ID . "," . ConstantUtils::COLUMN_SCRAPED_DATA_AGENT_NAME . "," . ConstantUtils::COLUMN_SCRAPED_DATA_AGENT_MOBILE . "," . ConstantUtils::COLUMN_SCRAPED_DATA_LISTING_ID . "," . ConstantUtils::COLUMN_SCRAPED_DATA_LISTING_TITLE . "," . ConstantUtils::COLUMN_SCRAPED_DATA_LISTING_PRICE . "," . ConstantUtils::COLUMN_SCRAPED_DATA_LISTING_POSTED_ON . "," . ConstantUtils::COLUMN_SCRAPED_DATA_PROPERTY_CATEGORY . "," . ConstantUtils::COLUMN_SCRAPED_DATA_LISTING_TRANSACTION_TYPE . "," . ConstantUtils::COLUMN_SCRAPED_DATA_LISTING_LOCATION . "," . ConstantUtils::COLUMN_SCRAPED_DATA_LISTING_CITY . "," . ConstantUtils::COLUMN_SCRAPED_DATA_IMAGES_PER_LISTING . "," . ConstantUtils::COLUMN_SCRAPED_DATA_LISTING_URL . ") VALUES";
                }
                $qry .= "('$crawlType','$source','$latest_crawl','$date','$AGENT_ID','$AGENT_NAME','$AGENT_MOBILE','$LISTING_ID','$LISTING_TITLE','$LISTING_PRICE','$LISTING_POSTED_ON','$PROPERTY_CATEGORY','$LISTING_TRANSACTION_TYPE','$LISTING_LOCATION','$LISTING_CITY','$IMAGES_PER_LISTING','$LISTING_URL') ";
            }
            $qry = rtrim($qry, ',');
            echo $qry;
            $res = pg_query($con, $qry);
            $res = pg_result_status($res);
            if ($res == 1) {
                $this->insertedRecordCounter += $batchSizeCounter - 1;
                $this->insertedRecordCounter;
            }
            return $this->insertedRecordCounter;
        }catch(\Exception $exp){
            $this->logger->error("Exception in saving olx listing data into redshift" ,$exp);
            return null;
        }
    }


    /**
     * function nnAcresListingsRecord insert records from 99acres.com listings S3 feed to redshift and returns number of records inserted successfully
     * @param  array $inputData dataArray from feed
     * @param string $crawlType for ex. listing_details or agent_details
     * @param string $source for ex. 99acres.com
     * @param string $date date for ex. 2015-10-05
     * @return  integer
     */
    public function nnAcresListingsRecord($filteredData,$crawlType,$source,$date){
        $con = $this->PostgreSQL_connect();
        if (!isset($con)){
            $this->logger->error("PostgreSQL Connection not set hence exiting");
            return null;
        }
        if(isset($this->insertedRecordCounter)){
            $this->insertedRecordCounter=1;
        }
        try {
            $batchSize = ConstantUtils::BATCH_INSERTION_SIZE;
            $this->logger->debug("Batch insertion size=".$batchSize);
            $qry = "INSERT INTO " . ConstantUtils::TABLE_SCRAPED_DATA . "(" . ConstantUtils::COLUMN_SCRAPED_DATA_CRAWL_TYPE . "," . ConstantUtils::COLUMN_SCRAPED_DATA_SOURCE . "," . ConstantUtils::COLUMN_SCRAPED_DATA_LATEST_CRAWL . "," . ConstantUtils::COLUMN_SCRAPED_DATA_DATE . "," . ConstantUtils::COLUMN_SCRAPED_DATA_AGENT_ID . "," . ConstantUtils::COLUMN_SCRAPED_DATA_AGENT_NAME . "," . ConstantUtils::COLUMN_SCRAPED_DATA_AGENT_MOBILE . "," . ConstantUtils::COLUMN_SCRAPED_DATA_AGENT_EMAIL_ADDRESS . "," . ConstantUtils::COLUMN_SCRAPED_DATA_COMPANY_NAME . "," . ConstantUtils::COLUMN_SCRAPED_DATA_COMPANY_CONTACT_NO . "," . ConstantUtils::COLUMN_SCRAPED_DATA_LISTING_ID . "," . ConstantUtils::COLUMN_SCRAPED_DATA_LISTING_TITLE . "," . ConstantUtils::COLUMN_SCRAPED_DATA_LISTING_PRICE . "," . ConstantUtils::COLUMN_SCRAPED_DATA_IMAGE_URL . "," . ConstantUtils::COLUMN_SCRAPED_DATA_IMAGES_PER_LISTING . "," . ConstantUtils::COLUMN_SCRAPED_DATA_LISTING_LOCATION . "," . ConstantUtils::COLUMN_SCRAPED_DATA_LISTING_CITY . "," . ConstantUtils::COLUMN_SCRAPED_DATA_SELLER_CATEGORY . "," . ConstantUtils::COLUMN_SCRAPED_DATA_LISTING_POSTED_ON . "," . ConstantUtils::COLUMN_SCRAPED_DATA_PROPERTY_CATEGORY . "," . ConstantUtils::COLUMN_SCRAPED_DATA_PROPERTY_AREA . "," . ConstantUtils::COLUMN_SCRAPED_DATA_PROPERTY_RATE_PER_SQ_FT . "," . ConstantUtils::COLUMN_SCRAPED_DATA_ZOOM_LEVEL . "," . ConstantUtils::COLUMN_SCRAPED_DATA_LONGITUDE . "," . ConstantUtils::COLUMN_SCRAPED_DATA_LATITUDE . "," . ConstantUtils::COLUMN_SCRAPED_DATA_LISTING_AMENITIES . "," . ConstantUtils::COLUMN_SCRAPED_DATA_LISTING_DESCRIPTION.",".ConstantUtils::COLUMN_SCRAPED_DATA_LISTING_TRANSACTION_TYPE . ") VALUES";
            for ($i = 0; $i < count($filteredData); $i++) {
                $latest_crawl = true;
                $AGENT_ID = pg_escape_string($con, $filteredData[$i][ConstantUtils::AGENT_ID]);
                $AGENT_NAME = pg_escape_string($con, $filteredData[$i][ConstantUtils::AGENT_NAME]);
                $AGENT_MOBILE = pg_escape_string($con, $filteredData[$i][ConstantUtils::AGENT_MOBILE]);
                $AGENT_EMAIL_ADDRESS = pg_escape_string($con, $filteredData[$i][ConstantUtils::AGENT_EMAIL_ADDRESS]);
                $COMPANY_NAME = pg_escape_string($con, $filteredData[$i][ConstantUtils::COMPANY_NAME]);
                $COMPANY_CONTACT_NO = pg_escape_string($con, $filteredData[$i][ConstantUtils::COMPANY_CONTACT_NO]);
                $LISTING_ID = pg_escape_string($con, $filteredData[$i][ConstantUtils::LISTING_ID]);
                $LISTING_TITLE = pg_escape_string($con, $filteredData[$i][ConstantUtils::LISTING_TITLE]);
                $LISTING_PRICE = pg_escape_string($con, $filteredData[$i][ConstantUtils::LISTING_PRICE]);
                $IMAGE_URL = pg_escape_string($con, $filteredData[$i][ConstantUtils::LISTING_IMAGE_URL]);
                $IMAGES_PER_LISTING = pg_escape_string($con, $filteredData[$i][ConstantUtils::IMAGES_PER_LISTING]);
                $LISTING_LOCATION = pg_escape_string($con, $filteredData[$i][ConstantUtils::LISTING_LOCATION]);
                $LISTING_CITY = pg_escape_string($con, $filteredData[$i][ConstantUtils::LISTING_CITY]);
                $SELLER_CATEGORY = pg_escape_string($con, $filteredData[$i][ConstantUtils::SELLER_CATEGORY]);
                $LISTING_POSTED_ON = pg_escape_string($con, $filteredData[$i][ConstantUtils::LISTING_POSTED_ON]);
                $PROPERTY_CATEGORY = pg_escape_string($con, $filteredData[$i][ConstantUtils::PROPERTY_CATEGORY]);
                $PROPERTY_AREA = pg_escape_string($con, $filteredData[$i][ConstantUtils::PROPERTY_AREA]);
                $PROPERTY_RATE_PER_SQ_FT = pg_escape_string($con, $filteredData[$i][ConstantUtils::PROPERTY_RATE_PER_SQ_FT]);
                $ZOOM_LEVEL = pg_escape_string($con, $filteredData[$i][ConstantUtils::ZOOM_LEVEL]);
                $LONGITUDE = pg_escape_string($con, $filteredData[$i][ConstantUtils::LONGITUDE]);
                $LATITUDE = pg_escape_string($con, $filteredData[$i][ConstantUtils::LATITUDE]);
                $LISTING_AMENITIES = pg_escape_string($con, $filteredData[$i][ConstantUtils::LISTING_AMENITIES]);
                $LISTING_DESCRIPTION = pg_escape_string($con, $filteredData[$i][ConstantUtils::LISTING_DESCRIPTION]);
                //$LISTING_TRANSACTION_TYPE=pg_escape_string($con,$filteredData[$i][ConstantUtils::LISTING_TRANSACTION_TYPE]);
                $LISTING_TRANSACTION_TYPE='Buy';
                $batchSizeCounter = ($i + 1) % $batchSize;
                if ($batchSizeCounter != 0) {
                    if ($i != 0) {
                        $qry .= ",";
                    }
                } else {
                    $qry = rtrim($qry, ',');
                    echo $qry;
                    $res = pg_query($con, $qry);
                    $res = pg_result_status($res);
                    if ($res == 1) {
                        $this->insertedRecordCounter += $batchSize;
                    }
                    $qry = "INSERT INTO " . ConstantUtils::TABLE_SCRAPED_DATA . "(" . ConstantUtils::COLUMN_SCRAPED_DATA_CRAWL_TYPE . "," . ConstantUtils::COLUMN_SCRAPED_DATA_SOURCE . "," . ConstantUtils::COLUMN_SCRAPED_DATA_LATEST_CRAWL . "," . ConstantUtils::COLUMN_SCRAPED_DATA_DATE . "," . ConstantUtils::COLUMN_SCRAPED_DATA_AGENT_ID . "," . ConstantUtils::COLUMN_SCRAPED_DATA_AGENT_NAME . "," . ConstantUtils::COLUMN_SCRAPED_DATA_AGENT_MOBILE . "," . ConstantUtils::COLUMN_SCRAPED_DATA_AGENT_EMAIL_ADDRESS . "," . ConstantUtils::COLUMN_SCRAPED_DATA_COMPANY_NAME . "," . ConstantUtils::COLUMN_SCRAPED_DATA_COMPANY_CONTACT_NO . "," . ConstantUtils::COLUMN_SCRAPED_DATA_LISTING_ID . "," . ConstantUtils::COLUMN_SCRAPED_DATA_LISTING_TITLE . "," . ConstantUtils::COLUMN_SCRAPED_DATA_LISTING_PRICE . "," . ConstantUtils::COLUMN_SCRAPED_DATA_IMAGE_URL . "," . ConstantUtils::COLUMN_SCRAPED_DATA_IMAGES_PER_LISTING . "," . ConstantUtils::COLUMN_SCRAPED_DATA_LISTING_LOCATION . "," . ConstantUtils::COLUMN_SCRAPED_DATA_LISTING_CITY . "," . ConstantUtils::COLUMN_SCRAPED_DATA_SELLER_CATEGORY . "," . ConstantUtils::COLUMN_SCRAPED_DATA_LISTING_POSTED_ON . "," . ConstantUtils::COLUMN_SCRAPED_DATA_PROPERTY_CATEGORY . "," . ConstantUtils::COLUMN_SCRAPED_DATA_PROPERTY_AREA . "," . ConstantUtils::COLUMN_SCRAPED_DATA_PROPERTY_RATE_PER_SQ_FT . "," . ConstantUtils::COLUMN_SCRAPED_DATA_ZOOM_LEVEL . "," . ConstantUtils::COLUMN_SCRAPED_DATA_LONGITUDE . "," . ConstantUtils::COLUMN_SCRAPED_DATA_LATITUDE . "," . ConstantUtils::COLUMN_SCRAPED_DATA_LISTING_AMENITIES . "," . ConstantUtils::COLUMN_SCRAPED_DATA_LISTING_DESCRIPTION.",".ConstantUtils::COLUMN_SCRAPED_DATA_LISTING_TRANSACTION_TYPE . ") VALUES";
                }
                $qry .= "('$crawlType','$source','$latest_crawl','$date','$AGENT_ID','$AGENT_NAME','$AGENT_MOBILE','$AGENT_EMAIL_ADDRESS','$COMPANY_NAME','$COMPANY_CONTACT_NO','$LISTING_ID','$LISTING_TITLE','$LISTING_PRICE','$IMAGE_URL','$IMAGES_PER_LISTING','$LISTING_LOCATION','$LISTING_CITY','$SELLER_CATEGORY','$LISTING_POSTED_ON','$PROPERTY_CATEGORY','$PROPERTY_AREA','$PROPERTY_RATE_PER_SQ_FT','$ZOOM_LEVEL','$LONGITUDE','$LATITUDE','$LISTING_AMENITIES','$LISTING_DESCRIPTION','$LISTING_TRANSACTION_TYPE')";
            }
            $qry = rtrim($qry, ',');
            echo $qry;
            $res = pg_query($con, $qry);
            $res = pg_result_status($res);
            if ($res == 1) {
                $this->insertedRecordCounter += $batchSizeCounter - 1;
                $this->insertedRecordCounter;
            }
            return $this->insertedRecordCounter;
        }catch(\Exception $exp){
            $this->logger->error("Exception in saving 99acres data into redshift" ,$exp);
            return null;
        }
    }


    /**
     * function saveQuikrListingsRecord insert records from quikr.com listings S3 feed to redshift and returns number of records inserted successfully
     * @param  array $inputData dataArray from feed
     * @param string $crawlType for ex. listing_details or agent_details
     * @param string $source source= quikr.com
     * @param string $date date for ex. 2015-10-05
     * @return  integer
     */
    public function saveQuikrListingsRecord($inputData,$crawlType,$source,$date){
        $con = $this->PostgreSQL_connect();
        if (!isset($con)){
            $this->logger->error("PostgreSQL Connection not set hence exiting");
            return null;
        }
        if(isset($this->insertedRecordCounter)){
            $this->insertedRecordCounter=1;
        }
        try {
            $batchSize = ConstantUtils::BATCH_INSERTION_SIZE;
            $this->logger->debug("Batch insertion size=".$batchSize);
            $qry = "INSERT INTO " . ConstantUtils::TABLE_SCRAPED_DATA . "(" . ConstantUtils::COLUMN_SCRAPED_DATA_CRAWL_TYPE . "," . ConstantUtils::COLUMN_SCRAPED_DATA_SOURCE . "," . ConstantUtils::COLUMN_SCRAPED_DATA_LATEST_CRAWL . "," . ConstantUtils::COLUMN_SCRAPED_DATA_DATE . "," . ConstantUtils::COLUMN_SCRAPED_DATA_AGENT_ID . "," . ConstantUtils::COLUMN_SCRAPED_DATA_AGENT_MOBILE . "," . ConstantUtils::COLUMN_SCRAPED_DATA_LISTING_ID . "," . ConstantUtils::COLUMN_SCRAPED_DATA_LISTING_TITLE . "," . ConstantUtils::COLUMN_SCRAPED_DATA_LISTING_PRICE . "," . ConstantUtils::COLUMN_SCRAPED_DATA_IMAGE_URL . "," . ConstantUtils::COLUMN_SCRAPED_DATA_AGENT_NAME . "," . ConstantUtils::COLUMN_SCRAPED_DATA_LISTING_LOCATION . "," . ConstantUtils::COLUMN_SCRAPED_DATA_LISTING_CITY . "," . ConstantUtils::COLUMN_SCRAPED_DATA_LISTING_POSTED_ON . "," . ConstantUtils::COLUMN_SCRAPED_DATA_PROPERTY_AREA. "," . ConstantUtils::COLUMN_SCRAPED_DATA_PROPERTY_CATEGORY . "," . ConstantUtils::COLUMN_SCRAPED_DATA_LISTING_TRANSACTION_TYPE . "," . ConstantUtils::COLUMN_SCRAPED_DATA_SELLER_CATEGORY . "," . ConstantUtils::COLUMN_SCRAPED_DATA_LATITUDE.",".ConstantUtils::COLUMN_SCRAPED_DATA_LONGITUDE.",".ConstantUtils::AGENT_EMAIL_ADDRESS . ") VALUES";
            for ($i = 0; $i < count($inputData); $i++) {
                $latest_crawl = true;
                $AGENT_ID     = pg_escape_string($con, $inputData[$i][ConstantUtils::AGENT_MOBILE]);
                $AGENT_MOBILE = pg_escape_string($con, $inputData[$i][ConstantUtils::AGENT_MOBILE]);
                $AGENT_NAME   = pg_escape_string($con, $inputData[$i][ConstantUtils::AGENT_NAME]);
                $LATITUDE     = pg_escape_string($con, $inputData[$i][ConstantUtils::LATITUDE]);
                $LONGITUDE    = pg_escape_string($con, $inputData[$i][ConstantUtils::LONGITUDE]);
                $LISTING_CITY = pg_escape_string($con, $inputData[$i][ConstantUtils::LISTING_CITY]);
                $LISTING_ID   = pg_escape_string($con, $inputData[$i][ConstantUtils::LISTING_ID]);
                $IMAGE_URL    = pg_escape_string($con, $inputData[$i][ConstantUtils::LISTING_IMAGE_URL]);
                $LISTING_LOCATION  = pg_escape_string($con, $inputData[$i][ConstantUtils::LISTING_LOCATION]);
                $LISTING_POSTED_ON = pg_escape_string($con, $inputData[$i][ConstantUtils::LISTING_POSTED_ON]);
                $LISTING_PRICE     = pg_escape_string($con, $inputData[$i][ConstantUtils::LISTING_PRICE]);
                $LISTING_TITLE     = pg_escape_string($con, $inputData[$i][ConstantUtils::LISTING_TITLE]);
                $LISTING_TRANSACTION_TYPE = pg_escape_string($con, $inputData[$i][ConstantUtils::LISTING_TRANSACTION_TYPE]);
                $PROPERTY_AREA     = pg_escape_string($con ,$inputData[$i][ConstantUtils::PROPERTY_AREA]);
                $PROPERTY_CATEGORY = pg_escape_string($con ,$inputData[$i][ConstantUtils::PROPERTY_CATEGORY]);
                $SELLER_CATEGORY   = pg_escape_string($con, $inputData[$i][ConstantUtils::SELLER_CATEGORY]);
                $AGENT_EMAIL_ADDRESS = pg_escape_string($con, $inputData[$i][ConstantUtils::AGENT_EMAIL_ADDRESS]);


                $AGENT_ID=$this->truncate($AGENT_ID,200);
                $AGENT_MOBILE=$this->truncate($AGENT_MOBILE,100);
                $AGENT_NAME=$this->truncate($AGENT_NAME,200);
                $LATITUDE=$this->truncate($LATITUDE,100);
                $LONGITUDE=$this->truncate($LONGITUDE,100);
                $LISTING_CITY=$this->truncate($LISTING_CITY,100);
                $LISTING_ID=$this->truncate($LISTING_ID,150);
                $IMAGE_URL=$this->truncate($IMAGE_URL,500);
                $LISTING_LOCATION=$this->truncate($LISTING_LOCATION,250);
                $LISTING_POSTED_ON=$this->truncate($LISTING_POSTED_ON,200);
                $LISTING_PRICE=$this->truncate($LISTING_PRICE,100);
                $LISTING_TITLE=$this->truncate($LISTING_TITLE,200);
                $LISTING_TRANSACTION_TYPE=$this->truncate($LISTING_TRANSACTION_TYPE,100);
                $PROPERTY_AREA=$this->truncate($PROPERTY_AREA,200);
                $PROPERTY_CATEGORY=$this->truncate($PROPERTY_CATEGORY,100);
                $SELLER_CATEGORY=$this->truncate($SELLER_CATEGORY,100);
                $AGENT_EMAIL_ADDRESS=$this->truncate($AGENT_EMAIL_ADDRESS,250);

                $batchSizeCounter = ($i + 1) % $batchSize;
                if ($batchSizeCounter != 0) {
                    if ($i != 0) {
                        $qry .= ",";
                    }
                } else {
                    $qry = rtrim($qry, ',');
                    echo $qry;
                    $res = pg_query($con, $qry);
                    $res = pg_result_status($res);
                    if ($res == 1) {
                        $this->insertedRecordCounter += $batchSize;
                    }
                    $qry = "INSERT INTO " . ConstantUtils::TABLE_SCRAPED_DATA . "(" . ConstantUtils::COLUMN_SCRAPED_DATA_CRAWL_TYPE . "," . ConstantUtils::COLUMN_SCRAPED_DATA_SOURCE . "," . ConstantUtils::COLUMN_SCRAPED_DATA_LATEST_CRAWL . "," . ConstantUtils::COLUMN_SCRAPED_DATA_DATE . "," . ConstantUtils::COLUMN_SCRAPED_DATA_AGENT_ID . "," . ConstantUtils::COLUMN_SCRAPED_DATA_AGENT_MOBILE . "," . ConstantUtils::COLUMN_SCRAPED_DATA_LISTING_ID . "," . ConstantUtils::COLUMN_SCRAPED_DATA_LISTING_TITLE . "," . ConstantUtils::COLUMN_SCRAPED_DATA_LISTING_PRICE . "," . ConstantUtils::COLUMN_SCRAPED_DATA_IMAGE_URL . "," . ConstantUtils::COLUMN_SCRAPED_DATA_AGENT_NAME . "," . ConstantUtils::COLUMN_SCRAPED_DATA_LISTING_LOCATION . "," . ConstantUtils::COLUMN_SCRAPED_DATA_LISTING_CITY . "," . ConstantUtils::COLUMN_SCRAPED_DATA_LISTING_POSTED_ON . "," . ConstantUtils::COLUMN_SCRAPED_DATA_PROPERTY_AREA. "," . ConstantUtils::COLUMN_SCRAPED_DATA_PROPERTY_CATEGORY . "," . ConstantUtils::COLUMN_SCRAPED_DATA_LISTING_TRANSACTION_TYPE . "," . ConstantUtils::COLUMN_SCRAPED_DATA_SELLER_CATEGORY . "," . ConstantUtils::COLUMN_SCRAPED_DATA_LATITUDE.",".ConstantUtils::COLUMN_SCRAPED_DATA_LONGITUDE.",".ConstantUtils::AGENT_EMAIL_ADDRESS . ") VALUES";
                }
                $qry .= "('$crawlType','$source','$latest_crawl','$date','$AGENT_ID','$AGENT_MOBILE','$LISTING_ID','$LISTING_TITLE','$LISTING_PRICE','$IMAGE_URL','$AGENT_NAME','$LISTING_LOCATION','$LISTING_CITY','$LISTING_POSTED_ON','$PROPERTY_AREA','$PROPERTY_CATEGORY','$LISTING_TRANSACTION_TYPE','$SELLER_CATEGORY','$LATITUDE','$LONGITUDE','$AGENT_EMAIL_ADDRESS')";
            }
            $qry = rtrim($qry, ',');
            echo $qry;
            $res = pg_query($con, $qry);
            $res = pg_result_status($res);
            if ($res == 1) {
                $this->insertedRecordCounter += $batchSizeCounter - 1;
                $this->insertedRecordCounter;
            }
            return $this->insertedRecordCounter;
        }catch(\Exception $exp){
            $this->logger->error("Exception in saving quikr data into redshift" ,$exp);
            return null;
        }
    }

    public function saveHousingRentListingsRecord($inputData,$crawlType,$source,$date){
        $con = $this->PostgreSQL_connect();
        if (!isset($con)){
            $this->logger->error("PostgreSQL Connection not set hence exiting");
            return null;
        }
        if(isset($this->insertedRecordCounter)){
            $this->insertedRecordCounter=1;
        }
        try {
            $batchSize = ConstantUtils::BATCH_INSERTION_SIZE;
            $this->logger->debug("Batch insertion size=".$batchSize);
            $qry = "INSERT INTO " . ConstantUtils::TABLE_SCRAPED_DATA . "(" . ConstantUtils::COLUMN_SCRAPED_DATA_CRAWL_TYPE . "," . ConstantUtils::COLUMN_SCRAPED_DATA_SOURCE . "," . ConstantUtils::COLUMN_SCRAPED_DATA_LATEST_CRAWL . "," . ConstantUtils::COLUMN_SCRAPED_DATA_DATE . "," . ConstantUtils::COLUMN_SCRAPED_DATA_AGENT_ID . "," . ConstantUtils::COLUMN_SCRAPED_DATA_AGENT_NAME . "," . ConstantUtils::COLUMN_SCRAPED_DATA_AGENT_MOBILE . "," . ConstantUtils::COLUMN_SCRAPED_DATA_LISTING_ID . "," . ConstantUtils::COLUMN_SCRAPED_DATA_LISTING_TITLE . "," . ConstantUtils::COLUMN_SCRAPED_DATA_LISTING_PRICE . "," . ConstantUtils::COLUMN_SCRAPED_DATA_LISTING_TRANSACTION_TYPE . "," . ConstantUtils::COLUMN_SCRAPED_DATA_IMAGES_PER_LISTING . "," . ConstantUtils::COLUMN_SCRAPED_DATA_LISTING_LOCATION . "," . ConstantUtils::COLUMN_SCRAPED_DATA_LISTING_CITY_ID .",".ConstantUtils::COLUMN_SCRAPED_DATA_LISTING_CITY. "," . ConstantUtils::COLUMN_SCRAPED_DATA_LISTING_POSTED_ON . "," . ConstantUtils::COLUMN_SCRAPED_DATA_PROPERTY_AREA . "," . ConstantUtils::COLUMN_SCRAPED_DATA_PROPERTY_CATEGORY. "," . ConstantUtils::COLUMN_SCRAPED_DATA_LISTING_URL . "," . ConstantUtils::COLUMN_SCRAPED_DATA_LATITUDE . "," . ConstantUtils::COLUMN_SCRAPED_DATA_LONGITUDE.",".ConstantUtils::COLUMN_SCRAPED_DATA_SELLER_CATEGORY.",".ConstantUtils::COLUMN_SCRAPED_DATA_COMPANY_NAME.",".ConstantUtils::COLUMN_SCRAPED_DATA_LISTING_AMENITIES.",".ConstantUtils::COLUMN_SCRAPED_DATA_LISTING_DESCRIPTION . ") VALUES";

            for ($i = 0; $i < count($inputData); $i++) {
                $latest_crawl = true;
                $AGENT_ID = pg_escape_string($con, $inputData[$i][ConstantUtils::AGENT_ID]);
                $AGENT_NAME = pg_escape_string($con, $inputData[$i][ConstantUtils::AGENT_NAME]);
                $AGENT_MOBILE = pg_escape_string($con, $inputData[$i][ConstantUtils::AGENT_MOBILE]);
                $LISTING_ID = pg_escape_string($con, $inputData[$i][ConstantUtils::LISTING_ID]);
                $LISTING_TITLE = pg_escape_string($con, $inputData[$i][ConstantUtils::LISTING_TITLE]);
                $LISTING_TRANSACTION_TYPE = pg_escape_string($con, $inputData[$i][ConstantUtils::LISTING_TRANSACTION_TYPE]);
                $LISTING_TRANSACTION_TYPE=explode("_",$LISTING_TRANSACTION_TYPE);
                $LISTING_TRANSACTION_TYPE=$LISTING_TRANSACTION_TYPE[0];
                $LISTING_POSTED_ON = pg_escape_string($con, $inputData[$i][ConstantUtils::LISTING_POSTED_ON]);
                $LISTING_CITY_ID = pg_escape_string($con, $inputData[$i][ConstantUtils::LISTING_CITY_ID]);
                $LISTING_CITY = pg_escape_string($con,$inputData[$i][ConstantUtils::LISTING_CITY]);
                $LISTING_PRICE = pg_escape_string($con, $inputData[$i][ConstantUtils::LISTING_PRICE]);
                $LISTING_URL = pg_escape_string($con, $inputData[$i][ConstantUtils::LISTING_URL]);
                $IMAGES_PER_LISTING = pg_escape_string($con, $inputData[$i][ConstantUtils::IMAGES_PER_LISTING]);
                $LISTING_LOCATION = pg_escape_string($con, $inputData[$i][ConstantUtils::LISTING_LOCATION]);
                $PROPERTY_AREA = pg_escape_string($con, $inputData[$i][ConstantUtils::PROPERTY_AREA]);
                $PROPERTY_CATEGORY = pg_escape_string($con, $inputData[$i][ConstantUtils::PROPERTY_CATEGORY]);
                $SELLER_CATEGORY = pg_escape_string($con, $inputData[$i][ConstantUtils::SELLER_CATEGORY]);
                $LATITUDE = pg_escape_string($con, $inputData[$i][ConstantUtils::LATITUDE]);
                $LONGITUDE = pg_escape_string($con, $inputData[$i][ConstantUtils::LONGITUDE]);
                $COMPANY_NAME = pg_escape_string($con, $inputData[$i][ConstantUtils::COMPANY_NAME]);
                $LISTING_AMENITIES = pg_escape_string($con, $inputData[$i][ConstantUtils::LISTING_AMENITIES]);
                $LISTING_DESCRIPTION = pg_escape_string($con,$inputData[$i][ConstantUtils::LISTING_DETAILS]);


                $AGENT_ID=$this->truncate($AGENT_ID,100);
                $AGENT_NAME=$this->truncate($AGENT_NAME,100);
                $AGENT_MOBILE=$this->truncate($AGENT_MOBILE,100);
                $LISTING_ID=$this->truncate($LISTING_ID,100);
                $LISTING_TITLE=$this->truncate($LISTING_TITLE,200);
                $LISTING_TRANSACTION_TYPE=$this->truncate($LISTING_TRANSACTION_TYPE,100);
                $LISTING_POSTED_ON=$this->truncate($LISTING_POSTED_ON,200);
                $LISTING_CITY_ID=$this->truncate($LISTING_CITY_ID,100);
                $LISTING_CITY=$this->truncate($LISTING_CITY,100);
                $LISTING_PRICE=$this->truncate($LISTING_PRICE,100);
                $LISTING_URL=$this->truncate($LISTING_URL,300);
                $IMAGES_PER_LISTING=$this->truncate($IMAGES_PER_LISTING,100);
                $LISTING_LOCATION=$this->truncate($LISTING_LOCATION,200);
                $PROPERTY_AREA=$this->truncate($PROPERTY_AREA,50);
                $PROPERTY_CATEGORY=$this->truncate($PROPERTY_CATEGORY,100);
                $SELLER_CATEGORY=$this->truncate($SELLER_CATEGORY,100);
                $LATITUDE=$this->truncate($LATITUDE,50);
                $LONGITUDE=$this->truncate($LONGITUDE,50);
                $COMPANY_NAME=$this->truncate($COMPANY_NAME,200);
                $LISTING_AMENITIES=$this->truncate($LISTING_AMENITIES,500);
                $LISTING_DESCRIPTION=$this->truncate($LISTING_DESCRIPTION,500);


                $batchSizeCounter = ($i + 1) % $batchSize;
                if ($batchSizeCounter != 0) {
                    if ($i != 0) {
                        $qry .= ",";
                    }
                } else {
                    $qry = rtrim($qry, ',');
                    echo $qry;
                    $res = pg_query($con, $qry);
                    $res = pg_result_status($res);
                    if ($res == 1) {
                        $this->insertedRecordCounter += $batchSize;
                    }
                    $qry = "INSERT INTO " . ConstantUtils::TABLE_SCRAPED_DATA . "(" . ConstantUtils::COLUMN_SCRAPED_DATA_CRAWL_TYPE . "," . ConstantUtils::COLUMN_SCRAPED_DATA_SOURCE . "," . ConstantUtils::COLUMN_SCRAPED_DATA_LATEST_CRAWL . "," . ConstantUtils::COLUMN_SCRAPED_DATA_DATE . "," . ConstantUtils::COLUMN_SCRAPED_DATA_AGENT_ID . "," . ConstantUtils::COLUMN_SCRAPED_DATA_AGENT_NAME . "," . ConstantUtils::COLUMN_SCRAPED_DATA_AGENT_MOBILE . "," . ConstantUtils::COLUMN_SCRAPED_DATA_LISTING_ID . "," . ConstantUtils::COLUMN_SCRAPED_DATA_LISTING_TITLE . "," . ConstantUtils::COLUMN_SCRAPED_DATA_LISTING_PRICE . "," . ConstantUtils::COLUMN_SCRAPED_DATA_LISTING_TRANSACTION_TYPE . "," . ConstantUtils::COLUMN_SCRAPED_DATA_IMAGES_PER_LISTING . "," . ConstantUtils::COLUMN_SCRAPED_DATA_LISTING_LOCATION . "," . ConstantUtils::COLUMN_SCRAPED_DATA_LISTING_CITY_ID .",".ConstantUtils::COLUMN_SCRAPED_DATA_LISTING_CITY. "," . ConstantUtils::COLUMN_SCRAPED_DATA_LISTING_POSTED_ON . "," . ConstantUtils::COLUMN_SCRAPED_DATA_PROPERTY_AREA . "," . ConstantUtils::COLUMN_SCRAPED_DATA_PROPERTY_CATEGORY. "," . ConstantUtils::COLUMN_SCRAPED_DATA_LISTING_URL . "," . ConstantUtils::COLUMN_SCRAPED_DATA_LATITUDE . "," . ConstantUtils::COLUMN_SCRAPED_DATA_LONGITUDE.",".ConstantUtils::COLUMN_SCRAPED_DATA_SELLER_CATEGORY.",".ConstantUtils::COLUMN_SCRAPED_DATA_COMPANY_NAME.",".ConstantUtils::COLUMN_SCRAPED_DATA_LISTING_AMENITIES.",".ConstantUtils::COLUMN_SCRAPED_DATA_LISTING_DESCRIPTION . ") VALUES";
                }
                $qry .= "('$crawlType','$source','$latest_crawl','$date','$AGENT_ID','$AGENT_NAME','$AGENT_MOBILE','$LISTING_ID','$LISTING_TITLE','$LISTING_PRICE','$LISTING_TRANSACTION_TYPE','$IMAGES_PER_LISTING','$LISTING_LOCATION','$LISTING_CITY_ID','$LISTING_CITY','$LISTING_POSTED_ON','$PROPERTY_AREA','$PROPERTY_CATEGORY','$LISTING_URL','$LATITUDE','$LONGITUDE','$SELLER_CATEGORY','$COMPANY_NAME','$LISTING_AMENITIES','$LISTING_DESCRIPTION') ";
            }
            $qry = rtrim($qry, ',');
            echo $qry;
            $res = pg_query($con, $qry);
            $res = pg_result_status($res);
            if ($res == 1) {
                $this->insertedRecordCounter += $batchSizeCounter - 1;
                $this->insertedRecordCounter;
            }
            return $this->insertedRecordCounter;
        }catch(\Exception $exp){
            $this->logger->error("Exception in saving housing data into redshift" ,$exp);
            return null;
        }
    }

    /**
     * function saveHousingListingsRecord insert records from housing.com listings S3 feed to redshift and returns number of records inserted successfully
     * @param  array $inputData dataArray from feed
     * @param string $crawlType for ex. listing_details or agent_details
     * @param string $source source= housing.com
     * @param string $date date for ex. 2015-10-05
     * @return  integer
     */
    public function saveHousingListingsRecord($inputData,$crawlType,$source,$date){
        $con = $this->PostgreSQL_connect();
        if (!isset($con)){
            $this->logger->error("PostgreSQL Connection not set hence exiting");
            return null;
        }
        if(isset($this->insertedRecordCounter)){
            $this->insertedRecordCounter=1;
        }
        try {
            $batchSize = ConstantUtils::BATCH_INSERTION_SIZE;
            $this->logger->debug("Batch insertion size=".$batchSize);
            $qry = "INSERT INTO " . ConstantUtils::TABLE_SCRAPED_DATA . "(" . ConstantUtils::COLUMN_SCRAPED_DATA_CRAWL_TYPE . "," . ConstantUtils::COLUMN_SCRAPED_DATA_SOURCE . "," . ConstantUtils::COLUMN_SCRAPED_DATA_LATEST_CRAWL . "," . ConstantUtils::COLUMN_SCRAPED_DATA_DATE . "," . ConstantUtils::COLUMN_SCRAPED_DATA_AGENT_ID . "," . ConstantUtils::COLUMN_SCRAPED_DATA_AGENT_NAME . "," . ConstantUtils::COLUMN_SCRAPED_DATA_AGENT_MOBILE . "," . ConstantUtils::COLUMN_SCRAPED_DATA_LISTING_ID . "," . ConstantUtils::COLUMN_SCRAPED_DATA_LISTING_TITLE . "," . ConstantUtils::COLUMN_SCRAPED_DATA_LISTING_PRICE . "," . ConstantUtils::COLUMN_SCRAPED_DATA_LISTING_TRANSACTION_TYPE . "," . ConstantUtils::COLUMN_SCRAPED_DATA_IMAGES_PER_LISTING . "," . ConstantUtils::COLUMN_SCRAPED_DATA_LISTING_LOCATION . "," . ConstantUtils::COLUMN_SCRAPED_DATA_LISTING_CITY_ID . "," . ConstantUtils::COLUMN_SCRAPED_DATA_LISTING_POSTED_ON . "," . ConstantUtils::COLUMN_SCRAPED_DATA_PROPERTY_AREA . "," . ConstantUtils::COLUMN_SCRAPED_DATA_PROPERTY_RATE_PER_SQ_FT . "," . ConstantUtils::COLUMN_SCRAPED_DATA_LISTING_URL . "," . ConstantUtils::COLUMN_SCRAPED_DATA_LATITUDE . "," . ConstantUtils::COLUMN_SCRAPED_DATA_LONGITUDE . ") VALUES ";

            for ($i = 0; $i < count($inputData); $i++) {
                $latest_crawl = true;
                $AGENT_ID = pg_escape_string($con, $inputData[$i][ConstantUtils::AGENT_ID]);
                $AGENT_NAME = pg_escape_string($con, $inputData[$i][ConstantUtils::AGENT_NAME]);
                $AGENT_MOBILE = pg_escape_string($con, $inputData[$i][ConstantUtils::AGENT_MOBILE]);
                $LISTING_ID = pg_escape_string($con, $inputData[$i][ConstantUtils::LISTING_ID]);
                $LISTING_TITLE = pg_escape_string($con, $inputData[$i][ConstantUtils::LISTING_TITLE]);
                $LISTING_TRANSACTION_TYPE = pg_escape_string($con, $inputData[$i][ConstantUtils::LISTING_TRANSACTION_TYPE]);
                $LISTING_POSTED_ON = pg_escape_string($con, $inputData[$i][ConstantUtils::LISTING_POSTED_ON]);
                $LISTING_CITY_ID = pg_escape_string($con, $inputData[$i][ConstantUtils::LISTING_CITY_ID]);
                $LISTING_PRICE = pg_escape_string($con, $inputData[$i][ConstantUtils::LISTING_PRICE]);
                $LISTING_URL = pg_escape_string($con, $inputData[$i][ConstantUtils::LISTING_URL]);
                $IMAGES_PER_LISTING = pg_escape_string($con, $inputData[$i][ConstantUtils::IMAGES_PER_LISTING]);
                $LISTING_LOCATION = pg_escape_string($con, $inputData[$i][ConstantUtils::LISTING_LOCATION]);
                $PROPERTY_AREA = pg_escape_string($con, $inputData[$i][ConstantUtils::PROPERTY_AREA]);
                $PROPERTY_RATE_PER_SQ_FT = pg_escape_string($con, $inputData[$i][ConstantUtils::PROPERTY_RATE_PER_SQ_FT]);
                $GEO_COORDINATES = pg_escape_string($con, $inputData[$i][ConstantUtils::GEO_COORDINATES]);
                $arr = explode(',', $GEO_COORDINATES);
                $LATITUDE = $arr[0];
                $LONGITUDE = $arr[1];
                $AGENT_ID=$this->truncate($AGENT_ID,100);
                $AGENT_NAME=$this->truncate($AGENT_NAME,100);
                $AGENT_MOBILE=$this->truncate($AGENT_MOBILE,100);
                $LISTING_ID=$this->truncate($LISTING_ID,100);
                $LISTING_PRICE=$this->truncate($LISTING_PRICE,100);
                $LISTING_TRANSACTION_TYPE=$this->truncate($LISTING_TRANSACTION_TYPE,100);
                $LISTING_LOCATION=$this->truncate($LISTING_LOCATION,200);
                $LISTING_CITY_ID=$this->truncate($LISTING_CITY_ID,100);
                $PROPERTY_AREA=$this->truncate($PROPERTY_AREA,50);
                $PROPERTY_RATE_PER_SQ_FT=$this->truncate($PROPERTY_RATE_PER_SQ_FT,50);
                $LATITUDE=$this->truncate($LATITUDE,50);
                $LONGITUDE=$this->truncate($LONGITUDE,50);


                $batchSizeCounter = ($i + 1) % $batchSize;
                if ($batchSizeCounter != 0) {
                    if ($i != 0) {
                        $qry .= ",";
                    }
                } else {
                    $qry = rtrim($qry, ',');
                    echo $qry;
                    $res = pg_query($con, $qry);
                    $res = pg_result_status($res);
                    if ($res == 1) {
                        $this->insertedRecordCounter += $batchSize;
                    }
                    $qry = "INSERT INTO " . ConstantUtils::TABLE_SCRAPED_DATA . "(" . ConstantUtils::COLUMN_SCRAPED_DATA_CRAWL_TYPE . "," . ConstantUtils::COLUMN_SCRAPED_DATA_SOURCE . "," . ConstantUtils::COLUMN_SCRAPED_DATA_LATEST_CRAWL . "," . ConstantUtils::COLUMN_SCRAPED_DATA_DATE . "," . ConstantUtils::COLUMN_SCRAPED_DATA_AGENT_ID . "," . ConstantUtils::COLUMN_SCRAPED_DATA_AGENT_NAME . "," . ConstantUtils::COLUMN_SCRAPED_DATA_AGENT_MOBILE . "," . ConstantUtils::COLUMN_SCRAPED_DATA_LISTING_ID . "," . ConstantUtils::COLUMN_SCRAPED_DATA_LISTING_TITLE . "," . ConstantUtils::COLUMN_SCRAPED_DATA_LISTING_PRICE . "," . ConstantUtils::COLUMN_SCRAPED_DATA_LISTING_TRANSACTION_TYPE . "," . ConstantUtils::COLUMN_SCRAPED_DATA_IMAGES_PER_LISTING . "," . ConstantUtils::COLUMN_SCRAPED_DATA_LISTING_LOCATION . "," . ConstantUtils::COLUMN_SCRAPED_DATA_LISTING_CITY_ID . "," . ConstantUtils::COLUMN_SCRAPED_DATA_LISTING_POSTED_ON . "," . ConstantUtils::COLUMN_SCRAPED_DATA_PROPERTY_AREA . "," . ConstantUtils::COLUMN_SCRAPED_DATA_PROPERTY_RATE_PER_SQ_FT . "," . ConstantUtils::COLUMN_SCRAPED_DATA_LISTING_URL . "," . ConstantUtils::COLUMN_SCRAPED_DATA_LATITUDE . "," . ConstantUtils::COLUMN_SCRAPED_DATA_LONGITUDE . ") VALUES";
                }
                $qry .= "('$crawlType','$source','$latest_crawl','$date','$AGENT_ID','$AGENT_NAME','$AGENT_MOBILE','$LISTING_ID','$LISTING_TITLE','$LISTING_PRICE','$LISTING_TRANSACTION_TYPE','$IMAGES_PER_LISTING','$LISTING_LOCATION','$LISTING_CITY_ID','$LISTING_POSTED_ON','$PROPERTY_AREA','$PROPERTY_RATE_PER_SQ_FT','$LISTING_URL','$LATITUDE','$LONGITUDE') ";
            }
            $qry = rtrim($qry, ',');
            echo $qry;
            $res = pg_query($con, $qry);
            $res = pg_result_status($res);
            if ($res == 1) {
                $this->insertedRecordCounter += $batchSizeCounter - 1;
                $this->insertedRecordCounter;
            }
            return $this->insertedRecordCounter;
        }catch(\Exception $exp){
            $this->logger->error("Exception in saving housing data into redshift" ,$exp);
            return null;
        }
    }







    /**
     * function getAllKeysInS3Bucket returns total keys present in S3 bucket
     * @return array
     */
    public function getAllKeysInS3Bucket(){
        try {
            // Instantiate the client.
            $s3 = S3Client::factory();
            //$s3->registerStreamWrapper();
            $bucket = ConstantUtils::BUCKET;
            $objects = $s3->getIterator('ListObjects', array('Bucket' => $bucket));
            return $objects;
        }catch(\Exception $exp){
            $this->logger->error("Exception in getting all keys in S3 bucket." ,$exp);
            return null;
        }
    }


    /**
     * function getS3KeyPattern returns keys pattern based on params
     * @param string $date date for ex. 2015-10-05
     * @param string $source for ex. 99acres.com
     * @return string
     */
    public function getS3KeyPattern($date,$source,$crawlType){
        $pattern=ConstantUtils::FEED_STORAGE_ROOT_DIRECTORY_IN_S3.$date."/";
        $pattern.=$source;
        if($source == ConstantUtils::SOURCE_MAGICBRICKS && $crawlType==ConstantUtils::LISTINGS_DETAILS){
            $pattern .= '/listings';
        }elseif($source == ConstantUtils::SOURCE_MAGICBRICKS && $crawlType == ConstantUtils::AGENT_DETAILS){
            $pattern .= '/agents';
        }
        $this->logger->debug("S3 key pattern formed from date=".$date." and source=".$source." is ".$pattern);
        return $pattern;
    }


    /**
     * function getS3KeysFromPattern returns S3 keys matches with the pattern
     * @param array $S3Objects all S3 keys array
     * @param string $pattern pattern which is required for filtering required keys from S3 all keys
     * @return array
     */
    public function getS3KeysFromPattern($S3objects,$pattern){
        try {
            $keys = array();
            foreach ($S3objects as $object) {
                if (strpos($object['Key'], $pattern) !== false) {
                    array_push($keys, $object['Key']);
                }
            }
            $this->logger->debug("Total of ".count($keys)." S3 key formed matching pattern=".$pattern);
            return $keys;
        }catch(\Exception $exp){
            $this->logger->error("Exception in getting S3 Keys from pattern" ,$exp);
            return null;
        }
    }


    /**
     * function getDataFromS3Key returns data from S3 Key feed
     * @param string $key feed_uri on the basis of key
     * @return array
     */
    public function getDataFromS3Key($key){
        try {
            $s3 = S3Client::factory();
            $bucket = ConstantUtils::BUCKET;
            $result = $s3->getObject(array(
                'Bucket' => $bucket,
                'Key' => $key
            ));
            $jsonData = $result['Body']->__toString();
            $dataArray = json_decode($jsonData, true);
            $this->logger->debug("Found a total of =".count($dataArray)." records in s3 feed= ".$key);
            return $dataArray;
        }catch(\Exception $exp){
            $this->logger->error("Exception in getting data from S3 key" ,$exp);
            return null;
        }
    }


    /**
     * function processFeedIfNotProcessedSuccessfully update feed processing details
     * @param string $feed_path feed_uri of feed on which processing is done
     * @param string $date date for ex. 2015-10-05
     * @return  bool
     */
    public function processFeedIfNotProcessedSuccessfully($feed_path,$date){
        $con = $this->PostgreSQL_connect();
        if (!isset($con)){
            $this->logger->error("PostgreSQL Connection not set hence exiting");
            return null;
        }
        try {
            $feed_path = pg_escape_string($con, $feed_path);
            $query = "SELECT * FROM " . ConstantUtils::TABLE_FEEDS_PROCESSING_DETAILS . " WHERE " . ConstantUtils::COLUMN_FEEDS_PROCESSING_DETAILS_FEED_PATH . " = '" . $feed_path . "' AND " . ConstantUtils::COLUMN_FEEDS_PROCESSING_DETAILS_PROCESSED_STATUS . " = true AND " . ConstantUtils::COLUMN_FEEDS_PROCESSING_DETAILS_IS_MOVED_SUCCESSFULLY . " =true ";
            $result = pg_query($con, $query);
            $count = pg_num_rows($result);

            #check if feed record is not present inside table
            if ($count == 0) {
                $this->logger->debug("feed =".$feed_path." data has not been moved earlier.");
                $result = $this->insertIntoFeedProcessedStatus($feed_path, $date);
            } else {
                $this->logger->debug("Feed =".$feed_path." records has already been moved");
                $result = 0;
            }
            return $result;
        }catch(\Exception $exp){
            $this->logger->error("exception in inserting feed information into table" ,$exp);
            return null;
        }
    }


    /**
     * function processFeedIfNotProcessedSuccessfully update feed processing details
     * @param string $feed_path feed_uri of feed on which processing is done
     * @param string $date date for ex. 2015-10-05
     * @return  bool
     */
    public function insertIntoFeedProcessedStatus($feed_path,$date){
        $con = $this->PostgreSQL_connect();
        if (!isset($con)){
            $this->logger->error("PostgreSQL Connection not set. Hence exiting!");
            return null;
        }
        try {
            $processed_status = true;
            $time = time();
            $query = "INSERT INTO " . ConstantUtils::TABLE_FEEDS_PROCESSING_DETAILS . "(" . ConstantUtils::COLUMN_FEEDS_PROCESSING_DETAILS_FEED_PATH . "," . ConstantUtils::COLUMN_FEEDS_PROCESSING_DETAILS_PROCESSED_STATUS . "," . ConstantUtils::COLUMN_FEEDS_PROCESSING_DETAILS_DATE . "," . ConstantUtils::COLUMN_FEEDS_PROCESSING_DETAILS_CREATED_AT . " ) VALUES('$feed_path','$processed_status','$date','$time')";
            $result = pg_query($con, $query);
            $res = pg_result_status($result);
            if($res){
                $this->logger->debug("feed =".$feed_path." record has been inserted in table".ConstantUtils::TABLE_FEEDS_PROCESSING_DETAILS);
            }
            return $res;
        }catch(\Exception $exp){
            $this->logger->error("Error in inserting processed feed status" ,$exp);
            return null;
        }
    }


    /**
     * function isDataSuccessfullyMovedFromS3ToRedshift ensures and returns whether data is successfully moved to redshift
     * @param integer $movedRecordCount total number of record inserted into redshift from feed under processing
     * @param integer $feedSize total number of record present inside feed
     * @param string $feed_path feed_uri of feed on which processing is done
     * @param string $date date for ex. 2015-10-05
     * @return  bool
     */
    public function isDataSuccessfullyMovedFromS3ToRedshift($movedRecordCount,$feedSize,$feed_path,$date ){
        $con = $this->PostgreSQL_connect();
        if (!isset($con)){
            $this->logger->error("PostgreSQL Connection not set hence exiting");
            return null;
        }
        $isSuccessfullyMoved=false;
        $movedRecordCount=(int)$movedRecordCount;
        $feedSize=(int)$feedSize;
        $percentageOfRecordProcessedSuccessfully=round(($movedRecordCount/$feedSize)*100,2);

        $this->logger->debug("Moved Record count=".$movedRecordCount." and feedsize=".$feedSize);
        $this->logger->debug("Percentage of record moved successfully from s3 to redshift=".$percentageOfRecordProcessedSuccessfully);

        #if percentage of successfully moved record is greater than 98% then update feed record as successfully moved.
        if($percentageOfRecordProcessedSuccessfully > 98) {
             $isSuccessfullyMoved=true;
        }
        $query = "UPDATE " . ConstantUtils::TABLE_FEEDS_PROCESSING_DETAILS . " SET " . ConstantUtils::COLUMN_FEEDS_PROCESSING_DETAILS_IS_MOVED_SUCCESSFULLY . " = '".$isSuccessfullyMoved."' ,".ConstantUtils::COLUMN_FEEDS_PROCESSING_DETAILS_RECORD_IN_FEED." = '".$feedSize."',".ConstantUtils::COLUMN_FEEDS_PROCESSING_DETAILS_RECORD_MOVED_SUCCESSFULLY." = '".$movedRecordCount."',".ConstantUtils::COLUMN_FEEDS_PROCESSING_DETAILS_RECORD_MOVED_PERCENTAGE." = '".$percentageOfRecordProcessedSuccessfully."',".ConstantUtils::COLUMN_FEEDS_PROCESSING_DETAILS_UPDATED_AT." = '".time() ."' WHERE " . ConstantUtils::COLUMN_FEEDS_PROCESSING_DETAILS_FEED_PATH . " = '" . $feed_path . "' AND " . ConstantUtils::COLUMN_FEEDS_PROCESSING_DETAILS_DATE . " = '" . $date."'";
        $res=pg_query($con,$query);
        $res = pg_result_status($res);
        if($res){
            $this->logger->debug("Successfully updated status is_moved_successfully to true for feed= ".$feed_path);
        }
        return $isSuccessfullyMoved;
    }


    /**
     * function updateLatestCrawlFlag updates lastest crawl flag of historical data
     * @param string $source for ex. 99acres.com
     * @param string $date date for ex. 2015-10-05
     * @param array $uniqueIdentifierArray array containing unique identifiers of current feed
     * @param string $uniqueIdentifierKey key which tells about unique identifier field of feed
     * @return  bool
     */
    public function updateLatestCrawlFlag($source,$date,$uniqueIdentifierArray,$uniqueIdentifierKey){
        $con = $this->PostgreSQL_connect();
        if (!isset($con)){
            $this->logger->error("PostgreSQL Connection not set hence exiting");
            return null;
        }
        try {
            $in_condition = "'" . implode("','", $uniqueIdentifierArray) . "'";
            $query = "UPDATE " . ConstantUtils::TABLE_SCRAPED_DATA . " SET " . ConstantUtils::COLUMN_SCRAPED_DATA_LATEST_CRAWL . " = 0 WHERE " . ConstantUtils::COLUMN_SCRAPED_DATA_DATE . " != '" . $date . "' AND source = '" . $source . "' AND " . $uniqueIdentifierKey . " IN (" . $in_condition . ")";
            $result = pg_query($con, $query);
            $res = pg_result_status($result);
            return $res;
        }catch(\Exception $exp){
            $this->logger->error("exception in updating latest crawl flag for previous data" ,$exp);
            return null;
        }
    }


    /**
     * function deletePartiallyInsertedDataFromRedshift delete partially inserted data if all records from feed are not moved to redshift successfully
     * @param string $source for ex. 99acres.com
     * @param string $date date for ex. 2015-10-05
     * @param array $uniqueIdentifierArray array containing unique identifiers of current feed
     * @param string $uniqueIdentifierKey key which tells about unique identifier field of feed
     * @param string $feed_path feed_uri of feed on which processing is done
     * @return  bool
     */
    public function deletePartiallyInsertedDataFromRedshift($source,$date,$uniqueIdentifierArray,$uniqueIdentifierKey,$feed_path){
        $con = $this->PostgreSQL_connect();
        if (!isset($con)){
            $this->logger->error("PostgreSQL Connection not set hence exiting");
            return null;
        }
        try {
            $in_condition = "'" . implode("','", $uniqueIdentifierArray) . "'";
            $query = "DELETE FROM " . ConstantUtils::TABLE_SCRAPED_DATA . " WHERE " . ConstantUtils::COLUMN_SCRAPED_DATA_DATE . " = '" . $date . "' AND source = '" . $source . "' AND " . $uniqueIdentifierKey . " IN (" . $in_condition . ")";
            $result = pg_query($con, $query);
            $res = pg_result_status($result);
            if($res){
                $this->logger->debug("Partially inserted data from feed to redshift has been deleted successfully for key= ".$feed_path);
            }else{
                $this->logger->debug("Error in deleting partially inserted data from feed to redshift for key=b".$feed_path);
            }
            return $res;
        }catch(\Exception $exp){
            $this->logger->error("exception in deleting partially deleted data" ,$exp);
            return null;
        }
    }


    /**
     * function getUniqueIdentifierArrayFromDataArray returns array of unique ids from feed
     * @param array $dataArray array of data present in feed
     * @param string $uniqueIdentifierKey key which tells about unique identifier field of feed
     * @return  array
     */
    public function getUniqueIdentifierArrayFromDataArray($dataArray,$uniqueIdentifierKey){
        try {
            $uniqueIdentifierArray = array();
            for ($i = 0; $i < count($dataArray); $i++) {
                array_push($uniqueIdentifierArray, $dataArray[$i][$uniqueIdentifierKey]);
            }
            $this->logger->debug("Found a total of ".count($uniqueIdentifierArray)." ids in feed on the basis of uniqueIdentifierKey=".$uniqueIdentifierKey);
            return $uniqueIdentifierArray;
        }catch (\Exception $exp) {
            $this->logger->error("Exception in getting unique ids array from unique identifier key= ".$uniqueIdentifierKey,$exp);
            return null;
        }
    }


    /**
     * function doTask master function responsible for processing feed data from s3 to redshift
     * @param string $date date for ex. 2015-10-05
     * @param string $source for ex. 99acres.com
     * @param string $crawlType for ex. listing_details or agent_details
     * @return  bool
     */
    public function doTask($date,$source,$crawlType){
        if(empty($source)){
            $this->logger->error("Source is not provided.Hence exiting!");
            return null;
        }
        $startTime=time();
        $this->logger->debug("Data transfer from S3 bucket to redshift started!.Start Timestamp= ".$startTime);

        #getting s3 key pattern on the basis of date and source.
        try{
            $pattern=$this->getS3KeyPattern($date,$source,$crawlType);
        }catch (\Exception $exp) {
            $this->logger->error("Exception in getting S3 Key pattern for source =".$source." and date=".$date." .Hence exiting!",$exp);
            return null;
        }

        #getting all keys present in S3 bucket.
        try{
            $S3objects = $this->getAllKeysInS3Bucket();
            //$this->logger->debug("Found a total of ".count($S3objects)." keys insider bucket=".ConstantUtils::BUCKET);
        }catch (\Exception $exp) {
            $this->logger->error("Exception in getting S3 Objects present in bucket.bucket=".ConstantUtils::BUCKET,$exp);
            return null;
        }

        #getting S3 keys matching with the pattern.
        try{
            $keys=$this->getS3KeysFromPattern($S3objects,$pattern);
        }catch (\Exception $exp) {
            $this->logger->error("Exception in getting S3 Keys based on pattern from s3 bucket.pattern=".$pattern,$exp);
            return null;
        }

        #check for number of keys returned matching a pattern
//        if(count($keys) == 0){
//            $this->logger->error("No keys found in S3 bucket for pattern=".$pattern);
//        }else {
//            $this->logger->debug("Found a total of " . count($keys) . " keys for pattern= " . $pattern);
//        }

        #iterating over returned S3 keys one by one starts below.
        foreach($keys as $key){
            $this->logger->debug("Processing starts for S3 key=".$key);

            #check is performed to identify from table whether the feed is successfuly moved and processed or not.
            try{
                $isFeedReadyToBeProcessed=$this->processFeedIfNotProcessedSuccessfully($key,$date);
            }catch (\Exception $exp) {
                $this->logger->error("Exception in getting processing detail about feed.feed=".$key,$exp);
                return null;
            }

            #if feed is not moved successfully then processing starts for moving feed from s3 to redshift.
            if($isFeedReadyToBeProcessed){

                #Data is fetched on the basis of the key from the list of keys returned from pattern.
                try {
                    $dataArray = $this->getDataFromS3Key($key);
                }catch (\Exception $exp) {
                    $this->logger->error("Exception in getting data from S3 key.Key= ".$key,$exp);
                    return null;
                }

                #check for source is performed below and accordingly data insertion function is called which are responsible for inserting feed data into redshift
                try {
                    if ($source == ConstantUtils::SOURCE_NN_ACRES) {
                        $movedRecordCount = $this->nnAcresListingsRecord($dataArray, $crawlType, $source, $date);
                        $uniqueIdentifierKey = ConstantUtils::LISTING_ID;
                    } elseif ($source == ConstantUtils::SOURCE_MAGICBRICKS AND $crawlType == ConstantUtils::LISTINGS_DETAILS) {
                        $movedRecordCount = $this->saveMagicbricksListingsRecord($dataArray, $crawlType, $source, $date);
                        $uniqueIdentifierKey = ConstantUtils::LISTING_ID;
                    } elseif ($source == ConstantUtils::SOURCE_MAGICBRICKS AND $crawlType == ConstantUtils::AGENT_DETAILS){
                        $movedRecordCount = $this->saveMagicbricksAgentsRecord($dataArray, $crawlType, $source, $date);
                        $uniqueIdentifierKey = ConstantUtils::AGENT_ID;
                    } elseif ($source == ConstantUtils::SOURCE_OLX) {
                        $movedRecordCount = $this->saveOlxLisitngsRecord($dataArray, $crawlType, $source, $date);
                        $uniqueIdentifierKey = ConstantUtils::LISTING_ID;
                    } elseif ($source == ConstantUtils::SOURCE_QUIKR) {
                        $movedRecordCount = $this->saveQuikrListingsRecord($dataArray, $crawlType, $source, $date);
                        $uniqueIdentifierKey = ConstantUtils::LISTING_ID;
                    } elseif ($source == ConstantUtils::SOURCE_HOUSING) {
                        $movedRecordCount = $this->saveHousingRentListingsRecord($dataArray, $crawlType, $source, $date);
                        $uniqueIdentifierKey = ConstantUtils::LISTING_ID;
                    } else {
                        $this->logger->error("Invalid source provided.source=" . $source);
                        return null;
                    }
                }catch (\Exception $exp) {
                    $this->logger->error("Exception in getting moved record count from feed to redshift",$exp);
                    return null;
                }

                #check if no of record moved from s3 to redshift is valid or not
                if($movedRecordCount <= 0){
                    $this->logger->error("Moved data count from s3 to redshift is zero.hence exiting.kindly investigate");
                    return null;
                }

                #getting unique ids array on the basis of primary key for that feed.
                try {
                    $uniqueIdentifierArray = $this->getUniqueIdentifierArrayFromDataArray($dataArray, $uniqueIdentifierKey);
                }catch (\Exception $exp) {
                    $this->logger->error("Exception in fetching unique ids for feed= ".$key." and for unique identifier key= ".$uniqueIdentifierKey,$exp);
                    return null;
                }

                #check is performed in order to identify whether data is successfully moved or not on the basis of %age of data transferred from currently processing s3 feed to redshift.
                try {
                    $isDataSuccessfullyMoved = $this->isDataSuccessfullyMovedFromS3ToRedshift($movedRecordCount, count($dataArray), $key, $date);
                }catch (\Exception $exp) {
                    $this->logger->error("Exception in updating feeds processing record in table for feed=".$key,$exp);
                    return null;
                }

                try{
                    #if data successfully transferred from S3 feed to redshift then updating latest_crawl flag for all previous entries.
                    if($isDataSuccessfullyMoved){
                        $result=$this->updateLatestCrawlFlag($source,$date,$uniqueIdentifierArray,$uniqueIdentifierKey);
                        if($result){
                            $this->logger->debug("Successfully updated latest crawl flag for feed= ".$key);
                        }else{
                            $this->logger->error("Error in updating latest crawl flag for feed=.".$key);
                        }
                    }else{  #data is not moved successfully hence deleting partial inserted data from feed to s3 if any.
                        $this->deletePartiallyInsertedDataFromRedshift($source,$date,$uniqueIdentifierArray,$uniqueIdentifierKey,$key);
                    }
                }catch (\Exception $exp) {
                    $this->logger->error("Exception in updating feeds processing record in table for feed=".$key,$exp);
                    return null;
                }
            }else{  #feed already processed and its data already moved to reshift.so no need of processing feed again
                echo "Feed Data already processed successfully\n";
                $this->logger->debug("Feed data already processed and its data already moved successfully to redshift.feed=".$key);
            }
        }
        $endTime=time();
        $this->logger->debug("Process Completed!.End Timestamp= ".$endTime);
        $totalTimeTaken=$endTime-$startTime;
        $this->logger->debug("Total time taken= ".$totalTimeTaken." seconds");
        return true;
    }



    public function getDataInBatches(){
        $con = $this->PostgreSQL_connect();
        if (!isset($con)){
            $this->logger->error("PostgreSQL Connection not set. Hence exiting!");
            return null;
        }
        try{
            $offset=0;
            $flag=0;
            while($flag != 1) {
                $array = array();
                $qry = "SELECT * FROM " . ConstantUtils::TABLE_SCRAPED_DATA . " WHERE ".ConstantUtils::COLUMN_SCRAPED_DATA_LISTING_SERIALIZED_DATA." IS NULL LIMIT " . ConstantUtils::BATCH_INSERTION_SIZE . " OFFSET " . $offset;
                $res = pg_query($con, $qry);
                while ($row = pg_fetch_assoc($res)) {
                    array_push($array, $row);
                }
                if (count($array) > 0) {
                    for ($i = 0; $i < count($array); $i++) {
                        $string = '';
                        foreach ($array[$i] as $key => $value) {
                            $string .= $key . "=" . pg_escape_string($con,$value) . "&";
                        }
                        echo $string = rtrim($string, '&serialized_data=');
                        $qry = "UPDATE " . ConstantUtils::TABLE_SCRAPED_DATA . " SET " . ConstantUtils::COLUMN_SCRAPED_DATA_LISTING_SERIALIZED_DATA . " = '" . $string . "' WHERE " . ConstantUtils::COLUMN_SCRAPED_DATA_ID . " = '" . $array[$i][ConstantUtils::COLUMN_SCRAPED_DATA_ID] . "'";
                        $res = pg_query($con, $qry);
                        echo "\n";
                    }
                    $offset = $offset+ConstantUtils::BATCH_INSERTION_SIZE;
                } else {
                    $flag = 1;
                }
            }
            return true;
        }catch (\Exception $exp){
            $this->logger->error("Exception in updating feeds processing record in table for feed=",$exp);
            return null;
        }
    }


    public function insertSerializedData(){
        $con = $this->PostgreSQL_connect();
        if (!isset($con)){
            $this->logger->error("PostgreSQL Connection not set. Hence exiting!");
            return null;
        }
        try{
            $offset=0;
            $flag=0;
            while($flag != 1) {
                $array = array();
                $qry = "SELECT * FROM " . ConstantUtils::TABLE_SCRAPED_DATA." WHERE ".ConstantUtils::COLUMN_SCRAPED_DATA_ID." > 1451650 ORDER BY ".ConstantUtils::COLUMN_SCRAPED_DATA_ID." ASC LIMIT " . ConstantUtils::BATCH_INSERTION_SIZE . " OFFSET " . $offset;
                $res = pg_query($con, $qry);
                while ($row = pg_fetch_assoc($res)) {
                    array_push($array, $row);
                }
                $insert_qry='';
                if (count($array) > 0) {
                    $insert_qry = "INSERT INTO scraped_data_serialized(id,source,crawl_type,latest_crawl,move_status,agent_mobile,serialized_data,error_code) VALUES";
                    for ($i = 0; $i < count($array); $i++) {
                        $string = '';
                        foreach ($array[$i] as $key => $value) {
                            echo $key." = ".$value;
                            echo "\n";
                            $string .= $key . "=" . pg_escape_string($con,$value)."||";
                        }
                        #$string = rtrim($string, '||serialized_data=');
                        echo $string;
                        $id=$array[$i][ConstantUtils::COLUMN_SCRAPED_DATA_ID];
                        $source=$array[$i][ConstantUtils::COLUMN_SCRAPED_DATA_SOURCE];
                        $crawl_type=$array[$i][ConstantUtils::COLUMN_SCRAPED_DATA_CRAWL_TYPE];
                        $latest_crawl=$array[$i][ConstantUtils::COLUMN_SCRAPED_DATA_LATEST_CRAWL];
                        $move_status=$array[$i][ConstantUtils::COLUMN_SCRAPED_DATA_MOVE_STATUS];
                        $error_code=$array[$i][ConstantUtils::COLUMN_SCRAPED_DATA_ERROR_CODE];
                        $agent_mobile=$array[$i][ConstantUtils::COLUMN_SCRAPED_DATA_AGENT_MOBILE];
                        $serialized_data=$string;
                        $insert_qry .= "('$id','$source','$crawl_type','$latest_crawl','$move_status','$agent_mobile','$serialized_data','$error_code') ";
                        $insert_qry .= ",";
                    }
                    $insert_qry = rtrim($insert_qry, ',');
                    $res = pg_query($con, $insert_qry);
                    echo "\n";
                    $offset = $offset+ConstantUtils::BATCH_INSERTION_SIZE;
                } else {
                    $flag = 1;
                }
            }
            return true;
        }catch (\Exception $exp){
            $this->logger->error("Exception in inserting feeds processing record in table.",$exp);
            return null;
        }
    }

}
?>