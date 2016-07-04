<?php

/**
 * Created by PhpStorm.
 * User: Kage-Chan
 * Date: 04.07.2016
 * Time: 10:21
 */
class CustomerInfo
{
    public $email;
    public $phone;
    public function __construct($email = "user@example.com",$phone="+79001234567")
    {
        $this->email = $email;
        $this->phone = $phone;
    }

    public function array_all(){
        $array["customerInfo"] = array("email"=>$this->email,
                                 "phone"=>$this->phone);
        return($array);
    }

}