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
 * ConfigurationDocumentRepository
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
abstract class ConfigurationDocumentRepository extends DocumentRepository implements ConfigurationRepositoryInterface
{
    /**
    * {@inheritDoc}
    */
    public function getConfiguration($cache = true, $hydrate = true)
    {
        return $this->createQueryBuilder()
          ->sort('key', 'ASC')
          ->hydrate(false)
          ->getQuery()
          ->execute();
    }
    

}
