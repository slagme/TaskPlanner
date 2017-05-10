<?php

namespace MainBundle\Controller;

use MainBundle\Entity\Task;
use MainBundle\Form\TaskType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Task controller.
 *
 *
 */
class TaskController extends Controller
{
    /**
     * Lists all task entities.
     *
     * @Route("/", name="task_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $tasks = $em->getRepository('MainBundle:Task')->findAll();

        return $this->render('task/index.html.twig', array(
            'tasks' => $tasks,
        ));
    }

    /**
     * Finds and displays a task entity.
     *
     * @Route("/{id}", name="task_show")
     * @Method("GET")
     */
    public function showAction(Task $task)
    {

        return $this->render('task/show.html.twig', array(
            'task' => $task,
        ));
    }

    /**
     * @Route ("/profile/{id}/addTask" , name="task_add_Form")
     * @Method ("GET")
     * @Template("task/new.html.twig")
     */

    public function addTaskFormAction($id)
    {
        $em=$this->getDoctrine()->getManager();
        $user=$em->getRepository('MainBundle:User')->findOneBy(['id'=> $id]);


        if (!$user){
            throw $this->createNotFoundException('User not found');
        }

        $task = new Task();

        $formTask = $this->createForm(TaskType::class, $task,
            ['action' => $this->redirectToRoute('addTask', ['id' => $id])]);

        return ['formTask' => $formTask->createView()];
    }

    /**
     * @Route("/profile/{id}/addTask", name="addTask")
     * @Method("POST")
     * @Template("task/new.html.twig")
     */

    public function addTaskAction(Request $request, $id)
    {
        $em=$this->getDoctrine()->getManager();
        $user=$em->getRepository('MainBundle:User')->find($id);


        if (!$user){
            throw $this->createNotFoundException('User not found');
        }

        $task= new Task();

        $formTask = $this->createForm(TaskType::class, $task);
        $formTask->handleRequest($request);

        if ($formTask->isSubmitted() && $formTask->isValid()) {
            $task->setUser($user);
            $user->addTask($task);

            $em->persist($task);
            $em->flush();
        }
        return $this->redirectToRoute('main_user_show', ['id' => $id]);

    }
}