<?php
/*
 * This file is part of the SplicedCommerceAdminBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Bundle\CommerceAdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Spliced\Component\Commerce\Model\Cart as BaseCart;

/**
 * Cart
 *
 * @ORM\Table(name="cart")
 * @ORM\Entity(repositoryClass="Spliced\Bundle\CommerceAdminBundle\Repository\CartRepository")
 * @ORM\MappedSuperclass()
 */
class Cart extends BaseCart
{

}