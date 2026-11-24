<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="language" content="en" />
        <link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/layout.css" type="text/css" media="screen" />
        <title><?php echo CHtml::encode($this->pageTitle); ?></title>
        <link rel="icon" href="<?php echo Yii::app()->theme->baseUrl; ?>/images/favicon.gif" />

        <?php
        $cs = Yii::app()->getClientScript();
        $cs->registerCoreScript('jquery');
        //$cs->registerScriptFile(Yii::app()->theme->baseUrl . '/js/jquery.js');
        $cs->registerScriptFile(Yii::app()->theme->baseUrl . '/js/alertify.js');
        $cs->registerScript('jquery-live-polyfill', "
            if (typeof jQuery !== 'undefined' && !jQuery.fn.live) {
                jQuery.fn.live = function(types, data, fn) {
                    jQuery(document).on(types, this.selector, data, fn);
                    return this;
                };
            }
        ", CClientScript::POS_HEAD);
        ?>

        <!-- BOOTSTRAP FILES -->
        <link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/bootstrap.min.css" type="text/css" media="screen" />
        <!--
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/bootstrap.min.js"></script>-->

        <!-- ADMIN LTE CSS -->
        <link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/AdminLTE.min.css" type="text/css" media="screen" />
        <style type="text/css">
            #ui-datepicker-div {
                z-index: 99999 !important;
            }
        </style>
    </head>
    <body>
        <?php echo $content; ?>
        <!-- iframe area only use for export excel-->
        <iframe id="txtArea1" style="display:none"></iframe>
    </body>

    <!-- Custom Overload CSS -->
    <link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/pegi-css.css" type="text/css" media="screen" />

    <!-- Shathi CSS -->
    <link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/shathi.css" type="text/css" media="screen" />


<style>
    .flash-success, flash-error {
        -moz-animation: cssAnimation 0s ease-in 4s forwards;
        -webkit-animation: cssAnimation 0s ease-in 4s forwards;
        -o-animation: cssAnimation 0s ease-in 4s forwards;
        animation: cssAnimation 0s ease-in 4s forwards;
        -webkit-animation-fill-mode: forwards;
        animation-fill-mode: forwards;

        color: #fff !important;
        padding: 2px !important;
        border: none !important;
    }
    @keyframes cssAnimation {
        to {
            width:0;
            height:0;
            overflow:hidden;
        }
    }
    @-webkit-keyframes cssAnimation {
        to {
            width:0;
            height:0;
            visibility:hidden;
        }
    }
    .flash-success {
        background-color: #4949f7 !important;
    }
    .flash-error {
        background-color: red !important;
    }
</style>
<script>
    function fnExcelReport()
    {
        var tab_text="<table border='2'><tr>";
        var textRange; var j=0;
        tab = document.getElementById("table-id-for-excel"); // id of table

        for(j = 0 ; j < tab.rows.length ; j++)
        {
            tab_text=tab_text+tab.rows[j].innerHTML+"</tr>";
            //tab_text=tab_text+"</tr>";
        }

        tab_text=tab_text+"</table>";
        tab_text= tab_text.replace(/<A[^>]*>|<\/A>/g, "");//remove if u want links in your table
        tab_text= tab_text.replace(/<img[^>]*>/gi,""); // remove if u want images in your table
        tab_text= tab_text.replace(/<input[^>]*>|<\/input>/gi, ""); // reomves input params

        var ua = window.navigator.userAgent;
        var msie = ua.indexOf("MSIE ");

        if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./))      // If Internet Explorer
        {
            txtArea1.document.open("txt/html","replace");
            txtArea1.document.write(tab_text);
            txtArea1.document.close();
            txtArea1.focus();
            sa=txtArea1.document.execCommand("SaveAs",true,".xls");
        }
        else                 //other browser not tested on IE 11
            sa = window.open('data:application/vnd.ms-excel,' + encodeURIComponent(tab_text));

        return (sa);
    }
</script>



</html>
