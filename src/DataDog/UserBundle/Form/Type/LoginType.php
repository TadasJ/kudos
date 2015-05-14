<?php
namespace DataDog\UserBundle\Form\Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
/**
 * Class LoginType
 * @package DataDog\UserBundle\Form\Type
 */
class LoginType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('username', 'text', [
            'required' => false,
        ]);
        $builder->add('plainPassword', 'password', [
            'required' => false,
        ]);
        $builder->add('login', 'submit');
    }
    /**
     * @return string
     */
    public function getName()
    {
        return 'login';
    }
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection' => false
        ]);
    }
}