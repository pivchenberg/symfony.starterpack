<?php
/**
 * Created by PhpStorm.
 * User: pivchenberg
 * DateTime: 21.12.2016 13:30
 */

namespace AppBundle\Twig;


use AppBundle\Services\RuDateFormat;

class RuDateFormatExtension extends \Twig_Extension
{

	/**
	 * @var RuDateFormat
	 */
	private $ruDateFormat;

	public function __construct(RuDateFormat $ruDateFormat)
	{
		$this->ruDateFormat = $ruDateFormat;
	}

	public function getFilters()
	{
		return [
			new \Twig_SimpleFilter('ru_date_format', [$this, 'formatDate']),
			new \Twig_SimpleFilter('ru_date_time_format', [$this, 'formatDateTime'])
		];
	}

	public function formatDate(\DateTime $dateTime, $toLower = false, $isPlural = false)
	{
		$strRuDate = $this->ruDateFormat->formatDate($dateTime, $toLower, $isPlural);
		return $strRuDate;
	}

	public function formatDateTime(\DateTime $dateTime, $toLower = false, $isPlural = false)
	{
		$strRuDate = $this->ruDateFormat->formatDate($dateTime, $toLower, $isPlural);
		return $strRuDate . ' в ' . $dateTime->format('H:i');
	}
}