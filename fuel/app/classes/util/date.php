<?php

class Util_Date
{
	public static function check_is_same_minute($time1, $time2)
	{
		if (!$minute1 = self::remove_second_string($time1)) return false;
		if (!$minute2 = self::remove_second_string($time2)) return false;

		return $minute1 == $minute2;
	}

	public static function remove_second_string($datetime)
	{
		$pattern = '#^([12]{1}[0-9]{3}\-[0-9]{2}\-[0-9]{2} [0-9]{2}:[0-9]{2})(:[0-9]{2})?$#';
		if (!preg_match($pattern, $datetime, $matches)) return false;

		return $matches[1];
	}

	public static function check_is_past($val, $base = '', $min = '', $is_return_time = false, $is_return_time_format = 'Y-m-d H:i:s')
	{
		if (empty($val)) return false;
		if (!$time = strtotime($val)) return false;

		$base_time = empty($base) ? time() : strtotime($base);
		if ($time > $base_time) return false;

		$min_time = empty($min) ? strtotime('- 120 years') : strtotime($min);
		if ($time < $min_time) return false;

		if ($is_return_time) return date($is_return_time_format, $time);

		return true;
	}

	public static function check_is_future($val, $base = '', $max = '', $is_return_time = false, $is_return_time_format = 'Y-m-d H:i:s')
	{
		if (empty($val)) return false;
		if (!$time = strtotime($val)) return false;

		$base_time = empty($base) ? time() : strtotime($base);
		if ($time < $base_time) return false;

		$max_time = empty($max) ? strtotime('+ 50 years') : strtotime($max);
		if ($time > $max_time) return false;

		if ($is_return_time) return date($is_return_time_format, $time);

		return true;
	}
}
