<?php

/**
 * Class for managing the shortcode of PriorNotify
 * 
 * @package PriorNotify
 */
namespace PriorNotify\Base;

use PriorNotify\Base\BaseController;

/**
 * Class with methods for making operation with URL
 */
class URL extends BaseController
{
    /**
     * Get current site url
     *
     * @return string url site url
     */
    public static function getSiteURL(){
        $protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || 
            $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        return $protocol.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    }

    /**
     * Removes parameter '$key' from '$sourceURL' query string (if present)
     *
     * @param string $key -- query param name
     * @param string $sourceURL 
     * @return string new url without $key param 
     */
    public static function removeParam($key, $sourceURL) {
        $url = parse_url($sourceURL);
        if (!isset($url['query'])) return $sourceURL;
        parse_str($url['query'], $query_data);
        if (!isset($query_data[$key])) return $sourceURL;
        unset($query_data[$key]);
        $url['query'] = http_build_query($query_data);
        $port = $url['port'] ? ':'.$url['port'] : ''; 
        return $url['scheme'].'://'.$url['host'].$port.$url['path'].'?'.$url['query'];
    }

    /**
     * Read query param from url
     *
     * @param string $param - name of param
     * @return string param value or empty string
     */
    public static function getURLParam($param){
        $actual_link = "$_SERVER[REQUEST_URI]";
        $query_str = parse_url($actual_link, PHP_URL_QUERY);
        
        parse_str($query_str, $query_params);
        if(isset($query_params[$param])){
            return $query_params[$param];
        }
        return "";
    }
}