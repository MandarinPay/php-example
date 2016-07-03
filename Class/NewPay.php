<?php
/**
 * Created by PhpStorm.
 * User: Kage-Chan
 * Date: 02.07.2016
 * Time: 12:38
 */

  

class NewPay
{
    public $merchantid;
    public $secret;
    public $base_url;
    public $fields;
    function __construct($merchantid = "216",
                         $secret= "123321",
                         $base_url = "https://secure.mandarinpay.com"
                         )

    {
        $this->merchantid = $merchantid;
        $this->secret = $secret;
        $this->base_url = $base_url;

    }

   private function calc_sign($secret,$fields)
    {
        ksort($fields);
        $secret_t = '';
        foreach($fields as $key => $val)
        {
            $secret_t = $secret_t . '-' . $val;
        }

        $secret_t = substr($secret_t, 1) . '-' . $secret;
        return hash("sha256", $secret_t);
    }


    public function generate_form($fields)
    {
        $form = "";
        $fields["merchantId"]=$this->merchantid;
        $sign = $this->calc_sign($this->secret,$fields);
        $form=$form."<form action=\"{$this->base_url}/Pay\" method=\"POST\"> ";
        foreach($fields as $key => $val)
        {
            $form = $form . '<input type="hidden" name="'.$key.'" value="' . htmlspecialchars($val) . '"/>'."\n";
        }
        $form = $form . '<input type="hidden" name="sign" value="'.$sign.'"/>';
        $form=$form."<input type=\"submit\" value=\"Оплатить\" />";
        $form=$form."</form>";
        return $form;
    }

}