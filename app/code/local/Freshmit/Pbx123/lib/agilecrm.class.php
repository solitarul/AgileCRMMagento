<?php

/*
 * Agile CRM Magento Extension
 * Version: 1.0
 * Author: Purushotham Reddy J
 * Release date: Feb 26, 2015
 * Magento compatible versions >= 1.7.0.2
 */

class AgileCRM_Customer
{

    public $first_name, $last_name, $company, $email, $phone, $address;

    public function getAgileFormat()
    {

        $agileData = array();
        $agileData['properties'] = array();

        if ($this->first_name) {
            $agileData['properties'][] = array(
                "name" => "first_name",
                "value" => $this->first_name,
                "type" => "SYSTEM"
            );
        }

        if ($this->last_name) {
            $agileData['properties'][] = array(
                "name" => "last_name",
                "value" => $this->last_name,
                "type" => "SYSTEM"
            );
        }

        if ($this->company) {
            $agileData['properties'][] = array(
                "name" => "company",
                "value" => $this->company,
                "type" => "SYSTEM"
            );
        }

        if ($this->email) {
            $agileData['properties'][] = array(
                "name" => "email",
                "value" => $this->email,
                "type" => "SYSTEM"
            );
        }

        if ($this->phone) {
            $agileData['properties'][] = array(
                "name" => "phone",
                "value" => $this->phone,
                "type" => "SYSTEM"
            );
        }

        if ($this->address) {
            $agileData['properties'][] = array(
                "name" => "address",
                "value" => json_encode($this->address),
                "type" => "SYSTEM"
            );
        }
        return $agileData;
    }

}

class AgileCRM_Address
{

    public $address, $city, $state, $zip, $country;

}

class AgileCRM_Product
{

    public $id, $name, $cost, $quantity, $sku;

}

class AgileCRM_Order
{

    public $id, $status, $billingAddress, $shippingAddress, $grandTotal, $products = array(), $note, $paymentMethod;

}

class AgileCRM
{

    public static $VERSION = '1.0';
    private $endPoint = 'https://%s.agilecrm.com/ecommerce?api-key=%s';
    private $pluginType = 'Magento';
    public static $hooks = array(
        "customer.created" => "CUSTOMER_CREATED",
        "customer.updated" => "CUSTOMER_UPDATED",
        "order.created" => "ORDER_CREATED",
        "order.updated" => "ORDER_UPDATED",
        "note.created" => "NOTE_CREATED",
    );
    public $hook, $payLoad, $customerEmail;

    public function post()
    {
        $AGILEWC_DOMAIN = Mage::getStoreConfig(Freshmit_Pbx123_Helper_Data::XML_PATH_SITE);
        $AGILEWC_KEY = Mage::getStoreConfig(Freshmit_Pbx123_Helper_Data::XML_PATH_API_KEY);

        if ($AGILEWC_DOMAIN && $AGILEWC_KEY) {
            $postData = array(
                'email' => $this->customerEmail,
                'hook' => $this->hook,
                'payLoad' => json_encode($this->payLoad),
                'pluginType' => $this->pluginType
            );

            $curl = new Curl();
            $curl->post(sprintf($this->endPoint, $AGILEWC_DOMAIN, $AGILEWC_KEY), $postData);
            $resp = (array) $curl->response;
            return isset($resp['success']);
        }
        return false;
    }

}
