<?php
spl_autoload_register(function ($class_name) {
    require_once 'Class/' . $class_name . '.php';
});

date_default_timezone_set('UTC');

$new_index = new IndexForm('my_database');


$new_index->create_and_open_table();
$array_form = $new_index->protect_site($_POST);
$option_select_num = $new_index->get_option($array_form);


$costumerinfo = new CustomerInfo($array_form['email'], $array_form['phone']);

$new_user = new NewPay();
$new_index->create_data_value($array_form, $option_select_num);
$orderid = $new_index->get_order_id();
$price = $array_form['price'];
  

switch ($option_select_num) {
    case 0:
        $operation = $new_user->pay_interactive($orderid, $price, $costumerinfo);
        break;
    case 1:
        $operation = $new_user->payout_interactive($orderid, $price, $costumerinfo);
        break;
    case 2:
        $operation = $new_user->new_card_binding($costumerinfo);
        break;
    case 3:
        $id_card = $new_index->get_id_card($array_form,$orderId);
        $operation = $new_user->pay_from_card_binding($orderid, $price, $id_card);
        break;
    case 4:
        $id_card = $new_index->get_id_card($array_form,$orderId);
        $operation = $new_user->payout_from_card_binding($orderid, $price, $id_card);
        break;
    case 5:
        $id_card = $new_index->get_id_card($array_form,$orderId);
        $operation = $new_user->rebill_transaction($orderid, $price, $id_card);
        break;
    case 6:
        $card_num = $array_form['CardNumber'];
        $operation = $new_user->payout_to_known_card($orderid, $price, $costumerinfo, $card_num);
        break;
}
$new_index->write_and_go($operation, $orderid);



