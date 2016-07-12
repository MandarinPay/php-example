<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tutorial page </title>
    <link rel="stylesheet" href="./css/bootstrap.min.css"/>
    <link rel="stylesheet" href="./css/my.css"/>
</head>
<body>
<table class="table table-bordered">
    <thead>
    <tr>
        <th>Id</th>
        <th>Выбранная операция</th>
        <th>mail</th>
        <th>phone</th>
        <th>price</th>
        <th>ID in system or card_number_id</th>
        <th>status</th>
        <th>time operation</th>
    </tr>
    </thead>
<?php
spl_autoload_register(function ($class_name) {
    require_once 'Class/' . $class_name . '.php';
});

$new_index = new IndexForm('my_database');
$array_data = $new_index->get_all_data();
foreach ($array_data as $keys=> $values){
    echo "<tr>";
    foreach($values as $key => $value){
        echo "<td>";
        echo $value;
        echo "</td>";
    }
    echo "</tr>";
}
echo "</table>";
?>

    <a class="btn btn-lg btn-success"
       href="#" data-toggle="modal"
       data-target="#basicModal">Создать заказ</a>
    <div class="modal fade" id="basicModal" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button class="close" type="button" data-dismiss="modal">x</button>
                    <h4 class="modal-title" id="myModalLabel">Создать заказ</h4>
                </div>
                <div class="modal-body">
                    <form action="/form.php" method="post">
                        <div class="form-group row">
                            <label for="inputEmail3" class="col-sm-2 form-control-label">Email</label>
                            <div class="col-sm-10">
                                <input type="email" name="email" class="form-control" id="inputEmail3" placeholder="Email">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputphone" class="col-sm-2 form-control-label">Phone</label>
                            <div class="col-sm-10">
                                <input type="tel" name="phone" class="form-control" id="inputphone" placeholder="Phone">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="price" class="col-sm-2 form-control-label">Price</label>
                            <div class="col-sm-10">
                                <input type="text" name="price" class="form-control" id="price" placeholder="100000">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2">Выбор операции</label>
                            <div class="col-sm-10">
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="gridRadios" id="gridRadios0" value="option0" checked>
                                        Оплата
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="gridRadios" id="gridRadios1" value="option1">
                                        Выплата
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="gridRadios" id="gridRadios2" value="option2">
                                        Привязка карты
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="gridRadios" id="gridRadios3" value="option3">
                                        Оплата по привязаной карте
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="gridRadios" id="gridRadios4" value="option4">
                                        Выплата по привязаной карте
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="gridRadios" id="gridRadios5" value="option5">
                                        Повторная оплата(rebill)
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="gridRadios" class="card_value" id="gridRadios6"
                                               value="option6">
                                        Выплата по известному номеру карты
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row" id="cheks_but">
                            <label for="CardNumber" class="col-sm-2 form-control-label">Card_num</label>
                            <div class="col-sm-10">
                                <input type="text" name="CardNumber" class="form-control" id="CardNumber"
                                       placeholder="345435345345345">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-offset-2 col-sm-10">
                                <button type="submit" class="btn btn-secondary">Отпрввить</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-default" type="button" data-dismiss="modal">Закрыть</button>
                </div>
            </div>
        </div>

    </div>
</body>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="./js/bootstrap.min.js"></script>

<script>
    $(document).ready(function () {
        var cheksBut = $('#cheks_but');
        var radios = $('[name="gridRadios"]');
        var radio = $('.card_value');
        cheksBut.hide();
        radios.on('click', function () {
            if (radio.is(':checked')) {
                cheksBut.show();
            } else {
                cheksBut.hide();
            }
        });
    });
</script>
</html>
