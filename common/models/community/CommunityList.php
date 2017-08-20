<?php
namespace common\models\community;

use Yii;
use yii\db\ActiveRecord;

class CommunityList extends ActiveRecord
{
    /**
     * 数据库表名
     * @return string
     */
    public static function tableName()
    {
        return 'community_list';
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
        $res =  static::find()->select(['name','id'])->where($where)->orderBy('')->limit($limit)->offset($offset)->asArray()->all();
        return $res;
    }

}