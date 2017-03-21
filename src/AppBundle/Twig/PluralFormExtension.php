<?php
/**
 * Created by PhpStorm.
 * User: pivchenberg
 * DateTime: 21.12.2016 14:52
 */

namespace AppBundle\Twig;


use AppBundle\Services\PluralForm;

class PluralFormExtension extends \Twig_Extension
{

	/**
	 * @var PluralForm
	 */
	private $pluralForm;

	public function __construct(PluralForm $pluralForm)
	{
		$this->pluralForm = $pluralForm;
	}

	public function getFilters()
	{
		return [
			new \Twig_SimpleFilter('plural_form', [$this, 'getPluralForm'])
		];
	}
	
	public function getPluralForm(array $arForms, $count)
	{
		return $this->pluralForm->setForms($arForms)->pluralForm($count);
	}
}