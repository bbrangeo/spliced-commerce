<?php
/*
 * This file is part of the SplicedCommerceAdminBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Bundle\CommerceAdminBundle\Repository;

use Spliced\Component\Commerce\Repository\AffiliateDocumentRepository as BaseAffiliateDocumentRepository;
use Spliced\Bundle\CommerceAdminBundle\Model\ListFilter;

/**
 * CategoryDocumentRepository
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class AffiliateDocumentRepository extends BaseAffiliateDocumentRepository
{
	/**
	 * getAdminListQuery
	 */
	public function getAdminListQuery(ListFilter $filter = null, $toQuery = true)
	{
		$query = $this->createQueryBuilder()
		->sort('id', "ASC");
	
		if($filter) {
			//$this->applyListFilter($filter, $query);
		}
	
		return $toQuery ?
		$query->getQuery() : $query;
	}
}