<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\helpers\UtilityHelper;
use app\models\User;
use app\models\Staff;
use app\models\UserRole;
use app\assets\ReactStaffPageAsset;

$loggedInUserId = \Yii::$app->user->identity->id;

ReactStaffPageAsset::register($this);
/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */
?>
<?php
$template = '{label}<div class="col-xs-12 col-md-5">{input}{error}{hint}</div>';
$labelOptions = ['class' => 'col-xs-4 control-label'];

$options = ['template' => $template, 'labelOptions' => $labelOptions];
?>

<div class="user-form form-horizontal">

    <?php $form = ActiveForm::begin(['id' => 'staff-form']); ?>
    <input type="hidden" name="User[role]" value="<?php echo User::ROLE_USER?>"/>
    <?= $form->field($model, 'active', $options)->dropDownList([1 => 'Active', 0 => 'Inactive'],
        ['class' => 'form-control']
    ); ?>
    <?= $form->field($model, 'roles', $options)->checkboxList(UserRole::ROLES_DESC) ?>
    <?= $form->field($model, 'first_name', $options)->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'last_name', $options)->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'email', $options)->textInput(['class' => 'form-control', 'type' => 'email']) ?>
    <?= $form->field($model, 'username', $options)->textInput(['maxlength' => true]) ?>
    <?php if ($model->isNewRecord) { ?>
        <?= $form->field($model, 'password', $options)->passwordInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'confirmPassword', $options)->passwordInput(['maxlength' => true]) ?>
    <?php }else{ ?>
     <input type='hidden' name='saveType' value='account'/>
    <?php }?>
    <?= $form->field($model, 'homePhone', $options)->textInput(['maxlength' => true, 'class' => 'form-control phone']) ?>
    <?= $form->field($model, 'cellPhone', $options)->textInput(['maxlength' => true, 'class' => 'form-control phone']) ?>
    <?= $form->field($model, 'workPhone', $options)->textInput(['maxlength' => true, 'class' => 'form-control phone']) ?>
    <?= $form->field($model, 'fax', $options)->textInput(['class' => 'form-control phone']) ?>
    <?= $form->field($model, 'city', $options)->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'state', $options)->dropDownList(UtilityHelper::StateList(),
        ['prompt' => '', 'class' => 'form-control state']    // options
    ); ?>
    <?= $form->field($model, 'zip', $options)->textInput(['maxlength' => true, 'class' => 'form-control zip']) ?>
    <?= $form->field($model, 'address1', $options)->textInput(['maxlength' => true]) ?>


    <div class="form-group">
        <div class=" col-xs-12 col-md-offset-4 col-md-5">
            <?= Html::submitButton($model->isNewRecord ? 'Create User' : 'Save Changes', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

    <?php if ($model->isNewRecord == false) { ?>
        <?php $form = ActiveForm::begin(); ?>
         <input type='hidden' name='saveType' value='password'/>
        <div class="form-pwd-change-wrapper">
            <?= $form->field($model, 'password', $options)->passwordInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'confirmPassword', $options)->passwordInput(['maxlength' => true]) ?>
            <div class="form-group">
                <div class=" col-xs-12 col-md-offset-4 col-md-5">
                    <?= Html::submitButton('Save New Password', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                </div>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    <?php } ?>

    <div id="react-entry-staff-update">
    </div>
</div>


<style>
    .phone {
        width: 125px;
    }
    .state {
        width: 80px
    }
    .zip {
        width: 80px
    }
    .form-pwd-change-wrapper{
        padding-top: 40px;
        margin-right: 25px;
    }
</style>

<script>
var loggedInUserId = <?= $loggedInUserId ?>;
var user = <?= json_encode($user) ?>
</script>
