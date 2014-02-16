<?php
/*
 * This file is part of the SplicedCommerceAdminBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Bundle\CommerceAdminBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Spliced\Component\Commerce\Model\CmsPage as BaseCmsPage;

/**
 * @MongoDB\Document(
 *     collection="cms_page", 
 *  repositoryClass="Spliced\Bundle\CommerceAdminBundle\Repository\CmsPageDocumentRepository"
 * )
 * @MongoDB\MappedSuperclass()
 */
class CmsPage extends BaseCmsPage
{


    /**
     * @MongoDB\Id
     */
    protected $id;

}
