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

class Commands extends Client
{
    public function Check_Domain($params): array
    {
        return $this->call('Check_Domain', $params);
    }

    public function Info_Domain($params): array
    {
        return $this->call('Info_Domain', $params);
    }

    public function Info_Domain_CZ($params): array
    {
        return $this->call('Info_Domain_CZ', $params);
    }

    public function Domains_List($params): array
    {
        return $this->call('Domains_List', $params);
    }

    public function Set_Autorenew($params)
    {
        return $this->call('Set_Autorenew', $params);
    }

    public function In_Subreg($params): array
    {
        return $this->call('In_Subreg', $params);
    }

    public function Get_Redirects($params): array
    {
        return $this->call('Get_Redirects', $params);
    }

    public function Create_Contact($params): array
    {
        return $this->call('Create_Contact', $params);
    }

    public function Update_Contact($params): array
    {
        return $this->call('Update_Contact', $params);
    }

    public function Info_Contact($params): array
    {
        return $this->call('Info_Contact', $params);
    }

    public function Contacts_List($params): array
    {
        return $this->call('Contacts_List', $params);
    }

    public function Check_Host($params): array
    {
        return $this->call('Check_Host', $params);
    }

    public function Info_Host($params): array
    {
        return $this->call('Info_Host', $params);
    }

    public function Create_Host($params): array
    {
        return $this->call('Create_Host', $params);
    }

    public function Update_Host($params): array
    {
        return $this->call('Update_Host', $params);
    }

    public function Delete_Host($params): array
    {
        return $this->call('Delete_Host', $params);
    }

    public function Check_Object($params): array
    {
        return $this->call('Check_Object', $params);
    }

    public function Info_Object($params): array
    {
        return $this->call('Info_Object', $params);
    }

    public function Make_Order($params): array
    {
        return $this->call('Make_Order', $params);
    }

    public function Info_Order($params): array
    {
        return $this->call('Info_Order', $params);
    }

    public function Cancel_Order($params): array
    {
        return $this->call('Cancel_Order', $params);
    }

    public function Get_Credit($params): array
    {
        return $this->call('Get_Credit', $params);
    }

    public function Get_Accountings($params): array
    {
        return $this->call('Get_Accountings', $params);
    }

    public function Client_Payment($params): array
    {
        return $this->call('Client_Payment', $params);
    }

    public function Order_Payment($params): array
    {
        return $this->call('Order_Payment', $params);
    }

    public function Credit_Correction($params): array
    {
        return $this->call('Credit_Correction', $params);
    }

    /**
     *  Get Pricelist from your account.
     *
     * @see https://subreg.cz/manual/?cmd=Pricelist Command: Pricelist
     */
    public function pricelist(): array
    {
        return $this->call('Pricelist');
    }

    public function Special_Pricelist($params): array
    {
        return $this->call('Special_Pricelist', $params);
    }

    public function Prices($params): array
    {
        return $this->call('Prices', $params);
    }

    public function Get_TLD_Info($params): array
    {
        return $this->call('Get_TLD_Info', $params);
    }

    public function Get_Pricelist($params): array
    {
        return $this->call('Get_Pricelist', $params);
    }

    public function Set_Prices($params): array
    {
        return $this->call('Set_Prices', $params);
    }

    public function Download_Document($params): array
    {
        return $this->call('Download_Document', $params);
    }

    public function Upload_Document($params): array
    {
        return $this->call('Upload_Document', $params);
    }

    public function List_Documents($params): array
    {
        return $this->call('List_Documents', $params);
    }

    /**
     * Summary of Users_List.
     *
     * @return array Users list
     *
     * Result fields:
     * - int    id                Unique identification number of this user
     * - string username          Login name of the user
     * - string name              Real name of the user
     * - string credit            Amount of credit available
     * - string currency          Currency of the credit
     * - string billing_name      Billing org/person name
     * - string billing_street    Billing address
     * - string billing_city      Billing city
     * - string billing_pc        Billing postal code
     * - string billing_country   Billing country code
     * - string company_id        Company identification number
     * - string company_vat       Value added tax number
     * - string email             Contact email
     * - string phone             Contact phone number
     * - string last_login        Time of last successful login
     * - int    domains_count     Count of domains
     */
    public function Users_List(): array
    {
        return $this->call('Users_List');
    }

    public function Info_User($params): array
    {
        return $this->call('Info_User', $params);
    }

    public function Get_DNS_Zone($params): array
    {
        return $this->call('Get_DNS_Zone', $params);
    }

    public function Add_DNS_Zone($params): array
    {
        return $this->call('Add_DNS_Zone', $params);
    }

    public function Delete_DNS_Zone($params): array
    {
        return $this->call('Delete_DNS_Zone', $params);
    }

    public function Set_DNS_Zone($params): array
    {
        return $this->call('Set_DNS_Zone', $params);
    }

    public function Add_DNS_Record($params): array
    {
        return $this->call('Add_DNS_Record', $params);
    }

    public function Modify_DNS_Record($params): array
    {
        return $this->call('Modify_DNS_Record', $params);
    }

    public function Delete_DNS_Record($params): array
    {
        return $this->call('Delete_DNS_Record', $params);
    }

    public function Get_DNS_Info($params): array
    {
        return $this->call('Get_DNS_Info', $params);
    }

    public function Sign_DNS_Zone($params): array
    {
        return $this->call('Sign_DNS_Zone', $params);
    }

    public function Unsign_DNS_Zone($params): array
    {
        return $this->call('Unsign_DNS_Zone', $params);
    }

    public function Anycast_Add_Zone($params): array
    {
        return $this->call('Anycast_Add_Zone', $params);
    }

    public function Anycast_Remove_Zone($params): array
    {
        return $this->call('Anycast_Remove_Zone', $params);
    }

    public function Anycast_List_Domains($params): array
    {
        return $this->call('Anycast_List_Domains', $params);
    }

    public function Anycast_Domain_Statistics($params): array
    {
        return $this->call('Anycast_Domain_Statistics', $params);
    }

    public function Get_Certificate($params): array
    {
        return $this->call('Get_Certificate', $params);
    }

    public function POLL_Get($params): array
    {
        return $this->call('POLL_Get', $params);
    }

    public function POLL_Ack($params): array
    {
        return $this->call('POLL_Ack', $params);
    }

    public function OIB_Search($params): array
    {
        return $this->call('OIB_Search', $params);
    }
}
