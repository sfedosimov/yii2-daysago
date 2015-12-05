# DaysAgo
DaysAgo - Преобразователь даты в более дружественный формат

## Установка


Добавить в **composer.json**


    "require": {
        "sfedosimov/yii2-daysago": "*"
    },
    
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/sfedosimov/yii2-daysago.git"
        }
    ],



## Использование


##### Как класс

    use sfedosimov\daysago\DaysAgo;
    // ...
    // make([date, format], [date, format]);
    echo (new DaysAgo())->make('30.09.1998');
    // 2 месяца и 5 дней назад
    (new DaysAgo())->make(['02-12-2015', 'd-m-Y'], ['02-12-2015', 'd-m-Y']);
    // сегодня
    echo (new DaysAgo())->make(['01-06-2010', 'd-m-Y'], ['05.12.2015']);
    // 5 лет, 6 месяцев и 4 дня назад

##### Как компонент

В конфиг Yii2:

    'components' => [
    // .....
        'daysago' => [
            'class' => 'sfedosimov\daysago\DaysAgo',
            'format_in' => 'd-m-Y',
            'postfix' => ' прошло'
        ],
    // .....
    ]


В коде:

    echo Yii::$app->daysago->make('05.12.2015');
    // 5 лет, 6 месяцев и 4 дня прошло
