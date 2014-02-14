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
use Gedmo\Mapping\Annotation as Gedmo;
use Spliced\Component\Commerce\Model\Manufacturer as BaseManufacturer;

/**
 * @MongoDB\Document(
 *   collection="manufacturer", 
 *   repositoryClass="Spliced\Bundle\CommerceAdminBundle\Repository\ManufacturerDocumentRepository"
 * )
 * @MongoDB\MappedSuperclass()
 */
class Manufacturer extends BaseManufacturer
{
    
}