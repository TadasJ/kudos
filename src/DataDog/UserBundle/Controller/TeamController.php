<?php

namespace DataDog\UserBundle\Controller;

use DataDog\UserBundle\Entity\Team;
use DataDog\UserBundle\Entity\User;
use DataDog\UserBundle\Form\Type\TeamType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\DBAL\DBALException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use DateTime;

/**
 * Team controller.
 *
 * @Route("/")
 */
class TeamController extends Controller
{

    /**
     * Displays information of a single team
     * @Route("/team/view/{id}", name = "team_view")
     */
    public function viewAction($id){
        $em = $this->getDoctrine()->getManager();
        $team = $em->getRepository('UserBundle:Team')->find($id);

        if(!$team){
            throw $this->createNotFoundException('Team entity with ID: '.$id.' does not exist.');
        }

        if($this->getUser()->getRole()->getRole() === 'ROLE_EMPLOYEE') {
            $found = false;
            foreach ($team->getUsers() as $user) {
                if ($user === $this->getUser()) {
                    $found = true;
                    break;
                }
            }
            if(!$found) {
                throw $this->createAccessDeniedException('You do not have permission to view this team.');
            }
        }

        return $this->render('UserBundle:Team:view.html.twig', [
            'team' => $team,
        ]);
    }

    /**
     * Displays list of teams, individual for each user type.
     * @Route("/team", name = "team_index")
     */
    public function indexAction(){
        $user = $this->getUser();

        if($user->getRole()->getRole() === 'ROLE_EMPLOYEE'){
            $teams = $user->getTeams();
        }else if($user->getRole()->getRole() === 'ROLE_MANAGER'){
            $teams = $user->getManagedTeams();
        }else if($user->getRole()->getRole() === 'ROLE_ADMIN'){
            $em = $this->getDoctrine()->getManager();
            $teams = $em->getRepository('UserBundle:Team')->findAll();
        }else{
            $teams = null;
        }

        return $this->render('UserBundle:Team:table.html.twig', [
            'teams' => $teams,
        ]);
    }

    /**
     * Responsible for creating new Team entries
     *
     * @Route("/team/create", name = "team_create")
     * @param Request $request
     * @Template("UserBundle:Team:create.html.twig")
     */
    public function createAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $team = new Team();
        $form = $this->createForm(new TeamType($em), $team);
        $form->handleRequest($request);

        if($form->isValid()){
            $team = $form->getData();
            //$user->setUsername($form->getData()->getUsername());
            //$user->setPassword($form->getData()->getPassword());
            //$user->setRole($form->getData()->getRole());
            //$user->setFirstName($form->getData()->getFirstName());
            //$user->setLastName($form->getData()->getLastName());
            //$user->setTotalPoints($form->getData()->getTotalPoints());
            //$user->setIsActive($form->getData()->getIsActive());

            $em->persist($team);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'Team '.$team->getName().' successfully created.');
        }

        return $this->render('UserBundle:Team:create.html.twig', [
            'form' => $form->createView(),
            'team' => $team,
        ]);
    }

    /**
     * Responsible for editing Tean entries
     *
     * @Route("/team/edit/{id}", name = "team_edit")
     * @param Request $request
     * @Template("UserBundle:Team:edit.html.twig")
     */
    public function editAction(Request $request, $id){
        $em = $this->getDoctrine()->getManager();
        $team = $em->getRepository('UserBundle:Team')->find($id);

        if(!$team){
            throw $this->createNotFoundException('User entity with ID: '.$id.' does not exist.');
        }

        $form = $this->createForm(new TeamType($em), $team);

        if($request->getMethod() === 'POST') {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $team = $form->getData();
                //$user->setUsername($form->getData()->getUsername());
                //$user->setPassword($form->getData()->getPassword());
                //$user->setRole($form->getData()->getRole());
                //$user->setFirstName($form->getData()->getFirstName());
                //$user->setLastName($form->getData()->getLastName());
                //$user->setTotalPoints($form->getData()->getTotalPoints());
                //$user->setIsActive($form->getData()->getIsActive());
                $date = new DateTime();
                $team->setUpdateAt($date->setTimestamp(time()));
                $em->persist($team);
                $em->flush();
                $this->get('session')->getFlashBag()->add('success', 'Team ' . $team->getName() . ' successfully updated.');
            }
        }

        return $this->render('UserBundle:Team:edit.html.twig', [
            'form' => $form->createView(),
            'user' => $team,
        ]);
    }
}
