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
$orderid=12345;
$action="pay";
$price=10;
$array_info=$new_info->array_all();
$payment =$new_user->gen_payment($orderid,$action,$price);
$c=$new_user->gen_json_transaction($array_info,$payment);
$result=$new_user->get_auth($c);
header("Location:{$result}");



