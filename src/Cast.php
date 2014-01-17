<?php

namespace Wid;

/**
 * A class to help with casting strings to native php types (the to* methods)
 * and from php native types back to strings (from* methods)
 */
class Cast
{
	public static $datePattern = 'Y-m-d';
	public static $timePattern = 'H:i:s';
	public static $timestampPattern = 'U';

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
			$pattern = self::$datePattern.' '.self::$timePattern;
		}

		return \DateTime::createFromFormat($pattern, $source);
	}

	/**
	 * @param  string $source
	 * @return \DateTime
	 */
	public static function toDate($source)
	{
		return self::toDateTime($source, self::$datePattern);
	}

	/**
	 * @param  string $source
	 * @return \DateTime
	 */
	public static function toTime($source)
	{
		return self::toDateTime($source, self::$timePattern);
	}

	/**
	 * @param  mixed $source
	 * @return \DateTime
	 */
	public static function toTimestamp($source)
	{
		return self::toDateTime($source, self::$timestampPattern);
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
