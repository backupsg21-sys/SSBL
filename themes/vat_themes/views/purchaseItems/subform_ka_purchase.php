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
$yourCompanyInfo = YourCompany::model()->findByAttributes(array('is_active' => YourCompany::ACTIVE,));
if ($yourCompanyInfo->company_vat_type == YourCompany::STANDARD)
    $standard = true;
else
    $standard = false;

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
                        <col style=" width: 5%;">
                        <?php if ($note == 'eleven' || $note == 'thirteen' || $note == 'fifteen' || $note == 'seventeen' || $note == 'twentytwo') { ?>
                            <col>
                            <col>
                            <col>
                            <col>
                            <col>
                        <?php } ?>
                        <col>
                        <col>
                        <col>
                        <?php if ($note == 'eleven' || $note == 'thirteen' || $note == 'fifteen' || $note == 'seventeen' || $note == 'twentytwo') { ?>
                            <col>
                            <col>
                        <?php } ?>
                        <col>
                        <col>
                        <?php if ($note == 'eleven' || $note == 'thirteen' || $note == 'fifteen' || $note == 'seventeen' || $note == 'twentytwo') { ?>
                            <col>
                        <?php } ?>
                        <col>
                    </colgroup>
                    <tbody>
                    <tr style="border:1px solid #000; text-align: center">
                        <th colspan="<?= ($note == 'eleven' || $note == 'thirteen' || $note == 'fifteen' || $note == 'seventeen' || $note == 'twentytwo') ? '15' : '8' ?>">
                            <?= ($eng) ? 'Sub-form for ' : '' ?> <?= ($eng) && ($note == 'eleven' || $note == 'thirteen' || $note == 'fifteen' || $note == 'seventeen' || $note == 'twentytwo') ? 'Export/Import' : 'local supply' ?> <?= ($eng) ? '(For Note -' : 'সাবর্ফম্- ক  ( নোট' ?>
                            <?php
                            if ($note == 'ten') {
                                if ($eng) echo '10'; else echo '১০';
                            } else if ($note == 'eleven') {
                                if ($eng) echo '11'; else echo '১১';
                            } else if ($note == 'twelve') {
                                if ($eng) echo '12'; else echo '১২';
                            } else if ($note == 'thirteen') {
                                if ($eng) echo '13'; else echo '১৩';
                            } else if ($note == 'fourteen') {
                                if ($eng) echo '14'; else echo '১৪';
                            } else if ($note == 'fifteen') {
                                if ($eng) echo '15'; else echo '১৫';
                            } else if ($note == 'sixteen') {
                                if ($eng) echo '16'; else echo '১৬';
                            } else if ($note == 'seventeen') {
                                if ($eng) echo '17'; else echo '১৭';
                            } else if ($note == 'nineteen') {
                                if ($eng) echo '19'; else echo '১৯';
                            } else if ($note == 'twenty') {
                                if ($eng) echo '20'; else echo '২০';
                            } else if ($note == 'twentyone') {
                                if ($eng) echo '21'; else echo '২১';
                            } else if ($note == 'twentytwo') {
                                if ($eng) echo '22'; else echo '২২';
                            } else {
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
                        <?php if ($note == 'eleven' || $note == 'thirteen' || $note == 'fifteen' || $note == 'seventeen' || $note == 'twentytwo') { ?>

                            <th class="style_24 center">
                                <?= ($eng) ? 'Invoice/BOE No' : '' ?>
                            </th>
                            <th class="style_24 center">
                                <?= ($eng) ? 'Date' : '' ?>
                            </th>
                            <th class="style_24 center">
                                <?= ($eng) ? 'Office Code' : '' ?>
                            </th>
                            <th class="style_24 center">
                                <?= ($eng) ? 'Item No' : '' ?>
                            </th>
                            <th class="style_24 center">
                                <?= ($eng) ? 'CPC<br>(Optional)' : '' ?>
                            </th>

                        <?php } ?>

                        <th class="style_24 center">
                            <?= ($eng) ? 'Goods / Service<br>Commercial<br>Description' : 'পণ্য/সেবার  <br>বানিজ্যিক <br> বর্ণনা' ?>
                        </th>
                        <th class="style_24 center">
                            <?= ($eng) ? 'Goods /<br>Service Code' : 'পণ্য/সেবার কোড' ?>
                        </th>
                        <th class="style_24 center">
                            <?= ($eng) ? 'Goods /<br>Service Name' : 'পণ্য/সেবার নাম' ?>
                        </th>

                        <?php if ($note == 'eleven' || $note == 'thirteen' || $note == 'fifteen' || $note == 'seventeen' || $note == 'twentytwo') { ?>

                            <th class="style_24 center">
                                <?= ($eng) ? 'Asseassable<br>value' : '' ?>
                            </th>
                            <th class="style_24 center">
                                <?= ($eng) ? 'Base value of<br>VAT' : '' ?>
                            </th>

                        <?php } ?>
                        <?php if ($note == 'ten' || $note == 'twelve' || $note == 'fourteen' || $note == 'sixteen' || $note == 'nineteen' || $note == 'twenty' || $note == 'twentyone') { ?>

                            <th class="style_24 center">
                                <?= ($eng) ? 'Value <br>(a)' : 'মূল্য<br>(ক)' ?>
                            </th>

                        <?php } ?>

                        <th class="style_24 center">
                            <?= ($eng) ? 'SD<br>(b)' : 'সম্পূরক <br> শুল্ক (খ)' ?>
                        </th>
                        <th class="style_24 center">
                            <?= ($eng) ? 'VAT<br>(c)' : 'মূল্য সংযোজন <br> কর   <br> (গ)' ?>
                        </th>
                        <?php if ($note == 'eleven' || $note == 'thirteen' || $note == 'fifteen' || $note == 'seventeen' || $note == 'twentytwo') { ?>

                            <th class="style_24 center">
                                <?= ($eng) ? 'AT' : '' ?>
                            </th>

                        <?php } ?>
                        <th class="style_24 center">
                            <?= ($eng) ? 'Notes' : 'মন্তব্য' ?>
                        </th>
                    </tr>
                    <?php if ($note == 'eleven' || $note == 'thirteen' || $note == 'fifteen' || $note == 'seventeen' || $note == 'twentytwo') { ?>

                        <tr class="style_5" valign="top" align="center"
                            style="background-color:#eae8e8;vertical-align: middle">
                            <th class="style_24 center">
                                <?= ($eng) ? '1' : '১' ?>
                            </th>

                            <th class="style_24 center">
                                <?= ($eng) ? '2' : '২' ?>
                            </th>
                            <th class="style_24 center">
                                <?= ($eng) ? '3' : '৩' ?>
                            </th>
                            <th class="style_24 center">
                                <?= ($eng) ? '4' : '৪' ?>
                            </th>
                            <th class="style_24 center">
                                <?= ($eng) ? '5' : '৫' ?>
                            </th>
                            <th class="style_24 center">
                                <?= ($eng) ? '6' : '৬' ?>
                            </th>

                            <th class="style_24 center">
                                <?= ($eng) ? '7' : '৭' ?>
                            </th>
                            <th class="style_24 center">
                                <?= ($eng) ? '8' : '৮' ?>
                            </th>
                            <th class="style_24 center">
                                <?= ($eng) ? '9' : '৯' ?>
                            </th>

                            <th class="style_24 center">
                                <?= ($eng) ? '10' : '১০' ?>
                            </th>
                            <th class="style_24 center">
                                <?= ($eng) ? '11' : '১১' ?>
                            </th>


                            <th class="style_24 center">
                                <?= ($eng) ? '12' : '১২' ?>
                            </th>
                            <th class="style_24 center">
                                <?= ($eng) ? '13' : '১৩' ?>
                            </th>

                            <th class="style_24 center">
                                <?= ($eng) ? '14' : '১৪' ?>
                            </th>

                            <th class="style_24 center">
                                <?= ($eng) ? '15' : '১৫' ?>
                            </th>
                        </tr>
                    <?php } ?>
                    <?php
                    if (!empty($note)) {
                        if ($note == 'ten') {
                            $condition = "pd.vat_calculation_method = 5 AND pd.local_import=54 AND si.supplier_type_id = " . Suppliers::REGISTERED;
                        } else if ($note == 'eleven') {
                            $condition = "(pd.vat_calculation_method = 5) AND pd.local_import=55 AND si.supplier_type_id = " . Suppliers::REGISTERED;
                        } else if ($note == 'twelve') {
                            $condition = "(pd.vat_calculation_method = 4) AND pd.local_import=54 AND si.supplier_type_id = " . Suppliers::REGISTERED;
                        } else if ($note == 'thirteen') {
                            $condition = "(pd.vat_calculation_method = 4) AND pd.local_import=55 AND si.supplier_type_id = " . Suppliers::REGISTERED;
                        } else if ($note == 'fourteen') {
                            $condition = "pd.vat_calculation_method = 1 AND pd.local_import=54 AND rebate > 0 AND si.supplier_type_id = " . Suppliers::REGISTERED;
                        } else if ($note == 'fifteen') {
                            $condition = "pd.vat_calculation_method = 1 AND pd.local_import=55 AND rebate > 0 AND si.supplier_type_id = " . Suppliers::REGISTERED;
                        } else if ($note == 'sixteen') {
                            $condition = " (pi.total_vds = 0 OR pi.total_vds IS NULL) AND rebate > 0 AND pd.vat_calculation_method = 2 AND pd.local_import=54 AND si.supplier_type_id = " . Suppliers::REGISTERED;
                        } else if ($note == 'seventeen') {
                            $condition = "pd.vat_calculation_method = 2 AND pd.local_import=55 AND si.supplier_type_id = " . Suppliers::REGISTERED;
                        } else if ($note == 'nineteen') {
                            $condition = "si.supplier_type_id= " . Suppliers::TURNOVER;
                        } else if ($note == 'twenty') {
                            $condition = " (pi.total_vds = 0 OR pi.total_vds IS NULL) AND si.supplier_type_id= " . Suppliers::UNREGISTERED;
                        } else if ($note == 'twentyone') {
                            $condition = "pd.local_import=54";
                        } else if ($note == 'twentytwo') {
                            $condition = "pd.local_import=55";
                        }

                        if ($note == 'twentyone' || $note == 'twentytwo') {
                            $sql = "SELECT pd.*,pi.boe_no,pi.port_code,pi.po_no FROM purchase_details AS pd 
                            INNER JOIN purchase_items as pi ON pi.id = pd.purchase_id 
                            WHERE pd.effective_date BETWEEN '$start_date' AND '$end_date' AND $condition AND pd.rebate = 0 AND (pi.total_vds = 0 OR pi.total_vds IS NULL) ORDER BY pd.effective_date ASC"; //AND pd.approve_count=2
                        } else {
                            $sql = "SELECT pd.*,pi.boe_no,pi.port_code, pi.po_no FROM purchase_details AS pd 
                            INNER JOIN suppliers AS si ON pd.supplier_id = si.id 
                            INNER JOIN purchase_items as pi ON pi.id = pd.purchase_id 
                            WHERE pd.effective_date BETWEEN '$start_date' AND '$end_date'AND ($condition) ORDER BY pd.effective_date ASC"; // AND pd.approve_count=2
                        }

                        $data = Yii::app()->db->createCommand($sql)->queryAll();

//                        if (!$standard && ($note == 'twentyone' || $note == 'twentytwo')) {
//                        } else if ($standard && ($note == 'ten' || $note == 'eleven' || $note == 'twelve' || $note == 'thirteen' || $note == 'fourteen' || $note == 'fifteen' || $note == 'sixteen' || $note == 'seventeen' || $note == 'nineteen' || $note == 'twenty')) {
//                        } else {
//                            $data = '';
//                        }

                        if (!empty($data)) {
                            $i = 1;
                            $total = 0;
                            $sd = 0;
                            $vat = 0;
                            $total_av = 0;
                            $total_base_value_vat = 0;
                            foreach ($data as $d) {
                                if ($d['parts_service'] == 1) {
                                    $type = 'Goods';
                                    $info = ProdModels::model()->findByPk($d['model_id']);
                                    $name = !empty($info['model_name']) ? $info['model_name'] : '';
                                    $code = !empty($info['hs_code']) ? $info['hs_code'] : '';
                                } else {
                                    $type = 'Service';
                                    $info = Services::model()->findByPk($d['service_id']);
                                    $name = !empty($info['name']) ? $info['name'] : '';
                                    $code = !empty($info['code']) ? $info['code'] : '';
                                }
                                ?>
                                <tr class="csd">
                                    <td class="center"><?= $i ?></td>
                                    <?php if ($note == 'eleven' || $note == 'thirteen' || $note == 'fifteen' || $note == 'seventeen' || $note == 'twentytwo') { ?>
                                        <td class="center"> <?= $d['boe_no']; ?></td>
                                        <td class="center"> <?= $d['effective_date']; ?></td>
                                        <td class="center"> <?= $d['port_code']; ?></td>
                                        <td class="center"> <?= '' ?></td>
                                        <td class="center"> <?= '' ?></td>
                                    <?php } ?>

                                    <td class="center"> <?= $type ?></td>
                                    <td class="left" style="padding-left: 5px"> <?= ($code) ? $code : '' ?></td>
                                    <td class="left" style="padding-left: 5px"> <?= $name ?></td>

                                    <?php if ($note == 'ten' || $note == 'twelve' || $note == 'fourteen' || $note == 'sixteen' || $note == 'nineteen' || $note == 'twenty' || $note == 'twentyone') { ?>

                                        <td class="right"> <?= !empty($d['total']) ? number_format($d['total'] + $d['cd'] + $d['rd'], 2) : '0.00' ?></td>

                                    <?php } ?>

                                    <?php if ($note == 'eleven' || $note == 'thirteen' || $note == 'fifteen' || $note == 'seventeen' || $note == 'twentytwo') {
                                        $total_av = $total_av + $d['total'];
                                        $total_base_value_vat = $total_base_value_vat + $d['total'] + $d['cd'] + $d['rd'] + $d['sd'];

                                        ?>

                                        <td class="right"><?= $d['total'] ?></td>
                                        <td class="right"><?= $d['total'] + $d['cd'] + $d['rd'] + $d['sd'] ?></td>

                                    <?php } ?>

                                    <td class="right"><?= !empty($d['sd']) ? number_format($d['sd'], 2) : '0.00' ?></td>
                                    <td class="right"> <?= !empty($d['rebate']) ? number_format($d['rebate'], 2) : '0.00' ?></td>

                                    <?php if ($note == 'eleven' || $note == 'thirteen' || $note == 'fifteen' || $note == 'seventeen' || $note == 'twentytwo') { ?>
                                        <td class="right"> <?= '' ?></td>
                                    <?php } ?>

                                    <td class="center"> <?= $d['po_no']; ?>  </td>
                                </tr>
                                <?php
                                $i++;
                                $total = $total + $d['total'] + $d['cd'] + $d['rd'];
                                $sd = $sd + $d['sd'];
                                $vat = $vat + $d['rebate'];
                            } ?>
                            <tr class="right bold csd">
                                <td><?= '' ?></td>
                                <?php if ($note == 'eleven' || $note == 'thirteen' || $note == 'fifteen' || $note == 'seventeen' || $note == 'twentytwo') { ?>
                                    <td><?= '' ?></td>
                                    <td><?= '' ?></td>
                                    <td><?= '' ?></td>
                                    <td><?= '' ?></td>
                                    <td><?= '' ?></td>
                                <?php } ?>
                                <td colspan="3" style="padding-right: 5px"><?= ($eng) ? 'Total' : 'মোট' ?></td>
                                <?php if ($note == 'eleven' || $note == 'thirteen' || $note == 'fifteen' || $note == 'seventeen' || $note == 'twentytwo') { ?>
                                    <td><?= number_format($total_av, 2) ?></td>
                                    <td><?= number_format($total_base_value_vat, 2) ?></td>
                                <?php } ?>
                                <?php if ($note == 'ten' || $note == 'twelve' || $note == 'fourteen' || $note == 'sixteen' || $note == 'nineteen' || $note == 'twenty' || $note == 'twentyone') { ?>
                                    <td><?= number_format($total, 2) ?></td>
                                <?php } ?>
                                <td><?= number_format($sd, 2) ?></td>
                                <td><?= number_format($vat, 2) ?></td>
                                <?php if ($note == 'eleven' || $note == 'thirteen' || $note == 'fifteen' || $note == 'seventeen' || $note == 'twentytwo') { ?>
                                    <td><?= number_format(0, 2) ?></td>
                                <?php } ?>
                                <td>  </td>
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