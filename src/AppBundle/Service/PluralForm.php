<?php
/**
 * Created by PhpStorm.
 * User: pivchenberg
 * DateTime: 21.12.2016 13:37
 */

namespace AppBundle\Services;


class PluralForm
{
	private $forms;

	/**
	 * @param $n
	 * @return mixed
	 * @throws \Exception
	 */
	public function pluralForm($n) {
		if(empty($this->forms))
			throw new \Exception('plural forms are empty');

		$forms = $this->forms;
		$n = (int) $n;

		return $n%10==1&&$n%100!=11?$forms[0]:($n%10>=2&&$n%10<=4&&($n%100<10||$n%100>=20)?$forms[1]:$forms[2]);
	}

	/**
	 * @return mixed
	 */
	public function getForms()
	{
		return $this->forms;
	}

	/**
	 * @param mixed $forms
	 * @return $this
	 */
	public function setForms(array $forms)
	{
		$this->forms = $forms;

		return $this;
	}
}