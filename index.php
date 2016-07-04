<?php
spl_autoload_register(function($class_name){
    require_once 'Class/'.$class_name.'.php';
});
$new_user = new NewPay();
$new_info = new CustomerInfo();
$orderid=12345;
$price=10;
$action="pay";
$customer_mail="lox@mail.ru";
$form=$new_user->generate_form($orderid,$price,$customer_mail);
$costumerinfo=$new_info->to_array_costumerinfo();

//unknow ransactions
$payment=$new_user->unknow_transaction($orderid,$price,$costumerinfo);
$payment_url=$payment->userWebLink;
//header("Location:{$payment}");

//card_binding
$card_bindings=$new_user->binding_card($costumerinfo);
$card_bindings_link=$card_bindings->userWebLink;
$id_bindings=$card_bindings->id;
//header("Location:{$card_bindings_link}");


//know transactions
$orderid=554;
$knowcardnumber="4929509947106878";
$know_transactions=$new_user->know_transaction($orderid,$price,$costumerinfo,$knowcardnumber);
$know_transactions_id=$know_transactions->id;