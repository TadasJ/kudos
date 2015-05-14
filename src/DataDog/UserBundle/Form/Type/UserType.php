<?php
namespace DataDog\UserBundle\Form\Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityManager;
/**
 * Class UserType
 * @package DataDog\UserBundle\Form\Type
 */
class UserType extends AbstractType
{

    protected $em;
    /**
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('username', 'text', [
            'required' => false,
            'label' => 'Username',
            'attr' => ['class' => 'form-control', 'style' => 'width:100%;', 'placeholder' => 'Enter username'],
        ]);
        $builder->add('password', 'password', [
            'required' => false,
            'label' => 'Password',
            'attr' => ['class' => 'form-control', 'style' => 'width:100%;', 'placeholder' => 'Enter password'],
        ]);
        $builder->add('role', 'entity', [
            'class' => 'UserBundle:UserRole',
            'choices' => $this->fetchRoleChoices(),
            'empty_value' => 'Please Select',
            'required' => false,
            'label' => 'Role',
            'attr' => ['class' => 'form-control', 'style' => 'width:100%;'],
        ]);
        $builder->add('first_name', 'text', [
            'required' => false,
            'label' => 'Name',
            'attr' => ['class' => 'form-control', 'style' => 'width:100%;', 'placeholder' => 'Enter first name'],
        ]);
        $builder->add('last_name', 'text', [
            'required' => false,
            'label' => 'Surname',
            'attr' => ['class' => 'form-control', 'style' => 'width:100%;', 'placeholder' => 'Enter last name'],
        ]);
        $builder->add('total_points', 'text', [
            'required' => false,
            'label' => 'Points',
            'attr' => ['class' => 'form-control', 'style' => 'width:100%;', 'placeholder' => 'Enter current points'],
        ]);
        $builder->add('teams', 'entity', [
            'class' => 'UserBundle:Team',
            'choices' => $this->fetchTeamChoices(),
            'label' => 'Teams',
            'attr' => ['class' => 'form-control', 'style' => 'width:100%;'],
            'required' => false,
            'multiple' => true,
        ]);
        //if ($options['update']) {
            $builder->add('is_active', 'checkbox', [
                'required' => false,
                'label' => 'Is Active',
                'constraints' => [
                    new Assert\Type('boolean'),
                    new Assert\Choice([true, false])
                ]
            ]);
        //
        $builder->add('confirm', 'submit', [
            'attr' => ['class' => 'btn btn-primary']
        ]);

    }
    /**
     * @return string
     */
    public function getName()
    {
        return 'user';
    }

    /**
     * @return array
     */
    private function fetchRoleChoices()
    {
        $roles = $this->em->getRepository('UserBundle:UserRole')->findAll();
        return $roles;
    }

    private function fetchTeamChoices(){
        $teams = $this->em->getRepository('UserBundle:Team')->findAll();
        return $teams;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection' => false,
            'update' => false,
        ]);
    }
}