<?php
spl_autoload_register(function($class_name){
    require_once 'Class/'.$class_name.'.php';
});
$new_user = new NewPay();
$form=$new_user->generate_form(array("orderId"=>"328GVD", 
    "price"=>"12,34",
    "customer_email"=>"user@mail.ru"
                                        ));
echo $form;  
 
