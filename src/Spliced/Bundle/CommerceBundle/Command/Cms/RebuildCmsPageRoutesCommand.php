<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Bundle\CommerceBundle\Command\Cms;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Spliced\Bundle\CommerceBundle\Entity;
use Doctrine\ORM\Query;
use Doctrine\ORM\NoResultException;

/**
 * RebuildCmsPageRoutesCommand
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class RebuildCmsPageRoutesCommand extends ContainerAwareCommand
{
	const ROUTE_DESCRIPTION_TAG = 'CmsPage';
	const ROUTE_TARGET_PATH = 'SplicedCommerceBundle:Page:view';
	
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        parent::initialize($input, $output);

        $this->em = $this->getContainer()->get('doctrine')->getEntityManager();
    }

    protected function configure()
    {
        parent::configure();

        $this
        ->setName('smc:rebuild-cms-page-routes')
        ->setDescription('Deletes and Re-Creates CMS Page Routes');
    }

    
    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
    	
    	$this->em->createQuery('DELETE FROM SplicedCommerceBundle:Route route WHERE route.page IS NOT NULL AND route.description = :desc')
    	  ->setParameter('desc',static::ROUTE_DESCRIPTION_TAG)
    	  ->execute();
    	
    	$pages = $this->em->createQuery("SELECT page FROM SplicedCommerceBundle:CmsPage page WHERE page.isActive = 1")
    	  ->getResult();
    	
    	foreach($pages as $page) {
    		
    		$route = new Entity\Route();
    		
    		$route->setRequestPath('/'.preg_replace('/^\//','',$page->getUrlSlug()))
    		  ->setTargetPath(static::ROUTE_TARGET_PATH)
    		  ->setPage($page)
    		  ->setDescription(static::ROUTE_DESCRIPTION_TAG);
    		    		  
    		 $this->em->persist($route); 
    		
    		
    	}
    	
    	$this->em->flush();
    }
    
}
