<?php
/**
 * Record driver plugin manager
 *
 * PHP version 7
 *
 * Copyright (C) Villanova University 2010.
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
 * @package  RecordDrivers
 * @author   Demian Katz <demian.katz@villanova.edu>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @link     https://vufind.org/wiki/development:plugins:record_drivers Wiki
 */
namespace RecordDriver\RecordDriver;

/**
 * Record driver plugin manager
 *
 * @category VuFind
 * @package  RecordDrivers
 * @author   Demian Katz <demian.katz@villanova.edu>
 * @author   Hajo Seng <hajo.seng@sub.uni-hamburg.de>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @link     https://vufind.org/wiki/development:plugins:record_drivers Wiki
 */
class PluginManager extends \VuFind\RecordDriver\PluginManager
{
    /**
     * Default plugin aliases.
     *
     * @var array
     */
    protected $aliases = [
        'browzine' => 'VuFind\RecordDriver\BrowZine',
        'eds' => 'VuFind\RecordDriver\EDS',
        'eit' => 'VuFind\RecordDriver\EIT',
        'libguides' => 'VuFind\RecordDriver\LibGuides',
        'missing' => 'VuFind\RecordDriver\Missing',
        'pazpar2' => 'VuFind\RecordDriver\Pazpar2',
        'primo' => 'VuFind\RecordDriver\Primo',
        'solrauth' => 'VuFind\RecordDriver\SolrAuthMarc', // legacy name
        'solrauthdefault' => 'VuFind\RecordDriver\SolrAuthDefault',
        'solrauthmarc' => 'VuFind\RecordDriver\SolrAuthMarc',
        'solrdefault' => 'VuFind\RecordDriver\SolrDefault',
        'solrmarc' => 'RecordDriver\RecordDriver\SolrMarc',
        'solrmarcremote' => 'VuFind\RecordDriver\SolrMarcRemote',
        'solrreserves' => 'VuFind\RecordDriver\SolrReserves',
        'solrweb' => 'VuFind\RecordDriver\SolrWeb',
        'summon' => 'VuFind\RecordDriver\Summon',
        'worldcat' => 'VuFind\RecordDriver\WorldCat',
    ];

    /**
     * Default delegator factories.
     *
     * @var string[][]|\Zend\ServiceManager\Factory\DelegatorFactoryInterface[][]
     */
    protected $delegators = [
        'RecordDriver\RecordDriver\SolrMarc' =>
            ['VuFind\RecordDriver\IlsAwareDelegatorFactory'],
        'VuFind\RecordDriver\SolrMarcRemote' =>
            ['VuFind\RecordDriver\IlsAwareDelegatorFactory'],
    ];

    /**
     * Default plugin factories.
     *
     * @var array
     */
    protected $factories = [
        'RecordDriver\RecordDriver\SolrMarc' => 
            'RecordDriver\RecordDriver\SolrDefaultFactory',
        'VuFind\RecordDriver\BrowZine' =>
            'Zend\ServiceManager\Factory\InvokableFactory',
        'VuFind\RecordDriver\EDS' => 'VuFind\RecordDriver\NameBasedConfigFactory',
        'VuFind\RecordDriver\EIT' => 'VuFind\RecordDriver\NameBasedConfigFactory',
        'VuFind\RecordDriver\LibGuides' =>
            'Zend\ServiceManager\Factory\InvokableFactory',
        'VuFind\RecordDriver\Missing' => 'VuFind\RecordDriver\AbstractBaseFactory',
        'VuFind\RecordDriver\Pazpar2' =>
            'VuFind\RecordDriver\NameBasedConfigFactory',
        'VuFind\RecordDriver\Primo' => 'VuFind\RecordDriver\NameBasedConfigFactory',
        'VuFind\RecordDriver\SolrAuthDefault' =>
            'VuFind\RecordDriver\SolrDefaultWithoutSearchServiceFactory',
        'VuFind\RecordDriver\SolrAuthMarc' =>
            'VuFind\RecordDriver\SolrDefaultWithoutSearchServiceFactory',
        'VuFind\RecordDriver\SolrDefault' =>
            'VuFind\RecordDriver\SolrDefaultFactory',
        'VuFind\RecordDriver\SolrMarcRemote' =>
            'VuFind\RecordDriver\SolrDefaultFactory',
        'VuFind\RecordDriver\SolrReserves' =>
            'VuFind\RecordDriver\SolrDefaultWithoutSearchServiceFactory',
        'VuFind\RecordDriver\SolrWeb' => 'VuFind\RecordDriver\SolrWebFactory',
        'VuFind\RecordDriver\Summon' => 'VuFind\RecordDriver\SummonFactory',
        'VuFind\RecordDriver\WorldCat' =>
            'VuFind\RecordDriver\NameBasedConfigFactory',
    ];

    /**
     * Constructor
     *
     * Make sure plugins are properly initialized.
     *
     * @param mixed $configOrContainerInstance Configuration or container instance
     * @param array $v3config                  If $configOrContainerInstance is a
     * container, this value will be passed to the parent constructor.
     */
    public function __construct($configOrContainerInstance = null,
        array $v3config = []
    ) {
        $this->addAbstractFactory('RecordDriver\RecordDriver\PluginFactory');
        parent::__construct($configOrContainerInstance, $v3config);
    }
}
