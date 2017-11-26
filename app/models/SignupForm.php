<?php

namespace app\models;

use FB\Accountkit\DTO\Account;
use Yii;
use yii\base\Model;

/**
 * Signup form
 */
class SignupForm extends Model
{
    /**
     * @var string
     */
    public $username;
    /**
     * @var string
     */
    public $phone;
    /**
     * @var string
     */
    public $countryCode;
    /**
     * @var string
     */
    public $code;
    /**
     * @var string
     */
    public $password;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'phone', 'countryCode'], 'trim'],
            [['username', 'phone', 'countryCode', 'code', 'password'], 'required'],
            ['username', 'string', 'min' => 2, 'max' => 255],
            ['phone', 'string', 'max' => 10],
            ['countryCode', 'string', 'max' => 4],
            ['password', 'string', 'min' => 6],
        ];
    }

    /**
     * @return bool
     */
    public function validateFB()
    {
        try {
            //@todo need Constructor Injection
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
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if (!($this->validate() && $this->validateFB())) {
            return null;
        }
        $user = User::find()
            ->where(['country_code' => $this->countryCode, 'phone' => $this->phone])
            ->one();

        //if user isset
        if (!is_null($user)) {
            return $user;
        }

        $user = new User();
        $user->username = $this->username;
        $user->country_code = $this->countryCode;
        $user->phone = $this->phone;
        $user->setPassword($this->password);

        return $user->save() ? $user : null;
    }

}
