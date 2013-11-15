<?php

namespace Acme\JobeetBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Acme\JobeetBundle\Entity\Job;
use Acme\JobeetBundle\Form\JobType;

/**
 * Job controller.
 *
 */
class JobController extends Controller
{

    /**
     * Lists all Job entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        // El codigo de doctrine no corresponde a la capa del controlador
        /*
        $query = $em->createQuery(
            'SElECT j FROM AcmeJobeetBundle:Job j WHERE j.expires_at > :date')
        ->setParameter('date', date('Y-m-d H:i:s', time() - 86400 * 30));
        $entities = $query->getResult();
        */

        //$entities = $em->getRepository('AcmeJobeetBundle:Job')->getActiveJobs();

        $categories = $em->getRepository('AcmeJobeetBundle:Category')
            ->getWithJobs();

        foreach($categories as $category) {
            $category->setActiveJobs($em->getRepository('AcmeJobeetBundle:Job')
                ->getActiveJobs($category->getId(), 
                $this->container->getParameter('max_jobs_on_homepage')));

            $category->setMoreJobs($em->getRepository('AcmeJobeetBundle:Job')
                ->countActiveJobs($category->getId()) - $this->container
                ->getParameter('max_jobs_on_homepage'));
        }

        return $this->render('AcmeJobeetBundle:Job:index.html.twig', array(
            'categories' => $categories,
        ));
    }
    /**
     * Creates a new Job entity.
     * Processes the form (validation, form repopulation) and updates an
     * existing job with the user submitted values
     */
    public function createAction(Request $request)
    {
        $entity = new Job();
        //$form = $this->createCreateForm($entity);
        //$form->handleRequest($request);
        $form = $this->createCreateForm(new JobType(), $entity);
        
        // The form is bound
        // El formulario se liga a la clase
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            //return $this->redirect($this->generateUrl('job_show', array('id' => $entity->getId())));
            return $this->redirect($this->generateUrl('job_preview', array(
                'company' => $entity->getCompanySlug(),
                'location' => $entity->getLocationSlug(),
                'token' => $entity->getToken(),
                'position' => $entity->getPositionSlug())));

        }

        return $this->render('AcmeJobeetBundle:Job:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
    * Creates a form to create a Job entity.
    *
    * @param Job $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Job $entity)
    {
        $form = $this->createForm(new JobType(), $entity, array(
            'action' => $this->generateUrl('job_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Job entity.
     * - Displays a blank form to create a new job
     */
    public function newAction()
    {
        $entity = new Job();
        // We set a default value for type
        $entity->setType('full-time');
        $form   = $this->createForm(new JobType(), $entity);

        return $this->render('AcmeJobeetBundle:Job:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Job entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AcmeJobeetBundle:Job')->getACtiveJob($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Job entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('AcmeJobeetBundle:Job:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing Job entity.
     * 
     */
    public function editAction($token)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AcmeJobeetBundle:Job')->findOneByToken($token);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Job entity.');
        }

        if ($entity->getIsActivated()) {
            throw $this->createNotFoundException('Job is activated and cannot be edited');
        }

        $editForm = $this->createForm(new JobType(), $entity);
        $deleteForm = $this->createDeleteForm($token);

        return $this->render('AcmeJobeetBundle:Job:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Job entity.
    *
    * @param Job $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Job $entity)
    {
        $form = $this->createForm(new JobType(), $entity, array(
            'action' => $this->generateUrl('job_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Job entity.
     * Processes the form (validation, form repopulation) and updates an 
     * existing job with the user submitted values
     */
    public function updateAction(Request $request, $token)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AcmeJobeetBundle:Job')->findOneByToken($token);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Job entity.');
        }

        $deleteForm = $this->createDeleteForm($token);
        $editForm = $this->createForm(new JobType(), $entity);

        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('job_preview', array(
                'company' => $enitty->getCompanySlug(),
                'location' => $entity->getLocationSlug(),
                'token' => $token,
                'position' => $entity->getPositionSlug())));
        }

        return $this->render('AcmeJobeetBundle:Job:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Job entity.
     *
     */
    public function deleteAction(Request $request, $token)
    {
        $form = $this->createDeleteForm($token);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AcmeJobeetBundle:Job')->findOneByToken($token);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Job entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('job'));
    }

    /**
     * Creates a form to delete a Job entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($token)
    {
        /*return $this->createFormBuilder()
            ->setAction($this->generateUrl('job_delete', array('token' => $token)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;*/
        return $this->createFormBuilder(array('token' => $token))
                ->add('token', 'hidden')
                ->getForm();
    }

    public function previewAction($token)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AcmeJobeetBundle:Job')->findOneByToken($token);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Job entity');
        }

        $deleteForm = $this->createDeleteForm($entity->getToken());
        $publishFOrm = $this->createPublishForm($entity->getToken());
        $extendForm = $this->createExtendForm($entity->getToken());

        return $this->render('AcmeJobeetBundle:Job:show.html.twig', array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
            'publish_form' => $publishForm->createView(),
            'extend_form' => $extendForm->createView()
            ));
    }

    public function publishAction(Request $request, $token)
    {
        $form = $this->createPublishForm($token);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AcmeJobeetBundle:Job')->finOneByToken($token);

            if(!entity) {
                throw $this->createNotFoundException('Unable to find Job entity');
            }

            $entity->publish();
            $em->persist();
            $em->flush();

            $this->get('session')->getFlashBag()->add('notice', 'Your job is now online for 30 days');
        }

        return $this->redirect($this->generate('job_preview', array(
            'company' => $entity->getCompanySlug(),
            'location' => $entity->getLocationSlug(),
            'token' => $entity->getToken(),
            'position' => $entity->getPositionSlug())));
    }

    private function createPublishForm($token)
    {
        return $this->createFormBuilder(array('token' => $token))
                    ->add('token', 'hidden')
                    ->getForm();
    }

    public function extendAction(Request $request, $token) 
    {
        $form = $this->createExtendForm($token);
        $request = $this->getRequest();

        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AcmeJobeetBundle:Job')->findOneByToken($token);

            if (!$entity) {
                throw $this->createNewFoundException('Unable to find job entity.')
            }

            if(!$entity->extend()) {
                throw $this->createNotFoundException('Unable to extend the job');
            }

            $em->persist($entity);
            $em->flush();

            $this->get('session')->getFlashBag()->add('notice', sprintf('Your job validity has been extended until %s', $entity->getExpiresAt()->format('d/m/Y')));
        }

        return $this->redirect($this->generateUrl('job_preview', array(
            'company' => $entity->getCompanySlug(),
            'location' => $entity->getLocationSlug(),
            'token' => $entity->getToken(),
            'position' => $entity->getPositionSlug()
            )));
    }

    private function createExtendForm($token)
    {
        return $this->createFormBuilder(array('token' => $token))
                    ->add('token', 'hidden')
                    ->getForm();
    }

}
