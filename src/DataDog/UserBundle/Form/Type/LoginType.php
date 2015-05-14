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
            'attr' => ['class' => 'form-control', 'style' => 'width:100%;', 'placeholder' => 'Username'],
        ]);
        $builder->add('plainPassword', 'password', [
            'required' => false,
            'attr' => ['class' => 'form-control', 'style' => 'width:100%;', 'placeholder' => 'Password'],
        ]);
        $builder->add('login', 'submit', [
            'attr' => ['class' => 'btn btn-primary']
        ]);
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