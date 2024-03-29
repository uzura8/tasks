<?php
class Util_Array
{
	public static function array_in_array($targets, $haystacks)
	{
		if (!is_array($targets)) $targets = (array)$targets;
		foreach ($targets as $target)
		{
			if (!in_array($target, $haystacks)) return false;
		}

		return true;
	}

	public static function get_neighborings($item, $list)
	{
		$before = null;
		$after  = null;
		$is_hit = false;
		foreach ($list as $value)
		{
			if ($is_hit)
			{
				$after = $value;
				break;
			}
			if ($value == $item) $is_hit = true;
			if (!$is_hit) $before = $value;
		}

		return array($before, $after);
	}

	public static function cast_values(array $values, $type, $is_check_empty = false)
	{
		switch ($type)
		{
			case 'int':
				$func = 'intval';
				break;
			case 'string':
				$func = 'strval';
				break;
			default :
				throw new \InvalidArgumentException("Second parameter must be 'int' or 'string'.");
				break;
		}

		$return = array();
		foreach ($values as $value)
		{
			if ($is_check_empty && empty($value)) return false;
			$return[] = $func($value);
		}

		return $return;
	}

	public static function convert_for_callback(array $array)
	{
		$return = array();
		foreach ($array as $key => $values)
		{
			if (is_string($key))
			{
				$each = array($key);
				$max = count($values);
				for ($i = 0; $i < $max; $i++) $each[] = $values[$i];
				$return[] = $each;
			}
			else
			{
				$return[] = $values;
			}
		}

		return $values;
	}
}
