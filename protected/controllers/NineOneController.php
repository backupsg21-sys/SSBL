<?php

class NineOneController extends Controller
{
    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/column2';
    public $defaultAction = 'admin';

    // CREATE ADJUSTMENTS FORM
    public function actionSave()
    {

        $model = new NineOne();
        $model->submit_date         = isset($_POST['submit_date']) ? $_POST['submit_date'] : NULL;
        $model->vat_date            = isset($_POST['vat_date']) ? $_POST['vat_date'] : NULL;
        $model->vat_month           = isset($_POST['vat_month']) ? $_POST['vat_month'] : NULL;
        $model->year                = isset($_POST['year']) ? $_POST['year'] : NULL;
        $model->closing_vat         = isset($_POST['closing_vat']) ? $_POST['closing_vat'] : NULL;
        $model->closing_sd          = isset($_POST['closing_sd']) ? $_POST['closing_sd'] : NULL;
        $model->e_remain_vat        = isset($_POST['e_remain_vat']) ? $_POST['e_remain_vat'] : NULL;
        $model->e_remain_sd         = isset($_POST['e_remain_sd']) ? $_POST['e_remain_sd'] : NULL;
        $model->decrease_vat        = isset($_POST['decrease_vat']) ? $_POST['decrease_vat'] : NULL;
        $model->decrease_sd         = isset($_POST['decrease_sd']) ? $_POST['decrease_sd'] : NULL;
        $model->vat_refund_claim    = isset($_POST['vat_refund_claim']) ? $_POST['vat_refund_claim'] : NULL;
        $model->sd_refund_claim     = isset($_POST['sd_refund_claim']) ? $_POST['sd_refund_claim'] : NULL;
        $model->refund_status       = isset($_POST['refund_status']) ? $_POST['refund_status'] : 1;
        $model->last_status         = isset($_POST['last_status']) ? $_POST['last_status'] : 1;
        $model->doc_type            = isset($_POST['doc_type']) ? $_POST['doc_type'] : NULL;
        $model->is_trader_wholesaler = isset($_POST['is_trader_wholesaler'])? $_POST['is_trader_wholesaler']:NULL;
        if ($model->save())
            echo 1;
        else{
            echo 0;
        }
    }

    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'adjustments-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}