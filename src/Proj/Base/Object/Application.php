<?php
/**
 *
 */

namespace Proj\Base\Object;

use DbObject;
use IdGeneratorInterface;
use SessionAbstract;
use nil\session\SessionHandlerInterface;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\DependencyInjection\Container;
use Url;

/**
 * Class Application
 *
 * @package Proj\Base\Object
 */
class Application extends \Application {

    /**
     * @var string
     */
    private $rootDir;

    /**
     * @param string $rootDir
     */
    public static function _init($rootDir) {
        self::init();
        /** @noinspection PhpUndefinedFieldInspection */
        self::$_instance->rootDir = $rootDir;
    }

    public static function init() {
        if (self::$_instance === null) {
            self::$_instance = new self();
        }
    }

    /**
     * Nazev aplikace
     *
     * @return string
     */
    public function getName() {
        throw new Exception('Not implemented');
    }

    /**
     * Nazev modulu
     *
     * @return string
     */
    public function getSubApplicationName() {
        throw new Exception('Not implemented');
    }

    /**
     * @param bool $withEndSlash
     *
     * @return string
     */
    public function getSubApplicationPath($withEndSlash = true) {
        throw new Exception('Not implemented');
    }

    /**
     * @param string $plugin
     * @param bool   $relative
     * @param bool   $withEndSlash
     *
     * @return string
     */
    public function getPluginPath($plugin, $relative = false, $withEndSlash = true) {
        throw new Exception('Not implemented');
    }

    /**
     * @param bool $relative
     * @param bool $withEndSlash
     *
     * @return string
     */
    public function getPluginsPath($relative = false, $withEndSlash = true) {
        throw new Exception('Not implemented');
    }

    /**
     * Nazev majitele/autora aplikace
     *
     * @return string
     */
    public function getAuthor() {
        throw new Exception('Not implemented');
    }

    /**
     * @return SessionHandlerInterface
     */
    public function getSessionHandler() {
        throw new Exception('Not implemented');
    }

    /**
     * @param bool $withEndSlash
     *
     * @return string
     */
    public function getRootPath($withEndSlash = true) {
        return $this->rootDir . '/../web';
    }

    /**
     * @return string
     */
    public function getUrl() {
        throw new Exception('Not implemented');
    }

    /**
     * @param $scheme
     * @param null $portPart
     * @return string
     */
    public function getUrlWithScheme($scheme = null, $portPart = null) {
        $scheme = $scheme ?: $this->getScheme();
        $portPart = $portPart ?: Url::getPortPart($this->getPort($scheme), $scheme);
        $url = $this->getDomain();
        return "$scheme://$url$portPart/";
    }

    /**
     * @return string
     */
    public function getDomain() {
        throw new Exception('Not implemented');
    }

    /**
     * @param bool $withEndSlash
     *
     * @return string
     */
    public function getCacheDir($withEndSlash = true) {
        throw new Exception('Not implemented');
    }

    /**
     * @param bool $withEndSlash
     *
     * @return string
     */
    public function getTempDirectoryPath($withEndSlash = true) {
        throw new Exception('Not implemented');
    }

    /**
     * @return string
     */
    public function getDefaultDatabaseName() {
        throw new Exception('Not implemented');
    }

    public function getCachePersistenceGroup() {
        throw new Exception('Not implemented');
    }

    public function getDatabaseConfigurationFilePath() {
        throw new Exception('Not implemented');
    }

    /**
     * @param null|string $persistenceGroup
     *
     * @return string
     */
    public function getDefaultConnectionMode($persistenceGroup = null) {
        throw new Exception('Not implemented');
    }

    /** @return SessionAbstract */
    public function getSession() {
        throw new Exception('Not implemented');
    }

    public function getDatabaseInitConnectionString() {
        throw new Exception('Not implemented');
    }

    /**
     * @param string $key
     *
     * @return null
     */
    public function getSetting($key) {
        throw new Exception('Not implemented');
    }

    /**
     * @param $key
     *
     * @return mixed
     */
    public static function get($key) {
        throw new Exception('Not implemented');
    }

    /**
     * @param $key
     *
     * @return bool
     */
    public static function has($key) {
        throw new Exception('Not implemented');
    }

    /**
     * @param DbObject $object
     * @param array    $params
     *
     * @return IdGeneratorInterface
     */
    public function getIdGenerator($object, array $params = null) {
        throw new Exception('Not implemented');
    }

    /**
     * Cesta pro upload souboru
     *
     * @param bool $full
     *
     * @return string
     */
    public function getUploadPath($full = true) {
        $path = '/temp/';
        if ($full) {
            $path = $this->getRootPath() . $path;
        }
        return $path;
    }

    /**
     * @param bool $withEndSlash
     *
     * @return string
     */
    public function getDefaultViewPath($withEndSlash = true) {
        throw new Exception('Not implemented');
    }

    public function getCacheKeyPrefix() {
        throw new Exception('Not implemented');
    }

    /**
     * @return string
     */
    public function getTimezone() {
        return 'Europe/Prague';
    }

}