<?php


class CustomerInfo
{
    public $email;
    public $phone;
    public function __construct($email = "user@example.com",$phone="+79001234567")
    {
        $this->email = $email;
        $this->phone = $phone;
    }


}