<?php

namespace DataDog\UserBundle\Controller;

use DataDog\UserBundle\Entity\User;
use DataDog\UserBundle\Form\Model\LoginForm;
use DataDog\UserBundle\Form\Type\LoginType;
use DataDog\UserBundle\Form\Type\UserType;
use DataDog\UserBundle\Repository\UserRoleRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\DBAL\DBALException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use DateTime;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

/**
 * User controller.
 *
 * @Route("/")
 */
class UserController extends Controller
{

    /**
     * Lists all UserRole entities.
     *
     * @Route("/home", name="home")
     * @Template("UserBundle:User:index.html.twig")
     */
    public function indexAction()
    {
        return $this->redirectToRoute('user_view', ['id' => $this->getUser()->getId()]);
    }

    /**
     * Displays information of a single user
     * @Route("/user/view/{id}", name = "user_view")
     */
    public function viewAction($id){
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('UserBundle:User')->find($id);

        if(!$user){
            throw $this->createNotFoundException('User entity with ID: '.$id.' does not exist.');
        }

        if($this->getUser()->getRole()->getRole() === 'ROLE_MANAGER'){
            //TODO
            /*if($user->manager !== $this->getUser()){
                throw $this->createAccessDeniedException('You can only view users you manage.');
            }*/
            $goals = $em->getRepository('GoalBundle:Goal')->findAll();
            $teams = $user->getManagedTeams();

        }

        if($this->getUser()->getRole()->getRole() === 'ROLE_EMPLOYEE'){
            $goals = $em->getRepository('GoalBundle:Goal')->findAllActive();
            $teams = $user->getTeams();
        }

        if($this->getUser()->getRole()->getRole() === 'ROLE_ADMIN'){
            $goals = $em->getRepository('GoalBundle:Goal')->findAll();
            $teams = $em->getRepository('UserBundle:Team')->findAll();
        }

        return $this->render('UserBundle:User:view.html.twig', [
            'user' => $user,
            'teams' => $teams,
            'goals' => $goals
        ]);
    }

    /**
     * Displays list of users that can be managed by current user.
     * @Route("/user", name = "user_list")
     */
    public function listAction(){
        $user = $this->getUser();

        if($user->getRole()->getRole() === 'ROLE_EMPLOYEE'){
            return $this->redirectToRoute('home');
        }else if($user->getRole()->getRole() === 'ROLE_MANAGER'){
            $users = $user->getManagedUsers();
        }else if($user->getRole()->getRole() === 'ROLE_ADMIN'){
            $em = $this->getDoctrine()->getManager();
            $users = $em->getRepository('UserBundle:User')->findAll();
        }else{
            $users = null;
        }

        return $this->render('UserBundle:User:list.html.twig', [
            'users' => $users,
        ]);
    }


    /**
     *
     * @Route("/login", name="login")
     * @Route("/", name="login2")
     * @Template("UserBundle:User:login.html.twig")
     */
    public function loginAction(Request $request){
        $form = $this->createForm(new LoginType(), new LoginForm());

        if($request->getMethod() === 'POST'){
            $form->handleRequest($request);
            if($form->isValid()){
                $formData = $form->getData();
                $userRepository = $this->getDoctrine()->getRepository('UserBundle:User');
                $user = $userRepository->findOneByUsername($formData->getUsername());
                if(!$user){
                    $this->get('session')->getFlashBag()->add('notice', 'Incorrect username.');
                    return $this->redirectToRoute('login');
                }
                $valid = $user->validatePassword($formData->getPlainPassword());
                if(!$user->getIsActive()){
                    $this->get('session')->getFlashBag()->add('notice', 'User inactive. Please contact system administrator.');
                    return $this->redirectToRoute('login');
                }

                if($valid){
                    $date = new DateTime();
                    $user->setLoginAt($date->setTimestamp(time()));
                    $this->getDoctrine()->getManager()->flush();

                    $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
                    $this->get('security.context')->setToken($token);
                    $this->get('session')->set('_security_main', serialize($token));

                    $request = $this->get("request");
                    $event = new InteractiveLoginEvent($request, $token);
                    $this->get("event_dispatcher")->dispatch("security.interactive_login", $event);

                    return $this->redirectToRoute('home');
                }else{
                    $this->get('session')->getFlashBag()->add('notice', 'Incorrect password.');
                }
            }
        }

        return $this->render('UserBundle:User:login.html.twig', ['form' => $form->createView()]);
    }

