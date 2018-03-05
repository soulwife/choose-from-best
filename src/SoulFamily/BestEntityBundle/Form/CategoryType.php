<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SoulFamily\BestEntityBundle\Form;

use SoulFamily\BestEntityBundle\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * Defines the form used to create and manipulate categories.
 */
class CategoryType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, [
                'attr' => ['autofocus' => true],
                'label' => 'Name',
            ])
            ->add('url', null, [
                'attr' => ['rows' => 40],
                'label' => 'Url',
            ])
            ->add('imgUrl', null, [
                'label' => 'Image url',
            ])
            ->add('htmlCrawlPath', null, [
                'label' => 'Html Crawl Path (should ends with <a> tag)',
            ])
            ->add('Submit', SubmitType::class, [
               'attr' => ['class' => 'btn-success']
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Category::class
        ]);
    }
}
