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
 * AffiliateDocumentRepository
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
abstract class AffiliateDocumentRepository extends DocumentRepository
{

    /**
     * getAll
     * 
     * @return Collection
     */
    public function getAll()
    {
        return $this->createQueryBuilder()
          ->sort('sort', 'ASC')
          ->getQuery()
          ->execute();
    }
    
    /**
     * getAllActive
     * 
     * @return Collection
     */
    public function getAllActive()
    {
        return $this->createQueryBuilder()
          ->field('isActive')->equals(true)
          ->sort('sort', 'ASC')
          ->getQuery()
          ->execute();
    }
    
    /**
     * getAllActive
     *
     * @return AffiliateInterface
     */
    public function findAffilliateByUrlPrefix($urlPrefix)
    {
        return $this->createQueryBuilder()
          ->field('urlPrefix')->equals($urlPrefix)
          ->sort('sort', 'ASC')
          ->getQuery()
          ->getSingleResult();
    }
}
