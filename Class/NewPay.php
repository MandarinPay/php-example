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

    //base_url  -  you can choise  url  this is need  a more method !
    function __construct($merchantid = "216",
                         $secret= "123321",
                         $base_url = array("pay"=>"https://secure.mandarinpay.com/Pay",
                                           "transactions"=>"https://secure.mandarinpay.com/api/transactions",
                                           "card-bindings"=>"https://secure.mandarinpay.com/api/card-bindings")

    )

    {
        $this->merchantid = $merchantid;
        $this->secret = $secret;
        $this->base_url = $base_url;

    }

    private function calc_sign($secret,$fields) //sign  need to generate form   Pay
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


    public function generate_form($fields) //generate form  to pay
    {
        $form = "";
        $fields_copy=$fields;
        $fields_copy["merchantId"]=$this->merchantid;
        $sign = $this->calc_sign($this->secret,$fields_copy);
        $form=$form."<form action=\"{$this->base_url[0]}\" method=\"POST\"> ";
        foreach($fields_copy as $key => $val)
        {
            $form = $form . '<input type="hidden" name="'.$key.'" value="' . htmlspecialchars($val) . '"/>'."\n";
        }
        $form = $form . '<input type="hidden" name="sign" value="'.$sign.'"/>';
        $form=$form."<input type=\"submit\" value=\"Оплатить\" />";
        $form=$form."</form>";
        return $form;
    }

    private function reqid_calc(){    //this is calk reqid this is function need to registr Aut
        $reqid = time() ."_". microtime(true) ."_". rand();
        return $reqid;
    }

    private  function gen_auth()
    {
        $reqid = $this->reqid_calc();
        $hash = hash("sha256", $this->merchantid."-". $reqid ."-". $this->secret);
        return $this->merchantid ."-".$hash ."-". $reqid; //this is  "merchantId-SHA256(merchantId-requestId-secret)-requestId"
    }


    public function gen_payment($orderid,$action,$price){ //generate  array payment
        $array["payment"]=array("orderId"=>$orderid,
            "action"=>$action,
            "price"=>$price);
        return $array;
    }
    public  function gen_array_transaction($payment,$customerinfo, $customvalues=array()){ //generate json_code  transaction
        $array = array_merge($customerinfo,$payment);
        $array["customValues"] = $customvalues;
        return($array);
    }

    public function binding_transaction($base_url_choice,$json_content)
    {
        $json_content=json_encode($json_content);
        $url_transaction=$this->base_url[$base_url_choice];
        $ch=curl_init($url_transaction);
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $json_content);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Content-Type: application/json",
            "X-Auth:".$this->gen_auth(),
        ));
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        $result = curl_exec($ch);
        if (curl_errno($ch))
            throw new Exception(curl_error($ch));
        $result=json_decode($result);
        return $result;
    }

    //generate array  to pay transaction on card
    public function gen_array_know_transaction($payment,$customerinfo, $knowcardnumber){
        echo "<br>";
        print_r($payment);
        $array = array_merge($customerinfo,$payment);
        $array["target"] = array("knownCardNumber"=>$knowcardnumber);
        return($array);

    }


    

}