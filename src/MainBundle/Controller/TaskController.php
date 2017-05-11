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

//    /**
//     * Finds and displays a task entity.
//     *
//     * @Route("/{id}", name="task_show")
//     * @Method("GET")
//     */
//    public function showAction(Task $task)
//    {
//
//        return $this->render('task/show.html.twig', array(
//            'task' => $task,
//        ));
//    }

    /**
     * @Route ("/profile/{id}/addTask" , name="task_add_Form")
     * @Method ("GET")
     * @Template("task/new.html.twig")
     */

    public function addTaskFormAction($id)
    {
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
     * @Route("profile/{id}/edit", name="task_edit")
     * @Method({"GET", "POST"})
     */

    public function editTaskAction(Request $request, $id)
    {
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
     * Deletes a offer entity.
     *
     * @Route("/{id}", name="task_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, task $task)
    {
        $form = $this->createDeleteForm($task);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($task);
            $em->flush();
        }
        return $this->redirectToRoute('offer_index');
    }


    public function getUser()
    {
        return parent::getUser();
    }

}