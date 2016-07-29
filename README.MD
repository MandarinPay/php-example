# Документация

## Menu
1. [Подключение классов](#connection-classes)
2. [Класс CustomerInfo](#class-customerinfo)
    * [Пример создания экземпляра класса](#an-example-of-a-call-and-the-creation-of-an-instance)
3. [Класс NewPay](#class-newpay)
    * [Пример создания экземпляра класса](#an-example-of-a-call-and-the-creation-of-an-instance-newpay)
    * [Метод method generate_form](#method-generate_form)
    * [Метод pay_interactive](#method-pay_interactive)
    * [Метод payout_interactive](#method-payout_interactive)
    * [Метод new_card_binding](#method-new_card_binding)
    * [Метод pay_from_card_binding](#method-pay_from_card_binding)
    * [Метод payout_from_card_binding](#method-payout_from_card_binding)
    * [Метод rebill_transaction](#method-rebill_transaction)
    * [Метод payout_to_known_card](#method-payout_to_known_card)
    * [Метод check_sign](#method-check_sign)
6. [Файл callback.php](#file-callback)
7. [Вспомогательный класс для form.php IndexForm](#class-indexform)
5. [Демо](#demo)   


Класс предназначен для работы с методами API банка [mandarin](https://secure.mandarinpay.com/docs/api-ru.html)
Класс включает в себя следующие методы:

1. Оплата через API форм
2. Создание транзакции без привязки карты
3. Создание привязки карты
4. Создание транзакции по привязанной карте
5. Создание транзакции на повторное списание (rebill)
6. Создание транзакции по известному номеру карты
7. Уведомления о статусе транзакций и привязок карт

Подробно о структуре и необходимых файлах:

1. Папка Class содержит основные классы непосредственно для работы а так же вспомогательные классы, для работы с базой данных.
2. Папка css содержит файлы, отвечающие за форматирование внешнего вида файла index.php;
3. Папка fonts содержит шрифты для index.php;
4. Папка js содержит js скрипты и библиотеки для index.php;

5. Файл callback.php принимает callback уведомления от сервера.
6. Файл form.php основная логика по обработке формы, и работы с базой данных.
7. Файл index.php содержит в себе таблицу всех запросов, а так же форму  для создания  запроса.

Теперь подробнее о классах и о их методах.

#### Connection classes

Для начала мы подключаем наши классы из папки Class:
```
spl_autoload_register(function ($class_name) {
    require_once 'Class/' . $class_name . '.php';
});

```

## Class CustomerInfo

Класс принимающий некоторые поля данных пользователя.

#### **An example of a call and the creation of an instance**

```
$costumerinfo = new CustomerInfo($array_form['email'], $array_form['phone']);
```
Подробнее о каждом параметре.

1. 1ый параметр - EMAIL ПОЛЬЗОВАТЕЛЯ
2. 2ой параметр — НОМЕР ТЕЛЕФОНА (обязательно в формате +7ХХХХХХХХХХ, иначе  API не примет номер)


## Class NewPay

Класс занимающийся основными операциями по взаимодействию с API

#### **An example of a call and the creation of an instance NewPay**
```
$new_user = new NewPay('216','123321','https://secure.mandarinpay.com/');
```

Подробнее о каждом параметре.

1. 1ый параметр - mercantId можно получить в клиентском интерфейсе.
2. 2ой параметр - secret он же sharedsec так же можно получить в клиентском интерфейсе.
3. 3ий параметр - Базовый url API

**Параметрами по умолчанию являются приведенные в примере выше**. Это является оптимальным быстрым стартом для быстрой отладки вашего проекта.

#### method generate_form

Генерирует скрытую форму оплаты с видимой кнопкой оплатить.

Пример вызова:

```
$form=$new_user->generate_form($orderid, $price, $customer_mail)

```

Подробнее о каждом параметре.

1ый параметр - Уникальный номер заказа в вашей системе.
2ой параметр - Сумма платежа. Обязательна к передаче.
3ий параметр - email Пользователя.

В ответ вы получите скрытую форму с видимой кнопкой купить, после нажатия на неё Пользователя отправит на страницу оплаты.

#### method pay_interactive

Создание транзакции без привязки карты.

Пример вызова:

```
$operation = $new_user->pay_interactive($orderid, $price, $costumerinfo);
```
Подробнее о каждом параметре.

1ый параметр - Уникальный номер заказа в вашей системе.
2ой параметр - Сумма платежа. Обязательна к передаче.
3ий параметр - Экземпляр класса [custumer info](#class-customerinfo)

В ответ вы получите:
    
    Успешно:
```
{
  "id": "43913ddc000c4d3990fddbd3980c1725",
  "userWebLink": "https://secure.mandarinpay.com/Pay?transaction=0eb51e74-e704-4c36-b5cb-8f0227621518"
}
```
    Неуспешно:
```    
{
  "error": "Invalid request"
  
}
```

#### method payout_interactive

Метод схож с pay_interactive разница в том, что этот метод нужен для выплат.


#### method new_card_binding

Привязка новой карты

Пример вызова:

```
$operation = $new_user->new_card_binding($costumerinfo);
```

Подробнее о каждом параметре.

1ый параметр - Экземпляр класса [custumer info](#class-customerinfo)

В ответ вы получите:
    
    Успешно:
```
{
    "id": "0eb51e74-e704-4c36-b5cb-8f0227621518",
    "userWebLink": "https://secure.mandarinpay.com/CardBindings/New?id=0eb51e74-e704-4c36-b5cb-8f0227621518"
}
```
    Неуспешно:
```    
{
  "error": "Invalid request"
  
}
``` 
Полученный id следует сохранить, а пользователя перенаправить по ссылке, полученной в поле userWebLink. 
Привязанную карту можно будет использовать после получения callback-уведомления об её успешной привязке.

#### method pay_from_card_binding

Создание транзакции по привязанной карте

Пример вызова:

```
$operation = $new_user->pay_from_card_binding($orderid, $price, $id_card);
```
Подробнее о каждом параметре.

1ый параметр - Уникальный номер заказа в вашей системе.
2ой параметр - Сумма платежа. Обязательна к передаче.
3ий параметр - ID успешно привязанной карты.

В ответ вы получите:
    
    Успешно:
```
{
  "id": "43913ddc000c4d3990fddbd3980c1725"
}
```
    Неуспешно:
```    
{
  "error": "Invalid request"
  
}
``` 
Транзакция будет совершена в асинхронном режиме, ожидайте получение callback-вызова

#### method payout_from_card_binding

Метод схож с payout_from_card_binding разница в том, что этот метод нужен для выплат.

#### method rebill_transaction

Создание транзакции на повторное списание (rebill)

Пример вызова:

```
$operation = $new_user->rebill_transaction($orderid, $price, $id_card);
```
Подробнее о каждом параметре.

1ый параметр - Уникальный номер заказа в вашей системе.
2ой параметр - Сумма платежа. Обязательна к передаче.
3ий параметр - ID успешно привязанной карты.

В ответ вы получите:
    
    Успешно:
```
{
  "id": "43913ddc000c4d3990fddbd3980c1725"
}
```
    Неуспешно:
```    
{
  "error": "Invalid request"
  
}
``` 
Транзакция будет совершена в асинхронном режиме, ожидайте получение callback-вызова

#### method payout_to_known_card

Создание транзакции по известному номеру карты

Пример вызова:

```
$operation = $new_user->payout_to_known_card($orderid, $price, $costumerinfo, $card_num);
```

Подробнее о каждом параметре.

1ый параметр - Уникальный номер заказа в вашей системе.
2ой параметр - Сумма платежа. Обязательна к передаче.
3ий параметр - ID успешно привязанной карты.
4ый параметр - Номер карты.

Следует использовать **ТОЛЬКО** если номер карты был получен **ЛИЧНО** от клиента на бумаге без участия вашего веб-сайта. 
Ввод номера карты на вашем сайте без прохождения процедуры сертификации PCI-DSS недопустим.

В ответ вы получите:
    
    Успешно:
```
{
  "id": "43913ddc000c4d3990fddbd3980c1725"
}
```
    Неуспешно:
```    
{
  "error": "Invalid request"
  
}
``` 
Транзакция будет совершена в асинхронном режиме, ожидайте получение callback-вызова

#### method check_sign

Проверка совпадения sign
Для подтверждения того, что уведомление пришло именно от системы MandarinPay необходимо проверять значение поля sign.

Пример вызова:

```
$calc_sigh = $testform->check_sign($_POST);
```

## File callback

Служит для приема данных от API на ваш сервис.

Пример:

```
<?php

spl_autoload_register(function ($class_name) {
    require_once 'Class/' . $class_name . '.php';
});
$testform = new NewPay();
$calc_sigh = $testform->check_sign($_POST);
if ($calc_sigh == true) {
    $new_index = new IndexForm('my_database');
    $new_index->updtade_status($_POST);
}
```

Подробнее о методах IndexForm ниже

## Class IndexForm

Вспомогательный класс не имеет отношение к работе с API нужен для обслуживания, и удобной работы примера использования основого класса.

Создание Экземпляра Класса:

```
$new_index = new IndexForm('my_database');
```
Подробнее о каждом параметре.

1ый параметр - Имя базы данных(в тестовом варианте работает c SQLite)

#### method create_and_open_table

Создание и открытие для чтения таблицы

Пример использования:

```
$new_index->create_and_open_table();
```

#### method protect_site

Обработка полученых данных и очистка от спецсимволов

Пример вызова:

```
$array_form = $new_index->protect_site($_POST);
```
Подробнее о каждом параметре.

1ый параметр - Массив с данными(1-мерный)

#### method get_option

Получение интересующей услуги пользователя.

Пример вызова:

```
$option_select_num = $new_index->get_option($array_form);
```
Подробнее о каждом параметре.

1ый параметр - Массив с данными(1-мерный)

#### method create_data_value

Добавления значений в базу данных

Пример использования:

```
$new_index->create_data_value($array_form, $option_select_num);
```
Подробнее о каждом параметре.

1ый параметр - Массив с данными(1-мерный)
2ой параметр - Выбранная опция

#method get_order_id

Получить ID последней операции

Пример вызова:

```
$orderid = $new_index->get_order_id();
```
#### method get_id_card

Получить ID карточки определенного юзера c успешной привязкой.

Пример вызова:

```
$id_card = $new_index->get_id_card($array_form);
```
Подробнее о каждом параметре.

1ый параметр - Массив с данными(1-мерный)

#### method get_id_card_sucsess_pay

Получить ID карточки определенного юзера c успешной привязкой и транзакцией.

Пример вызова:

```
$id_card = $new_index->get_id_card_sucsess_pay($array_form);
```
Подробнее о каждом параметре.

1ый параметр - Массив с данными(1-мерный)

#### method write_and_go

Дозапись в базу данных после  выполнения операции и  перенаправления пользователя.

Пример вызова:

```
$new_index->write_and_go($operation, $orderid);
```
Подробнее о каждом параметре.

1ый параметр - Результат операции 
2ой параметр - Уникальный идентефикатор в вашей системе.

#### method update status

Обновляет данные после получения callback вызова.

Пример вызова:

```
$new_index->updtade_status($_POST);
```
Подробнее о каждом параметре.

1ый параметр - Данные Callback операции.

### Demo
Демо пример рабочего приложения, полностью настроенного для ознакомления:
* в index.php — формы для отправки данных;
* в form.php  — логика по взаимодействию с классом.
* mydatabase — база данных.









