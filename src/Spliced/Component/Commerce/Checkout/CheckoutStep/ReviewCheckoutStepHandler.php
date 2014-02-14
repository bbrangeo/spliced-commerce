<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Component\Commerce\Checkout\CheckoutStep;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Spliced\Component\Commerce\Checkout\CheckoutManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Spliced\Component\Commerce\Event\CheckoutMoveStepEvent;
use Spliced\Component\Commerce\Model\OrderInterface;


/**
 * ReviewCheckoutStepHandler
 * 
 * This CheckoutStepHandler handles showing all the collected information
 * to the customer before submitting the order for processing.
 * 
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class ReviewCheckoutStepHandler extends CheckoutStepHandler
{	
	/**
	 * @var $position - Default position for this step
	 */
	protected $position = 5;

    /**
     * Constructor
     * 
     * @param CheckoutManager $checkoutManager
     * @param EngineInterface $templatingEngine
     */
    public function __construct(CheckoutManager $checkoutManager, EngineInterface $templatingEngine)
    {
    	$this->checkoutManager = $checkoutManager;
        $this->templatingEngine = $templatingEngine;
    }
	
	/**
	 * getCheckoutManager
	 *
	 * @return CheckoutManager
	 */
	public function getCheckoutManager()
	{
	    return $this->checkoutManager;
	}
	
	/**
	 * getTemplatingEngine
	 *
	 * @return SecurityContext
	 */
	public function getTemplatingEngine()
	{
	    return $this->templatingEngine;
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function getName()
	{
		return 'review';
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function getProgressBarLabel()
	{
		return 'Review';
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function buildFormOptions(OrderInterface $order, array $formOptions = array())
	{
	    return array('validation_groups' => array(
	        $this->getName(),
	    ));
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function process(FormInterface $form, Request $request)
	{
	    if($request->getMethod() == 'POST') {
	        if($form->bind($request) && $form->isValid()) {
	            $order = $form->getData();
				
	            
	            return new CheckoutMoveStepEvent(
	                $order,
	                $this->getCheckoutManager()->getCurrentStep()
	            );
	        }
	    }
	     
	    if($request->isXmlHttpRequest()) {
	        return new JsonResponse(array(
	            'success' => true,
	            'replace_many' => array(
	            '#checkout-content' => $this->getTemplatingEngine()->render('SplicedCommerceBundle:Checkout:index_content.html.twig',array(
	                'form' => $form->createView(),
	                'step' => $this->getCheckoutManager()->getCurrentStep(),
	                'step_template' => 'review',
	            ))
	        )));
	    }
	     
	    return $this->getTemplatingEngine()->renderResponse('SplicedCommerceBundle:Checkout:index.html.twig', array(
	        'form' => $form->createView(),
	        'step' => $this->getCheckoutManager()->getCurrentStep(),
	        'step_template' => 'review',
	    ));
	}
}