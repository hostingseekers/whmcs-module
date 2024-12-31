<?php

/**
 * 
 * Manage api role for hosting seekers
 * 
 */

if (!defined("WHMCS")) 
{
    exit("This file cannot be accessed directly");
}

function apiroles_readApiFiles()
{
    $path = ROOTDIR . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'api';

    $files = [];
    if ($handle = opendir($path)) {

        while (false !== ($entry = readdir($handle))) {

            if ($entry != "." && $entry != ".." && strpos($entry, '.php') && $entry != 'index.php') {
                $files[] = str_replace('.php', '', $entry);
            }
        }

        closedir($handle);
        return $files;
    }
}

function apiroles_checkGroupOrAdd()
{
    $apiCatalogGroups =  \WHMCS\Api\V1\Catalog::get()->getGroups();
    
    if (!array_key_exists('ADD_YOUR_GROUP_KEY', $apiCatalogGroups))
    {
        \WHMCS\Api\V1\Catalog::add([], ['ADD_YOUR_GROUP_KEY' => array('name' => 'Hosting Seekers API')]);
    }
}

function apiroles_checkApiOrAdd()
{
    $api_files = apiroles_readApiFiles();
    $apiCatalogActions =  \WHMCS\Api\V1\Catalog::get()->getActions();

    foreach ($api_files as $k => $api) {
        if (!array_key_exists($api, $apiCatalogActions)) {
            \WHMCS\Api\V1\Catalog::add(array($api => array(
                'group' => 'ADD_YOUR_GROUP_KEY',
                'name' => apiroles_formatFileName($api),
                'default' => 0
            )));
        }
    }
}


function apiroles_formatFileName($filename)
{
    $hs_api_files = array(
        'getproductgroupshs'=> 'GetProductGroupsHS',
        'getproductsbygidhs'=> 'GetProductsBygidHS',
    );
    
    if (!empty($hs_api_files[$filename]))
    {
        return $hs_api_files[$filename];
    }
    return $filename;
}


add_hook('AdminAreaPage', 1, function () 
{
    apiroles_checkGroupOrAdd();
    apiroles_checkApiOrAdd();
});