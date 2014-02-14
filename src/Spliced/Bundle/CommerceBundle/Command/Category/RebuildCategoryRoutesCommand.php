<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Bundle\CommerceBundle\Command\Category;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Spliced\Bundle\CommerceBundle\Entity;
use Doctrine\ORM\Query;
use Doctrine\ORM\NoResultException;

/**
 * RebuildCategoryRoutesCommand
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class RebuildCategoryRoutesCommand extends ContainerAwareCommand
{
	const ROUTE_DESCRIPTION_TAG = 'Category';
	const ROUTE_TARGET_PATH = 'SplicedCommerceBundle:Category:view';
	
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        parent::initialize($input, $output);

        $this->em = $this->getContainer()->get('doctrine')->getEntityManager();
    }

    protected function configure()
    {
        parent::configure();

        $this
        ->setName('smc:rebuild-category-routes')
        ->setDescription('Deletes and Re-Creates Category Routes');
    }

    
    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
    	
    	$this->em->createQuery("DELETE FROM SplicedCommerceBundle:Route route WHERE route.category IS NOT NULL AND route.description = :desc")
    	  ->setParameter('desc',static::ROUTE_DESCRIPTION_TAG)
    	  ->execute();
    	
    	$categories = $this->em->createQuery("SELECT category FROM SplicedCommerceBundle:Category category WHERE category.isActive = 1")
    	  ->getResult();
    	
    	foreach($categories as $category) {
    		
    		$route = new Entity\Route();
    		
    		$route->setRequestPath('/'.preg_replace('/^\//','',$category->getUrlSlug()))
    		  ->setTargetPath(static::ROUTE_TARGET_PATH)
    		  ->setCategory($category)
    		  ->setDescription(static::ROUTE_DESCRIPTION_TAG);
    		  
    		 $this->em->persist($route); 
    		
    		
    	}
    	
    	$this->em->flush();
    }
    
}
