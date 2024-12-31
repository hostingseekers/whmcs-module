<?php

/*
 * custom api to get products details with custom fileds,config options, prices
 */


if (!defined("WHMCS")) {
    exit("This file cannot be accessed directly");
}


global $currency;

$currency = getCurrency();
$gid = $whmcs->get_req_var("gid");


try {
    
    
    if(empty((int)$gid))
    {
        throw new Exception('Product group id is required.');
    }
    
    $orderfrm = new WHMCS\OrderForm();
    $productGroup = WHMCS\Product\Group::find($gid);

    $apiresults["gid"] = $gid;
    
    $apiresults["groupname"] = WHMCS\Product\Group::getGroupName($gid, $productGroup->name);

    $products = array();

    $products = $orderfrm->getProducts($productGroup, true, true);
    
    $apiresults["currency"] = $currency;
    $apiresults["products"] = $products;
    $apiresults["productscount"] = count($products);
    
} catch (Exception $e) {
    $apiresults = array("result" => "error", "message" => $e->getMessage());
}
