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
                        <col style=" width: 7%;">
                        <col style=" width: 16%;">
                        <col style=" width: 10%;">
                        <col style=" width: 16%;">
                        <col style=" width: 9%;">
                        <col style=" width: 9%;">
                        <col style=" width: 9%;">
                        <col style=" width: 9%;">
                        <col style=" width: 9%;">
                        <col style=" ">
                    </colgroup>
                    <tbody>
                    <tr style="border:1px solid #000; text-align: center">
                        <th colspan="10">
                            <?= ($eng) ? 'Sub-form ' : '' ?> <?= ($eng) && ($note == 'eighteen') ? 'for local supply' : '' ?> <?= ($eng) ? '(For Note -' : 'সাবর্ফম্- গ  ( নোট' ?>
                            <?php
                            if ($note == 'six') {
                                if ($eng) echo '6'; else echo '৬';
                            } else if ($note == 'eighteen') {
                                if ($eng) echo '18'; else echo '১৮';
                            } else {
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
                            <?= ($eng) ? 'Goods /Service Commercial Description' : 'পণ্য/সেবার বানিজ্যিক বর্ণনা' ?>
                        </th>
                        <th class="style_24 center">
                            <?= ($eng) ? 'Goods /<br>Service Code' : 'পণ্য/সেবার কোড' ?>
                        </th>
                        <th class="style_24 center">
                            <?= ($eng) ? 'Goods /<br>Service Name' : 'পণ্য/সেবার নাম' ?>
                        </th>
                        <th class="style_24 center">
                            <?= ($eng) ? 'Unit of<br>Measure<br>(a)' : 'পরিমাপের<br>একক<br> (ক)' ?>
                        </th>
                        <th class="style_24 center">
                            <?= ($eng) ? 'Quantity<br>(b)' : 'পরিমাণ<br>(খ)' ?>
                        </th>
                        <th class="style_24 center">
                            <?= ($eng) ? 'Actual<br>Sales / Purchases Value<br>(c)' : 'প্রকৃত<br>বিক্রয়/ক্রয় <br>মূল্য<br>(গ)' ?>
                        </th>
                        <th class="style_24 center">
                            <?= ($eng) ? 'SD<br>(d)' : 'সম্পূরক <br> শুল্ক (ঘ)' ?>
                        </th>
                        <th class="style_24 center">
                            <?= ($eng) ? 'VAT<br>(e)' : 'মূল্য সংযোজন <br> কর   <br> (ঙ)' ?>
                        </th>
                        <th class="style_24 center">
                            <?= ($eng) ? 'Notes' : 'মন্তব্য' ?>
                        </th>
                    </tr>
                    <?php
                    if (!empty($note)) {
                        if ($note == 'six') {
                            $condition = "vat_calculation_method = 3";
                            $sql = "SELECT * FROM sales_details WHERE sales_date BETWEEN '$start_date' AND '$end_date' AND approve_count=2 AND ($condition) ORDER BY sales_date ASC";
                        } else if ($note == 'eighteen') {

                            $condition = "pd.vat_calculation_method = 3 AND pd.local_import=54 AND si.supplier_type_id = 1";
                            $sql = "SELECT * FROM purchase_details AS pd INNER JOIN suppliers AS si ON pd.supplier_id = si.id 
                                    WHERE pd.issue_date BETWEEN '$start_date' AND '$end_date' AND pd.approve_count=2 AND ($condition) ORDER BY pd.issue_date ASC";
                        }
                        $data = Yii::app()->db->createCommand($sql)->queryAll();

                        if (!empty($data)) {
                            $i = 1;
                            $total = 0;
                            $sd = 0;
                            $tax = 0;
                            foreach ($data as $d) {
                                if ($d['parts_service'] == 1) {
                                    $type = 'Parts';
                                    $info = ProdModels::model()->findByPk($d['model_id']);
                                    $name = !empty($info['model_name']) ? $info['model_name'] : '';
                                } else {
                                    $type = 'Service';
                                    $info = Services::model()->findByPk($d['service_id']);
                                    $name = !empty($info['name']) ? $info['name'] : '';
                                }

                                if ($note == 'six') {
                                    $qty = $d['sales_qty'];
                                } else if ($note == 'eighteen') {
                                    $qty = $d['order_qty'];
                                }
                                ?>
                                <tr class="csd">
                                    <td class="center"><?= $i ?></td>
                                    <td class="center"> <?= $type ?></td>
                                    <td class="left"
                                        style="padding-left: 5px"> <?= ($info['code']) ? $info['code'] : '' ?></td>
                                    <td class="left" style="padding-left: 5px"> <?= $name ?></td>
                                    <td class="center">
                                        <?= Units::model()->unitLabel($d['unit_id']); ?>
                                    </td>
                                    <td class="center">
                                        <?= !empty($qty) ? $qty : '0' ?>
                                    </td>
                                    <td class="right"> <?= !empty($d['total']) ? number_format($d['total'], 2) : '0.00' ?></td>
                                    <td class="right"> <?= !empty($d['sd']) ? number_format($d['sd'], 2) : '0.00' ?></td>
                                    <?php
                                    if ($note == 'six') {
                                        ?>
                                        <td class="right"> <?= !empty($d['vat']) ? number_format($d['vat'], 2) : '0.00' ?></td>
                                        <?php
                                        $tax = $tax + $d['vat'];
                                    } else if ($note == 'eighteen') { ?>
                                        <td class="right"> <?= !empty($d['rebate']) ? number_format($d['rebate'], 2) : '0.00' ?></td>
                                        <?php
                                        $tax = $tax + $d['rebate'];
                                    }
                                    ?>

                                    <td></td>
                                </tr>
                                <?php
                                $i++;
                                $total = $total + $d['total'];
                                $sd = $sd + $d['sd'];
                            } ?>
                            <tr class="right bold csd" style="background-color: #fbf7f7;">
                                <td colspan="6" style="padding-right: 5px"><?= ($eng) ? 'Total' : 'মোট' ?></td>
                                <td><?= number_format($total, 2) ?></td>
                                <td><?= number_format($sd, 2) ?></td>
                                <td><?= number_format($tax, 2) ?></td>
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

        .csd td {
            border: 1px solid #000;
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