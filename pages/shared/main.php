<div class="dashboard-grid-wrap">
    <?php
    global $userHandler, $httpHandler;

    if ($httpHandler->GetAccessLevel() > 0) {
        ?>
        <div class="dashboard-grid">
            <div class="grid-item" id="new-order"><i class="fa fa-address-card-o"></i><a class="link" href="?p=new_order"></a>новый заказ</div>
            <div class="grid-item" id="all-order"><i class="fa fa-list-alt"></i><a class="link" href="?p=orders"></a>список заказов</div>
            <div class="grid-item" id="history"><i class="fa fa-history"></i><a class="link" href="?p=history"></a>история</div>
        </div>
        <div class="dashboard-grid">
            <div class="grid-item" id="profile"><i class="fa fa-user-o"></i><a class="link" href="?p=profile"></a>профиль</div>
            <div class="grid-item" id="exit" onclick="$.coremanage.systemExit()"><i class="fa fa-sign-out"></i>выход</div>
        </div>
        <?php
    } else {
        ?>
        <div class="dashboard-grid">
            <div class="grid-item" id="login" onclick="$.coremanage.showModule('auth', true)"><i class="fa fa-sign-in"></i>вход</div>
        </div>
        <?php
    }
    ?>
</div>