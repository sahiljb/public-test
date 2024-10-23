<?php

if (! function_exists('getPaginationCount')) {

    function getPaginationCount(){
        return 10;
    }

}

if (! function_exists('layoutConfig')) {
    function layoutConfig() {

        if (Request::is('siteadmin/*')) {
           
            $__getConfiguration = Config::get('app-config.layout.vlm');

            
        } else if (Request::is('collapsible-menu/*')) {
            
            $__getConfiguration = Config::get('app-config.layout.cm');
            
        } 

        

        // Login

        else if (Request::is('signin')) {

            $__getConfiguration = Config::get('app-config.layout.vlm');
            
        } else {
            $__getConfiguration = Config::get('barebone-config.layout.bb');
        }

        return $__getConfiguration;
    }
}


if (!function_exists('getRouterValue')) {
    function getRouterValue() {
        
        if (Request::is('siteadmin/*')) {
            
            $__getRoutingValue = '/siteadmin';
            
        } else if (Request::is('collapsible-menu/*')) {
            
            $__getRoutingValue = '/collapsible-menu';

        }

        // Login

        else if (Request::is('login')) {

            $__getRoutingValue = '/siteadmin';
            
        } else {
            $__getRoutingValue = '';
        }
        
        
        return $__getRoutingValue;
    }
}