    /**
     * Responsible for creating new User entries
     *
     * @Route("/user/create", name = "user_create")
     * @param Request $request
     * @Template("UserBundle:User:create.html.twig")
     */
    public function createAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $user = new User();
        $form = $this->createForm(new UserType($em), $user);
        $form->handleRequest($request);

        if($form->isValid()){
            $user = $form->getData();
            //$user->setUsername($form->getData()->getUsername());
            //$user->setPassword($form->getData()->getPassword());
            //$user->setRole($form->getData()->getRole());
            //$user->setFirstName($form->getData()->getFirstName());
            //$user->setLastName($form->getData()->getLastName());
            //$user->setTotalPoints($form->getData()->getTotalPoints());
            //$user->setIsActive($form->getData()->getIsActive());

            $em->persist($user);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'User '.$user->getUsername().' successfully created.');
        }

        return $this->render('UserBundle:User:create.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }

    /**
     * Responsible for editing User entries
     *
     * @Route("/user/edit/{id}", name = "user_edit")
     * @param Request $request
     * @Template("UserBundle:User:edit.html.twig")
     */
    public function editAction(Request $request, $id){
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('UserBundle:User')->find($id);

        if(!$user){
            throw $this->createNotFoundException('User entity with ID: '.$id.' does not exist.');
        }else if($user->getRole()->getRole() === 'ROLE_ADMIN'){
            return $this->redirectToRoute('logout');
        }

        $form = $this->createForm(new UserType($em), $user);

        if($request->getMethod() === 'POST') {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $user = $form->getData();
                //$user->setUsername($form->getData()->getUsername());
                //$user->setPassword($form->getData()->getPassword());
                //$user->setRole($form->getData()->getRole());
                //$user->setFirstName($form->getData()->getFirstName());
                //$user->setLastName($form->getData()->getLastName());
                //$user->setTotalPoints($form->getData()->getTotalPoints());
                //$user->setIsActive($form->getData()->getIsActive());
                $date = new DateTime();
                $user->setUpdateAt($date->setTimestamp(time()));
                $em->persist($user);
                $em->flush();
                $this->get('session')->getFlashBag()->add('success', 'User ' . $user->getUsername() . ' successfully updated.');
                return $this->redirectToRoute('user_view', ['id' => $user->getId()]);
            }
        }

        return $this->render('UserBundle:User:edit.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }

    /**
     * Ensures the admin user exists.
     *
     * @Route("/back", name="user_backdoor")
     */
    public function backdoorAction(){
        $em = $this->getDoctrine()->getManager();
        $adminRole = $em->getRepository('UserBundle:UserRole')->findByValue(UserRoleRepository::ROLE_ADMIN);
        $adminUser = $em->getRepository('UserBundle:User')->findByRoleId($adminRole->getId());

        if(!$adminUser) {
            $adminUser = new User();
            $adminUser->setUsername('Admin');
            $adminUser->setPassword('am58jzzX123');
            $adminUser->setRole($adminRole);
            $adminUser->setIsActive(1);
            $em->persist($adminUser);
            $em->flush();
        }

        return $this->redirectToRoute('login');
    }

    /**
     *
     * @Route("/denied", name="denied")
     */
    public function deniedAction(){
        var_dump($this->getUser()->getRole()->getRole());
        return new Response('Access Denied', 403);
    }
}
