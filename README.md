Тест на ПХП

В БД хранятся данные (уникальный идентификатор, значение, версия). 

Таблица имеет следующий вид: 
CREATE TABLE `data` ( 
`ident` varchar(32) NOT NULL, 
`value` varchar(255) NOT NULL, 
`version` int(10) unsigned NOT NULL, 
UNIQUE KEY `ident` (`ident`) 
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 

На вход скрипта в GET приходит запрос в следующем формате: 
ident[0]=<IDENT_0>&value[0]=<VALUE_0>&version[0]=<VERSION_0>&....&ident[N]=<IDENT_N>&value[N]=<VALUE_N>&version[N]=<VERSION_N> 

Считается, что в БД всегда хранятся актуальные данные. 
Скрипт должен обработать данные поступившие на вход и на выходе вернуть сериализованный массив с 3мя ключами: 

1. delete - список идентификаторов, которые пришли в запросе и отсутствуют в БД 
2. update - список значений и версий по идентификаторам, где версия в БД стала больше чем версия пришедшая в запросе 
3. new - список значений и версий по идентификаторам, которые отсутствуют в пришедшем запросе, но есть в БД 

Пример структуры массива который может получиться на выходе скрипта: 
array ( 
'delete' => 
array ( 
0 => 'ident1', 
1 => 'iden3', 
), 
'update' => 
array ( 
'ident2' => 
array ( 
'value' => 'some value 1', 
'version' => 56, 
), 
), 
'new' => 
array ( 
'ident4' => 
array ( 
'value' => 'some value 44', 
'version' => 1, 
), 
'ident5' => 
array ( 
'value' => 'some value 567', 
'version' => 2, 
), 
) 




# PHP_test

test?ident0=414&value0=732&version0=2&ident1=415&value1=787&version1=1&ident2=416&value2=234&version2=2&ident3=417&value3=787&version3=3&ident4=418&value4=123&version4=2&ident5=419&value5=678&version5=3




