<?php if ($data1) { ?>
    <?php
    echo "<div class='printBtn'>";
    $this->widget('ext.mPrint.mPrint', array(
        'title' => ' ', //the title of the document. Defaults to the HTML title
        'tooltip' => 'Print', //tooltip message of the print icon. Defaults to 'print'
        'text' => '', //text which will appear beside the print icon. Defaults to NULL
        'element' => '.printAllTableForThisReport', //the element to be printed.
        'exceptions' => array(//the element/s which will be ignored
            '.summary',
            '.search-form',
        ),
        'publishCss' => FALSE, //publish the CSS for the whole page?
        'visible' => Yii::app()->user->checkAccess('print'), //should this be visible to the current user?
        'alt' => 'print', //text which will appear if image can't be loaded
        'debug' => FALSE, //enable the debugger to see what you will get
        'id' => 'print-div'         //id of the print link
    ));
    echo "</div>";
    ?>
<?php } ?>
<div class='printAllTableForThisReport'>
    <table class="reportTab" id="table-1">
        <thead>
            <tr>
                <td colspan="8" style="font-weight:bold; font-size:16;"><?php echo $message; ?></td>
                <td colspan="3" class="textAlgnLeft">
                    PP- Purchase Price<br />
                    RcvQ- Received Quantity<br />
                    LA- Line Amount
                </td>
                <td colspan="4" class="textAlgnLeft">
					OQ- Ordered Quantity<br />
                    RetrnQ- Returned Quantity<br />
                    TA- Total Amount
                </td>
            </tr>
            <tr class="titlesTr">
                <th style="width: 20px;">SL</th>
                <th style="width: 60px;">Order No</th>
                <th style="width: 75px;">Ref No</th>
                <th style="width: 70px;">Purchase Date</th>
                <th style="width: 130px;">Supplier</th>
                <th style="width: 130px;">Memo No.</th>
                <th style="width: 140px;">Item</th>
                <th style="width: 100px;">Code</th>
                <th style="width: 100px;">Received To</th>
                <th style="width: 50px;">OQ</th>
                <th style="width: 50px;">RcvQ</th>
                <th style="width: 50px;">RetrnQ</th>
                <th style="width: 50px;">PP</th>
                <th style="width: 50px;">LA ($)</th>
                <th style="width: 50px;">TA ($)</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!$data1) { ?>
                <tr>
                    <td colspan="14"><div class="flash-error">No result found!</div></td>
                </tr>
            <?php } else { ?>  
                <?php
                $thisSellNoFormat = VoucherNoFormate::model()->numberFormatOfThis(VoucherNoFormate::PURCHASE_ORDER);
                $sl = 1;
                $i = 1;
                $lineTotalWithODGrand = 0;
                
				foreach ($data1 as $d1):
				
                    $subTotal = 0;
                    $soGroupCount = 1;
                    //$condition = $condition2 . " AND po_no=" . $d1->po_no . " ORDER BY id DESC";
                    //$soData = PurchaseItems::model()->findAll(array('condition' => $condition,), 'id');
                    
                    $criteria = new CDbCriteria;
                    $criteria->select = 't.*,prod_models.code as prod_code,prod_models.item_id as prod_item_id,prod_models.brand_id as prod_brand_id';
                    $criteria->join =' inner JOIN `prod_models` on t.model_id=prod_models.id ';
                    $criteria->condition = $condition2 . " AND po_no=" . $d1->po_no . " ORDER BY id DESC";
                    $soData = PurchaseItems::model()->findAll($criteria);                    
                    $rowspan = 0;
                    
					foreach ($soData as $sod){					
                        if ($storeId == "") {
                             $rowspan++;
                        }
						else{														
							$deliveFromStore = ReceivedPurchase::model()->findByAttributes(array('purchase_id' => $sod->id));
							if ($deliveFromStore) {
								if ($deliveFromStore->store_id === $storeId) {
                                	$rowspan++;
								}								
							}
						}
					}
					if($rowspan==0){$rowspan=1;}
					
					foreach ($soData as $sod):
					
                        if ($storeId == "") {
							
                            $deliveFromStore = ReceivedPurchase::model()->findByAttributes(array('purchase_id' => $sod->id));
                            
							$criteria2 = new CDbCriteria();
                            $criteria2->select = 'sum(return_qty) AS totalReturnQty, sum(received_qty) AS orderedQty, supplier_bill_no';
                            $criteria2->condition = 'purchase_id=' . $sod->id;
                            $sellDeliveryInfo = ReceivedPurchase::model()->findAll($criteria2);
                            $totalDeliveryQuantity = 0;
                            $totalReturnQuantity = 0;
                            $supplierBillNo="";
                            if ($sellDeliveryInfo) {
                                foreach ($sellDeliveryInfo as $sdi):
                                    $totalDeliveryQuantity = $sdi->orderedQty;
                                    $totalReturnQuantity = $sdi->totalReturnQty;
                                    $supplierBillNo=$sdi->supplier_bill_no;
                                endforeach;
                            }
                            $totalQty = ($totalDeliveryQuantity - $totalReturnQuantity);
                            $lineTotalOne = ($totalQty * $sod->purchase_price);
                            $subTotal = $lineTotalOne + $subTotal;                            
							
							if ($i % 2 == 0) {
								$class = 'even';
							} else {
								$class = 'odd';
							}
							$i++;							
                            ?>
                            <tr class="<?php echo $class;?>">
								<?php 
                                if ($soGroupCount == 1) {
                                    ?>
                                    <td class="merge" rowspan="<?php echo $rowspan; ?>"><?php echo $sl++; ?></td>
                                    <td class="merge textAlgnLeft" rowspan="<?php echo $rowspan; ?>"><?php echo $thisSellNoFormat . $d1->po_no; ?></td>
                                    <td class="merge textAlgnLeft" rowspan="<?php echo $rowspan; ?>"><?php echo $d1->ref_no; ?></td>
                                    <td class="merge" rowspan="<?php echo $rowspan; ?>"><?php echo $d1->issue_date; ?></td>
                                    <td class="merge textAlgnLeft" rowspan="<?php echo $rowspan; ?>">
                                    	<?php echo CHtml::encode(Suppliers::model()->supplierName($d1->supplier_id)); ?>                                    	
                                    </td>
                                    <td class="merge textAlgnLeft" rowspan="<?php echo $rowspan; ?>">
                                    	<?php
                                        if($supplierBillNo !=""){
                                            echo $supplierBillNo;
										}
                                    	?>
                                    </td>
                                	<?php 
								}
								?>
                                <td class="textAlgnLeft"><?php echo CHtml::encode(ProdModels::model()->modelName($sod->model_id)); ?></td>
                                <td><?php echo $sod->prod_code; ?></td>
                                <td class="textAlgnLeft">
                                    <?php
                                    if ($deliveFromStore) {
                                        echo CHtml::encode(Stores::model()->storeName($deliveFromStore->store_id));
                                    } else {
                                        echo "<font style='color: red; font-weight: bold;'>Not Delivered Yet!</font>";
                                    }
                                    ?>
                                </td>
                                <td><?php echo number_format(floatval($sod->order_qty)); ?></td>
                                <td><?php echo number_format(floatval($totalDeliveryQuantity)); ?></td>
                                <td><?php echo number_format(floatval($totalReturnQuantity)); ?></td>
                                <td><?php echo number_format(floatval($sod->purchase_price)); ?></td>
                                <td><?php echo number_format(floatval($lineTotalOne)); ?></td>
                                <?php 
								if ($soGroupCount == 1) { ?>
                                    <td class="merge textAlgnRight" id="subTotal_<?php echo $sl; ?>" rowspan="<?php echo $rowspan; ?>">&nbsp;</td>
                                	<?php 
								}
								
                                if ($rowspan == $soGroupCount) {
                                    ?>
                            		<script>
										$("#subTotal_<?php echo $sl; ?>").html(parseFloat(<?php echo $subTotal; ?>));
									</script>
									<?php 
                                }
								
                                $lineTotalWithODGrand=$lineTotalWithODGrand+$lineTotalOne;
                                ?>

                        	</tr>
							<?php 
							$soGroupCount++;
                        }
						else{
														
							$deliveFromStore = ReceivedPurchase::model()->findByAttributes(array('purchase_id' => $sod->id));
							if ($deliveFromStore) {
								if ($deliveFromStore->store_id === $storeId) {
                                
									$criteria2 = new CDbCriteria();
									$criteria2->select = 'sum(return_qty) AS totalReturnQty, sum(received_qty) AS orderedQty, supplier_bill_no';
									$criteria2->condition = 'purchase_id=' . $sod->id;
									$sellDeliveryInfo = ReceivedPurchase::model()->findAll($criteria2);
									$totalDeliveryQuantity = 0;
									$totalReturnQuantity = 0;
									$supplierBillNo="";
									if ($sellDeliveryInfo) {
										foreach ($sellDeliveryInfo as $sdi):
											$totalDeliveryQuantity = $sdi->orderedQty;
											$totalReturnQuantity = $sdi->totalReturnQty;
											$supplierBillNo=$sdi->supplier_bill_no;
										endforeach;
									}
									$totalQty = ($totalDeliveryQuantity - $totalReturnQuantity);
									$lineTotalOne = ($totalQty * $sod->purchase_price);
									$subTotal = $lineTotalOne + $subTotal;
									
									if ($i % 2 == 0) {
										$class = 'even';
									} else {
										$class = 'odd';
									}
									$i++;
									?>
                                	<tr class="<?php echo $class;?>">
                                    	<?php 
										if ($soGroupCount == 1) {
											?>
                                            <td class="merge" rowspan="<?php echo $rowspan; ?>"><?php echo $sl++; ?></td>
                                            <td class="merge textAlgnLeft" rowspan="<?php echo $rowspan; ?>"><?php echo $thisSellNoFormat . $d1->po_no; ?></td>
                                            <td class="merge textAlgnLeft" rowspan="<?php echo $rowspan; ?>"><?php echo $d1->ref_no; ?></td>
                                            <td class="merge" rowspan="<?php echo $rowspan; ?>"><?php echo $d1->issue_date; ?></td>
                                            <td class="merge textAlgnLeft" rowspan="<?php echo $rowspan; ?>">
                                            	<?php echo CHtml::encode(Suppliers::model()->supplierName($d1->supplier_id)); ?>
                                            </td>
                                            <td class="merge textAlgnLeft" rowspan="<?php echo $rowspan; ?>">
												<?php
                                                if($supplierBillNo!="")
                                                    echo $supplierBillNo;
                                            	?>
                                        	</td>
											<?php
                                        } ?>
                                        <td class="textAlgnLeft"><?php echo CHtml::encode(ProdModels::model()->modelName($sod->model_id)); ?></td>
                                        <td><?php echo $sod->prod_code; ?></td>
                                        <td class="textAlgnLeft">
                                            <?php
                                            if ($deliveFromStore) {
                                                echo CHtml::encode(Stores::model()->storeName($deliveFromStore->store_id));
                                            } else {
                                                echo "<font style='color: red; font-weight: bold;'>Not Delivered Yet!</font>";
                                            }
                                            ?>
                                        </td>
                                        <td><?php echo number_format(floatval($sod->order_qty)); ?></td>
                                        <td><?php echo number_format(floatval($totalDeliveryQuantity)); ?></td>
                                        <td><?php echo number_format(floatval($totalReturnQuantity)); ?></td>
                                        <td><?php echo number_format(floatval($sod->purchase_price)); ?></td>
                                        <td><?php echo number_format(floatval($lineTotalOne)); ?></td>
                                    	<?php 
										if ($soGroupCount == 1) { ?>
                                        	<td class="merge textAlgnRight" id="subTotal_<?php echo $sl; ?>" rowspan="<?php echo $rowspan; ?>">&nbsp;</td>
											<?php 
                                       	}
										
                                        if ($rowspan == $soGroupCount) {
                                            ?>
                                            <script>
                                                $("#subTotal_<?php echo $sl; ?>").html(parseFloat(<?php echo $subTotal; ?>));
                                            </script>
                                            <?php 
                                        }
										
										$lineTotalWithODGrand=$lineTotalWithODGrand+$lineTotalOne;
										?>
                                	</tr>
                            		<?php 
									$soGroupCount++;
								}
								
							}
						}
						
						
					endforeach;
				endforeach;
				?>
                <tr class="odd">
                    <td colspan="14" class="textAlgnRight">Total</td>
                    <td class="textAlgnRight heightLight"><?php echo number_format(floatval($lineTotalWithODGrand)); ?></td>
                </tr>  
				<?php 
            } ?>
        </tbody>
    </table>
    <table id="header-fixed"></table>
</div>

<script>
    var tableOffset = $("#table-1 thead tr.titlesTr").offset().top;
    var $header = $("#table-1 > thead tr.titlesTr").clone();
    var $fixedHeader = $("#header-fixed").append($header);

    $(window).bind("scroll", function() {
        var offset = $(this).scrollTop();
    
        if (offset >= tableOffset && $fixedHeader.is(":hidden")) {
            $fixedHeader.show();
        }
        else if (offset < tableOffset) {
            $fixedHeader.hide();
        }
    });

</script>
