<?php 
namespace Acme\JobeetBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Validator\ErrorElement;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Acme\JobeetBundle\Entity\Job;

class JobAdmin extends Admin
{
    // setup the defaut sort column and order
    protected $datagridValues = array(
        '_sort_order' => 'DESC',
        '_sort_by' => 'created_at'
    );

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('Category')
            ->add('type', 'choice', array('choices' => Job::getTypes(), 'expanded' => true))
            ->add('company')
            ->add('file', 'file', array('label' => 'Company logo', 'required' => false))
            ->add('url')
            ->add('position')
            ->add('location')
            ->add('description')
            ->add('how_to_apply')
            ->add('is_public')
            ->add('email')
            ->add('is_activated')
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('Category')
            ->add('company')
            ->add('position')
            ->add('description')
            ->add('is_activated')
            ->add('is_public')
            ->add('email')
            ->add('expires_at')
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('company')
            ->add('position')
            ->add('location')
            ->add('url')
            ->add('is_activated')
            ->add('email')
            ->add('Category')
            ->add('expires_at')
            ->add('_action', 'actions', array(
                'actions' => array(
                    'view' => array(),
                    'edit' => array(),
                    'delete' => array(),
                )
            ))
        ;
    }

    protected function configureShowField(ShowMapper $showMapper)
    {
        $showMapper
            ->add('Category')
            ->add('type')
            ->add('company')
            ->add('webPath', 'string', array('template' => 'IbwJobeetBundle:JobAdmin:list_image.html.twig'))
            ->add('url')
            ->add('position')
            ->add('location')
            ->add('description')
            ->add('how_to_apply')
            ->add('is_public')
            ->add('is_activated')
            ->add('token')
            ->add('email')
            ->add('expires_at')
        ;
    }

    // Override the getBatchAction from admin class
    // Allows to set more actions on a set of selected models
    public function getBatchActions()
    {
    	// Retrieve the default (currently only the delete action) actions
    	$actions = parent::getBatchActions();

    	// Check user permissions
    	if($this->hasRoute('edit') && $this->isGranted('EDIT') && $this->hasRoute('delete') && $this->isGranted('DELETE'))
    	{
    		$actions['extend'] = array(
    			'label' => 'Extend',
    			'ask_confirmation' => true
    			// If tru,e a confirmation will be asked before performing the action
    			);

    		$actions['deleteNeverActivated'] = array(
    			'label' => 'Delete never activated jobs',
    			'ask_confirmation' => true
    			);
    	}

    	return $actions;
    }

    public function batchActionDeleteNeverActivatedIsRelevant()
    {
    	return true;
    }

    public function batchActionDeleteNeverActivated()
    {
    	if ($this->admin->isGranted('EDIT') === false || $this->admin->isGranted('DELETE') === false ) {
    		throw new AccessDeniedException();
    	}

    	$em = $this->getDoctrine()->getManager();
    	$nb = $em->getRepository('AcmeJobeetBundle:Job')->cleanup(60);

    	if($nb) {
    		$this->get('session')->getFlashBag()->add('sonata_flash_success',
    			sprintf('%d never activated jobs have been deleted successfully.', $nb));
    	} else {
    		$this->get('session')->getFlashBag()->add('sonata_flash_info',
    			'No job to delete');
    	}

    	return new RedirectResponse($this->admin->generateUrl('list', $this->admin->getFilterParameters()));
    }




}