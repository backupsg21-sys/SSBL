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
    'visible' => !Yii::app()->user->isGuest, //should this be visible to the current user?
    'alt' => 'print', //text which will appear if image can't be loaded
    'debug' => FALSE, //enable the debugger to see what you will get
    'id' => 'print-div'         //id of the print link
));
echo "</div>";

$supplieInfo = Suppliers::model()->findAll(array('order' => 'id ASC'));
?>
<style>
    table.reportTab tr td img {
        height: 79px;
        position: relative;
        top: 10px;
    }
</style>
<div class='printAllTableForThisReport'>
    <table class="reportTab" id="table-1" style="width:100%">
        <thead>
            <tr>
                <th>Product Name</th>
                <th>Code</th>
                <th>Store</th>
                <th>Qty</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $storeModels = Stores::model()->findAll();
            foreach ($storeModels as $store) {
                $stoteId = $store->id;
                $stock = Inventory::model()->inventoryInfoOfThisModel($stoteId, $modelId);
                $storeName = Stores::model()->nameOfThis($stoteId);
                $modelName = ProdModels::model()->prodNameOfThis($modelId);
                $code = ProdModels::model()->prodCodeOfThis($modelId);
                if ($stock > 0) {
                    echo "<tr>";
                    echo "<td>$modelName</td>";
                    echo "<td>$code</td>";
                    echo "<td>$storeName</td>";
                    echo "<td>$stock</td>";
                    echo "</tr>";
                }
            }
            ?>
        </tbody>
    </table>
    <table id="header-fixed"></table>
</div>
<script>
    var tableOffset = $("#table-1 thead tr.titlesTr").offset().top;
    var $header = $("#table-1 > thead tr.titlesTr").clone();
    var $fixedHeader = $("#header-fixed").append($header);

    $(window).bind("scroll", function () {
        var offset = $(this).scrollTop();

        if (offset >= tableOffset && $fixedHeader.is(":hidden")) {
            $fixedHeader.show();
        } else if (offset < tableOffset) {
            $fixedHeader.hide();
        }
    });

</script>

