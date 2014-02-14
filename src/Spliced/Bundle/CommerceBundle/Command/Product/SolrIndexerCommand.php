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
use Spliced\Bundle\CommerceBundle\Entity\Media;
use Spliced\Component\Commerce\Search\Solr\SolrProductDocumentBuilder;

/**
 * SolrIndexerCommand
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class SolrIndexerCommand extends BaseCommand
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
        ->setName('smc:product-solr-indexer')
        ->setDescription('Updates Solr Index for Products');
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
        $client = $this->getContainer()->get('solarium.client');

        $this->writeLine('Connected to Solr Core: <info>core0</info>');

        $productsQuery = $this->getContainer()->get('commerce.product.repository')
          ->getAllProductsQuery();

        $products = $productsQuery->getQuery()
          ->setHint(Query::HINT_FORCE_PARTIAL_LOAD, true)
          #->setMaxResults(5)
          ->getResult();

        $this->writeLine(sprintf('Loaded <info>%s</info> Products', count($products)));

        $query = $client->createUpdate();

        // delete existing items first for a clean index
        $deleteQuery = $client->createUpdate();
        $deleteQuery->addDeleteQuery('*:*');
        $deleteQuery->addCommit();
        $client->update($deleteQuery);
        $this->writeLine('<info>Deleted Current Documents</info>');
        
        foreach ($products as $product) {
            $this->writeLine(sprintf('Starting Product <info>%s - %s</info>', $product->getSku(), $product->getName()));

            $productDocument = new SolrProductDocumentBuilder($product);

            $solrDocument = $productDocument->getDocument();

            $query->addDocument($solrDocument);
        }

        $query->addCommit();
        
        $client->update($query);
    }
}
