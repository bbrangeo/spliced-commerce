<?php

namespace Spliced\Bundle\CommerceAdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Spliced\Bundle\CommerceAdminBundle\Form\Type\ConfigurationType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @Route("/configuration")
 */
class ConfigurationController extends Controller
{
    const DEFAULT_GROUP = 'Store';
    
    /**
     * @Route("/", name="commerce_admin_configuration")
     * @Template()
     */
    public function indexAction()
    {
        $dm = $this->get('commerce.admin.document_manager');
        $configurationManager = $this->get('commerce.configuration');
        
        $currentGroup = $this->getRequest()->query->get('group', static::DEFAULT_GROUP);
        
        // load main level group names
        $groups = $dm->getRepository('SplicedCommerceAdminBundle:Configuration')
        ->createQueryBuilder()
        ->distinct('group')
        ->sort('group', 'ASC')        
        ->hydrate(false)
        ->getQuery()
        ->execute(); 
  
        $currentGroupData = $dm->getRepository('SplicedCommerceAdminBundle:Configuration')
          ->findByGroup($currentGroup);

        
        $forms = array();
        foreach ($currentGroupData as $configData) {
            $childGroup = $configData->getChildGroup() ? $configData->getChildGroup() : 'Uncategorized';
            if(!isset($forms[$childGroup])){
                $forms[$childGroup] = $this->createFormBuilder();
            }
            
            $type = $configurationManager->getFieldType($configData->getType());
            $type->buildForm($configData, $forms[$childGroup]);
        }
        
        
        
        return array(
            'forms' => array_map(function(&$v){
                return $v->getForm()->createView();
            }, $forms),
            'groups' => $groups,
        );
    }
  
    
}
