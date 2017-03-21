<?php
/**
 * Created by PhpStorm.
 * User: Ксенич
 * Date: 20.01.2017
 * Time: 18:02
 */

namespace AppBundle\Services;

class EmailMessages
{
	/**
	 * @var \Twig_Environment
	 */
	private $twig;

	public function __construct(\Twig_Environment $twig)
	{
		$this->twig = $twig;
	}

	protected function renderView($view, array $parameters = array())
	{
		return $this->twig->render($view, $parameters);
	}

	public function getFirstStepRegistrationMessage($arData)
	{
		return \Swift_Message::newInstance()
			->setSubject('name')
			->setFrom('noreply@site.ru')
			->setTo($arData['emailTo'])
			->setBody(
				$this->renderView(

					'email/template.html.twig',
					$arData
				),
				'text/html'
			);
	}
}