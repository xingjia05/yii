<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;

class Live extends ActiveRecord
{
    const STATUS_VALID = 1;
    const STATUS_INVALID = 2;
    
    /**
     * 数据库表名
     * @return string
     */
    public static function tableName()
    {
        return 'live';
    }

    /**
     * 连接的数据库
     * @return mixed
     */
    public static function getDb()
    {
        return Yii::$app->db;
    }

    public function getList($offset = 0, $limit = 10)
    {
        $where = array('status' => self::STATUS_VALID);
        $res =  static::find()->select(['*'])->where($where)->orderBy('id DESC')->limit($limit)->offset($offset)->asArray()->all();
        return $res;
    }

    public function getCount()
    {
        $where = array('status' => self::STATUS_INVALID);
        $res =  static::find()->select(['*'])->where($where)->orderBy('')->asArray()->count();
        return $res;
    }
    
    public static function findById($id)
    {
        $where = array('id' => $id);
        return static::find()->select(['*'])->where($where)->orderBy('')->asArray()->one();
    }
    
    public function edit($id, $data)
    {
        if (empty($data)) {
            return true;
        }
        try {
            self::getDB()->createCommand()->update(static::tableName(), $data, 'id=:id', ['id' => $id])->execute();
        } catch (\Exception $e) {
            return false;
        }
        return true;
    }
    
}