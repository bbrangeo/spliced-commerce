<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Component\Commerce\Repository;

use Doctrine\ODM\MongoDB\DocumentRepository;
use Spliced\Component\Commerce\Model\CategoryInterface;

/**
 * ProductDocumentRepository
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
abstract class ProductDocumentRepository extends DocumentRepository implements ProductRepositoryInterface
{
	/**
	 * {@inheritDoc}
	 */
	public function findByCategoryQuery(CategoryInterface $category)
	{
		return $this->createQueryBuilder('product')
		  ->eagerCursor(false)
		  ->field('categories.id')->equals($category->getId());
	}
}
