<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\bootstrap\Tabs;
use kartik\datetime\DateTimePicker;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model star\marketing\models\Coupon */
/* @var $form yii\widgets\ActiveForm */

list($path, $link) = $this->getAssetManager()->publish('@star/marketing/web/js');
$this->registerJsFile($link . '/coupon.js', ['depends' => [\yii\web\JqueryAsset::className()]]);

if(isset($model->start_at) && isset($model->end_at)) {
    $model->start_at = date('Y-m-d H:i', $model->start_at);
    $model->end_at = date('Y-m-d H:i', $model->end_at);
    $start_at = $model->start_at;
    $end_at = $model->end_at;
} else {
    $start_at = date('Y-m-d H:i', time());
    $end_at = date('Y-m-d H:i', time());
}
?>

<div class="coupon-form">

    <?php $form = ActiveForm::begin();

    $fieldGroups = [];
    $fields = [];
    $stations = \core\models\Station::findAll(['enabled' => 1]);
    $stationArray = ArrayHelper::map($stations, 'id', 'name');
    $fields[] = $form->field($model, 'star_id')->dropDownList($stationArray);
    $fields[] = $form->field($model, 'total')->textInput(['maxlength' => true]);
    $fields[] = $form->field($model, 'desc')->textarea(['maxlength' => true]);
    $fields[] = $form->field($model, 'status')->dropDownList([1 => '是',0 => '否']);
    $fields[] = $form->field($model, 'start_at')->widget(DateTimePicker::className(),[
        'options' => [
            'value' => $start_at,
        ],
        'pluginOptions' => [
            'language' => 'zh-CN',
            'autoclose'=>true,
        ]
    ]);
    $fields[] = $form->field($model, 'end_at')->widget(DateTimePicker::className(),[
        'options' => [
            'value' => $end_at,
        ],
        'pluginOptions' => [
            'language' => 'zh-CN',
            'autoclose'=>true,
        ]
    ]);
    $fieldGroups[] = ['label' => Yii::t('marketing','Coupon Info'), 'content' => implode('', $fields)];

    $fields = [];
    $fields[] = $form->field($model, 'total_price')->textInput(['maxlength' => true]);
    $fields[] = $form->field($model, 'qty')->textInput(['maxlength' => true]);

    //todo categories
//    $root = \common\models\Tree::find()->where(['name' => '商品分类'])->one();
//    $categories = $root->children(1)->all();
//    $categories = ArrayHelper::map($categories, 'id', 'name');
//    $fields[] = $form->field($model, 'category_id')->widget(Select2::classname(), [
//        'data' => $categories,
//        'language' => 'en',
//        'pluginOptions' => [
//            'placeholder' => 'Select a state ...',
//        ],
//        'options' => [
//            'multiple' => true,
//        ],
//    ]);

    $fieldGroups[] = ['label' => Yii::t('marketing','Coupon Conditions'), 'content' => implode('', $fields)];

    $fields = [];
    $fields[] = $form->field($model, 'type')->dropDownList([0 => '满减', 1 => '打折']);
    $fields[] = $form->field($model, 'number')->textInput(['maxlength' => true]);
    $fields[] = $form->field($model, 'shipping')->dropDownList([0 => '邮费不变', 1 => '减邮费', 2 => '包邮']);
    $fields[] = $form->field($model, 'shippingFee')->textInput(['maxlength' => true]);
    $fieldGroups[] = ['label' => Yii::t('marketing','Coupon Result'), 'content' => implode('', $fields)];

    echo Tabs::widget(['items' => $fieldGroups]);
    ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('coupon', 'Create') : Yii::t('coupon', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>