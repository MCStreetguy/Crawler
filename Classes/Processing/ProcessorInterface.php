<?php

/**
 * This file is part of the mcstreetguy/crawler package.
 *
 * @package    mcstreetguy/crawler
 * @author     Maximilian Schmidt <maximilianschmidt404@gmail.com>
 * @copyright  2019, Maximilian Schmidt
 * @license    https://github.com/MCStreetguy/Crawler/blob/master/LICENSE MIT
 * @version    SVN: $Id$
 */

namespace MCStreetguy\Crawler\Processing;

use MCStreetguy\Crawler\Result\CrawlResult;

/**
 * A standard interface for processor classes.
 *
 * @api
 * @author     Maximilian Schmidt <maximilianschmidt404@gmail.com>
 * @copyright  2019, Maximilian Schmidt
 * @license    https://github.com/MCStreetguy/Crawler/blob/master/LICENSE MIT
 * @package    mcstreetguy/crawler
 * @since      always
 */
interface ProcessorInterface
{
    /**
     * Invoke this processor.
     *
     * Invoke this processor.
     *
     * @api
     * @param CrawlResult $result The crawl result to process
     * @return void
     */
    public function invoke(CrawlResult $result);
}
