<?php
/**
 * Created by PhpStorm.
 * User: pivchenberg
 * DateTime: 21.12.2016 14:25
 */

namespace AppBundle\Services;

use AppBundle\Services\PluralForm;

class RuDateFormat
{
	/**
	 * @var PluralForm
	 */
	private $pluralForm;

	public function __construct(PluralForm $pluralForm)
	{
		$this->pluralForm = $pluralForm;
	}

	public function formatDate(\DateTime $dateTime, $toLower = false,  $isPlural = true)
	{
		$arRuDate[] = $dateTime->format('j');

		$ruMonth = $this->getRuMonth($dateTime->format('m'), $toLower, $isPlural);
		if(empty($ruMonth))
			$ruMonth = $dateTime->format('m');
		$arRuDate[] = $ruMonth;

		$arRuDate[] = $dateTime->format('Y');

		return implode(' ', $arRuDate);
	}
	
	public function getRuMonth($intMonth, $toLower = false,  $isPlural = true)
	{
		$intMonth = (int) $intMonth;
		$count = $isPlural ? 2 : 1;
		
		$arMonth = [
			1 => [
				"Январь",
				"Января"
			],
			2 => [
				"Февраль",
				"Февраля"
			],
			3 => [
				"Март",
				"Марта"
			],
			4 => [
				"Апрель",
				"Апреля",
			],
			5 => [
				"Май",
				"Мая"
			],
			6 => [
				"Июнь",
				"Июня"
			],
			7 => [
				"Июль",
				"Июля"
			],
			8 => [
				"Август",
				"Августа"
			],
			9 => [
				"Сентябрь",
				"Сентября"
			],
			10 => [
				"Октябрь",
				"Октября"
			],
			11 => [
				"Ноябрь",
				"Ноября"
			],
			12 => [
				"Декабрь",
				"Декабря"
			]
		];
		
		if(isset($arMonth[$intMonth]))
		{
			$ruMonth = $this->pluralForm->setForms($arMonth[$intMonth])->pluralForm($count);

			if($toLower)
				$ruMonth = mb_strtolower($ruMonth);

			return $ruMonth;
		}

		return "";
	}
}