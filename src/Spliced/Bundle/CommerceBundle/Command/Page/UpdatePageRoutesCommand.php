<?php
/*
* This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Bundle\CommerceBundle\Command\Page;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Spliced\Bundle\CommerceBundle\Command\BaseCommand;
use Spliced\Bundle\CommerceBundle\Entity;

/**
 * UpdatePageRoutesCommand
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class UpdatePageRoutesCommand extends BaseCommand
{

    const CMS_PAGE_BASE_ROUTE = 'SplicedCommerceBundle:Page:view';

    /**
     * {@inheritDoc}
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        parent::initialize($input, $output);

    }

    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        parent::configure();

        $this
        ->setName('smc:update-page-routes')
        ->setDescription('Updates CMS Page Routes');
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $pages = $this->em->createQuery('SELECT page, route FROM SplicedCommerceBundle:CmsPage page LEFT JOIN page.route route')->getResult();

        foreach ($pages as $page) {
            $this->writeLine(sprintf('%s - %s - %s', $page->getId(), $page->getTitle(), $page->getUrlSlug()));

            if (!$page->getRoute()) {
                $route = new Entity\Route();
                $route->setPage($page);
                $route->setTargetPath(self::CMS_PAGE_BASE_ROUTE);
                $this->writeLine('Creating New Route');
            } else {
                $route = $page->getRoute();
                $this->writeLine('Updating Route');
            }

            $route->setRequestPath(preg_replace('/\/\/+/','/','/'.$page->getUrlSlug()));

            $this->em->persist($route);

            try {
                $this->em->flush();
            } catch (\Exception $e) {
                $this->writeLine('Error %s Route with Slug %s - %s',$route->getId()?'Updating':'Creating',$route->getRequestPath(), $e->getMessage());
            }
        }

    }
}