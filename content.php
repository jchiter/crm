<!DOCTYPE html>
<html>
<?php global $block, $httpHandler, $page; ?>
<head>
    <title>ШарШарыч - Управление :: <?php echo $page->GetCurrentPageName(); ?></title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="icon" type="image/png" href="styles/fimg/login/icons/favicon.ico">

    <!--<link rel="stylesheet" href="styles/fonts/login/font-awesome-4.7.0/css/font-awesome.min.css?nocache=<?php echo rand(); ?>">
    <link rel="stylesheet" href="styles/fcss/bootstrap/bootstrap.min.css?nocache=<?php echo rand(); ?>">
    <link rel="stylesheet" href="styles/fcss/bootstrap/bootstrap-datetimepicker.min.css?nocache=<?php echo rand(); ?>">
    <link rel="stylesheet" href="styles/fcss/animate/animate.css?nocache=<?php echo rand(); ?>">
	<!--<link rel="stylesheet" href="styles/fcss/bootstrap/bootstrap-table.min.css">-->

    <link rel="stylesheet" href="vendor/fontawesome/css/all.css">
    <link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="vendor/bootstrap/css/bootstrap-dialog.min.css?nocache=<?php echo rand(); ?>">
    <link rel="stylesheet" href="vendor/bootstrap/css/bootstrap-select.min.css?nocache=<?php echo rand(); ?>">
    <link rel="stylesheet" href="vendor/datatables/datatables.min.css">
    <link rel="stylesheet" href="vendor/datetimepicker/datetimepicker.css">
    <link rel="stylesheet" href="vendor/daterangepicker/daterangepicker.css">
    <link rel="stylesheet" href="styles/fcss/sb-admin-2.min.css">

    <link rel="stylesheet" href="styles/fcss/table.css?nocache=<?php echo rand(); ?>">
    <link rel="stylesheet" href="styles/fcss/login/util.css?nocache=<?php echo rand(); ?>">
    <link rel="stylesheet" href="styles/fcss/login/main.css?nocache=<?php echo rand(); ?>">
    <link rel="stylesheet" href="styles/fcss/dialog/dialog.css?nocache=<?php echo rand(); ?>">

    <link rel="stylesheet" href="styles/global.css?nocache=<?php echo rand(); ?>">
    <link rel="stylesheet" href="styles/fcss/content.css?nocache=<?php echo rand(); ?>">
</head>

