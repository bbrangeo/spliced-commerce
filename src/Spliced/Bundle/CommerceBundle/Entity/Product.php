<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Bundle\CommerceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Spliced\Component\Commerce\Model\Product as BaseProduct;

/**
 * @ORM\Table(name="product")
 * @ORM\Entity(repositoryClass="Spliced\Bundle\CommerceBundle\Repository\ProductRepository")
 * @ORM\MappedSuperclass()
 */ 
class Product extends BaseProduct
{

}
