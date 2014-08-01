<?php

namespace Victoire\Widget\RenderBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Victoire\Bundle\CoreBundle\Form\EntityProxyFormType;
use Victoire\Bundle\CoreBundle\Form\WidgetType;
use Victoire\Widget\RenderBundle\DataTransformer\JsonToArrayTransformer;

use Victoire\Widget\RenderBundle\Entity\WidgetRender;

/**
 * WidgetRender form type
 */
class WidgetRenderType extends WidgetType
{

    /**
     * define form fields
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //
        parent::buildForm($builder, $options);

        $namespace = $options['namespace'];
        $entityName = $options['entityName'];

        if ($entityName !== null) {
            if ($namespace === null) {
                throw new \Exception('The namespace is mandatory if the entity_name is given.');
            }
        }

        //choose form mode
        if ($entityName === null) {
            //if no entity is given, we generate the static form
            $transformer = new JsonToArrayTransformer();
            $builder
                ->add('mode', 'choice', array(
                    'choices'  => array(
                        WidgetRender::MODE_ROUTE            => 'form.render.mode.choice.route',
                        WidgetRender::MODE_WIDGET_REFERENCE => 'form.render.mode.choice.widget_reference'
                    ),
                ))
                ->add('route')
                ->add($builder->create('params', 'text', array(
                    'help_block' => 'form.appventus_victoirecorebundle_widgetrendertype.children.params.help_block'
                    )
                )->addModelTransformer($transformer))
                ->add('widget');
        }
    }


    /**
     * Bind form to WidgetRender entity
     *
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);

        $resolver->setDefaults(array(
            'data_class'         => 'Victoire\Widget\RenderBundle\Entity\WidgetRender',
            'widget'             => 'render',
            'translation_domain' => 'victoire'
        ));
    }

    /**
     * get form name
     *
     * @return string
     */
    public function getName()
    {
        return 'victoire_widget_form_render';
    }
}