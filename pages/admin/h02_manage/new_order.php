<?
global $httpHandler, $streetTable, $eventTable, $sexTable, $nameTable, $prepayTable, $salesTable, $keywordTable, $db;

if ($httpHandler->GetAccessLevel() <= 0) {
    echo "Ошибка доступа.";
    exit();
}

?>

<form class="contact2-form view-order" method="post" id="uploadForm">
    <fieldset>
        <div class="section"><span>1</span>Информация о заказе</div>
        <div class="wrap-input2 validate-input row" style="border-bottom: 0; min-height: 227px;">
            <div class="col-md-6" id="orderdatepicker"><input type='hidden' name="<?php echo CaptionField::$inputDate; ?>" class="form-control"/></div>
            <div class="col-md-6" id="ordertimepicker"><input type='hidden' name="<?php echo CaptionField::$inputTime; ?>" class="form-control"/></div>
        </div>
        <br><br>

        <div class="row">
            <div class="col-md-4">
                <div class="wrap-input2 validate-input">
                    <input class="input2" type="number" min="0" step="1" name="<?php echo CaptionField::$inputAmount; ?>" oninput="if (this.value.length >= 9) { this.value = this.value.slice(0, 9); }">
                    <span class="focus-input2" data-placeholder="Сумма"></span>
                </div>
            </div>

            <div class="col-md-4">
                <div class="wrap-input2 validate-input">
                    <input class="input2" type="number" min="0" step="1" name="<?php echo CaptionField::$inputDiscount; ?>" oninput="if (this.value.length >= 9) { this.value = this.value.slice(0, 9); }">
                    <span class="focus-input2" data-placeholder="Скидка"></span>
                </div>
            </div>

            <div class="col-md-4">
                <div class="wrap-input2 validate-input">
                    <input class="input2" type="number" min="0" step="1" name="<?php echo CaptionField::$inputPrepay; ?>" oninput="if (this.value.length >= 9) { this.value = this.value.slice(0, 9); }">
                    <span class="focus-input2" data-placeholder="Предоплата"></span>
                </div>
            </div>
        </div>

        <div class="wrap-input2 validate-input" style="border-bottom: 0;">
            <select name="<?php echo CaptionField::$inputPrepayType; ?>" class="selectpicker show-tick" data-showinsert="true" data-showremove="true" data-showedit="true" data-width="100%">
                <option data-hidden="true">Выберите источник предоплаты...</option>
                <?php
                $resultPrepay = $prepayTable->Select();
                while ($item = $db->fetch_array($resultPrepay))
                    echo "<option value=\"" . $item[PrepayTableStruct::$columnID] . "\">" . $item[PrepayTableStruct::$columnValue] . "</option>";
                ?>
            </select>
        </div>

        <div class="wrap-input2 validate-input" style="border-bottom: 0;">
            <select name="<?php echo CaptionField::$inputSalesType; ?>" class="selectpicker show-tick" data-showremove="true" data-showedit="true" data-width="100%">
                <option data-hidden="true">Выберите канал продаж...</option>
                <?php
                $resultSales = $salesTable->Select();
                while ($item = $db->fetch_array($resultSales))
                    echo "<option value=\"" . $item[SalesTableStruct::$columnID] . "\">" . $item[SalesTableStruct::$columnValue] . "</option>";
                ?>
            </select>
        </div>

        <div class="wrap-input2 validate-input" style="border-bottom: 0;">
            <select name="<?php echo CaptionField::$inputEvent; ?>" class="selectpicker show-tick" data-showremove="true" data-showedit="true" data-width="100%">
                <option data-hidden="true">Выберите событие...</option>
                <?php
                $resultEvent = $eventTable->Select();
                while ($item = $db->fetch_array($resultEvent))
                    echo "<option value=\"" . $item[EventTableStruct::$columnID] . "\">" . $item[EventTableStruct::$columnName] . "</option>";
                ?>
            </select>
        </div>

        <div class="wrap-input2 validate-input" style="border-bottom: 0;">
            <textarea name="<?php echo CaptionField::$inputDesc; ?>"></textarea>
            <div class="keywordContent">
                <div class="keywordInsert">
                    <input name="<?php echo CaptionField::$inputKeyword; ?>" onKeyPress="if (event.which == 13) $(this).next().click()" class="form-control keywordText"/>
                    <button type="button" class="btn btn-default btn-md" onclick="addKeywordItem(this)"><span class="glyphicon glyphicon-ok"></span></button>
                </div>
                <span class="keywordBtn">добавить</span>
                <?php
                $resultKeyword = $keywordTable->Select();
                while ($item = $db->fetch_array($resultKeyword))
                    echo "<span class=\"keywordValue\">" . $item[KeywordTableStruct::$columnValue] . "</span><sup name=\"" . CaptionField::$inputKeyword . "\" id=\"" . $item[KeywordTableStruct::$columnID] . "\">X</sup>";
                ?>
            </div>
        </div>
        <div class="section-footer">
            <button type="button" name="next" top="-205px" class="btn btn-primary" onclick="$(function() { $('.section-content#section1').fadeOut(); $('.section-content#section2').removeClass('hide')} )">Далее&nbsp;&gt;</button>
            <button type="button" class="btn btn-success" onclick="$.coremanage.createOrder()">Добавить</button>
            <button type="button" name="cancel" top="-205px" class="btn btn-danger" onclick="$(function() { $('.section-content#section1').fadeOut(); $('.section-content#section2').removeClass('hide')} )">Отмена</button>
        </div>
    </fieldset>

    <fieldset>
        <div class="section"><span>2</span>Информация о заказчике</div>
        <div class="row">
            <div class="col-md-6">
                <div class="wrap-input2 validate-input" style="border-bottom: 0;">
                    <select name="<?php echo CaptionField::$inputSex; ?>" class="selectpicker show-tick" id="<?php echo CaptionField::$inputSex; ?>" data-width="100%" onchange="$(function() {
                            $('.selectpicker#<?php echo captionfield::$inputName; ?>').removeAttr('disabled');
                            $('.selectpicker#<?php echo captionfield::$inputName; ?>').selectpicker('refresh');
                            })">
                        <option value="-1" data-hidden="true">Выберите пол...</option>
                        <?php
                        $resultSex = $sexTable->Select();
                        while ($item = $db->fetch_array($resultSex))
                            echo "<option value=\"" . $item[SexTableStruct::$columnID] . "\">" . $item[SexTableStruct::$columnName] . "</option>";
                        ?>
                    </select>
                </div>
            </div>

            <div class="col-md-6">
                <div class="wrap-input2 validate-input" style="border-bottom: 0;">
                    <select name="<?php echo CaptionField::$inputName; ?>" class="selectpicker show-tick" id="<?php echo CaptionField::$inputName; ?>" disabled data-live-search="true" data-showremove="true" data-showedit="true" data-width="100%" data-size="10">
                        <option data-hidden="true">Выберите имя...</option>
                        <?php
                        $resultName = $nameTable->Select();
                        while ($item = $db->fetch_array($resultName))
                            echo "<option value=\"" . $item[NameTableStruct::$columnID] . "\">" . $item[NameTableStruct::$columnName] . "</option>";
                        ?>
                    </select>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="wrap-input2 validate-input">
                    <input type="text" class="input2 input-medium bfh-phone" data-format="+7 (ddd) ddd-dd-dd" maxlength="18" name="<?php echo CaptionField::$inputPhone; ?>">
                    <span class="focus-input2" data-placeholder="Телефон"></span>
                </div>
            </div>

            <div class="col-md-4">
                <div class="wrap-input2 validate-input">
                    <input class="input2" type="text" name="<?php echo CaptionField::$inputAddPhone; ?>"><span class="focus-input2" data-placeholder="Телефон доп."></span>
                </div>
            </div>

            <div class="col-md-4">
                <div class="wrap-input2 validate-input">
                    <input class="input2" type="number" min="0" step="1" name="<?php echo CaptionField::$inputAge; ?>" oninput="if (this.value.length >= 2) { this.value = this.value.slice(0, 2); }">
                    <span class="focus-input2" data-placeholder="Возраст"></span>
                </div>
            </div>
        </div>

        <div class="section-footer">
            <button type="button" name="previous" class="btn btn-primary" onclick="$(function() { $('.section-content#section2').fadeOut(); $('.section-content#section1').fadeIn('fast') } )">&lt;&nbsp;Назад</button>
            <button type="button" name="next" class="btn btn-primary" onclick="$(function() { $('.section-content#section2').fadeOut(); $('.section-content#section3').fadeIn('fast', function() { $(this).removeClass('hide') }); } )">Далее&nbsp;&gt;</button>
            <button type="button" class="btn btn-success" onclick="$.coremanage.createOrder()">Добавить</button>
            <button type="button" name="cancel" top="-205px" class="btn btn-danger" onclick="$(function() { $('.section-content#section1').fadeOut(); $('.section-content#section2').removeClass('hide')} )">Отмена</button>
        </div>
    </fieldset>

    <fieldset>
        <div class="section"><span>3</span>Информация о доставке</div>
        <div class="row">
            <div class="col-md-12">
                <div class="wrap-input2 validate-input" style="border-bottom: 0;">
                    <select name="<?php echo CaptionField::$inputStreet; ?>" class="selectpicker show-tick" data-live-search="true" data-showremove="true" data-showedit="true" data-width="100%" data-size="10">
                        <option data-hidden="true">Выберите улицу...</option>
                        <?php
                        $resultStreet = $streetTable->Select();
                        while ($item = $db->fetch_array($resultStreet))
                            echo "<option value=\"" . $item[StreetTableStruct::$columnID] . "\">" . $item[StreetTableStruct::$columnName] . "</option>";
                        ?>
                    </select>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-3">
                <div class="wrap-input2 validate-input">
                    <input class="input2" type="text" name="<?php echo CaptionField::$inputHouse; ?>" oninput="if (this.value.length >= 5) { this.value = this.value.slice(0, 3); }">
                    <span class="focus-input2" data-placeholder="№ дома"></span>
                </div>
            </div>

            <div class="col-md-3">
                <div class="wrap-input2 validate-input">
                    <input class="input2" type="number" min="0" step="1" name="<?php echo CaptionField::$inputEntrance; ?>" oninput="if (this.value.length >= 2) { this.value = this.value.slice(0, 2); }">
                    <span class="focus-input2" data-placeholder="подъезд"></span>
                </div>
            </div>

            <div class="col-md-3">
                <div class="wrap-input2 validate-input">
                    <input class="input2" type="number" min="0" step="1" name="<?php echo CaptionField::$inputFloor; ?>" oninput="if (this.value.length >= 3) { this.value = this.value.slice(0, 3); }">
                    <span class="focus-input2" data-placeholder="этаж"></span>
                </div>
            </div>

            <div class="col-md-3">
                <div class="wrap-input2 validate-input">
                    <input class="input2" type="number" min="0" step="1" name="<?php echo CaptionField::$inputApart; ?>" oninput="if (this.value.length >= 4) { this.value = this.value.slice(0, 4); }">
                    <span class="focus-input2" data-placeholder="квартира"></span>
                </div>
            </div>
        </div>

        <div class="section-footer">
            <button type="button" name="previous" class="btn btn-primary" onclick="$(function() { $('.section-content#section3').fadeOut(); $('.section-content#section2').fadeIn('fast') } )">&lt;&nbsp;Назад</button>
            <button type="button" class="btn btn-success" onclick="$.coremanage.createOrder()">Добавить</button>
            <button type="button" name="cancel" top="-205px" class="btn btn-danger" onclick="$(function() { $('.section-content#section1').fadeOut(); $('.section-content#section2').removeClass('hide')} )">Отмена</button>
        </div>
    </fieldset>
</form>