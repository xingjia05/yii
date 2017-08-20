<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;

class Maintaince extends ActiveRecord
{
    /**
     * 数据库表名
     * @return string
     */
    public static function tableName()
    {
        return 'maintaince_list';
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
        $where = array();
        $res =  static::find()->select(['*'])->where($where)->orderBy('')->limit($limit)->offset($offset)->asArray()->all();
        return $res;
    }

    public function add($data)
    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        }
        try {
            $this->save();
        } catch (\Exception $e) {
            return false;
        }
        return $this->primaryKey;
    }
    
    public static function findById($id)
    {
        $where = array('id' => $id);
        return static::find()->select(['*'])->where($where)->orderBy('')->asArray()->one();
    }
    
    public static function getCountByServer($serverId)
    {
        $where = array('server_id' => $serverId);
        return static::find()->select(['*'])->where($where)->orderBy('')->asArray()->count();
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