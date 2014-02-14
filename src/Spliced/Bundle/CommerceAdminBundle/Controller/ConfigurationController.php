<?php

namespace Spliced\Bundle\CommerceAdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Spliced\Bundle\CommerceAdminBundle\Form\Type\ConfigDataType;

/**
 * @Route("/configuration")
 */
class ConfigurationController extends Controller
{
    /**
     * @Route("/", name="commerce_admin_configuration")
     * @Template()
     */
    public function indexAction()
    {
        $configurationData = $this->getDoctrine()->getManager()
          ->getRepository('SplicedCommerceAdminBundle:ConfigData')
          ->findAll();
        
        $forms = $this->createForms($configurationData);
        
        return array(
            'forms' => $forms,
        );
    }
  
    /**
     * createForms
     */
    protected function createForms($configurationData)
    {
        // must be a better way to do this
        
        $forms = array();
        // level 1
        foreach($configurationData as $configData) {
            if(!strlen($configData->getConfigGroup())){
                continue;
            }
            $groups = explode('/', $configData->getConfigGroup());
            $forms[$groups[0]][] = $configData;
        }
        // level 2
        foreach($forms as $groupName => $groupItems){
            $tmp = array();
            foreach($groupItems as $groupItem){
                $groups = explode('/', $groupItem->getConfigGroup());
                if(2 <= count($groups)){
                    $tmp[$groups[1]][] = $groupItem;
                } else {
                   $tmp[] = $this->createForm(new ConfigDataType($groupItem), $groupItem)->createView();
                   //$tmp[$groupItem->getKey()] = $groupItem->getKey();
                }
            }
            $forms[$groupName] = $tmp;
        }

        // level 3
        foreach($forms as $groupTopName => $groupTopItems){
            if(is_array($groupTopItems)){
                foreach($groupTopItems as $groupTopItemName => $groupTopItem){
                    if(is_array($groupTopItem)){
                        $tmp = array();
                        foreach($groupTopItem as $groupKey => $groupItem){
                            $groups = explode('/', $groupItem->getConfigGroup());
                            if(3 <= count($groups)){
                                $tmp[$groups[2]][] = $this->createForm(new ConfigDataType($groupItem), $groupItem)->createView();
                                //$tmp[$groups[2]][$groupItem->getKey()] = $groupItem->getKey();
                            } else {
                                $tmp[] = $this->createForm(new ConfigDataType($groupItem), $groupItem)->createView();
                                //$tmp[$groupItem->getKey()] = $groupItem->getKey();
                            }
                        }
                        $forms[$groupTopName][$groupTopItemName] = $tmp;
                    }
                    
                }
            }  
        }

        ksort($forms); 
        return $forms;
    }
    
    
}
