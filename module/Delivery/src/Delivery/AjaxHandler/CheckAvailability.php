<?php
/**
 * "Get Record Details" AJAX handler
 *
 * PHP version 7
 *
 * Copyright (C) Villanova University 2018.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License version 2,
 * as published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @category VuFind
 * @package  AJAX
 * @author   Demian Katz <demian.katz@villanova.edu>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @link     https://vufind.org/wiki/development Wiki
 */
namespace Delivery\AjaxHandler;

use Delivery\AvailabilityHelper;
use VuFind\AjaxHandler\AbstractBase;
use VuFind\Search\Results\PluginManager as ResultsManager;
use Zend\Http\PhpEnvironment\Request;
use Zend\Mvc\Controller\Plugin\Params;
use Zend\Stdlib\Parameters;

/**
 * "Get Record Details" AJAX handler
 *
 * Get record for integrated list view.
 *
 * @category VuFind
 * @package  AJAX
 * @author   Demian Katz <demian.katz@villanova.edu>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @link     https://vufind.org/wiki/development Wiki
 */
class CheckAvailability extends AbstractBase
{
    /**
     * Request
     *
     * @var Request
     */
    protected $AvailabilityHelper;

    protected $resultsManager;

    /**
     * Constructor
     *
     * @param array             $config   ZF configuration
     * @param Request           $request  HTTP request
     * @param Loader            $loader   Record loader
     * @param TabManager        $pm       RecordTab plugin manager
     * @param RendererInterface $renderer Renderer
     */
    public function __construct($config, ResultsManager $resultsManager) {
        $this->AvailabilityHelper = new AvailabilityHelper(null, $config['default']);
        $this->resultsManager = $resultsManager;
    }

    /**
     * Handle a request.
     *
     * @param Params $params Parameter helper from controller
     *
     * @return array [response data, HTTP status code]
     */
    public function handleRequest(Params $params)
    {
        $ppn = $params->fromQuery('ppn');
        $backend = $params->fromQuery('source', DEFAULT_SEARCH_BACKEND);
        $backend = DEFAULT_SEARCH_BACKEND;
        $results = $this->resultsManager->get($backend);
        $paramsObj = $results->getParams();
        $paramsObj->initFromRequest(new Parameters(['lookfor' => 'id:' . $ppn, 'limit' => 1]));

        $records = $results->getResults();
        $driver = $records[0];
        $this->AvailabilityHelper->setSolrDriver($driver);
        $available = ($this->AvailabilityHelper->checkSignature()) ? 'available' : 'not available';
        return $this->formatResponse(['available' => $available]);
    }
}