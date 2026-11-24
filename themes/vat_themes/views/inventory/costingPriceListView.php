<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/report.css" type="text/css" media="screen" />
<?php if ($data) { ?>
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
    ?>
<?php } ?>

<div class='printAllTableForThisReport'>
    <table class="reportTab">
        <tr>
            <td colspan="4"><?php echo $message; ?></td>
        </tr>
        <tr>
            <th style="width: 20px;">S.L</th>
            <th>DATE</th>
            <th>QUANTITY IN</th>
            <th>COSTING / PURCHASE PRICE</th>
        </tr>

        <?php if ($data) {
            $i = 1; ?>
            <?php foreach ($data as $d) { ?>
                <tr class="
                <?php
                if ($i % 2 == 0)
                    echo 'even';
                else
                    echo 'odd';
                ?>
                    ">
                    <td><?php echo $i++; ?></td>
                    <td><?php echo $d->date; ?></td>
                    <td><?php echo number_format(floatval($d->qty)); ?></td>
                    <td><?php echo number_format(floatval($d->purchase_price)); ?></td>
                </tr>
            <?php } ?>
<?php }else { ?>
            <tr>
                <td colspan="4"><div class="flash-error">No result found!</div></td>
            </tr>
<?php } ?>
    </table>
</div>

