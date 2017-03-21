<?php
/**
 * Created by PhpStorm.
 * User: pivchenberg
 * DateTime: 15.03.2017 13:04
 */

namespace AppBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class Builder implements ContainerAwareInterface
{
	use ContainerAwareTrait;

	public function mainMenu(FactoryInterface $factory, array $options)
	{
		$menu = $factory->createItem('root', ['childrenAttributes' => ['class' => 'nav navbar-nav']]);

		$menu->addChild("Symfony Demo", ['route' => 'homepage', 'linkAttributes' => ['class' => 'navbar-brand']]);
		$menu->addChild("<span class='fa fa-home'></span>", ['route' => 'homepage', 'extras' => ['safe_label' => true], 'linkAttributes' => ['class' => 'navbar-brand']]);
		$menu->addChild("Список клиентов агентства", ['route' => '']);

		return $menu;
	}
}