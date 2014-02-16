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
 * RouteDocumentRepository
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
abstract class RouteDocumentRepository extends DocumentRepository implements RouteRepositoryInterface
{

    /**
     * {@inheritDoc}
     */
    public function matchRoute($requestPath)
    {
        
        return $this->createQueryBuilder('route')
          ->field('requestPath')->equals($requestPath)
          //->hydrate(false)
          ->getQuery()
          ->getSingleResult();
    }
}
