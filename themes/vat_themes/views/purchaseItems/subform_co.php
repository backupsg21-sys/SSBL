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
                        <col style=" width: 22%;">
                        <col style=" width: 12%;">
                        <col style=" width: 30%;">
                        <col style=" width: 18%;">
                    </colgroup>
                    <tbody>
                    <tr style="border:1px solid #000;text-align: center">
                        <th colspan="8">
                            <?= ($eng) ? 'Sub-form (For Note - 30)' : 'সাবর্ফম্- চ  ( নোট ৩০ “আমদানি পর্যায়ে প্রদেয় আগাম কর”এর জন্য প্রযোজ্য”)' ?>
                        </th>
                    </tr>
                    <tr class="style_5" valign="top" align="center"
                        style="background-color:#F2F0F0;vertical-align: middle">
                        <th class="style_24 center">
                            <?= ($eng) ? 'Serial<br>No.' : 'ক্রমিক <br> নং' ?>
                        </th>
                        <th class="style_24 center">
                            <?= ($eng) ? 'Bill of Entry<br>Number' : 'বিল অব এন্ট্রি <br>নম্বর' ?>
                        </th>
                        <th class="style_24 center">
                            <?= ($eng) ? 'Date' : 'তারিখ' ?>
                        </th>
                        <th class="style_24 center">
                            <?= ($eng) ? 'Custom<br>House/Customs<br>Station' : 'কাস্টম হাউস/ <br>শুল্ক ষ্টেশন' ?>
                        </th>
                        <th class="style_24 center">
                            <?= ($eng) ? 'Advance Tax<br>Amount' : 'আগাম করের পরিমাণ' ?>
                        </th>
                        <th class="style_24 center">
                            <?= ($eng) ? 'Notes' : 'মন্তব্য' ?>
                        </th>
                    </tr>
                    <?php
                    if (!empty($note)) {
                        if ($note == 'thirty') {
                            $condition = "local_import=55 AND at > 0";
                            $sql = "SELECT * FROM purchase_details WHERE  effective_date BETWEEN '$start_date' AND '$end_date' AND ($condition) ORDER BY effective_date ASC";
                        }
                        $connection = Yii::app()->db;
                        $command = $connection->createCommand($sql);
                        $data = $command->queryAll();

                        if (!empty($data)) {
                            $i = 1;
                            $total = 0;
                            $at = 0;
                            foreach ($data as $d) {
                                $purchaseInfo = PurchaseItems::model()->findByPk($d['purchase_id']);
                                ?>
                                <tr class="csd">
                                    <td class="center"><?= $i ?></td>
                                    <td class="center"><?= !empty($purchaseInfo['boe_no']) ? $purchaseInfo['boe_no'] : '' ?></td>
                                    <td class="center"><?= !empty($purchaseInfo['boe_date']) ? date('d-m-Y', strtotime($purchaseInfo['boe_date'])) : '' ?></td>
                                    <td class="center"><?= !empty($purchaseInfo['port_id']) ? Ports::model()->portNameOfThis($purchaseInfo['port_id']) : '' ?></td>
                                    <td class="right"><?= !empty($d['at']) ? number_format($d['at'], 2) : '0.00' ?></td>
                                    <td></td>
                                </tr>
                                <?php
                                $i++;
                                $at = $at + $d['at'];
                            } ?>
                            <tr class="right bold csd" style="background-color: #fbf7f7;">
                                <td colspan="4" style="padding-right: 5px"><?= ($eng) ? 'Total' : 'মোট' ?></td>
                                <td><?= number_format($at, 2) ?></td>
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