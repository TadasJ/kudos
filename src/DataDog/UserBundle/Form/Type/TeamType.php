<?php
namespace DataDog\UserBundle\Form\Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityManager;
/**
 * Class TeamType
 * @package DataDog\UserBundle\Form\Type
 */
class TeamType extends AbstractType
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
        $builder->add('name', 'text', [
            'required' => false,
            'label' => 'Name',
            'attr' => ['class' => 'form-control', 'style' => 'width:100%;', 'placeholder' => 'Enter team name'],
        ]);
        $builder->add('manager', 'entity', [
            'class' => 'UserBundle:User',
            'choices' => $this->fetchManagerChoices(),
            'empty_value' => 'Please Select',
            'label' => 'Manager',
            'attr' => ['class' => 'form-control', 'style' => 'width:100%;'],
            'required' => false,
        ]);
        $builder->add('users', 'entity', [
            'class' => 'UserBundle:User',
            'choices' => $this->fetchEmployeeChoices(),
            'label' => 'Users',
            'attr' => ['class' => 'form-control', 'style' => 'width:100%;'],
            'required' => false,
            'multiple' => true,
        ]);
        $builder->add('confirm', 'submit', [
            'attr' => ['class' => 'btn btn-primary']
        ]);

    }
    /**
     * @return string
     */
    public function getName()
    {
        return 'team';
    }

    /**
     * @return array
     */
    private function fetchManagerChoices()
    {
        $users = $this->em->getRepository('UserBundle:User')->findAllActiveManagers();
        return $users;
    }

    private function fetchEmployeeChoices()
    {
        $users = $this->em->getRepository('UserBundle:User')->findAllActiveEmployees();
        return $users;
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