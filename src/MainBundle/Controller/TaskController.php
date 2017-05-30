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
     * @Route  ("profile/{id}/task", name="task_show")
     * @Method("GET")
     */

    public function showAction(Task $task)
    {


        $deleteForm = $this->createDeleteForm($task);

        return $this->render('task/show.html.twig', array(
            'task' => $task,
            'delete_form' => $deleteForm->createView(),
        ));
    }


    /**
     * @Route ("/profile/{id}/addTask" , name="task_add_Form")
     * @Method ("GET")
     * @Template("task/new.html.twig")
     */

    public function addTaskFormAction($id)
    {
        $this->denyAccessUnlessGranted('ROLE_USER', null, 'Unable to access this page!');


        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('MainBundle:User')->findOneBy(['id' => $id]);

        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }

        $task = new Task();

        $formTask = $this->createForm(TaskType::class, $task,
            ['action' => $this->generateUrl('addTask', ['id' => $id])]);

        return ['formTask' => $formTask->createView()];
    }

    /**
     * @Route("/profile/{id}/addTask", name="addTask")
     * @Method("POST")
     * @Template("task/new.html.twig")
     */

    public function addTaskAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('MainBundle:User')->find($id);


        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }

        $task = new Task();

        $formTask = $this->createForm(TaskType::class, $task);
        $formTask->handleRequest($request);

        if ($formTask->isSubmitted() && $formTask->isValid()) {
            $task->setUser($user);
            $user->addTask($task);

            $em->persist($task);
            $em->flush();
        }

        return $this->redirectToRoute('fos_user_profile_show');
    }

    /**
     * @Route("/{id}/edit", name="task_edit")
     * @Method({"GET", "POST"})
     */

    public function editTaskAction(Request $request, Task $task)
    {
        $this->denyAccessUnlessGranted('ROLE_USER', null, 'Unable to access this page!');

        $deleteForm = $this->createDeleteForm($task);
        $editForm = $this->createForm('MainBundle\Form\TaskType', $task);

        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('task_edit', array('id' => $task->getId()));
        }

        return $this->render('task/edit.html.twig', array(
            'task' => $task,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    private function createDeleteForm(Task $task)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('task_delete', array('id' => $task->getId())))
            ->setMethod('DELETE')
            ->getForm()
            ;
    }

    /**
     * Deletes a task entity.
     *
     * @Route("/profile/{id}/deleteTask", name="task_delete")
     * @Method({"GET", "DELETE"})
     */
    public function deleteAction(Request $request, $id)
    {
        $this->denyAccessUnlessGranted('ROLE_USER', null, 'Unable to access this page!');

        $em = $this->getDoctrine()->getManager();
        $task = $em->getRepository('MainBundle:Task')->find($id);

        if (!$task) {
            throw $this->createNotFoundException('Task not found');
        }

        $em->remove($task);
        $em->flush();


        return $this->redirectToRoute('fos_user_profile_show');

    }


    public function getUser()
    {
        return parent::getUser();
    }

}