<?php

namespace Wid;

/**
 * A class to help with casting strings to native php types (the to* methods)
 * and from php native types back to strings (from* methods)
 */
class Cast
{
	const DATE_PATTERN = 'Y-m-d';
	const TIME_PATTERN = 'H:i:s';
	const TIMESTAMP_PATTERN = 'U';

	public static function toBoolean($source)
	{
		$false = array('', 'false', false, null, 0, '0');
		return  ! \in_array($source, $false);
	}

	public static function toInteger($source)
	{
		return (int) $source;
	}

	public static function toFloat($source)
	{
		return (float) $source;
	}

	public static function toDateTime($source, $pattern = null)
	{
		if ($pattern === null)
		{
			$pattern = self::DATE_PATTERN.' '.self::TIME_PATTERN;
		}

		return \DateTime::createFromFormat($pattern, $source);
	}

	public static function toDate($source)
	{
		return self::toDateTime($source, self::DATE_PATTERN);
	}

	public static function toTime($source)
	{
		return self::toDateTime($source, self::TIME_PATTERN);
	}

	public static function toTimestamp($source)
	{
		return self::toDateTime($source, self::TIMESTAMP_PATTERN);
	}

	public static function toCallback($source, $callback)
	{
		return \call_user_func($callback, $source);
	}

}