<body>
<?php
if (!in_array($httpHandler->GetPageName(), ["main", "dictionary"])) {
    ?>
    <div>
        <nav class="navbar navbar-default">
            <div id="logo"><a class="link" href="index.php"></a></div>
            <div class="container-fluid">
                <div class="navbar-collapse collapse" id="navbarNav">
                    <ul class="nav navbar-nav navbar-left">
                        <?php
                        if ($httpHandler->GetAccessLevel() > 0) {
                            echo "<li class=\"nav-item " . $httpHandler->GetClass('new_order') . "\">";
                            echo "<a class=\"nav-link\" href=\"?p=new_order\"><i class=\"fa fa-address-card-o\"></i>новый заказ</a>";
                            echo "</li>";

                            echo "<li class=\"nav-item " . $httpHandler->GetClass('orders') . "\">";
                            echo "<a class=\"nav-link\" href=\"?p=orders\"><i class=\"fa fa-list-alt\"></i>список заказов</a>";
                            echo "</li>";

                            echo "<li class=\"nav-item " . $httpHandler->GetClass('history') . "\">";
                            echo "<a class=\"nav-link\" href=\"?p=history\"><i class=\"fa fa-history\"></i>история</a>";
                            echo "</li>";

                            //echo "<li class=\"nav-item " . $httpHandler->GetClass('statistics') . "\">";
                            //echo "<a class=\"nav-link\" href=\"?p=statistics\"><i class=\"fa fa-area-chart\"></i>статистика выручки</a>";
                            //echo "</li>";

                            echo "<li class=\"dropdown nav-item " . $httpHandler->GetClass('statistics') . "\">";
                            echo "<a class=\"dropdown-toggle nav-link\" data-toggle=\"dropdown\" href=\"#\"><i class=\"fa fa-area-chart\"></i>&nbsp;статистика<span class=\"caret\"></span></a>";
                            echo "<ul class=\"dropdown-menu\">";
                            echo "<li><a class=\"nav-link\" href=\"?p=statistics-pay\">выручки</a></li>";
                            echo "<li><a class=\"nav-link\" href=\"?p=statistics-sale\">каналов продаж</a></li>";
                            echo "</ul>";
                            echo "</li>";
                        } ?>
                    </ul>

                    <ul class="nav navbar-nav navbar-right">
                        <?php
                        if ($httpHandler->GetAccessLevel() > 0) {
                            echo "<li class=\"nav-item " . $httpHandler->GetClass('profile') . "\"><a class=\"nav-link\" href=\"?p=profile\"><i class=\"fa fa-user-o\"></i>профиль</a></li>";
                            echo "<li class=\"nav-item " . $httpHandler->GetClass('exit') . "\"><a class=\"nav-link\" onclick=\"$.coremanage.systemExit(true)\"><i class=\"fa fa-sign-out\"></i>выход</a></li>";
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </nav>
    </div>
    <?php
}
?>
<div class="bg-contact2">
    <div class="container-contact2" <?php echo in_array($httpHandler->GetPageName(), ["main", "dictionary"]) ? "style='min-height: 100vh;'" : ""; ?>>
        <?php
        $block->Content();
        ?>
    </div>
</div>
<div id="faDialog" class="dialog-overlay">
    <div class="dialog-card">
        <div class="dialog-title"><h5></h5><span class="fa fa-remove"></span></div>
        <div class="dialog-content">
            <div id="typeIcon"><i></i></div>
            <div class="dialog-info"><p></p></div>
        </div>
        <div class="dialog-footer">
            <button class="dialog-confirm-button">OK</button>
        </div>
    </div>
</div>
<!--<script src="js/jquery/jquery-1.12.4.min.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script src="js/bootstrap/bootstrap.min.js"></script>
<script src="js/bootstrap/defaults-ru_RU.js"></script>
<script src="js/bootstrap/bootstrap-formhelpers-phone.js"></script>
<script src="js/bootstrap/moment-with-locales.min.js"></script>
<script src="js/bootstrap/bootstrap-datetimepicker.min.js"></script>
<script src="js/jquery/jquery.fancybox.js"></script>
<script src="js/jquery/jquery.fancybox-buttons.js"></script>
<script src="js/jquery/jquery.fancybox-media.js"></script>
<script src="js/jquery/jquery.fancybox-thumbs.js"></script>
<script src="js/jquery/jquery.form.js"></script>
<script src="js/jquery/jquery.animate-colors.js"></script>
<script src="https://api-maps.yandex.ru/2.1.77/?lang=ru_RU" type="text/javascript"></script>

<script src="js/bootstrap/bootstrap-table.min.js?nocache=<?php echo rand(); ?>"></script>
<script src="js/bootstrap/bootstrap-table-ru-RU.min.js?nocache=<?php echo rand(); ?>">-->

<script type="text/javascript" src="vendor/jquery/jquery-3.5.1.min.js"></script>
<script type="text/javascript" src="vendor/form/jquery.form.js"></script>
<script type="text/javascript" src="js/jquery/jquery.confirm.js"></script>
<script type="text/javascript" src="vendor/popper/popper.min.js"></script>
<script type="text/javascript" src="vendor/bootstrap/js/bootstrap.min.js"></script>
<script type="text/javascript" src="vendor/bootstrap/js/bootstrap-dialog.min.js"></script>
<script type="text/javascript" src="vendor/datatables/datatables.min.js"></script>
<script type="text/javascript" src="vendor/moment/moment.min.js"></script>
<script type="text/javascript" src="vendor/moment/moment-with-locales.min.js"></script>
<script type="text/javascript" src="vendor/datetimepicker/datetimepicker.js"></script>
<script type="text/javascript" src="vendor/daterangepicker/daterangepicker.js"></script>
<script type="text/javascript" src="vendor/bootstrap/js/bootstrap-select.js"></script>
<script type="text/javascript" src="vendor/bootstrap/js/i18n/defaults-ru_RU.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>

<script type="text/javascript" src="js/global.js?nocache=<?php echo rand(); ?>"></script>
<script type="text/javascript" src="js/coreui.js?nocache=<?php echo rand(); ?>"></script>
<script type="text/javascript" src="js/coremanage.js?nocache=<?php echo rand(); ?>"></script>
</body>
</html>