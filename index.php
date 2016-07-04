<?php
spl_autoload_register(function($class_name){
    require_once 'Class/'.$class_name.'.php';
});
$new_user = new NewPay();
$new_info = new CustomerInfo();
$form=$new_user->generate_form(array("orderId"=>"328GVD",
    "price"=>"12,34",
    "customer_email"=>"user@mail.ru"
));
$base_url_choice = array(0=>"pay",1=>"transactions",2=>"card-bindings");
$action=array(0=>"pay",1=>"payout");

$orderid=12345;
$price=10;
$array_costumerinfo=$new_info->to_array_costumerinfo();

//unknow ransactions
$payment_array =$new_user->gen_payment($orderid,$action[0],$price);

$c=$new_user->gen_array_transaction($array_costumerinfo,$payment_array);
$payment=$new_user->binding_transaction($base_url_choice[1],$c);
$payment_url=$payment->userWebLink;
//header("Location:{$payment}");

//card_binding
$card_bindings=$new_user->binding_transaction($base_url_choice[2],$array_costumerinfo);
$card_bindings_link=$card_bindings->userWebLink;
$id_bindings=$card_bindings->id;
//header("Location:{$card_bindings_link}");


//know transactions
$orderid=1234;
$payment_array =$new_user->gen_payment($orderid,$action[1],$price);
$knowcardnumber="4929509947106878";
$know_array_transaction =$new_user->gen_array_know_transaction($payment_array,$array_costumerinfo,$knowcardnumber);
$know_transactions=$new_user->binding_transaction($base_url_choice[1],$know_array_transaction);
$know_transactions_id=$know_transactions->id;