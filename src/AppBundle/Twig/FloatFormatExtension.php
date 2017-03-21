<?php
/**
 * Created by PhpStorm.
 * User: pivchenberg
 * DateTime: 13.02.2017 18:26
 */

namespace AppBundle\Twig;


class FloatFormatExtension extends \Twig_Extension
{
	public function getFilters()
	{
		return [
			new \Twig_SimpleFilter('float_format', array($this, 'floatFormat')),
		];
	}

	public function floatFormat($floatNumber)
	{

		$decimals = 2;
		$formattedFloatNumber = number_format((float) $floatNumber, $decimals, '.', ' ');
		if(preg_match('/00$/i', $formattedFloatNumber))
		{
			$deleteLast = $decimals+1;
			$formattedFloatNumber = substr($formattedFloatNumber, 0, -$deleteLast);
		}

		return $formattedFloatNumber;
	}
}