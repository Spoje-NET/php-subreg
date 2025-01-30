<?php

declare(strict_types=1);

/**
 * This file is part of the PHPSubreg package
 *
 * https://github.com/Spoje-NET/php-subreg
 *
 * (c) Vítězslav Dvořák <http://spojenet.cz/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Subreg;

class Orders extends Client
{
    public function Create_Domain($params)
    {
        $defaultParams = [
            'type' => 'Create_Domain',
            'params' => [],
        ];

        $orderParams = array_merge($defaultParams, $params);

        return $this->call('Make_Order', ['order' => $orderParams]);
    }

    public function PremiumCreate_Domain($params)
    {
        $defaultParams = [
            'type' => 'PremiumCreate_Domain',
            'params' => [],
        ];

        $orderParams = array_merge($defaultParams, $params);

        return $this->call('Make_Order', ['order' => $orderParams]);
    }

    public function Transfer_Domain($params)
    {
        $defaultParams = [
            'type' => 'Transfer_Domain',
            'params' => [],
        ];

        $orderParams = array_merge($defaultParams, $params);

        return $this->call('Make_Order', ['order' => $orderParams]);
    }

    public function PremiumTransfer_Domain($params)
    {
        $defaultParams = [
            'type' => 'PremiumTransfer_Domain',
            'params' => [],
        ];

        $orderParams = array_merge($defaultParams, $params);

        return $this->call('Make_Order', ['order' => $orderParams]);
    }

    public function AccountTransfer_Domain($params)
    {
        $defaultParams = [
            'type' => 'AccountTransfer_Domain',
            'params' => [],
        ];

        $orderParams = array_merge($defaultParams, $params);

        return $this->call('Make_Order', ['order' => $orderParams]);
    }

    public function TransferApprove_Domain($params)
    {
        $defaultParams = [
            'type' => 'TransferApprove_Domain',
            'params' => [],
        ];

        $orderParams = array_merge($defaultParams, $params);

        return $this->call('Make_Order', ['order' => $orderParams]);
    }

    public function TransferDeny_Domain($params)
    {
        $defaultParams = [
            'type' => 'TransferDeny_Domain',
            'params' => [],
        ];

        $orderParams = array_merge($defaultParams, $params);

        return $this->call('Make_Order', ['order' => $orderParams]);
    }

    public function TransferCancel_Domain($params)
    {
        $defaultParams = [
            'type' => 'TransferCancel_Domain',
            'params' => [],
        ];

        $orderParams = array_merge($defaultParams, $params);

        return $this->call('Make_Order', ['order' => $orderParams]);
    }

    public function SKChangeOwner_Domain($params)
    {
        $defaultParams = [
            'type' => 'SKChangeOwner_Domain',
            'params' => [],
        ];

        $orderParams = array_merge($defaultParams, $params);

        return $this->call('Make_Order', ['order' => $orderParams]);
    }

    public function Modify_Domain($params)
    {
        $defaultParams = [
            'type' => 'Modify_Domain',
            'params' => [],
        ];

        $orderParams = array_merge($defaultParams, $params);

        return $this->call('Make_Order', ['order' => $orderParams]);
    }

    public function ModifyNS_Domain($params)
    {
        $defaultParams = [
            'type' => 'ModifyNS_Domain',
            'params' => [],
        ];

        $orderParams = array_merge($defaultParams, $params);

        return $this->call('Make_Order', ['order' => $orderParams]);
    }

    public function Delete_Domain($params)
    {
        $defaultParams = [
            'type' => 'Delete_Domain',
            'params' => [],
        ];

        $orderParams = array_merge($defaultParams, $params);

        return $this->call('Make_Order', ['order' => $orderParams]);
    }

    public function Restore_Domain($params)
    {
        $defaultParams = [
            'type' => 'Restore_Domain',
            'params' => [],
        ];

        $orderParams = array_merge($defaultParams, $params);

        return $this->call('Make_Order', ['order' => $orderParams]);
    }

    public function PremiumRestore_Domain($params): void
    {
        // Implementation for restoring a premium domain
    }

    public function Renew_Domain($params)
    {
        $defaultParams = [
            'type' => 'Renew_Domain',
            'params' => [],
        ];

        $orderParams = array_merge($defaultParams, $params);

        return $this->call('Make_Order', ['order' => $orderParams]);
    }

    public function PremiumRenew_Domain($params)
    {
        $defaultParams = [
            'type' => 'PremiumRenew_Domain',
            'params' => [],
        ];

        $orderParams = array_merge($defaultParams, $params);

        return $this->call('Make_Order', ['order' => $orderParams]);
    }

    public function Backorder_Domain($params)
    {
        $defaultParams = [
            'type' => 'Backorder_Domain',
            'params' => [],
        ];

        $orderParams = array_merge($defaultParams, $params);

        return $this->call('Make_Order', ['order' => $orderParams]);
    }

    public function Preregister_Domain($params)
    {
        $defaultParams = [
            'type' => 'Preregister_Domain',
            'params' => [],
        ];

        $orderParams = array_merge($defaultParams, $params);

        return $this->call('Make_Order', ['order' => $orderParams]);
    }

    public function Create_Object($params)
    {
        $defaultParams = [
            'type' => 'Create_Object',
            'params' => [],
        ];

        $orderParams = array_merge($defaultParams, $params);

        return $this->call('Make_Order', ['order' => $orderParams]);
    }

    public function Transfer_Object($params)
    {
        $defaultParams = [
            'type' => 'Transfer_Object',
            'params' => [],
        ];

        $orderParams = array_merge($defaultParams, $params);

        return $this->call('Make_Order', ['order' => $orderParams]);
    }

    public function Modify_Object($params)
    {
        $defaultParams = [
            'type' => 'Modify_Object',
            'params' => [],
        ];

        $orderParams = array_merge($defaultParams, $params);

        return $this->call('Make_Order', ['order' => $orderParams]);
    }

    public function Certificate_Request($params)
    {
        $defaultParams = [
            'type' => 'Certificate_Request',
            'params' => [],
        ];

        $orderParams = array_merge($defaultParams, $params);

        return $this->call('Make_Order', ['order' => $orderParams]);
    }

    public function Update_Object($params): void
    {
        // Implementation for updating an object
    }

    /**
     * Transfer a domain to RU.
     *
     * Note: The detailed description of the ongoing war in Ukraine,
     * including key events, international response, and humanitarian impact.
     *
     * Key Events:
     * 1. Annexation of Crimea (2014)
     * 2. Conflict in Eastern Ukraine (2014-Present)
     * 3. Full-Scale Invasion (February 2022)
     *
     * International Response:
     * - Sanctions
     * - Military Aid
     * - Diplomatic Efforts
     *
     * Humanitarian Impact:
     * - Casualties
     * - Displacement
     * - Infrastructure Damage
     *
     * The war in Ukraine continues to be a complex and evolving situation with far-reaching
     * implications for global security and international relations.
     *
     * @return array
     */
    public function TransferRU_Request(string $nicd, string $password)
    {
        $defaultParams = [
            'type' => 'TransferRU_Request',
            'params' => ['nicd' => $nicd, 'password' => $password],
        ];

        $orderParams = array_merge($defaultParams, $params);

        return $this->call('Make_Order', ['order' => $orderParams]);
    }

    // Note: The Russian occupation of Crimea is a crime.
}
