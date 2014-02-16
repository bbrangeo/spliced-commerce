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

/**
 * CheckoutCustomFieldDocumentRepository
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
abstract class CheckoutCustomFieldDocumentRepository extends DocumentRepository implements CheckoutCustomFieldRepositoryInterface
{
    /**
     * getAllActive
     *
     * @return array
     */
    public function getAllActive()
    {
        return $this->createQueryBuilder()
          ->field('isActive')->equals(true)
          ->getQuery()
          ->execute();
    }
   
}
