<?php

namespace Spliced\Bundle\CommerceAdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Spliced\Bundle\CommerceAdminBundle\Document\CmsPage;
use Spliced\Bundle\CommerceAdminBundle\Model\ListFilter;
use Spliced\Bundle\CommerceAdminBundle\Form\Type\CmsPageFilterType;
use Spliced\Component\Commerce\Event as Events;

/**
 * CmsPageController
 *
 * @Route("/cms/page")
 */
class CmsPageController extends BaseFilterableController
{
    const FILTER_TAG = 'commerce.cms_page';
    const FILTER_FORM = 'Spliced\Bundle\CommerceAdminBundle\Form\TypeCmsPageFilterType';
    
    /**
     * @Route("/", name="commerce_admin_cms_page")
     * @Method("GET")
     * @Template()
     */
    public function listAction()
    {
		// load products
        $pages = $this->get('knp_paginator')->paginate(
            $this->get('commerce.admin.document_manager')
        	  ->getRepository('SplicedCommerceAdminBundle:CmsPage')
        	  ->getAdminListQuery($this->getFilters()),
            $this->getRequest()->query->get('page',1),
            $this->getRequest()->query->get('limit',25)
        );
         
        $filterForm = $this->createForm(new CmsPageFilterType());

        return array(
            'pages' => $pages,
            'filterForm' => $filterForm->createView(),
        );
    }

    /**
     * @Route("/new", name="commerce_admin_cms_page_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {

        $form = $this->get('commerce.admin.form_factory')->createCmsPageForm();

        return array(
            'form'   => $form->createView(),
        );
    }

    /**
     * @Route("/save", name="commerce_admin_cms_page_save")
     * @Method("POST")
     * @Template("SplicedCmsBundle:CmsPage:new.html.twig")
     */
    public function saveAction(Request $request)
    {
    	$form = $this->get('commerce.admin.form_factory')->createCmsPageForm();

    	if ($form->bind($request) && $form->isValid()) {
    	    
    	    $this->get('event_dispatcher')->dispatch(
    	        Events\Event::EVENT_CMS_PAGE_SAVE,
    	        new Events\CmsPageEvent($form->getData())
    	    );
    
    		$this->get('session')->getFlashBag()->add('success', 'CMS Page Successfully Added');
    		return $this->redirect($this->generateUrl('commerce_admin_cms_page'));
    	}
    
    	$this->get('session')->getFlashBag()->add('error', 'There was an error validating your data.');
    	
    	return array(
    	    'form'   => $form->createView(),
    	);
    }
    
    /**
     * @Route("/edit/{id}", name="commerce_admin_cms_page_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $page = $this->get('commerce.admin.document_manager')
          ->getRepository('SplicedCommerceAdminBundle:CmsPage')
          ->findOneById($id);
		
        if(!$page) {
			throw $this->createNotFoundException('Unable to find CmsPage.');
		}

        $form = $this->get('commerce.admin.form_factory')
          ->createCmsPageForm($page);
        
        $deleteForm = $this->createDeleteForm($id);
        
        return array(
            'page'    => $page,
            'form'   => $form->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * @Route("/update/{id}", name="commerce_admin_cms_page_update")
     * @Method({"POST","PUT"})
     * @Template()
     */
    public function updateAction(Request $request, $id)
    {
        $page = $this->get('commerce.admin.document_manager')
          ->getRepository('SplicedCommerceAdminBundle:CmsPage')
          ->findOneById($id);
		
        if(!$page) {
			throw $this->createNotFoundException('Unable to find CmsPage.');
		}

        $deleteForm = $this->createDeleteForm($id);
        $form = $this->get('commerce.admin.form_factory')->createCmsPageForm($page);
        

        if ($form->bind($request) && $form->isValid()) {
            $this->get('commerce.admin.document_manager')->persist($page);
            $this->get('commerce.admin.document_manager')->flush();

            return $this->redirect($this->generateUrl('commerce_admin_cms_page_edit', array('id' => $id)));
        }

        return array(
            'page'   => $page,
            'form'   => $form->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * @Route("/{id}", name="commerce_admin_cms_page_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $page = $this->get('commerce.admin.document_manager')
            ->getRepository('SplicedCommerceAdminBundle:CmsPage')
            ->findOneById($id);

            if (!$page) {
                throw $this->createNotFoundException('Unable to find CmsPage.');
            }

            $this->get('commerce.admin.document_manager')->remove($page);
            $this->get('commerce.admin.document_manager')->flush();
        }

        return $this->redirect($this->generateUrl('commerce_admin_cms_page'));
    }

    /**
     * Creates a form to delete a CmsPage entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }

    /**
     * Filters the list of entities for CmsPage entity.
     *
     * @Route("/filter", name="commerce_admin_cms_page_filter")
     * @Method("POST")
     * @Template() 
     */
    public function filterAction()
    {
        $session = $this->get('session');
        $form   = $this->createForm(new CmsPageFilterType());
        $filters = array();
        
        if($form->bindRequest($this->getRequest()) && $form->isValid()) {
            $filters = $form->getData();
            
            $session->set('filter.cms_page_admin', serialize($filters));
        }
        

        $this->get('session')->getFlashBag()->add('notice', 'CmsPage Filters Updated');
        return $this->redirect($this->generateUrl('commerce_admin_cms_page'));
    }


    /**
     * Clears the currently applied filters
     * @Route("/filter/reset", name="cms_page_admin_filter_reset")
     * @return array
     */
    public function clearFiltersActions()
    {
        $filters = new ListFilter();
        $this->get('session')->setFilters($filters);
        $this->get('session')->getFlashBag()->add('notice', 'CmsPage Filters Cleared');
        return $this->redirect($this->generateUrl('cms_page_admin'));
    }
    
    /**
     * Deletes a CmsPage entity.
     *
     * @Route("/batch", name="cms_page_admin_batch")
     * @Method("POST")
     */
    public function batchAction()
    {
        $ids = $this->getRequest()->request->get('ids');
        $action = $this->getRequest()->request->get('action');
        $methodName = 'batch'.ucwords($action);

        if(method_exists($this,$methodName)) {
        	return call_user_func($this, $methodName, $ids);
        }
        
        throw new \InvalidArgumentException(sprintf('Method %s does not exist',$methodName));
    }
    

	/**
	* batchDelete
	* 
	* @param array $ids
	*/
	protected function batchDelete(array $ids)
	{
		$entities = $em->getRepository('SplicedCmsBundle:CmsPage')->findById($id);
		
		$count = count($entities);
		
		foreach($entities as $entity) {
			$em->remove($entity);
		}
		
		try{
			$em->flush();
			$this->get('session')->getFlashBag()->add('success', sprintf('Successfully deleted %s records.', $count));
		} catch( \Exception $e) {
			$this->get('session')->getFlashBag()->add('error', sprintf('Error deleting Records. Error: %s', $e->getMessage()));
		}
		
		return $this->redirect($this->generateUrl('cms_page_admin'));
	}

}
