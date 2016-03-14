<?php
/**
 * Created by PhpStorm.
 * User: balwant
 * Date: 28/7/15
 * Time: 11:26 AM
 */
namespace App\response;
use Illuminate\Support\Contracts\JsonableInterface;

class ProductAttr implements \JsonSerializable{
    public $id = null;
    public $name = null;
    public $noOfViews = null;
    public $views360 = false;
    public $video = false;
    public $slideShare = false;
    public $camera = false;
    public $screenShot = false;
    public $benchMark = false;
    public $add_date = null;
    public $edit_date = null;
    public $attr_key = null;

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
            "id"         => $this->id,
            "name"       => $this->name,
            "views"      => $this->noOfViews,
            "views360"   => $this->views360,
            "video"      => $this->video,
            "slideShare" => $this->slideShare,
            "camera"     => $this->camera,
            "screenShot"  => $this->screenShot,
            "benchMark"  => $this->benchMark,
            "add_date"   => $this->add_date,
            "edit_date"  => $this->edit_date
        );
    }


}