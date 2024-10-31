<?php

/**
 * @package PriorNotify
 */
namespace PriorNotify\Api\Callbacks;

use \PriorNotify\Api\RestfulApi;
use \PriorNotify\Base\URL;

/**
 * Class for PriorNotify Admin panel.
 */
class AdminCallbacks extends RestfulApi
{
    public $isUserLogged = false;
    /**
     * Render PriorNotify Admin Dashboard using Twig template engine
     */
    public function adminDashboard()
    {
        $this->tryLogin();
        if(URL::getURLParam('logout')=="true"){
            $this->logout();
        }

        $notifies = empty($notifies) ? NULL : $notifies;
        $data = array(
            'admin' => $this->config["admin"],
            'pluginUrl' => esc_url($this->plugin_url),
            'widgets' => $widgets_data['data']['widgets'],
            'errors' => $this->getErrorsFromWidgets($widgets_data),
            'site_url' => esc_url(URL::getSiteURL()),
            'notifies' => $notifies,
            'api' => $this->config["api"],
            'isUserLogged'=>$this->isUserLogged
        );
        echo $this->twig->render("admin.twig", $data);
    }

    /**
     * Read token from url and try to login
     */
    public function tryLogin(){
        $this->clearCookies();
        if(strlen(URL::getURLParam('user_id')) > 0 ){
            if(URL::getURLParam('success') === '1'){
                setcookie('priorNotifyToken', URL::getURLParam('user_id'), time() + ($this->config["api"]["tokenExpireAt"]),"/");
                setcookie('reload', '1');
                $this->isUserLogged = true;
            }

            $redirect_uri = esc_url_raw($this->config["api"]["baseUrl"] . "/connect-woocommerce/auth?success=1&token=".URL::getURLParam('user_id'));
            header('Location: ' . $redirect_uri, true, $this->config["api"]["REDIRECT_STATUS"]);
        } elseif (isset($_COOKIE['priorNotifyToken'])){
            setcookie('priorNotifyToken', sanitize_key($_COOKIE['priorNotifyToken']), time() + ($this->config["api"]["tokenExpireAt"]), '/');

            $url = esc_url_raw($this->config["api"]["apiUrl"]. "/users/me/woocommerce-connection");
            $args = array(
                'headers' => array(
                    'x-woocommerce-plugin-token' => 'Token ' . sanitize_key($_COOKIE['priorNotifyToken'])
                )
            );
            
            $response = wp_remote_get( $url, $args );
            if ( is_array( $response ) && ! is_wp_error( $response ) ) {
                $body = json_decode($response['body'], true);
                if (is_array( $body )  && is_array( $body['data'] )) {
                    if (strlen($body['data']['host'])) {
                        $this->isUserLogged = true;
                    }
                }
            }
        }
    }

    public function clearCookies(){
        // unset cookies
        if (isset($_SERVER['HTTP_COOKIE'])) {
            $cookie = 'priorNotifyToken';
            $parts = explode('=', $cookie);
            $name = trim($parts[0]);
            setcookie($name, '', 1);
            setcookie($name, '', 1, '/'); 
        }
    }

    /**
     * Check errors from widgets
     *
     * @param object $widget_data object with widgets and error 
     * @return array array of errors or NULL
     */
    function getErrorsFromWidgets($widgets_data){
        $errors = [];
        if(!empty($widgets_data['error'])){
            $errors[] = $widgets_data['error'];
        }
        return  empty($errors) ? NULL : $errors;
    }
    /**
     * Clear user token in cookies
     */
    public function logout(){
        $url = esc_url_raw($this->config["api"]["apiUrl"]. "/integrations/woocommerce/logout");
        $args = array(
            'body'        => array(),
            'timeout'     => '5',
            'redirection' => '5',
            'httpversion' => '1.0',
            'blocking'    => true,
            'headers'     => array(
                'x-woocommerce-plugin-token' => 'Token ' . sanitize_key($_COOKIE['priorNotifyToken'])
            ),
            'cookies'     => array(),
        );

        $response = wp_remote_post( $url, $args );

        $this->isUserLogged = false;
        $this->clearCookies();
    }
}
