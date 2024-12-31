<?php

/*
 * custom api to get product groups
 * 
 * created on 30-July 2024
 */

if (!defined("WHMCS")) 
{
    exit("This file cannot be accessed directly");
}

$gid = $whmcs->get_req_var("gid");


try 
{
    $productGroupModel = WHMCS\Product\Group::select('id','name','slug','headline','tagline')->where('hidden',0);
    
    if ((int)$gid) 
    {
        $productGroupModel->where('id',(int)$gid);
    }
    
    $productGroup = $productGroupModel->get()->toArray();
    
    $apiresults = array("result" => "success", "groups" => $productGroup);
} 
catch (Exception $e) 
{
    $apiresults = array("result" => "error", "message" => $e->getMessage());
}
