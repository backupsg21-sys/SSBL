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
$eng = false;
if (isset($_SESSION['translation_type']) && $_SESSION['translation_type'] == 2)
    $eng = true;
?>
<div class='printAllTableForThisReport' style="background-color: #F1F3F4; min-height: 650px;">
    <table class="reportTabCustom" style="border-collapse: collapse; empty-cells: show;">
        <colgroup>
            <col>
        </colgroup>
        <tbody style="border:5px solid #fff;">
        <tr valign="top">
            <td class="style_14">
                <table class="style_23" style="border-collapse: collapse; empty-cells: show; width: 100%;">
                    <colgroup>
                        <col style=" width: 8%;">
                        <col style=" width: 15%;">
                        <col style=" width: 15%;">
                        <col style=" width: 15%;">
                        <col style=" width: 10%;">
                        <col style=" width: 12%;">
                        <col style=" width: 12%;">
                        <col style=" ">
                    </colgroup>
                    <tbody>
                    <tr style="border:1px solid #000;text-align: center">
                        <th colspan="8">
                            <?= ($eng) ? 'Sub-form : (For Note -' : 'সাবর্ফম্- ছ  ( নোট' ?>
                            <?php
                            if ($note == 'fiftytwo') {
                                $condition = "purpose = 1";
                                if ($eng) echo '52'; else echo '৫২';
                            } else if ($note == 'fiftythree') {
                                $condition = "purpose = 2";
                                if ($eng) echo '53'; else echo '৫৩';
                            } else if ($note == 'fiftyfour') {
                                $condition = "treasury_purpose = 1";
                                if ($eng) echo '54'; else echo '৫৪';
                            } else if ($note == 'fiftyfive') {
                                $condition = "treasury_purpose = 2";
                                if ($eng) echo '55'; else echo '৫৫';
                            } else if ($note == 'fiftysix') {
                                $condition = "treasury_purpose = 3";
                                if ($eng) echo '56'; else echo '৫৬';
                            } else if ($note == 'fiftyseven') {
                                $condition = "treasury_purpose = 4";
                                if ($eng) echo '57'; else echo '৫৭';
                            } else if ($note == 'fiftyeight') {
                                $condition = "treasury_purpose = 5";
                                if ($eng) echo '58'; else echo '৫৮';
                            } else if ($note == 'fiftynine') {
                                $condition = "treasury_purpose = 6";
                                if ($eng) echo '59'; else echo '৫৯';
                            } else if ($note == 'sixty') {
                                $condition = "treasury_purpose = 7";
                                if ($eng) echo '60'; else echo '৬০';
                            } else if ($note == 'sixtyone') {
                                $condition = "treasury_purpose = 8";
                                if ($eng) echo '61'; else echo '৬১';
                            } else {
                                $condition = " id > 0 ";
                                echo 'N/A';
                            }
                            ?>
                            <?= ($eng) ? ')' : 'এর জন্য প্রযোজ্য)' ?>
                        </th>
                    </tr>
                    <tr class="style_5" valign="top" align="center"
                        style="background-color:#f2f0f0;vertical-align: middle">
                        <th class="style_24 center">
                            <?= ($eng) ? 'Serial<br>No.' : 'ক্রমিক <br> নং' ?>
                        </th>
                        <th class="style_24 center">
                            <?= ($eng) ? 'Treasury Challan<br>Number' : '' ?>
                            <?= ($eng) && ($note == 'fiftyeight' || $note == 'fiftynine' || $note == 'sixty' || $note == 'sixtyone') ? '/Token Number' : '' ?>
                            <?= ($eng) ? '' : 'ট্রেজারি চালান <br>নম্বর' ?>
                        </th>
                        <th class="style_24 center">
                            <?= ($eng) ? 'Bank' : '' ?>
                            <?= ($eng) && ($note == 'fiftyeight' || $note == 'fiftynine' || $note == 'sixty' || $note == 'sixtyone') ? '/CGA' : '' ?>
                            <?= ($eng) ? '' : 'ব্যাংক' ?>
                        </th>
                        <th class="style_24 center">
                            <?= ($eng) ? 'Branch' : '' ?>
                            <?= ($eng) && ($note == 'fiftyeight' || $note == 'fiftynine' || $note == 'sixty' || $note == 'sixtyone') ? '/CAFO/DCO' : '' ?>
                            <?= ($eng) ? '' : 'শাখা' ?>
                        </th>
                        <th class="style_24 center">
                            <?= ($eng) ? 'Date' : 'তারিখ' ?>
                        </th>
                        <th class="style_24 center">
                            <?= ($eng) ? 'Tax deposit Account Code' : 'একাউন্ট কোডে কর পরিশোধিত' ?>
                        </th>
                        <th class="style_24 center">
                            <?= ($eng) ? 'Amount' : 'পরিমাণ' ?>
                        </th>
                        <th class="style_24 center">
                            <?= ($eng) ? 'Notes' : 'মন্তব্য' ?>
                        </th>
                    </tr>
                    <?php
                    if (!empty($note)) {
                        $month = date("Y-m", strtotime($end_date));
                        echo $note;
                        if ($note != 'fiftytwo' || $note != 'fiftythree' || !empty($note)) {
                            $sql = "SELECT * FROM adjustments WHERE  payment_date BETWEEN '$start_date' AND '$end_date' AND ($condition) ORDER BY payment_date ASC";
                        }
                        //if ($note == 'fiftytwo' || $note == 'fiftythree') {
                        if ($note == 'fiftyeight'){
                            //$sql = "SELECT * FROM vat_payment WHERE  payment_date BETWEEN '$start_date' AND '$end_date' AND ($condition) ORDER BY payment_date ASC";
                            //$sql = "SELECT * FROM vat_payment WHERE  payment_month LIKE '%$month%' AND ($condition) ORDER BY payment_month ASC"; //shohag
                            $sql = "SELECT * FROM vat_payment WHERE  payment_month LIKE '%$month%' ORDER BY payment_month ASC";
                        }
                        if ($note == 'fiftynine'){
                            $sql = "SELECT * FROM vat_payment WHERE  payment_date BETWEEN '$start_date' AND '$end_date' AND (purpose = 2) ORDER BY payment_date ASC";
                        }
                        $data = Yii::app()->db->createCommand($sql)->queryAll();
                        if (!empty($data)) {
                            $i = 1;
                            $total = 0;
                            $amount = 0;
                            foreach ($data as $d) {
                                //$supplierInfo = Suppliers::model()->findByPk($d['supplier_id']);
                                //$purchaseInfo = PurchaseItems::model()->findByPk($d['purchase_id']);
                                //$vdsChallanOfPurchase = VdsPayment::model()->findByAttributes(array('po_no' => $d['po_no']));
                                if ($d['bank_id']) {
                                    $bankinfo = ChartOfAc::model()->nameOfThis($d['bank_id']);
                                }
                                ?>
                                <tr class="csd">
                                    <td class="center"><?= $i ?></td>
                                    <td class="center">
                                        <?php
                                        if ($note == 'fiftytwo' || $note == 'fiftythree') {
                                            echo $d['chalan_no'];
                                        } else {
                                            //echo $d['challan_no'];
                                            echo $d['tr_challan_no'];
                                        }
                                        ?>
                                    </td>
                                    <td class="center"><?= !empty($bankinfo) ? $bankinfo : '' ?></td>
                                    <td class="center"><?= $d['branch'] ?></td>
                                    <td class="center"><?= !empty($d['tr_challan_date']) ? date('d-m-Y', strtotime($d['tr_challan_date'])) : '' ?></td>
                                    <td class="center"><?= isset($d['account_code_id']) ? AccountCode::model()->accountCodeOfThis($d['account_code_id']) : "" ?></td>
                                    <td class="right"><?= !empty($d['amount']) ? number_format($d['amount'], 2) : '0.00' ?></td>
                                    <td class="center"><?= $d['note'] ?></td>
                                </tr>
                                <?php
                                $i++;
                                $amount = $amount + $d['amount'];
                            } ?>
                            <tr class="right bold csd" style="background-color: #fbf7f7;">
                                <td colspan="6" style="padding-right: 5px"><?= ($eng) ? 'Total' : 'মোট' ?></td>
                                <td><?= number_format($amount, 2) ?></td>
                                <td></td>
                            </tr>
                            <?php
                        }
                    }
                    ?>
                    </tbody>
                </table>
            </td>
        </tr>
        </tbody>
    </table>

    <style>
        .fieldset {
            background-color: #fbf7f7f7;
        }

        .reportTabCustom {
            font-size: 12px;
            background-color: #fff;
            width: 8.3in;
            margin: auto;
            font-family: 'times new roman', arial;
            min-height: 8in;
            color: #000;
        }

        .style_5 th, .style_21 th, .style_21 td {
            border: 1px solid #000 !important; /*#c0c0c0*/
        }

        .style_19 {
            border: none;
        }

        .center_tr th {
            text-align: center;
            border: 1px solid #000;
        }

        .csd td, .csdtr td {
            border: 1px solid #000;
        }

        .csdtr td {
            font-weight: bold;
        }

        .left {
            text-align: left;
        }

        .center {
            text-align: center;
        }

        .right {
            text-align: right;
        }

        .bold {
            font-weight: bold;
        }

        @media print {
            .reportTabCustom {
                width: 100%
            }
        }
    </style>
</div>