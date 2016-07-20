<?php

/**
 * Created by PhpStorm.
 * User: Kage-Chan
 * Date: 12.07.2016
 * Time: 13:52
 */
class IndexForm
{
    public $table_name;

    public function __construct($table_name = 'mandarin_modul_api')
    {
        $this->table_name = $table_name;
        $this->table = new PDO("sqlite:{$table_name}.sqlite3");
        $this->options = ["option0" => "Оплата",
            "option1" => "Выплата",
            "option2" => "Привязка новой карты",
            "option3" => "Оплата по привязаной карте",
            "option4" => "Выплата по привязаной карте",
            "option5" => "Повторная оплата",
            "option6" => "Выплата по номеру карты"];
    }

    public function create_and_open_table()
    {


        $this->table->setAttribute(PDO::ATTR_ERRMODE,
            PDO::ERRMODE_EXCEPTION);


// Set errormode to exceptions
        $this->table->exec("CREATE TABLE IF NOT EXISTS $this->table_name(
                              id INTEGER PRIMARY KEY AUTOINCREMENT, 
                              /* id автоматически станет автоинкрементным */ 
                               select_option TEXT,
                               mail TEXT,
                               phone TEXT,
                               price INT,
                               idsystem_and_card_id TEXT,
                               card_number INT,
                               status TEXT,
                               operation_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                               )");
    }

    public function get_order_id()
    {
        $table = $this->table;
        $result = $table->lastInsertId();
        return $result;
    }

    public function protect_site($array_in_form)
    {
        foreach ($array_in_form as $key => $value) {
            $array_in_form[$key] = htmlspecialchars($value);
        }
        return ($array_in_form);
    }

    public function get_option($option_select)
    {
        $option_select = $option_select['gridRadios'];
        $i = 0;
        foreach ($this->options as $key => $value) {
            if ($option_select == $key) {
                break;
            } else {
                $i++;
                continue;
            }
        }
        return $i;
    }

    public function create_data_value($data_value, $option_select_num)
    {
        $status = "Waiting";
        $option_select = $this->options["option{$option_select_num}"];
        $insert = "INSERT INTO $this->table_name (select_option, mail, phone,price,status) 
                VALUES (:select_option, :mail, :phone,:price,:status)";
        $stmt = $this->table->prepare($insert);
        $stmt->bindParam(':select_option', $option_select);
        $stmt->bindParam(':mail', $data_value['email']);
        $stmt->bindParam(':phone', $data_value['phone']);
        $stmt->bindParam(':price', $data_value['price']);
        $stmt->bindParam(':status', $status);
        $stmt->execute();
    }


    public function write_id_card_system($card_id, $orderid)
    {
        $sql = "UPDATE {$this->table_name} SET idsystem_and_card_id=:id_card_bind WHERE id=:id";
        $stmt = $this->table->prepare($sql);
        $stmt->bindParam(':id', $orderid);

        $stmt->bindParam(':id_card_bind', $card_id);
        $stmt->execute();
    }

    public function write_and_go($card_binding, $orderid)
    {
        $card_id = $card_binding->id;
        $payment_url = $card_binding->userWebLink;
        $this->write_id_card_system($card_id, $orderid);
        if (empty($payment_url)) {
            header("Location:/");
        } else {
            header("Location:{$payment_url}");
        };
    }

    public function get_id_card($array_form)
    {
        $option = $this->options["option2"];
        $mail = $array_form['email'];
        $phone = $array_form['phone'];
        $status = "success";
        $sql = $this->table->prepare("SELECT idsystem_and_card_id FROM my_database 
                                    WHERE select_option =:select_options
                                    AND mail = :mail
                                    AND phone = :phone
                                    AND  status = :status");
        $sql->bindParam(':mail', $mail);
        $sql->bindParam(':select_options', $option);
        $sql->bindParam(':phone', $phone);
        $sql->bindParam(':status', $status);
        $sql->execute();
        $id_card = $sql->fetchColumn();
        if (empty($id_card)) {
            echo("Вы еще не привязали карту или она не прошла поддтверждение!!!!</br>");
            echo "<a href =\"/\">На главную</a>";
            $this->delete_row();

            exit();
        } else {
            return $id_card;
        }
    }  public function get_id_card_sucsess_pay($array_form)
    {
        $option = $this->options["option3"];
        $mail = $array_form['email'];
        $phone = $array_form['phone'];
        $status = "success";
        $sql = $this->table->prepare("SELECT idsystem_and_card_id FROM my_database 
                                    WHERE select_option =:select_options
                                    AND mail = :mail
                                    AND phone = :phone
                                    AND  status = :status");
        $sql->bindParam(':mail', $mail);
        $sql->bindParam(':select_options', $option);
        $sql->bindParam(':phone', $phone);
        $sql->bindParam(':status', $status);
        $sql->execute();
        $id_card = $sql->fetchColumn();
        if (empty($id_card)) {
            echo("Вы еще не пользовались картой!!!!</br>");
            echo "<a href =\"/\">На главную</a>";
            $this->delete_row();

            exit();
        } else {
            return $id_card;
        }
    }

    public function get_all_data()
    {
        $stmt = $this->table->query("SELECT * FROM $this->table_name")->fetchAll(PDO::FETCH_NUM);
        return ($stmt);
    }

    private function delete_row()
    {
        $sql = "DELETE FROM $this->table_name WHERE id = :id";
        $stmt = $this->table->prepare($sql);
        $stmt->bindParam(':id', $this->table->lastInsertId());
        $stmt->execute();
    }

    public function updtade_status($array)
    {
        $status = $array['status'];
        $card_binding_transaction = !empty($array['transaction']) ? $array['transaction'] : $array['card_binding'];
        $sql = "UPDATE {$this->table_name} SET status=:status WHERE  idsystem_and_card_id=:card_bind_transaction";
        $stmt = $this->table->prepare($sql);
        $stmt->bindParam(':card_bind_transaction', $card_binding_transaction);
        $stmt->bindParam(':status', $status);
        $stmt->execute();
    }
}