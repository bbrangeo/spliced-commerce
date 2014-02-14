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
use Spliced\Component\Commerce\Model\ProductAttributeOption as BaseProductAttributeOption;

/**
 * @MongoDB\Document(
 * 	collection="product_attribute_option", 
 * 	repositoryClass="Spliced\Bundle\CommerceBundle\Repository\ProductAttributeOptionDocumentRepository"
 * )
 * @MongoDB\EmbeddedDocument()
 * @MongoDB\MappedSuperclass()
 */
class ProductAttributeOption extends BaseProductAttributeOption
{


}
