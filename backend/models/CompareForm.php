<?php
namespace backend\models;

use tpmanc\imagick\Imagick;
use yii\base\Model;
use Yii;

/**
 * Account form
 */
class CompareForm extends Model
{
    public $image1;
    public $image2;
    public $facebook_id;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['facebook_id', 'image1', 'image2'], 'string'],
            [['image1', 'image2'], 'required'],
            [['image1', 'image2'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
        ];
    }

    /**
     * @inheritdoc
     */
    public function compareImage()
    {
        //var_dump($this->image1['base_url'].'/'.$this->image1['path']);die;
        $img = Imagick::open($this->image1['base_url'].'/'.$this->image1['path']);

        $img->getWidth();
        $img->getHeight();
        var_dump($img);die;
        return 1;
    }

    private function mimeType($i)
    {
        /*returns array with mime type and if its jpg or png. Returns false if it isn't jpg or png*/
        $mime = getimagesize($i);
        $return = array($mime[0],$mime[1]);

        switch ($mime['mime'])
        {
            case 'image/jpeg':
                $return[] = 'jpg';
                return $return;
            case 'image/png':
                $return[] = 'png';
                return $return;
            default:
                return false;
        }
    }

    private function createImage($i)
    {
        /*retuns image resource or false if its not jpg or png*/
        $mime = $this->mimeType($i);

        if($mime[2] == 'jpg')
        {
            return imagecreatefromjpeg ($i);
        }
        else if ($mime[2] == 'png')
        {
            return imagecreatefrompng ($i);
        }
        else
        {
            return false;
        }
    }

    private function resizeImage($i,$source)
    {
        /*resizes the image to a 8x8 squere and returns as image resource*/
        $mime = $this->mimeType($source);

        $t = imagecreatetruecolor(8, 8);

        $source = $this->createImage($source);

        imagecopyresized($t, $source, 0, 0, 0, 0, 8, 8, $mime[0], $mime[1]);

        return $t;
    }

    private function colorMeanValue($i)
    {
        /*returns the mean value of the colors and the list of all pixel's colors*/
        $colorList = array();
        $colorSum = 0;
        for($a = 0;$a<8;$a++)
        {
            for($b = 0;$b<8;$b++)
            {

                $rgb = imagecolorat($i, $a, $b);
                $colorList[] = $rgb & 0xFF;
                $colorSum += $rgb & 0xFF;

            }
        }

        return array($colorSum/64,$colorList);
    }

    private function bits($colorMean)
    {
        /*returns an array with 1 and zeros. If a color is bigger than the mean value of colors it is 1*/
        $bits = array();

        foreach($colorMean[1] as $color){$bits[]= ($color>=$colorMean[0])?1:0;}
        return $bits;
    }

    public function compare()
    {

        $a = $this->image1['base_url'].'/'.$this->image1['path'];
        $b = $this->image2['base_url'].'/'.$this->image2['path'];
        /*main function. returns the hammering distance of two images' bit value*/
        $i1 = $this->createImage($a);
        $i2 = $this->createImage($b);

        if(!$i1 || !$i2){return false;}

        $i1 = $this->resizeImage($i1,$a);
        $i2 = $this->resizeImage($i2,$b);

        imagefilter($i1, IMG_FILTER_GRAYSCALE);
        imagefilter($i2, IMG_FILTER_GRAYSCALE);

        $colorMean1 = $this->colorMeanValue($i1);
        $colorMean2 = $this->colorMeanValue($i2);

        $bits1 = $this->bits($colorMean1);
        $bits2 = $this->bits($colorMean2);

        $hammeringDistance = 0;

        for($a = 0;$a<64;$a++)
        {
            if($bits1[$a] != $bits2[$a])
            {
                $hammeringDistance++;
            }
        }
        return (64 - $hammeringDistance)* 100 / 64;

    }

}
