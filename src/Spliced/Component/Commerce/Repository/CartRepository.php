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

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Query\Expr;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Spliced\Component\Commerce\Model\VisitorInterface;

/**
 * CartRepository
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
abstract class CartRepository extends EntityRepository implements CartRepositoryInterface
{
        
    /**
     * findOneByIdWithItemsSorted
     *
     * @param int $id
     */
    public function findOneByIdWithItems($id)
    {
        try{
            $cart = $this->createQueryBuilder('cart')
            ->select('cart, items')
            ->leftJoin('cart.items', 'items')
            ->leftJoin('items.children', 'childrenItems')
            ->where('cart.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getSingleResult();
            
        } catch(NoResultException $e){
            return null;
        }
 
        return $cart;
    }

    
    /**
     * findOneByVisitor
     * 
     * @param VisitorInterface
     */
    public function findOneByVisitorWithProducts(VisitorInterface $visitor)
    {
        return $this->getJoinedQuery()
          ->where('cart.visitor = :visitor')
          ->setParameter('visitor', $visitor->getId())
          ->getQuery()          
          ->getSingleResult();
    }
    
    
}