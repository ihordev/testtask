<?php

namespace app\models;

use FB\Accountkit\DTO\Account;
use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 *
 * @property User|null $user This property is read-only.
 *
 */
class LoginForm extends Model
{
    /**
     * @var string
     */
    public $countryCode;
    /**
     * @var string
     */
    public $phone;
    /**
     * @var string
     */
    public $code;

    /**
     * @var bool
     */
    private $user = false;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['countryCode', 'phone', 'code'], 'required'],
        ];
    }

    /**
     * @return bool
     */
    public function validateFB()
    {
        try {
            $token = Yii::$app->fbAccountKitService->getAccessToken($this->code);
            /** @var Account $account*/
            $account = Yii::$app->fbAccountKitService->getUser($token);
        } catch (\Exception $exception) {
            //@todo logging
            return false;
        }

        return ($account->getFullPhoneNumber() === ($this->countryCode . $this->phone));
    }

    /**
     * Logs in a user using the provided username and password.
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate() && $this->validateFB() && !is_null($user = $this->getUser())) {
            return Yii::$app->user->login($user, 0);
        }
        return false;
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->user === false) {
            $this->user = User::find()
                ->where(['country_code' => $this->countryCode, 'phone' => $this->phone])
                ->one();
        }

        return $this->user;
    }
}
