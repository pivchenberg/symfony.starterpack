<?php
/**
 * Created by PhpStorm.
 * User: pivchenberg
 * DateTime: 25.11.2016 18:38
 */

namespace AppBundle\Services;


use Symfony\Bundle\FrameworkBundle\Routing\Router;
use WhiteOctober\BreadcrumbsBundle\Model\Breadcrumbs as WOBreadCrumbs;

class Breadcrumbs
{
	private $breadcrumbs;
	private $router;

	public function __construct(WOBreadCrumbs $breadcrumbs, Router $router)
	{
		$this->breadcrumbs = $breadcrumbs;
		$this->router = $router;
	}

	public function addWithHomePage(array $arr)
	{
		$this->breadcrumbs->addItem('breadcrumb.home.icon', $this->router->generate('homepage'));
		$this->add($arr);
	}

	public function add(array $arr)
	{
		foreach ($arr as $item) {
			$url = "";

			if(count($item) < 3)
				$item[] = [];

			list($title, $routeName, $params) = $item;

			if(!empty($routeName))
				$url = $this->router->generate($routeName, $params);

			$this->breadcrumbs->addItem($title, $url);
		}
	}
}