<?php
namespace common\widgets\forms\models;

use yii\base\Model;
use yii\web\UploadedFile;

class UploadForm extends Model
{
    /**
     * @var UploadedFile
     */
    public $FileName;
    public $path;

    public function rules()
    {
        return [
//            [['FileName'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg'],
        ];
    }

    public function upload()
    {
        if ($this->validate() && $this->FileName) {
            $this->FileName->saveAs($this->path.'/' . $this->FileName->baseName . '.' . $this->FileName->extension);
            return true;
        } else {
            return false;
        }
    }
}
?>
