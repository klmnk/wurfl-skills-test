<?php
use ScientiaMobile\WurflCloud\Config;
use ScientiaMobile\WurflCloud\Cache\APC;
use ScientiaMobile\WurflCloud\Cache\Memcache;
use ScientiaMobile\WurflCloud\Cache\Memcached;
use ScientiaMobile\WurflCloud\Cache\File;
use ScientiaMobile\WurflCloud\Cache\NullCache;
use ScientiaMobile\WurflCloud\Cache\Cookie;
use ScientiaMobile\WurflCloud\Client;

/**
 * WURFL Cloud Singleton
 *
 * @package WurflCloud_Client
 * @subpackage Examples
 */
/**
 * WURFL Cloud Singleton
 *
 * An example of using a single class to easily get WURFL Capabilities.
 * Make sure you edit the $api_key in this file.
 *
 * You can use this class in your scripts like this:
 * <code>
 * // Include the MyWurfl.php file
 * require_once './MyWurfl.php';
 * // Get the is_wireless_device capability from the visiting device
 * $wireless = MyWurfl::get('is_wireless_device');
 * </code>
 *
 * @package WurflCloud_Client
 * @subpackage Example
 */
class MyWurfl
{
    /**
     * Enter your API Key here
     * @var string
     */
    private static $api_key = '';
    /**
     * Initialize static instance
     */
    private static function init()
    {
        require_once __DIR__.'/../src/autoload.php';
        // Additional configuration options can be used here
	self::$api_key = getenv('API_KEY');
        $config = new Config();
        $config->api_key = self::$api_key;
        
        // Set the cache that you'd like to use.  Here are some options:
        $cache = new Cookie();
        //$cache = new APC();
        //$cache = new Memcache();
        //$cache = new Memcached();
        //$cache = new File();
        
        // These two lines setup the Client and do the device detection
        self::$instance = new Client($config, $cache);
        self::$instance->detectDevice();
    }
    /**
     * Returns the value of the requested capability
     * @param string $capability_name
     * @return mixed Value of the requested capability
     */
    public static function get($capability_name)
    {
        if (self::$instance === null) {
            self::init();
        }
        return self::$instance->getDeviceCapability($capability_name);
    }
    public static function getInstance()
    {
        return self::$instance;
    }
    /**
     * @var WurflCloud_Client_Client
     */
    private static $instance;
}
