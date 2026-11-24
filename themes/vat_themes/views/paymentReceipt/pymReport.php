
<div style="float: left; width: 800px;">
    <?php
    if ($data) {

        $yourCompanyInfo = YourCompany::model()->findByAttributes(array('is_active' => YourCompany::ACTIVE,));
        if ($yourCompanyInfo) {
            $yourCompanyName = $yourCompanyInfo->company_name;
            $yourCompanyLocation = $yourCompanyInfo->location;
            $yourCompanyRoad = $yourCompanyInfo->road;
            $yourCompanyHouse = $yourCompanyInfo->house;
            $yourCompanyContact = $yourCompanyInfo->contact;
            $yourCompanyEmail = $yourCompanyInfo->email;
            $yourCompanyWeb = $yourCompanyInfo->web;
        } else {
            $yourCompanyName = 'N/A';
            $yourCompanyLocation = 'N/A';
            $yourCompanyRoad = 'N/A';
            $yourCompanyHouse = 'N/A';
            $yourCompanyContact = 'N/A';
            $yourCompanyEmail = 'N/A';
            $yourCompanyWeb = 'N/A';
        }
        ?>

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
                '#calculateBtnTr',
            ),
            'publishCss' => TRUE, //publish the CSS for the whole page?
            'visible' => !Yii::app()->user->isGuest, //should this be visible to the current user?
            'alt' => 'print', //text which will appear if image can't be loaded
            'debug' => FALSE, //enable the debugger to see what you will get
            'id' => 'print-div'         //id of the print link
        ));
        echo "</div>";
        ?>

        <div class='printAllTableForThisReport' style="float: left;">
            <table class="poReportTabUpper">
                <tr>
                    <th colspan="5" style="text-align: center;">Money Receipt Voucher</th>
                </tr>
                <tr>
                    <td style="width: 94px; font-weight: bold;">Received By:</td>
                    <td><?php echo $yourCompanyName; ?></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td style="width: 94px;">Location:</td>
                    <td><?php echo $yourCompanyLocation; ?></td>
                    <td></td>
                    <td style="text-align: right;">Payment Receipt No:</td>
                    <td style="text-align: right; width: 145px;"><?php echo $data->id; ?></td>
                </tr>
                <tr>
                    <td style="width: 94px;">Road:</td>
                    <td><?php echo $yourCompanyRoad; ?></td>
                    <td></td>
                    <td style="text-align: right; font-weight: bold;">Paid To</td>
                    <td style="text-align: right;"><?php echo Suppliers::model()->supplierName($data->supplier_id); ?></td>
                </tr>
                <tr>
                    <td style="width: 94px;">House:</td>
                    <td><?php echo $yourCompanyHouse; ?></td>
                    <td></td>
                    <td style="text-align: right;">Date:</td>
                    <td style="text-align: right; width: 145px;"><?php echo $data->date; ?></td>
                </tr>
                <tr>
                    <td style="width: 94px;">E-Mail:</td>
                    <td><?php echo $yourCompanyEmail; ?></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td style="width: 94px;">Web</td>
                    <td><?php echo $yourCompanyWeb; ?></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </table>
            <div class="grid-view">
                <table class="items">
                    <tr>
                        <th>Amount</th>
                    </tr>
                    <tr class="odd">
                        <td><?php echo number_format(floatval($data->amount)); ?></td>
                    </tr>                  
                </table>
            </div>
            <table class="poReportTabUpper" style="margin-top: 20px;">
                <tr>
                    <td colspan="6" style="text-align: left;">Thank you for your business</td>
                </tr>
            </table>
        </div>
        <?php
    } else {
        
    }
    ?>

</div>
