<?php
namespace common\models;

use Yii;
use yii\base\Model;
use common\models\Member;

/**
 * Login form
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;

    private $_user;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect username or password.');
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            $userInfo = $this->getUser();
            $ret = Yii::$app->user->login($userInfo, $this->rememberMe ? 3600 * 24 * 30 : 0);
            if ($ret) {
                Yii::$app->session['session_data'] = $userInfo->attributes;
            }
            return $ret;
        } else {
            return false;
        }
    }

    /**
     * check user
     *
     * @return User|null
     */
    public function checkLogin()
    {
        if (Yii::$app->user->isGuest) {
            $userInfo = $this->getUser();
            if (empty($userInfo) || $userInfo['password'] != md5($this->password)) {
                return FALSE;
            }
            $ret = Yii::$app->user->login($userInfo, $this->rememberMe ? 3600 * 24 * 30 : 0);
            if ($ret) {
                Yii::$app->session['session_data'] = $userInfo->attributes;
            }
            return $ret;
        } else {
            return true;
        }
    }
    
    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = Member::findByUsername($this->username);
        }
        return $this->_user;
    }
}
