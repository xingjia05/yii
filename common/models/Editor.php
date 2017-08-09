<?php
namespace common\models;

use Yii;
use yii\base\Model;

/**
 * Editor form
 */
class Editor extends Model
{
    public $title;
    public $content;
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['title', 'content'], 'required'],
        ];
    }

}
