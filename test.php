<?php

spl_autoload_register(function ($class) {
    require_once $class . '.php';
});


ui_factory::button('test')->type('small')->render();

/*

<div class="small ui button">
  Small
</div>

 */

ui_factory::input(['placeholder' => 'Search...'])->icon('search');
ui_factory::input()->placeholder('Search..')->icon('search');
ui_factory::input()->massive()->icon('search');

ui_factory::steps(['step' => 'Billing'])->step('Confirm order')->active('Billing');
ui_factory::steps([['step' => 'Billing'], ['step' => 'Confirm order', 'active' => 'true']]);
ui_factory::steps(['Billing', 'Confirm order'])->active('Billing');
ui_factory::steps(['Billing', 'Confirm order'])->active('Billing')->render('evenly');