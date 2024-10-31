<?php

/**
 * @package PriorNotify
 */
namespace PriorNotify;

final class Init
{
    /**
     * Store all the classes iside an array
     * @return array Full list of classes
     */
    public static function get_services()
    {
        return [
            Pages\Admin::class,
            Base\Enqueue::class,
            Base\SettingsLinks::class,
        ];
    }

    /**
     * Loop throught the classes, initialize them,
     * and call the register() method if it exists
     * @return
     */
    public static function register_services() 
    {
        foreach (self::get_services() as $class) {
            $service = self::instantiate( $class );
            if ( method_exists( $service, 'register' ) ) {
                $service->register();
            }
        }
    }

    /**
     * Initialize the class
     * @param class $class class from the services array
     * @return class instance new instance of the class
     */

    private static function instantiate($class)
    {
        return new $class();
    }

    /**
     * Initialization basic scripts for PriorNotify
     */
    public static function initScriptUrls() {
        add_action('wp_footer', function () {
            $baseController = new Base\BaseController();
            ?>
            <script type="text/javascript" >
                window.priorNotifyBackendUrl = '<?php echo esc_url($baseController->config["api"]["baseUrl"]); ?>';
                const siteUrl = "<?php echo(esc_url(admin_url('admin-ajax.php'))); ?>";
                const plugin_url = '<?php echo esc_url($baseController->plugin_url); ?>';
                const contentFolder = '<?php echo str_replace(ABSPATH, '', content_url()) ?>'
            </script>
            <?php
        }, 9);
    }
}
