<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Bundle\CommerceAdminBundle\Repository;

use Spliced\Component\Commerce\Repository\ProductRepository as BaseProductRepository;
use Spliced\Bundle\CommerceAdminBundle\Model\ListFilter;

/**
 * ProductRepository
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class ProductRepository extends BaseProductRepository
{
    
    /**
     * getAdminListQuery
     */
    public function getAdminListQuery(ListFilter $filter = null, $toQuery = true)
    {
        $query = $this->createQueryBuilder('product')
        ->orderBy('product.id', "ASC");
    
        if($filter) {
            //$this->applyListFilter($filter, $query);
        }
    
        return $toQuery ?  $query->getQuery() : $query;
    }
}
