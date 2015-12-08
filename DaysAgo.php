<?php

	/**
	 * @author Fedosimov Sergey <sfedosimov@gmail.com>
	 */

	namespace sfedosimov\daysago;
	use yii\base\Object;

	class DaysAgo extends Object
	{
		public $format_in = 'd.m.Y';
		public $past_prefix = '';
		public $past_postfix = '';
		public $future_prefix = '';
		public $future_postfix = '';

		private $format_out = 'd.m.Y';

		/**
		 * Возвращает красивое представление прошедшего времени
		 *
		 * @param date|array  $date    дата для преобразования (дата|[дата, формат])
		 * @param date|array  $to_date дата отсчета (дата|[дата, формат])
		 *
		 * @return null|string
		 */
		public function make($date, array $to_date = [])
		{
			if (is_array($date)) {
				$dt = \DateTime::createFromFormat($date[1] ?: $this->format_in,$date[0]);
			} else {
				$dt = \DateTime::createFromFormat($this->format_in, $date);
			}

			$cmp_date = $dt->format($this->format_out);

			if (!empty($to_date) && is_array($to_date)) {
				$today_dt = \DateTime::createFromFormat($to_date[1] ?: $this->format_in, $to_date[0]);
			} else {
				$today_dt = new \DateTime();
			}

			$today = $today_dt->format($this->format_out);

            $today_dt_tmp = clone $today_dt;
            $yesterday = $today_dt_tmp->modify('-1 day')->format($this->format_out);
            $tomorrow = $today_dt_tmp->modify('+2 day')->format($this->format_out);
            unset($today_dt_tmp);

            $diffs = $today_dt->diff($dt)->format('%R %y %m %d');
            list($z, $y, $m, $d) = explode(' ', $diffs);

            if ($cmp_date == $today) {
                return \Yii::t('daysago','сегодня');
            } else if ($cmp_date == $yesterday) {
                return \Yii::t('daysago','вчера');
            } else if ($cmp_date == $tomorrow) {
                return \Yii::t('daysago','завтра');
            } else {

				$out = [];

                if ($y > 0) {
                    $out[] = $y . ' ' . self::getDecline($y, \Yii::t('daysago','год'), \Yii::t('daysago','года'), \Yii::t('daysago','лет'));
                }

                if ($m > 0) {
                    $out[] = $m . ' ' . self::getDecline($m, \Yii::t('daysago','месяц'), \Yii::t('daysago','месяца'), \Yii::t('daysago','месяцев'));
                }

                if ($d > 0) {
                    $out[] = $d . ' ' . self::getDecline($d, \Yii::t('daysago','день'), \Yii::t('daysago','дня'), \Yii::t('daysago','дней'));
                }

                $out_cnt = count($out);

                if ($out_cnt == 2) {
                    array_splice($out, 1, 0, array(\Yii::t('daysago','и')));
                } else if ($out_cnt == 3) {
                    array_splice($out, 1, 0, array(','));
                    array_splice($out, 3, 0, array(\Yii::t('daysago','и')));
                }

				if ($z == '-') {
					$prefix = $this->past_prefix;
					$postfix = $this->past_postfix;
				} else {
					$prefix = $this->future_prefix;
					$postfix = $this->future_postfix;
				}

				return $prefix . str_replace(' ,', ',', implode(' ', $out)) . $postfix;

            }
		}

		/**
		 * @param integer $num  число, от которого будет зависеть форма слова
		 * @param string  $dec1 первая форма слова, например Год
		 * @param string  $dec2 вторая форма слова - Года
		 * @param string  $dec3 третья форма множественного числа слова - Лет
		 *
		 * @return mixed
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
