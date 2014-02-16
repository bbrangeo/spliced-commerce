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
     * getCategoryFilters
     *
     * Gets the filter preferences saved in the session
     */
    protected function getCategoryFilters()
    {
        return array_merge_recursive(
            $this->getDefaultCategoryFilters(),
            $this->get('session')->get('commerce.category.filters', array())
        );
    }
    
    /**
     * setCategoryFilters
     * Sets the filter preferences saved in the session
     * @param array $filters
     */
    protected function setCategoryFilters(array $filters)
    {
        $this->get('session')->set('commerce.category.filters', array_merge_recursive(
            $this->getCategoryFilters(),
            $filters
        ));

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
            'listMethod' => 'list',
            'manufacturers' => array(),
            'specifications' => array(),
        );
    }
    
    /**
     * @Route("/category/{categoryId}/filter/manufacturer/{manufacturerId}/{action}",
     *   name="category_update_filter_manufacturer"
     * )
     */
    public function updateFilterManufacturerAction($categoryId, $manufacturerId, $action = 'add')
    {
        $referer = $this->getRequest()->server->get('HTTP_REFERER');
        $filterManager = $this->get('commerce.product.filter_manager');
        $request = $this->getRequest();
        
        $category = $this->get('commerce.document_manager')
          ->getRepository('SplicedCommerceBundle:Category')
          ->findOneById($categoryId);
        
        if(!$category){
            throw $this->createNotFoundException('Category Not Found');
        }
        
        $manufacturer = $this->get('commerce.document_manager')
        ->getRepository('SplicedCommerceBundle:Manufacturer')
        ->findOneById($manufacturerId);
        
        if(!$manufacturer){
            throw $this->createNotFoundException('Manufacturer Not Found');
        }  

        if ($action == 'add') {
            $filterManager->addSelectedManufacturer($manufacturer->getId(), $category->getId());
            
        } else if ($action == 'delete') {
            $filterManager->removeSelectedManufacturer($manufacturer->getId(), $category->getId());
        }        

        $this->get('session')->getFlashBag()->add('success', 'Filter Updated Successfully');
        
        return $this->redirect(empty($referer) ? $this->generateUrl('homepage') : $referer);
    }
    
    /**
     * @Route("/category/{categoryId}/filter/specification/{specificationId}/{value}/{action}",
     *   name="category_update_filter_specification"
     * )
     */
    public function updateFilterSpecificationAction($categoryId, $specificationId, $value, $action = 'add')
    {
        $referer = $this->getRequest()->server->get('HTTP_REFERER');
        $filterManager = $this->get('commerce.product.filter_manager');
        $request = $this->getRequest();
    
        $category = $this->get('commerce.document_manager')
        ->getRepository('SplicedCommerceBundle:Category')
        ->findOneById($categoryId);
    
        if(!$category){
            throw $this->createNotFoundException('Category Not Found');
        }
    
        $specification = $this->get('commerce.document_manager')
        ->getRepository('SplicedCommerceBundle:ProductSpecificationOption')
        ->findOneById($specificationId);
    
        if(!$specification){
            throw $this->createNotFoundException('Specification Not Found');
        }

        if ($action == 'add') {
            if ($specification->hasValue($value)) {
                $filterManager->addSelectedSpecification($specification->getId(), $value, $category->getId());
            }
        } else if ($action == 'delete') {
            $filterManager->removeSelectedManufacturer($specification->getId(), $value, $category->getId());
        }
    
        $this->get('session')->getFlashBag()->add('success', 'Filter Updated Successfully');
    
        return $this->redirect(empty($referer) ? $this->generateUrl('homepage') : $referer);
    }
    
    /**
     * @Route("/categories/reset-fiter/{categoryId}",
     *   name="category_reset_filter"
     * )
     */
    public function resetFilterAction($categoryId)
    {
        $filterManager = $this->get('commerce.product.filter_manager');
        
        $filterManager
        ->setSelectedManufacturers(array(), $categoryId)
        ->setSelectedSpecifications(array(), $categoryId);
        
        $referer = $this->getRequest()->server->get('HTTP_REFERER');
        return $this->redirect(empty($referer) ? $this->generateUrl('homepage') : $referer);
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

    	
    	 $productFilterManager->setCategory($category)
    	  ->prepareView();
    	
    	// load paginated and filtered products
    	$products = $this->get('knp_paginator')->paginate(
    		$productFilterManager->getPaginationQuery(),
    		$this->getRequest()->query->get('page', 1),
    		$productFilterManager->getPerPage()
    	);
    	
    	return array(
    		'category'  => $category,
    		'products'  => $products,
    		'productFilterManager' => $productFilterManager,
    	);
    }
}
