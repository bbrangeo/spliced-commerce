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
use Gedmo\Tree\Document\MongoDB\Repository\MaterializedPathRepository;

/**
 * CategoryDocumentRepository
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
abstract class CategoryDocumentRepository extends MaterializedPathRepository implements CategoryRepositoryInterface
{
	/**
	 * {@inheritDoc}
	 */
	public function getRoot()
	{
		return $this->createQueryBuilder('category')
		->field('parent')->exists(false)
		->eagerCursor(true)
		->getQuery()
		->execute();
	}
	
	/**
	 * {@inheritDoc}
	
	public function getTree()
	{
		return $this->createQueryBuilder('category')
		->field('parent')->exists(false)
		->eagerCursor(true)
		->getQuery()
		->execute();
	} */
}
