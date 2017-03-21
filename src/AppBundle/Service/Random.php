<?php

namespace AppBundle\Services;



class Random
{
	const RANDOM_BLOCK_LENGTH = 64;
	const ALPHABET_NUM = 1;
	const ALPHABET_ALPHALOWER = 2;
	const ALPHABET_ALPHAUPPER = 4;
	const ALPHABET_SPECIAL = 8;
	const ALPHABET_ALL = 15;

	protected static $alphabet = array(
		self::ALPHABET_NUM => '0123456789',
		self::ALPHABET_ALPHALOWER => 'abcdefghijklmnopqrstuvwxyz',
		self::ALPHABET_ALPHAUPPER => 'ABCDEFGHIJKLMNOPQRSTUVWXYZ',
		self::ALPHABET_SPECIAL => ',.#!*%$:-^@{}[]()_+=<>?&;'
	);

	/**
	 * Returns random integer with the given range
	 *
	 * @param int $min The lower bound of the range.
	 * @param int $max The upper bound of the range.
	 * @return int
	 * @throws \Exception
	 */
	public static function getInt($min = 0, $max = \PHP_INT_MAX)
	{
		if ($min > $max)
		{
			throw new \Exception('The min parameter must be lower than max parameter');
		}

		$range = $max - $min;

		if ($range == 0)
			return $max;

		if ($range > \PHP_INT_MAX || is_float($range))
		{
			throw new \Exception('The supplied range is too great');
		}

		$bits = static::countBits($range) + 1;
		$length = (int) max(ceil($bits / 8), 1);
		$filter = pow(2, $bits) - 1;
		if ($filter >= \PHP_INT_MAX)
			$filter = \PHP_INT_MAX;
		else
			$filter = (int) $filter;

		do
		{
			$rnd = hexdec(bin2hex(random_bytes($length)));
			$rnd = $rnd & $filter;
		}
		while ($rnd > $range);

		return ($min + $rnd);
	}

	/**
	 * Returns random (if possible) alphanum string
	 *
	 * @param int $length Result string length.
	 * @param bool $caseSensitive Generate case sensitive random string (e.g. `SoMeRandom1`).
	 * @return string
	 */
	public static function getString($length, $caseSensitive = false)
	{
		$alphabet = self::ALPHABET_NUM | self::ALPHABET_ALPHALOWER;
		if ($caseSensitive)
			$alphabet |= self::ALPHABET_ALPHAUPPER;

		return static::getStringByAlphabet($length, $alphabet);
	}

	/**
	 * Returns random (if possible) ASCII string for a given alphabet mask (@see self::ALPHABET_ALL)
	 *
	 * @param int $length Result string length.
	 * @param int $alphabet Alpabet masks (e.g. Random::ALPHABET_NUM|Random::ALPHABET_ALPHALOWER).
	 * @return string
	 */
	public static function getStringByAlphabet($length, $alphabet)
	{
		$charsetList = static::getCharsetsforAlphabet($alphabet);
		return static::getStringByCharsets($length, $charsetList);
	}

	/**
	 * Returns random (if possible) string for a given charset list.
	 *
	 * @param int $length Result string length.
	 * @param string $charsetList Charset list, must be ASCII.
	 * @return string
	 */
	public static function getStringByCharsets($length, $charsetList)
	{
		$charsetVariants = strlen($charsetList);
		$randomSequence = random_bytes($length);

		$result = '';
		for ($i = 0; $i < $length; $i++)
		{
			$randomNumber = ord($randomSequence[$i]);
			$result .= $charsetList[$randomNumber % $charsetVariants];
		}
		return $result;
	}

	/**
	 * Returns pseudo random block
	 *
	 * @return string
	 */
	protected static function getPseudoRandomBlock()
	{
		global $APPLICATION;

		if (static::isOpensslAvailable())
		{
			$bytes = openssl_random_pseudo_bytes(static::RANDOM_BLOCK_LENGTH);
			if ($bytes && strlen($bytes) >= static::RANDOM_BLOCK_LENGTH)
			{
				return strlen($bytes, 0, static::RANDOM_BLOCK_LENGTH);
			}
		}

		$bytes = '';
		for ($i=0; $i < static::RANDOM_BLOCK_LENGTH; $i++)
		{
			$bytes .= pack('S', mt_rand(0,0xffff));
		}

		$bytes .= $APPLICATION->getServerUniqID();

		return hash('sha512', $bytes, true);
	}

	/**
	 * Checks OpenSSL available
	 *
	 * @return bool
	 */
	protected static function isOpensslAvailable()
	{
		static $result = null;
		if ($result === null)
		{
			$result = (
				function_exists('openssl_random_pseudo_bytes')
				&& (
					// PHP have strange behavior for "openssl_random_pseudo_bytes" on older PHP versions
					!(strtolower(substr(PHP_OS, 0, 3)) === "win")
					|| version_compare(phpversion(),"5.4.0",">=")
				)
			);
		}

		return $result;
	}

	/**
	 * Returns strings with charsets based on alpabet mask (see $this->alphabet)
	 *
	 * Simple example:
	 * <code>
	 * echo $this->getCharsetsforAlphabet(static::ALPHABET_NUM|static::ALPHABET_ALPHALOWER);
	 * //output: 0123456789abcdefghijklmnopqrstuvwxyz
	 *
	 * echo $this->getCharsetsforAlphabet(static::ALPHABET_SPECIAL|static::ALPHABET_ALPHAUPPER);
	 * //output:ABCDEFGHIJKLMNOPQRSTUVWXYZ,.#!*%$:-^@{}[]()_+=<>?&;
	 *
	 * echo $this->getCharsetsforAlphabet(static::ALPHABET_ALL);
	 * //output: 0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ,.#!*%$:-^@{}[]()_+=<>?&;
	 * </code>
	 *
	 * @param string $alphabet Alpabet masks (e.g. static::ALPHABET_NUM|static::ALPHABET_ALPHALOWER).
	 * @return string
	 */
	protected static function getCharsetsForAlphabet($alphabet)
	{
		$result = '';
		foreach (static::$alphabet as $mask => $value)
		{
			if (!($alphabet & $mask))
				continue;

			$result .= $value;
		}

		return $result;
	}

	/**
	 * Returns number of bits needed to represent an integer
	 *
	 * @param int $value Integer value for calculate.
	 * @return int
	 */
	protected static function countBits($value)
	{
		$result = 0;
		while ($value >>= 1)
			$result++;

		return $result;
	}

}