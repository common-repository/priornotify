<?php

/*
 * Base controller of PriorNotify
 * 
 * @package PriorNotify
 */
namespace PriorNotify\Base;
use Symfony\Component\Yaml\Yaml as SymfonyYamlParser;
use ScssPhp\ScssPhp\Compiler;
use Twig\Loader\FilesystemLoader;
use Twig\Environment;

class BaseController
{
    /*
     * Path to priorNotify plugin directory
     * 
     * @var string
     */
    public $plugin_path;
    /*
     * Url to priorNotify plugin, e.g. https://baseurl/wp-content/plugins/priorNotify-plugin
     * 
     * @var string
     */
    public $plugin_url;
    /*
     * Name of priorNotify plugin, e.g. priorNotify-plugin/priorNotify-plugin.php
     * 
     * @var string
     */
    public $plugin;
    /*
     * All configurations from ../../config/config.yml
     * 
     * @var array
     */
    public $config;
    /*
     * Instanse of class FilesystemLoader
     * 
     * @var array
     */
    public $loader;
    /*
     * Instanse of class Environment
     * 
     * @var array
     */
    public $twig;

    public function __construct()
    {
        $this->plugin_path = plugin_dir_path( dirname( __FILE__, 2 ) );
        $this->plugin_url = plugin_dir_url( dirname( __FILE__, 2 ) );
        $this->plugin = plugin_basename( dirname( __FILE__, 3 ) ) . '/priorNotify-plugin.php';
        $this->config = $this->getConfigurationsFromFile('config.yml');
        $this->loader = new FilesystemLoader($this->plugin_path."templates/");
        $this->twig = new Environment($this->loader);
        $this->compileScssFromFile($this->plugin_path.$this->config['general']['pathToCss'], 'priorNotify');
    }

    /*
     * Compile SCSS file to CSS and compress it.
     * 
     * @param string path to the SCSS file
     * @param string name of the file that should be compiled to CSS
     */
    public function compileScssFromFile($filePath, $fileName)
    {
        $scss = new Compiler();
        $scss->setFormatter('ScssPhp\ScssPhp\Formatter\Compressed');
        $cssOut = $scss->compile(file_get_contents($filePath.$fileName.'.scss'));
        file_put_contents(wp_upload_dir().$fileName.'.min.css', $cssOut);
    }

    /*
     * Get congig file by file name from config directory and parse it
     * 
     * @param string name of the config file
     */
    public function getConfigurationsFromFile($fileName)
    {
        $parser = new SymfonyYamlParser();
        return $parser->parse(file_get_contents($this->plugin_path.'config/'.$fileName));
    }

    /*
     * Flush WordPress rewrite rules when activating a theme of plugin.
     */
    public static function activate()
    {
        flush_rewrite_rules();
    }
}