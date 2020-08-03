<div class="dashboard-grid-wrap">
    <?php
    global $userHandler, $httpHandler;

    if ($httpHandler->GetAccessLevel() <= 0) {
        ?>
        <div class="dashboard-grid">
            <div class="grid-item" id="login" onclick="$.coremanage.showModule('auth', true)"><i class="fas fa-sign-in-alt"></i>вход</div>
        </div>
        <? return false; ?>
        <?php
    }
    ?>

    <div class="dashboard-grid">
        <div class="grid-item"><i class="fas fa-home"></i><a class="link" href="?p=main"></a>главная</div>
        <div class="grid-item"><i class="fas fa-building"></i><a class="link" href="?p=dictionary-cities"></a>города</div>
        <div class="grid-item"><i class="fas fa-city"></i><a class="link" href="?p=dictionary-districts"></a>районы</div>
        <div class="grid-item"><i class="fas fa-road"></i><a class="link" href="?p=dictionary-streets"></a>улицы</div>
    </div>
</div>