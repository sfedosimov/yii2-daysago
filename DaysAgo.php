<?php

	namespace sfedosimov\daysago;
	use yii\base\Object;

	class DaysAgo extends Object
	{
		public $format_in = 'd.m.Y';
		public $postfix = 'назад';
		public $prefix = 'более';

		private $format_out = 'd.m.Y';

		public function make($date, array $to_date = [])
		{
			if (is_array($date) && isset($date['format'],$date['date'])) {
				$dt = \DateTime::createFromFormat($date['format'],$date['date']);
			} else {
				$dt = \DateTime::createFromFormat($this->format_in, $date);
			}

         	$cmp_date = $dt->format($this->format_out);

			if (!empty($to_date) && isset($to_date['format'], $to_date['date'])) {
				$today_dt = \DateTime::createFromFormat($to_date['format'], $to_date['date']);
			} else {
				$today_dt = new \DateTime();
			}

			$today = $today_dt->format($this->format_out);
			$yesterday = $today_dt->modify('-1 day')->format($this->format_out);
         	$before_yesterday = $today_dt->modify('-2 day')->format($this->format_out);

         	$diff_days = $today_dt->diff($dt)->format('%a');
         	$diff_years = $today_dt->diff($dt)->format('%y');

         	if ($cmp_date == $today) {
         		return 'Сегодня';
         	} else if ($cmp_date == $yesterday) {
         		return 'Вчера';
         	} else if ($cmp_date == $before_yesterday) {
				return 'Позавчера';
         	} else if ($diff_days >= 3) {
         		return $this->prefix . self::getDecline($diff_days, 'день', 'дня', 'дней') . $this->postfix;
         	}else if ($diff_years >= 1) {
         		return $this->prefix . self::getDecline($diff_years, 'год', 'года', 'лет') . $this->postfix;
         	}
		}

		/*
		 * $num число, от которого будет зависеть форма слова
		 * $dec1 первая форма слова, например Товар
		 * $dec2 вторая форма слова - Товара
		 * $dec3 третья форма множественного числа слова - Товаров
		 */
		public static function getDecline($num, $dec1, $dec2, $dec3)
		{
			$num = abs($num) % 100; // берем число по модулю и сбрасываем сотни (делим на 100, а остаток присваиваем переменной $num)
			$num_x = $num % 10; // сбрасываем десятки и записываем в новую переменную
			if ($num > 10 && $num < 20) // если число принадлежит отрезку [11;19]
				return $dec3;
			if ($num_x > 1 && $num_x < 5) // иначе если число оканчивается на 2,3,4
				return $dec2;
			if ($num_x == 1) // иначе если оканчивается на 1
				return $dec1;
			return $dec3;
		}
	}
