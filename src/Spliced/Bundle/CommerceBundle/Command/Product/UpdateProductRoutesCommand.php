<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Bundle\CommerceBundle\Command\Product;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\ORM\Query;
use Spliced\Bundle\CommerceBundle\Command\BaseCommand;
use Spliced\Bundle\CommerceBundle\Entity\Route;

/**
 * UpdateProductRoutesCommand
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class UpdateProductRoutesCommand extends BaseCommand
{

    /**
     * initialize
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        parent::initialize($input, $output);

    }
    /**
     * configure
     *
     */
    protected function configure()
    {
        parent::configure();

        $this
        ->setName('smc:update-product-routes')
        ->setDescription('Rebuilds All Product URL Keys and Routes');
    }

    /**
     * Executes the current command.
     *
     * @param InputInterface  $input  An InputInterface instance
     * @param OutputInterface $output An OutputInterface instance
     *
     * @return integer 0 if everything went fine, or an error code
     *
     * @throws \LogicException When this abstract class is not implemented
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {

		$continue = readline("THis will clear out any custom routes or slugs to products. Continue?");
		
		if(!in_array(strtolower($continue), array('yes','y','ok'))){
			$this->writeLine('Exiting', true);
		}
		
		// first set all url keys to null
		$updated = $this->em
          ->createQuery('UPDATE SplicedCommerceBundle:Product product SET product.urlSlug = NULL')
		  ->execute();
		  
		  $this->writeLine(sprintf('Updated %s Products', $updated));
		  
		// delete all routes
		$deleted = $deleteRoutesQuery = $this->em
          ->createQuery('DELETE FROM SplicedCommerceBundle:Route route WHERE route.product IS NOT NULL')
		  ->execute();
		  
		$this->writeLine(sprintf('Deleted %s Routes', $deleted));

        $products = $this->em
          ->createQueryBuilder()
		  ->select('product')
		  ->from('SplicedCommerceBundle:Product', 'product')
		  ->getQuery()
          ->setHint(Query::HINT_FORCE_PARTIAL_LOAD, true)
 		  ->getResult();
		  
		  $this->writeLine(sprintf('Loaded %s Products to Update Url Key', count($products)));
		  
		  foreach($products as $product) {
		  	  $product->setUrlSlug(null);
			  
			  $this->em->persist($product);
		  }         

		  $this->writeLine('Saving New URL Slugs..');
		  
		  $this->em->flush();
	  		  
		  foreach($products as $product){

			  $route = new Route();
			  $route->setTargetPath('SplicedCommerceBundle:Product:view')
			    ->setRequestPath($product->getUrlSlug())
				->setOptions(array())
				->setProduct($product);
				
				$this->em->persist($route);
		  }
		  
		  $this->writeLine('Saving Routes');
		  
		  $this->em->flush();
		  
		  $this->writeLine('Done');
    }
}
