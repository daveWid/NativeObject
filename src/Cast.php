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

	/**
	 * @param  mixed $source
	 * @return false
	 */
	public static function toBoolean($source)
	{
		$false = array('', 'false', false, null, 0, '0');
		return  ! \in_array($source, $false);
	}

	/**
	 * @param  string $source
	 * @return int
	 */
	public static function toInteger($source)
	{
		return (int) $source;
	}

	/**
	 * @param  string $source
	 * @return float
	 */
	public static function toFloat($source)
	{
		return (float) $source;
	}

	/**
	 * @param  string $source
	 * @param  string $pattern  The pattern to convert to a DateTime object
	 * @return \DateTime
	 */
	public static function toDateTime($source, $pattern = null)
	{
		if ($pattern === null)
		{
			$pattern = self::DATE_PATTERN.' '.self::TIME_PATTERN;
		}

		return \DateTime::createFromFormat($pattern, $source);
	}

	/**
	 * @param  string $source
	 * @return \DateTime
	 */
	public static function toDate($source)
	{
		return self::toDateTime($source, self::DATE_PATTERN);
	}

	/**
	 * @param  string $source
	 * @return \DateTime
	 */
	public static function toTime($source)
	{
		return self::toDateTime($source, self::TIME_PATTERN);
	}

	/**
	 * @param  mixed $source
	 * @return \DateTime
	 */
	public static function toTimestamp($source)
	{
		return self::toDateTime($source, self::TIMESTAMP_PATTERN);
	}

	/**
	 * @param  string    $source
	 * @param  callable  $callback  An value that can be used in \call_user_func
	 *
	 * @uses \call_user_func
	 *
	 * @return \DateTime
	 */
	public static function toCallback($source, $callback)
	{
		return \call_user_func($callback, $source);
	}

}
