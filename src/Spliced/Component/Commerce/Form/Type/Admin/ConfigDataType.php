<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Component\Commerce\Form\Type\Admin;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Spliced\Component\Commerce\Model\ConfigDataInterface;

/**
 * ConfigDataType
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class ConfigDataType extends AbstractType
{
    protected $configData;
    
    /**
     * Constructor
     */
    public function __construct(ConfigDataInterface $configData)
    {
        $this->configData = $configData;
    }
    
    /**
     * getConfigData
     * 
     * @return ConfigDataInterface
     */
    protected function getConfigData()
    {
        return $this->configData;    
    }
    
    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $fieldParams = array(
            'required' => $this->getConfigData()->getIsRequired(),
             'label' => $this->getConfigData()->getConfigLabel() ? $this->getConfigData()->getConfigLabel() : $this->getConfigData()->getKey(),       
        );
        
        switch($this->getConfigData()->getType()){
            case 'html':
                $fieldType = 'textarea';
                break;
            case 'string':
                $fieldType = 'text';
                break;
            case 'string':
                $fieldType = 'email';
                break;
            case 'float':
            case 'integer':
                $fieldType = 'number';
                break;
            case 'boolean':
                $fieldType = 'choice';
                $fieldParams = array_merge(
                    $fieldParams, 
                    array('choices' => array( 0 => 'No', 1 => 'Yes'))
                );
                break;
            case 'status':
                $fieldType = 'choice';
                $fieldParams = array_merge(
                    $fieldParams,
                    array('choices' => $this->getStatusChoices())
                );
                break;
            case 'statuses':
                $fieldType = 'choice';
                $fieldParams = array_merge(
                    $fieldParams,
                    array('choices' => $this->getStatusChoices(), 'multiple' => true)
                );
                break;
            case 'countries':
                $fieldType = 'country';
                $fieldParams = array_merge(
                    $fieldParams,
                    array('multiple' => true)
                );
                break;
            
            case 'array': //TODO
                $fieldType = 'collection';
                $fieldParams = array_merge(
                    $fieldParams,
                    array('type' => 'text')
                );
                break;
            case 'encrypted': //TODO
            case 'string':
                $fieldType = 'text';
                break;
            default:
                $fieldType = 'text';
                break;
        }
        
        $builder->add('value', $fieldType, $fieldParams);
    }


    /**
     * getStatusChoices
     */
    protected function getStatusChoices()
    {
        $return = array();
        
        try{
            $classInfo = new \ReflectionClass('Spliced\Component\Commerce\Model\OrderInterface');
        } catch(\Exception $e) {
            return array('ERROR' => 'ERROR');
        }
        
        foreach($classInfo->getConstants() as $constant => $constantValue) {
            if(preg_match('/^STATUS\_/', $constant)) {
                $return[$constantValue] = ucwords(strtolower(str_replace(array('STATUS_','_'), array('',' '),$constant)));
            }
        }
        return $return;
    }
    
    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'commerce_config_data';
    }
}