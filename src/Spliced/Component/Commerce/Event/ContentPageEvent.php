<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Component\Commerce\Event;

use Spliced\Component\Commerce\Model\ContentPageInterface;

/**
 * ContentPageEvent
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class ContentPageEvent extends Event
{

    public function __construct(ContentPageInterface $cmsPage)
    {
        $this->cmsPage = $cmsPage;
    }
    
    /**
     * getContentPage
     * 
     * @return ContentPageInterface
     */
    public function getContentPage()
    {
        return $this->cmsPage;
    }
}
