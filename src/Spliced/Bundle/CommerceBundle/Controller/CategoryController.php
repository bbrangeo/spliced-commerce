<?php
/*
* This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Bundle\CommerceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Spliced\Component\Commerce\Model\CategoryInterface;

/**
 * CategoryController
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class CategoryController extends Controller
{
    /**
     * @Template("SplicedCommerceBundle:Category:index.html.twig")
     * @Route("/catalog", name="commerce_catalog")
     *
     */
    public function indexAction()
    {

        $categories = $this->get('commerce.document_manager')
          ->getRepository('SplicedCommerceBundle:Category')
          ->getRoot();

        return array(
            'categories' => $categories,
        );
    }

    /**
     * @Template("SplicedCommerceBundle:Category:Blocks/list.html.twig")
     *
     * Loads all Categories with Their related children
     */
    public function listAction()
    {
        $categories = $this->get('commerce.document_manager')
          ->getRepository('SplicedCommerceBundle:Category')
          ->getRoot();

        return array(
            'categories' => $categories
        );
    }

    /**
     * @Template("SplicedCommerceBundle:Category:Blocks/menu.html.twig")
     *
     * Loads a Top Level Category Menu
     */
    public function menuAction()
    {
        $categories = $this->getDoctrine()
          ->getRepository('SplicedCommerceBundle:Category')
          ->getAllTopLevel();

        return array(
            'categories' => $categories
        );
    }

    /**
     * Loads the view for a single category, displaying children categories
     * as well as related paginated products
     *
     * @Template("SplicedCommerceBundle:Category:view.html.twig")
     * @Route("/catalog/category/view/{id}", name="commerce_category_view")
     *
     * @param int $id - Category ID to Load
     */
    public function viewAction($id)
    {
        // load category
        $category = $this->get('commerce.document_manager')
          ->getRepository('SplicedCommerceBundle:Category')
          ->findOneById($id);
        
        if(!$category){
            throw $this->createNotFoundException('Category Not Found');
        }
        
        return $this->renderCategoryView($category);
    }
    
    /**
     * Loads the view for a single category, displaying children categories
     * as well as related paginated products
     *
     * @Template("SplicedCommerceBundle:Category:view.html.twig")
     * @Route("/catalog/category/{slug}", name="commerce_category_view_by_slug")
     *
     * @param int $id - Category ID to Load
     */
    public function viewBySlugAction($slug)
    {
    	// load category
    	$category = $this->get('commerce.document_manager')
    	->getRepository('SplicedCommerceBundle:Category')
    	->findOneByUrlSlug($slug);
    
    	if(!$category){
    		throw $this->createNotFoundException('Category Not Found');
    	}
    
    	return $this->renderCategoryView($category);
    }
    
    /**
     * filterBlockAction
     *
     * @Template("SplicedCommerceBundle:Category/Blocks:filter.html.twig")
     */
    public function filterBlockAction()
    {

    }

    /**
     * getCategoryFilters
     *
     * Gets the filter preferences saved in the session
     */
    protected function getCategoryFilters()
    {
        return array_merge($this->getDefaultCategoryFilters(),$this->get('session')->get('commerce.category.filters',array()));
    }

    /**
     * setCategoryBreadcrumbs

     * @param CategoryInterface $category
     */
    protected function setCategoryBreadcrumbs(CategoryInterface $category)
    {
        $breadcrumb = $this->get('commerce.breadcrumb');

        $breadcrumb->createBreadcrumb('Catalog', 'Catalog', $this->generateUrl('commerce_catalog'), 1, true);

        // set crumbs
        if ($category->getParent()) {
            if ($category->getParent()->getParent()) {
                $breadcrumb->createBreadcrumb(
                    $category->getParent()->getParent()->getName(),
                    $category->getParent()->getParent()->getName(),
                    $category->getParent()->getParent()->getUrlSlug(),
                    2,
                    true
                );
            }
            $breadcrumb->createBreadcrumb(
                $category->getParent()->getName(),
                $category->getParent()->getName(),
                $category->getParent()->getUrlSlug(),
                3,
                true
            );
        }

        $breadcrumb->createBreadcrumb($category->getName(), $category->getName(), $category->getUrlSlug(), 4, true);

    }

    /**
     * setCategoryFilters
     * Sets the filter preferences saved in the session
     * @param array $filters
     */
    protected function setCategoryFilters(array $filters)
    {
        $this->get('session')->set('commerce.category.filters',array_merge($this->getCategoryFilters(),$filters));

        return $this;
    }

     /**
     *
     */
    protected function getDefaultCategoryFilters()
    {
        return array(
            'perPage' => 10,
            'orderChoices' => 'price_asc',
            'listMethod' => 'list'
        );
    }
    
    /**
     * @Route("/categories/update-attribute-fiter/{attributeOptionId}/{attributeOptionValueId}/{action}",
     *   requirements={"attributeOptionId" = "\d+","attributeOptionValueId" = "\d+"},
     *   defaults={"action" = "update"},
     *   name="category_update_attribute_filter"
     * )
     */
    public function updateAttributeFilterAction($attributeOptionId, $attributeOptionValueId, $action)
    {
         $referer = $this->getRequest()->server->get('HTTP_REFERER');

         try {
             $attributeOption = $this->getDoctrine()->getRepository('SplicedCommerceBundle:ProductAttributeOption')
              ->findOneById($attributeOptionId);

              $attributeOptionValue = $this->getDoctrine()->getRepository('SplicedCommerceBundle:ProductAttributeOptionValue')
              ->findOneById($attributeOptionValueId);

         } catch (NoResultException $e) {
             $this->get('session')->getFlashBag()->add('error','Attribute(s) Not Found');
             return $this->redirect(empty($referer) ? $this->generateUrl('homepage') : $referer);
         }

         if (strtolower($action) == 'delete') {
            $this->get('commerce.product.filter_manager')->removeAttribute($attributeOption, $attributeOptionValue);
         } else {
            $this->get('commerce.product.filter_manager')->addAttribute($attributeOption, $attributeOptionValue);
         }
         
         $this->get('session')->getFlashBag()->add('success', 'Filter Updated Successfully');

         return $this->redirect(empty($referer) ? $this->generateUrl('homepage') : $referer);
    }

    /**
     * @Route("/categories/reset-attribute-fiter",
     *   name="category_reset_attribute_filter"
     * )
     */
    public function resetAttributeFilterAction()
    {
        
        //TODO
    }
    
    
    /**
     * @Route("/categories/update-order-fiter/{orderFilterName}/{orderFilterValue}",
     *   name="category_update_order_filter"
     * )
     */
    public function updateOrderFilterAction($orderFilterName, $orderFilterValue)
    {

        $referer = $this->getRequest()->server->get('HTTP_REFERER');

        switch (strtolower($orderFilterName)) {
            case 'orderby':
                $this->get('commerce.product.filter_manager')->setOrderBy($orderFilterValue);
                break;
            case 'perpage':
                $this->get('commerce.product.filter_manager')->setPerPage($orderFilterValue);
                break;
            case 'displaymode':
                $this->get('commerce.product.filter_manager')->setDisplayMode($orderFilterValue);
                break;
        }

        $this->get('session')->getFlashBag()->add('success', 'Filter Updated Successfully');

        return $this->redirect(empty($referer) ? $this->generateUrl('homepage') : $referer);
    }

    /**
     * @Template("SplicedCommerceBundle:Category/Rss:feed.html.twig")
     * @Route("/categories/rss", name="category_rss")
     */
    public function rssFeedAction()
    {

    }
    
    protected function renderCategoryView(CategoryInterface $category)
    {
    	$this->setCategoryBreadcrumbs($category);
    	
    	$productFilterManager = $this->get('commerce.product.filter_manager');
    	
    	$productFilterManager->setCategory($category);
    	
    	$specificationFilters = $productFilterManager->getAvailableSpecifications();
    	
    	// load paginated and filtered products
    	$products = $this->get('knp_paginator')->paginate(
    		$productFilterManager->getPaginationQuery(),
    		$this->getRequest()->query->get('page', 1),
    		$productFilterManager->getPerPage()
    	);
    	
    	return array(
    		'category'  => $category,
    		'products'  => $products,
    		'viewMode'  => $productFilterManager->getDisplayMode(),
    		'productFilterManager' => $productFilterManager,
    		'specificationFilters' => $specificationFilters,
    	);
    }
}
