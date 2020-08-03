<div class="dashboard-grid-wrap">
    <?php
    global $userHandler, $httpHandler;

    if ($httpHandler->GetAccessLevel() > 0) {
        ?>
        <div class="dashboard-grid">
            <div class="grid-item" id="new-order"><i class="fas fa-address-card"></i><a class="link" href="?p=new_order"></a>новый заказ</div>
            <div class="grid-item" id="all-order"><i class="fas fa-list-alt"></i><a class="link" href="?p=orders"></a>список заказов</div>
            <div class="grid-item" id="history"><i class="fas fa-history"></i><a class="link" href="?p=history"></a>история</div>
            <div class="grid-item" id="statistics"><i class="fas fa-chart-bar"></i><a class="link" href="?p=statistics"></a>статистика</div>
            <div class="grid-item" id="profile"><i class="fas fa-id-card"></i><a class="link" href="?p=profile"></a>профиль</div>
            <?php
            if ($httpHandler->GetAccessLevel() > 0) {?>
                <div class="grid-item"><i class="fas fa-book"></i><a class="link" href="?p=dictionary"></a>справочники</div>
            <? } ?>
            <div class="grid-item" id="exit" onclick="$.coremanage.systemExit()"><i class="fas fa-sign-out-alt"></i>выход</div>
        </div>
        <?php
    } else {
        ?>
        <div class="dashboard-grid">
            <div class="grid-item" id="login" onclick="$.coremanage.showModule('auth', true)"><i class="fas fa-sign-in-alt"></i>вход</div>
        </div>
        <?php
    }
    ?>
</div>