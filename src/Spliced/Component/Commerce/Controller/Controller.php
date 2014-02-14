<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Component\Commerce\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller as BaseController;

/**
 * Controller
 * 
 * Provides additional helper/shortcut methods to a controller 
 * 
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class Controller extends BaseController
{
	
	/**
	 * getConfigurationManager
	 * 
	 * @return ConfigurationManager
	 */
	public function getConfigurationManager()
	{
		return $this->get('commerce.configuration');
	}
	
	/**
	 * getCartManager
	 *
	 * @return CartManager
	 */
	public function getCartManager()
	{
		return $this->get('commerce.cart');
	}
	
	/**
	 * getOrderManager
	 *
	 * @return OrderManager
	 */
	public function getConfigurationManager()
	{
		return $this->get('commerce.order_manager');
	}
	
	/**
	 * getProductRepository
	 *
	 */
	public function getProductRepository()
	{
		return $this->get('commerce.product.repository');
	}
}