<?php
spl_autoload_register(function($class_name){
    require_once 'Class/'.$class_name.'.php';
});
$new_user = new NewPay();
$form=$new_user->generate_form(array("orderId"=>"328GVD", 
    "price"=>"12,34",
    "customer_email"=>"user@mail.ru"
                                        ));
$orderid=12345;
$action="pay";
$price=10;
$mail="user@example.com";
$phone="+79001234567";
$costum1=array();
$c=$new_user->gen_json_transaction($orderid,$action,$price,$mail,$phone,$costum1);
$result=$new_user->get_auth($c);
header("Location:{$result}");


