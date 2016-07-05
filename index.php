<?php
spl_autoload_register(function($class_name){
    require_once 'Class/'.$class_name.'.php';
});
$new_info = new CustomerInfo();
$new_user = new NewPay();
$costumerinfo=$new_user->to_array_costumerinfo($new_info);
$orderid=rand(1,500000000);
$price=10;
$action="pay";
$customer_mail="lox@mail.ru";
$form=$new_user->generate_form($orderid,$price,$customer_mail);

//unknow transactions
$payment=$new_user->pay_interactive($orderid,$price,$costumerinfo);
$payment_url=$payment->userWebLink;
//echo "<br>";
//print_r($payment_url);
//header("Location:{$payment}");

//pauout_interactive
$orderid=rand(1,500000000);
$payout=$new_user->payout_interactive($orderid,$price,$costumerinfo);
$payout_url=$payment->userWebLink;
$payout_id=$payment->id;
//echo "<br>";
//print_r($payout_id);
//header("Location:{$payout_url}");


//card_binding
$card_bindings=$new_user->new_card_binding($costumerinfo);
$card_bindings_link=$card_bindings->userWebLink;
$id_bindings=$card_bindings->id;
//echo "<br>";
//print_r($id_bindings);
//header("Location:{$card_bindings_link}");


//know transactions
$orderid=rand(1,500000000);
$knowcardnumber="4929509947106878";
$know_transactions=$new_user->payout_to_known_card($orderid,$price,$costumerinfo,$knowcardnumber);
$know_transactions_id=$know_transactions->id;
//echo "<br>";
//print_r($know_transactions_id);
//transaction rebill know card number


//rebill
$orderid=rand(1,5000);
$rebill="$know_transactions_id";
$rebill_transactions=$new_user->rebill_transaction($orderid,$price,$rebill);
$rebill_transactions_id=$rebill_transactions->id;
//echo "<br>";
//print_r($rebill_transactions);