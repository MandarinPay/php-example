# Доккументация 

## Введение <a id="Enter"></a>
Класс предназначент для работы с инфошлюзом банка [mandarin](http://mandarinpay.com/) и способен выполнять следующие функции:

    - Эмиссия карты 
    - Упрощенная идентификация старт 
    - Упрощенная идентификация конец

Подробно о структуре  и о нужных фалах:

Нужные:
    - папка Class Содержит в себе основные классы   непоследственно для работы с инфошлюзом
    - папка css содержит в себе  файлы отвечающие за  форматирование  внешнего вида файла index.php 
    - папка fonts   содержит в себе шрифты  для index.php
    - папка js содержит в себе js скрипты и библиотеки для index.php 
    
    - файл index.php - содержит в себе 2 простые формы  для отправки данных в form.php 
    - файл form.php - содержит основную логику работы с классом и возвращением ответа от инфошлюза.

Теперь подробнее о логике файла и о доступных методах,  рассмотрим как пример  файл вызова form.php

<h1 id ="get_class">Подключение классов</h1>
Для начала мы подключаем наши классы из папки Class:
```
spl_autoload_register(function ($class_name) {
    require_once 'Class/' . $class_name . '.php';
});

```
#### Проверка данных <a id="protect_site"></a>
Первым делом что я делаю, это проверяю входные данные на спец символы  функцией:
```
$array_in_form = defender_site($_POST);

function defender_site($array_post)
{
    $array = [];
    foreach ($array_post as $key => $value) {
        $array[$key] = htmlspecialchars($value);
    }
    return $array;

}
```
Это не столь важно, но в качестве примера наглядно  показывает то, что все ваши данные должы быть хотя-бы чуть-чуть защищены 

Далее поговорим о классах и о том какие методы доступны

## Class Person<a id="Person"></a>

Класс отвечающий за данные  передаваемые пользователем: 


#### **Пример вызова и создание экземпляра класса**<a id="class_get"></a>

```
$person = new Person($array_in_form['family'], $array_in_form['name'], $array_in_form['patronymic'],
    $array_in_form['input_tel'], $array_in_form['input_RFNSPDocid'],
    $array_in_form['input_docseria'], $array_in_form['input_docnum']);
```

#### Передаваемые параметры <a id="enter_param_person_exemple"></a>
Подробнее о том что передаем и да **ЭТО ВСЕ ОБЯЗАТЕЛЬНЫЕ ПОЛЯ** если не передать то или иное значение класс просто сломается.
Подробнее  о каждм параметре.


* 1ый параметр  является Фамилия Человека
* 2ой параметр Имя  Человека
* 3ий параметр Фамилия Человека
* 4ый параметр Номер телефона в формате +7 **Обязательно писать с + иначе инфошлюз не примет номер**
* 5ый параметр является RFNSPDocid 
* 6ой параметр Номер серии документа
* 7ой параметр Номер документа 



Может показатся что  входных параметров очень много, но это сделано для ващего же удобства и прошу внимательно относится к тому, что вы передаете.
Иначе вы можете получить не валидный ответ от инфошлюза, я постарался реализовать обработку ошибок  по максимуму, но не стоит полагатся на неё всецело.

#### Методы нужные для отладки в этом классе **Не рекомендуется использовать их в  рабочем проекте  как самостоятельные**

##### метод **prepare_identification_user** <a id="prepare_identefication"></a>

```
$person->prepare_identification_user($snils)
```
Принимает единственный параметр snils Пользователя  и генерирует массив для упрощенной идентефикаций, типа 

```
Array
(
    [Person] => Array
        (
            [Family] => Василий
            [Name] => Пупкин
            [Patronim] => Иванович
            [Phone] => +7000000000
            [SNILS] => 15497129800
            [Document] => Array
                (
                    [RFNSPDocid] => 21
                    [Docseria] => 7709
                    [Docnum] => 543987
                )

        )

)
```
Напоминаю используйте данный метод лишь для того чтобы посмотреть то , что бы посмотреть сформированный масив и данные которые вы передали 

#### метод **prepare_user_emission** <a id = "preapare_user_emission"></a>

```
$person->prepare_user_emission($sex, $birthday, $birthplace, $mail, $docissuingdate, $docissuer, $docissuercode, $rawaddress, $numcard, $passphrase)
```
Я сейчас не буду рассказывать о том, за что отвечает каждое поле   об этом позже, данный метод генерирует массив нуный для эммисии карты, типа

```
Array
(
    [Person] => Array
        (
            [Name] => Пупкин
            [Family] => Василий
            [Patronim] => Иванович
            [Sex] => муж
            [BirthDay] => 2016-07-06
            [BirthPlace] => Москва
            [Email] => mail@exemple.com
            [Phone] => +7000000000
            [Document] => Array
                (
                    [RFNSPDocid] => 21
                    [Docseria] => 7709
                    [Docnum] => 543987
                    [Docissuingdate] => 2016-07-14
                    [Docissuer] => УПРАВЛЕНИЕ ФЕДЕРАЛЬНОЙ МИГРАЦИОННОЙ СЛУЖБЫ РОССИИ ПО ГОР.МОСКВЕ
                    [Docissuercode] => 770-661
                )

            [Address] => Array
                (
                    [rawaddress] => Москва, Большая Садовая, 10, кв. 50
                )

            [Card] => Array
                (
                    [num] => 43543534
                    [passphrase] => Шарик
                )

        )

)
```
Напоминаю используйте данный метод лишь для того чтобы посмотреть то , что бы посмотреть сформированный масив и данные которые вы передали 

Больше доступных методов у данного класса нету.

## Class Identification <a id="Class_identefication"></a>

Основной класс по работе с инфошлюзом постараюсь расписать о нем максимально подробно

#### **создание экземпляра класса** <a id="exemple_class_identefication"></a>

```
$new_identification = new Identification();
```
Можно вызвать без параметров, но **не рекомендуется**  оптимальный вызов:

```
$new_identification = new Identification('NbtjifAADi','000008-0001-0001','testing');
```
#### Передаваемые параметры <a id="enter_param_identefication_exemple"</a>
Подробнее о каждом из параметров:

* 1ый параметр  - это secret который знаете вы и он вам выдается системой.
* 2ой параметр  - это  номер продукта, который вам выдается тоже системой.
* 3ий параметр  - это  выбор базового урл на данном этапе существует 2 режима:
     * 1ый режим это testing  - подключение к тестовому серверу для отладки.
     * 2ой режим это work - подключение к   рабочему проекту для реальных запросов, а не отладочных

При вызове  такого метода 
```
$new_identification = new Identification();
```
**Дефолтными параметрами являются параметры приведенные в примере выше**  это является  оптимальным быстрым стартом для быстрой отладки  вашего проекта
 
#### метод **emission_new_card**<a id="method_emission_card"></a>

пример вызова:
```
 try {
            $emission_user = $new_identification->emission_new_card($person, $array_in_form['input_sex'],
                $array_in_form['input_birthday'],
                $array_in_form['input_birthplace'],
                $array_in_form['input_mail'],
                $array_in_form['input_docissuingdate'],
                $array_in_form['input_docissuer'],
                $array_in_form['input_docissuercode'],
                $array_in_form['input_adress'],
                $array_in_form['input_card_num'],
                $array_in_form['input_secret']);
        } catch (Exception $e) {
            echo "<b>Ошибка в данных:</b>" . $e->getMessage(), "\n";
            exit(1);
        }
```
Блок обарачивается в Try Cath т.к  используются  исключения внутри класса

Является самым большим методом в данном классе вызывается следующим образом
```
            $emission_user = $new_identification->emission_new_card($person, $array_in_form['input_sex'],
                $array_in_form['input_birthday'],
                $array_in_form['input_birthplace'],
                $array_in_form['input_mail'],
                $array_in_form['input_docissuingdate'],
                $array_in_form['input_docissuer'],
                $array_in_form['input_docissuercode'],
                $array_in_form['input_adress'],
                $array_in_form['input_card_num'],
                $array_in_form['input_secret']);
                
```
 
 Рассмотрим подробно каждый параметр который мы передаем:
 
 * 1ый параметр Экземпляр класса [Person](#class-person)
 * 2ой параметр Пол Юзера в формате: муж или жен
 * 3ий параметр День рождения в формате: 1928-05-20
 * 4ый параметр Место Рождения в формате: Москва
 * 5ый параметр mail в формате: mail@exemple.com
 * 6ой параметр дата получения докумеента в формате: 2016-07-14
 * 7ой параметр Место получения документа в формате : "УПРАВЛЕНИЕ ФЕДЕРАЛЬНОЙ МИГРАЦИОННОЙ СЛУЖБЫ РОССИИ ПО ГОР.МОСКВЕ"
 * 8ой параметр Код подразделения в котором получили документ в формате : 770-661
 * 9ый параметр Адресс прописки(проживания) в формате:"Москва, Большая Садовая, 10, кв. 50"
 * 10ый параметр Номер карточки в формате: 4595153453412 
 * 11ый параметр Кодовое слово например: Шарик
 
 Результатом операции  будет Массив следующего вида: 
```
Array
 (
     [code] => 1100
     [hash] => 14b8f22946e24405b8d352e3850ff845
     [entities] => Array
         (
             [provider_desc] => Проведен
             [actionName] => FINDPAY
             [billNumber] => 1234567890123456789
             [provider_code] => Array
                 (
                 )
 
             [result_desc] => Проведен
             [result_finish] => true
             [result_code] => 1
         )
 
     [request_id] => 13567
     [date] => 2016-07-28T11:53:49+00:00
     [message] => OK
 )
```
Если будут какие-то ошибки  то они выведутся вам на экран 

Что дальше с ним делать , решать вам 
 
#### метод **identification_user** <a id="identification_user"></a>

пример вызова.

```
    try {
            $identification_user = $new_identification->identification_user($person, $array_in_form['input_snils']);
        } catch (Exception $e) {
            echo 'Ошибка в данных: ', $e->getMessage(), "\n";
            exit(1);

        }
```
Блок обарачивается в Try Cath т.к  используются  исключения внутри класса

передаваемые параметры:

 * 1ый параметр Экземпляр класса [Person](#class-person)
 * 2ой параметр SNILS пользователя
 
 результатом данной операции будет число:
```
13579
```
при успешной операции.

В противном случае приложение выведет данные в которых была допущенна ошибка.

#### метод **end_identification_user**<a id="end_identification_user"></a>

пример вызова:
```
  try {
                $end_identification_user = $new_identification->end_identification_user($_POST['pin_code'], $_POST['request_id']);
            } catch (Exception $e) {
                echo 'Ошибка в данных: ', $e->getMessage(), "\n";
                exit(1);
            }
```
Блок обарачивается в Try Cath т.к  используются  исключения внутри класса

передаваемые параметры:
 
  * 1ый параметр 6-значный пин код который  пришел юзеру на телефон и введен в вашем приложени
  * 2ой параметр request_id полученый из в начале идентефикации.

Результат операции массив:
```
Array
(
    [date] => 2016-07-28T12:15:28+00:00
    [reply] => Array
        (
            [passport_valid] => false/true
            [person_valid] => false/true
            [snils_valid] => false/true
        )

    [message] => OK
    [code] => 1100
    [request_id] => 13580
)
```
В противном случае приложение выведет данные в которых была допущенна ошибка.

### Демо <a id="demo"></a>
Демо пример  рабочего приложенния полностью настроенного для ознакомления:
* в index.php - формы для отправки данных
* в form.php  - логика  по взаимодействию с классом 
 