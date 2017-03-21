<?php
/**
 * Created by PhpStorm.
 * User: pivchenberg
 * DateTime: 20.03.2017 17:40
 */

namespace AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
	/**
	 * @Route("/", name="homepage")
	 */
	public function indexAction()
	{
		return $this->render('default/homepage.html.twig');
	}
}