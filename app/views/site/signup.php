<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Signup';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-signup">
    <h1><?= Html::encode($this->title) ?></h1>
    <p>Please fill out the following fields to signup:</p>
    <div class="row">
        <div class="col-lg-5">

            <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>
            <?= $form->field($model, 'countryCode')->textInput(['autofocus' => true, 'size' => 4]) ?>
            <?= $form->field($model, 'phone') ?>
            <?= $form->field($model, 'username')->textInput() ?>
            <?= $form->field($model, 'password')->passwordInput() ?>
            <?= $form->field($model, 'code')->hiddenInput() ?>
            <div class="form-group">
                <?= Html::button('Signup', ['class' => 'btn btn-primary', 'onclick' => '(function ( $event ) { smsLogin(); })();']); ?>
            </div>
            <?php ActiveForm::end(); ?>

        </div>
    </div>
</div>

<script>
    //@todo move this script in AssetBundle
    AccountKit_OnInteractive = function(){
        AccountKit.init(
            {
                appId:<?=$appId?>,
                state:"{{csrf}}",//@todo
                version:"v1.1",
                fbAppEventsEnabled:true
            }
        );
    };

    // login callback
    function loginCallback(response) {
        if (response.status === "PARTIALLY_AUTHENTICATED") {
            document.getElementById("signupform-code").value = response.code;

            document.getElementById("form-signup").submit();
        }
        else if (response.status === "NOT_AUTHENTICATED") {
// handle authentication failure
        }
        else if (response.status === "BAD_PARAMS") {
// handle bad parameters
        }
    }

    // phone form submission handler
    function smsLogin() {
        var countryCode = document.getElementById("signupform-countrycode").value;
        var phoneNumber = document.getElementById("signupform-phone").value;
        AccountKit.login(
            'PHONE',
            {countryCode: countryCode, phoneNumber: phoneNumber}, // will use default values if not specified
            loginCallback
        );
    }
</script>