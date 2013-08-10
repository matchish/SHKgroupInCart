SHKgroupInCart
==============

Плагин для MODX Evolution Shopkeeper 

Группирует товары в корзине по произпольному параметру.

Установка
--------------------
1. Создайте плагин с именем SHKgroupInCart
2. Повесьте на него событие OnSHKcartLoad
3. Код плагина возьмите из файла SHKgroupInCart_plugin.php
3. В настройки добавьте &groupWrapTpl=group wrapper;string;cart-group-tpl
&groupWrapTpl - чанк который оборачивает группу (по умолчанию cart-group-tpl)
4. В шаблон вывода товара добавьте скрытое поле 
`<input type="hidden" name="group__[+id+]__add" value="Имя группы" />`. По этому полю товары будут группироватся в корзине.
5. В шаблоне корзины вместо [+inner+] поставьте [+plugin+]

Плейсхолдеры чанка &groupWrapTpl
---------------------------------------

* [+price_total+] - цена товаров в группе
* [+total_items+] - количество товаров в группе
* [+group_name+] - имя группы
* [+group+] - список товаров в группе
