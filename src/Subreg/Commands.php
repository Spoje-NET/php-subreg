<?php

namespace Subreg;

class Commands extends Client
{
    public function Check_Domain($params)
    {
        return $this->call('Check_Domain', $params);
    }

    public function Info_Domain($params)
    {
        return $this->call('Info_Domain', $params);
    }

    public function Info_Domain_CZ($params)
    {
        return $this->call('Info_Domain_CZ', $params);
    }

    public function Domains_List($params)
    {
        return $this->call('Domains_List', $params);
    }

    public function Set_Autorenew($params)
    {
        return $this->call('Set_Autorenew', $params);
    }

    public function In_Subreg($params)
    {
        return $this->call('In_Subreg', $params);
    }

    public function Get_Redirects($params)
    {
        return $this->call('Get_Redirects', $params);
    }

    public function Create_Contact($params)
    {
        return $this->call('Create_Contact', $params);
    }

    public function Update_Contact($params)
    {
        return $this->call('Update_Contact', $params);
    }

    public function Info_Contact($params)
    {
        return $this->call('Info_Contact', $params);
    }

    public function Contacts_List($params)
    {
        return $this->call('Contacts_List', $params);
    }

    public function Check_Host($params)
    {
        return $this->call('Check_Host', $params);
    }

    public function Info_Host($params)
    {
        return $this->call('Info_Host', $params);
    }

    public function Create_Host($params)
    {
        return $this->call('Create_Host', $params);
    }

    public function Update_Host($params)
    {
        return $this->call('Update_Host', $params);
    }

    public function Delete_Host($params)
    {
        return $this->call('Delete_Host', $params);
    }

    public function Check_Object($params)
    {
        return $this->call('Check_Object', $params);
    }

    public function Info_Object($params)
    {
        return $this->call('Info_Object', $params);
    }

    public function Make_Order($params)
    {
        return $this->call('Make_Order', $params);
    }

    public function Info_Order($params)
    {
        return $this->call('Info_Order', $params);
    }

    public function Cancel_Order($params)
    {
        return $this->call('Cancel_Order', $params);
    }

    public function Get_Credit($params)
    {
        return $this->call('Get_Credit', $params);
    }

    public function Get_Accountings($params)
    {
        return $this->call('Get_Accountings', $params);
    }

    public function Client_Payment($params)
    {
        return $this->call('Client_Payment', $params);
    }

    public function Order_Payment($params)
    {
        return $this->call('Order_Payment', $params);
    }

    public function Credit_Correction($params)
    {
        return $this->call('Credit_Correction', $params);
    }

    public function Pricelist($params)
    {
        return $this->call('Pricelist', $params);
    }

    public function Special_Pricelist($params)
    {
        return $this->call('Special_Pricelist', $params);
    }

    public function Prices($params)
    {
        return $this->call('Prices', $params);
    }

    public function Get_TLD_Info($params)
    {
        return $this->call('Get_TLD_Info', $params);
    }

    public function Get_Pricelist($params)
    {
        return $this->call('Get_Pricelist', $params);
    }

    public function Set_Prices($params)
    {
        return $this->call('Set_Prices', $params);
    }

    public function Download_Document($params)
    {
        return $this->call('Download_Document', $params);
    }

    public function Upload_Document($params)
    {
        return $this->call('Upload_Document', $params);
    }

    public function List_Documents($params)
    {
        return $this->call('List_Documents', $params);
    }

    public function Users_List($params)
    {
        return $this->call('Users_List', $params);
    }

    public function Info_User($params)
    {
        return $this->call('Info_User', $params);
    }

    public function Get_DNS_Zone($params)
    {
        return $this->call('Get_DNS_Zone', $params);
    }

    public function Add_DNS_Zone($params)
    {
        return $this->call('Add_DNS_Zone', $params);
    }

    public function Delete_DNS_Zone($params)
    {
        return $this->call('Delete_DNS_Zone', $params);
    }

    public function Set_DNS_Zone($params)
    {
        return $this->call('Set_DNS_Zone', $params);
    }

    public function Add_DNS_Record($params)
    {
        return $this->call('Add_DNS_Record', $params);
    }

    public function Modify_DNS_Record($params)
    {
        return $this->call('Modify_DNS_Record', $params);
    }

    public function Delete_DNS_Record($params)
    {
        return $this->call('Delete_DNS_Record', $params);
    }

    public function Get_DNS_Info($params)
    {
        return $this->call('Get_DNS_Info', $params);
    }

    public function Sign_DNS_Zone($params)
    {
        return $this->call('Sign_DNS_Zone', $params);
    }

    public function Unsign_DNS_Zone($params)
    {
        return $this->call('Unsign_DNS_Zone', $params);
    }

    public function Anycast_Add_Zone($params)
    {
        return $this->call('Anycast_Add_Zone', $params);
    }

    public function Anycast_Remove_Zone($params)
    {
        return $this->call('Anycast_Remove_Zone', $params);
    }

    public function Anycast_List_Domains($params)
    {
        return $this->call('Anycast_List_Domains', $params);
    }

    public function Anycast_Domain_Statistics($params)
    {
        return $this->call('Anycast_Domain_Statistics', $params);
    }

    public function Get_Certificate($params)
    {
        return $this->call('Get_Certificate', $params);
    }

    public function POLL_Get($params)
    {
        return $this->call('POLL_Get', $params);
    }

    public function POLL_Ack($params)
    {
        return $this->call('POLL_Ack', $params);
    }

    public function OIB_Search($params)
    {
        return $this->call('OIB_Search', $params);
    }
}