DaysAgo
=======
DaysAgo - Преобразователь даты в более дружественный формат

Установка
------------

Добавить в `composer.json`

```
"require": {
    "sfedosimov/yii2-daysago": "*"
},

"repositories": [
    {
        "type": "vcs",
        "url": "https://github.com/sfedosimov/yii2-daysago.git"
    }
],

```

Использование
------------

##### Как класс
```
use sfedosimov\daysago\DaysAgo;
// ...
// make([date, format], [date, format]);
echo (new DaysAgo())->make('02.11.1998');
// более 17 лет назад
(new DaysAgo())->make(['02-12-2015', 'd-m-Y'], ['02-12-2015', 'd-m-Y']);
// сегодня
echo (new DaysAgo())->make(['02-04-2015', 'd-m-Y'], ['02.11.2015']);
// 214 дней назад
```

##### Как компонент

В конфиг Yii2:
```
'components' => [
// .....
    'daysago' => [
        'class' => 'sfedosimov\daysago\DaysAgo',
        'format_in' => 'd-m-Y',
        'postfix' => ' прошло'
    ],
// .....
]
```

В коде:
```
echo Yii::$app->daysago->make('02-11-1998');
// более 24 лет прошло
```