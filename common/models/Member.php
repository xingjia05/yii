<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

class Member extends ActiveRecord implements IdentityInterface
{
    const IS_DELETED_YES = 1;
    const IS_DELETED_NO  = 0;

    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;

    /**
     * 数据库表名
     * @return string
     */
    public static function tableName()
    {
        return 'member';
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
        $where = array('is_deleted' => self::IS_DELETED_NO);
        $res =  static::find()->select(['*'])->where($where)->orderBy('id DESC')->limit($limit)->offset($offset)->asArray()->all();
        return $res;
    }

    public function getCount()
    {
        $where = array('is_deleted' => self::IS_DELETED_NO);
        $res =  static::find()->select(['*'])->where($where)->orderBy('')->asArray()->count();
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

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        $a = static::findOne(['id' => $id, 'is_deleted' => self::IS_DELETED_NO]);
        return $a;
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['phone' => $username, 'is_deleted' => self::IS_DELETED_NO]);
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
//        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
//        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }
}