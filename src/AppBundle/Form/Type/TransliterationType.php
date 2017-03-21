<?php
/**
 * Created by PhpStorm.
 * User: pivchenberg
 * DateTime: 01.03.2017 18:34
 */

namespace AppBundle\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TransliterationType extends AbstractType
{
	public function buildView(FormView $view, FormInterface $form, array $options)
	{
		$formName = $form->getParent()->getName();
		$fieldName = $options['translit-to'];
		$view->vars['attr']['data-translit-to'] = implode('_', [$formName, $fieldName]);
	}

	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setRequired('translit-to')->setAllowedTypes('translit-to', 'string');
	}

	public function getParent()
	{
		return TextType::class;
	}
}