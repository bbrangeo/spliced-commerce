<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Bundle\CommerceBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Spliced\Component\Commerce\Model\ContentPage as BaseContentPage;

/**
 * @MongoDB\Document(
 *     collection="content_page", 
 *  repositoryClass="Spliced\Bundle\CommerceBundle\Repository\ContentPageDocumentRepository"
 * )
 * @MongoDB\MappedSuperclass()
 */
class ContentPage extends BaseContentPage
{


    /**
     * @MongoDB\Id
     */
    protected $id;

}
