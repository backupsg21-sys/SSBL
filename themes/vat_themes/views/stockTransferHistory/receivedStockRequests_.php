<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/gridview/styles.css" type="text/css" media="screen" />

<fieldset>
    <legend>Stock Receive</legend>
    <div class="grid-view">
        <table class="items">
            <tr>
                <th>Sent From</th>
                <th>Sent Quantity</th>
                <th>Sent Date</th>
                <th>Category</th>
                <th>Sub-Category</th>
                <th>Product</th>
                <th>Code</th>
                <th>Additional Info</th>
                <th>Sent To</th>
                <th>Option</th>
            </tr>
            <?php if ($data) {
                $i = 0; ?>
                <?php foreach ($data as $d): ?>
                    <?php
                    if (UserStore::model()->isThisStoreAssignedToThisUser($d->received_to_store_id) === 1) {
                        $i++;
                        ?>
                        <tr class="<?php
            if ($i % 2 == 0)
                echo "odd";
            else
                echo "even";
                        ?>">
                            <td><?php echo CHtml::encode(Stores::model()->storeName($d->sent_from_store_id)) ?></td>
                            <td><?php echo $d->sent_qty; ?></td>
                            <td><?php echo $d->sent_date; ?></td>
                            <td><?php echo CHtml::encode(ProdItems::model()->itemName($d->item_id)) ?></td>
                            <td><?php echo CHtml::encode(ProdBrands::model()->brandName($d->brand_id)) ?></td>
                            <td><?php echo CHtml::encode(ProdModels::model()->modelName($d->model_id)) ?></td>
                            <td><?php echo $d->code; ?></td>
                            <td><?php ProdModels::model()->optionalImportantInfos($d->model_id); ?></td>
                            <td><?php echo CHtml::encode(Stores::model()->storeName($d->received_to_store_id)) ?></td>
                            <td style="text-align: center; width: 20px;">
                                <input style="background-color: #FFA500;" class="add-more-btn" type="button" value="RECEIVE" onclick="addSections_<?php echo $d->id; ?>(); $('#dialogSections').dialog('open');" />

                                <?php
//                                echo CHtml::link(CHtml::image(Yii::app()->theme->baseUrl . "/images/receive_stock.ico"), "", // the link for open the dialog
//                                        array(
//                                    'title' => 'Receive',
//                                    'style' => 'cursor: pointer;',
//                                    'onclick' => "{receiveStock(); $('#receiveStockDialog').dialog('open');}",
//                                        )
//                                );
                                ?>  
                               <script type="text/javascript">
                                    // here is the magic
                                    function addSections_<?php echo $d->id; ?>(){
        <?php
        echo CHtml::ajax(array(
            'url' => array('stockTransferHistory/stockTransferReqReceive', 'id' => $d->id),
            'data' => "js:$(this).serialize()",
            'type' => 'post',
            'dataType' => 'json',
            'beforeSend' => "function(){
                        $('.ajaxLoaderFormLoad').show();
                        }",
            'complete' => "function(){
                        $('.ajaxLoaderFormLoad').hide();
                        }",
            'success' => "function(data){
                        if (data.status == 'failure'){
                            $('#dialogSections div.divForForm').html(data.content);
                            $('#dialogSections div.divForForm form').submit(addSections_" . $d->id . ");
                        }else{
                            $('#dialogSections div.divForForm').html(data.content);
                             setTimeout(\"$('#dialogSections').dialog('close') \",1000);
                             window.location.reload();
                        }
                        }",
        ))
        ?>;
                                        return false; 
                                    } 
                                </script> 
                            </td>                       
                        </tr>
                    <?php } ?>
                <?php endforeach; ?>
            <?php }else { ?>
                <tr>
                    <td colspan="10"><div class="flash-error">No stock transfer request!</div></td>
                </tr>
            <?php } ?>
        </table>
          <?php
$this->beginWidget('zii.widgets.jui.CJuiDialog', array(// the dialog
'id' => 'dialogSections',
'options' => array(
'title' => 'Stock Receive',
'autoOpen' => false,
'modal' => true,
'width' => 'auto',
'resizable' => false,
'left'=>100,
),
));
?>
<div class="divForForm">
<div class="ajaxLoaderFormLoad" style="display: none;"><img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/ajax-loader.gif" /></div>
</div>

<?php $this->endWidget(); ?>
    </div>
</fieldset>