<?php
namespace DataDog\GoalBundle\Form\Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use DataDog\GoalBundle\Entity\Goal;
use DataDog\UserBundle\Entity\User;
use Doctrine\ORM\EntityManager;
/**
 * Class GoalType
 * @package DataDog\GoalBundle\Form\Type
 */
class AchievementType extends AbstractType
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
        $builder->add('goal', 'entity', [
            'class' => 'GoalBundle:Goal',
            'choices' => $this->fetchGoalChoices(),
            'label' => 'Goals',
            'attr' => ['class' => 'form-control', 'style' => 'width:100%;'],
            'required' => false,
            'multiple' => true,
        ]);

        $builder->add('user', 'entity', [
            'class' => 'UserBundle:User',
            'choices' => $this->fetchEmployeeChoices(),
            'label' => 'Goals',
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
        return 'achievement';
    }

    private function fetchGoalChoices()
    {
        $goals = $this->em->getRepository('GoalBundle:Goal')->findAll();
        return $goals;
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