<?php
/**
 * Created by PhpStorm.
 * User: balwant
 * Date: 30/7/15
 * Time: 10:57 AM
 */
namespace App\response;
use Illuminate\Support\Contracts\JsonableInterface;

class ContentSummary implements \JsonSerializable{
    public $views360Count = 0;
    public $videoCount = 0;
    public $slideShareCount = 0;
    public $cameraCount = 0;
    public $screenShotCount = 0;
    public $benchMarkCount = 0;
    public $fromDate = null;
    public $toDate = null;
    public $actualVideoCount = 0;

    public $views360Product = null;
    public $videoProduct = null;
    public $slideShareProduct = null;
    public $cameraProduct = null;
    public $screenShotProduct = null;
    public $benchMarkProduct = null;

    /**
     * (PHP 5 &gt;= 5.4.0)<br/>
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     */
    function jsonSerialize()
    {
        return array(
            "views360"   => $this->views360Count,
            "video"      => $this->videoCount,
            "slideShare" => $this->slideShareCount,
            "camera"     => $this->cameraCount,
            "screenShot"  => $this->screenShotCount,
            "benchMark"  => $this->benchMarkCount,
            "add_date"   => $this->fromDate,
            "edit_date"  => $this->toDate,
            "actualVideoCount" => $this->actualVideoCount
        );
    }


}