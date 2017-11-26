<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Please fill out the following fields to login:</p>

    <?php $form = ActiveForm::begin([
        'id' => 'login-form',
        'layout' => 'horizontal',
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-1 control-label'],
        ],
    ]); ?>

        <?= $form->field($model, 'countryCode')->textInput(['autofocus' => true]) ?>

        <?= $form->field($model, 'phone')->textInput() ?>

        <?= $form->field($model, 'code')->hiddenInput() ?>

        <div class="form-group">
            <div class="col-lg-offset-1 col-lg-11">
                <?= Html::button('Login', ['class' => 'btn btn-primary', 'onclick' => '(function ( $event ) { smsLogin(); })();']); ?>
            </div>
        </div>

    <?php ActiveForm::end(); ?>
</div>

<script>
    //@todo move this script in AssetBundle
    AccountKit_OnInteractive = function(){
        AccountKit.init(
            {
                appId: "370055530111268",
                state: document.getElementsByName("_csrf")[0].value,//@todo
                version: "v1.1",
                fbAppEventsEnabled: true
            }
        );
    };

    // login callback
    function loginCallback(response) {
        if (response.status === "PARTIALLY_AUTHENTICATED") {
            document.getElementById("loginform-code").value = response.code;
            document.getElementsByName("_csrf")[0].value = response.state;
            document.getElementById("login-form").submit();
        }
        else if (response.status === "NOT_AUTHENTICATED") {
        //@todo show ui error
        }
        else if (response.status === "BAD_PARAMS") {
        //@todo show ui error
        }
    }

    function smsLogin() {
        var countryCode = document.getElementById("loginform-countrycode").value;
        var phoneNumber = document.getElementById("loginform-phone").value;
        AccountKit.login(
            'PHONE',
            {countryCode: countryCode, phoneNumber: phoneNumber}, // will use default values if not specified
            loginCallback
        );
    }
</script>
