<?php
/**
 * Class for managing ILS-specific authentication.
 *
 * PHP version 7
 *
 * Copyright (C) Villanova University 2007.
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
 * @package  Authentication
 * @author   Demian Katz <demian.katz@villanova.edu>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @link     https://vufind.org Main Page
 */
namespace Delivery\Auth;

use PAIA\Auth\ILSAuthenticator;
use PAIA\Auth\Manager;
use PAIA\ILS\Connection as ILSConnection;

/**
 * Class for managing ILS-specific authentication.
 *
 * @category VuFind
 * @package  Authentication
 * @author   Demian Katz <demian.katz@villanova.edu>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @link     https://vufind.org Main Page
 */
class DeliveryAuthenticator extends ILSAuthenticator
{
    protected $config;

    /**
     * Constructor
     *
     * @param Manager       $auth    Auth manager
     * @param ILSConnection $catalog ILS connection
     */
    public function __construct(Manager $auth, ILSConnection $catalog)
    {
        parent::__construct($auth, $catalog);
    }

    /**
     * Get access to the user table.
     *
     * @return \VuFind\Db\Table\User
     */
    public function setConfig($config)
    {
        $this->config = $config;
    }

    /**
     * Get access to the user table.
     *
     * @return \VuFind\Db\Table\User
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Get access to the user table.
     *
     * @return \VuFind\Db\Table\User
     */
    public function setTable($table)
    {
        $this->table = $table;
    }

    /**
     * Get access to the user table.
     *
     * @return \VuFind\Db\Table\User
     */
    public function getTable()
    {
        return $this->table;
    }

    /**
     * Get a database table object.
     *
     * @param string $table Name of table to retrieve
     *
     * @return \VuFind\Db\Table\Gateway
     */
    private function extractUserType($rawType)
    {
        return (preg_replace('/^.+;type=([0-9]+)$/', '$1', $rawType));
    }

    /**
     * Get stored catalog credentials for the current user.
     *
     * Returns associative array of cat_username and cat_password if they are
     * available, false otherwise. This method does not verify that the credentials
     * are valid.
     *
     * @return array|bool
     */
    public function authenticate()
    {
        if (!$user = $this->auth->isLoggedIn()) {
            return 'not_logged_in';
        }
        $config = $this->getConfig()->toArray();
        $allowedTypes = $config['Patron']['allowed'];

        $patron = $this->storedCatalogLogin();
        $patronTypes = array_map([$this, 'extractUserType'], explode(', ', $patron['type']));

        if (!empty(array_intersect($patronTypes, $allowedTypes))) {
            $userDeliveryTable = $this->getTable();
            $userDeliveryData = $userDeliveryTable->get($user->id)->toArray();
            if (empty($userDeliveryData)) {
                $userDeliveryTable->createRowForUserId($user->id, $user->email);
            } elseif (empty($userDeliveryData['delivery_email'])) {
                $userDeliveryTable->saveEmail($user->email);
            }
            return 'authorized';
        }
        return 'not_authorized';
    }

}