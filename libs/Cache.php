<?php
/**
 * NG Framework
 * Version 0.1 Beta
 * Copyright (c) 2012, Nick Gejadze
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy 
 * of this software and associated documentation files (the "Software"), 
 * to deal in the Software without restriction, including without limitation 
 * the rights to use, copy, modify, merge, publish, distribute, sublicense, 
 * and/or sell copies of the Software, and to permit persons to whom the 
 * Software is furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included 
 * in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, 
 * INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A 
 * PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR 
 * COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, 
 * WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, 
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */

namespace NG;

/**
 * Cache
 * @package NG
 * @subpackage library
 * @version 0.1
 * @copyright (c) 2012, Nick Gejadze
 */
class Cache {
    /**
     * $instance
     * Holds Class Instance
     * @access protected
     * @var object
     */
    protected static $instance = null;
    protected static $cacheDir;
    
    public static function init() {
        if (self::$instance === null):
            self::$instance = new Cache;
            self::$cacheDir = ROOT . DS . TMP . DS . CACHE;
        endif;
        return self::$instance;
    }
    
    private static function validateCacheDir($dir) {
        if (!is_dir($dir)):
            mkdir($dir, 0755, true);
        endif;
    }
    
    private static function cacheFile($key) {
        $cacheFilename = sha1($key);
        $subdir = DS . substr($cacheFilename, 0, 3);
        self::validateCacheDir(self::$cacheDir . $subdir);
        return sprintf("%s/%s", self::$cacheDir . $subdir, sha1($key));
    }
    
    public static function set($key, $data) {
        $cacheFilePath = self::cacheFile($key);
        if (!$fp = fopen($cacheFilePath, 'wb')):
            return false;
        endif;
        if (flock($fp, LOCK_EX)):
            fwrite($fp, serialize($data));
            flock($fp, LOCK_UN);
        else:
            return false;
        endif;
        fclose($fp);
        return true;
    }
    
    public static function get($key, $expiration = 86400) {
        $cacheFilePath = self::cacheFile($key);
        if (!@file_exists($cacheFilePath)):
            return false;
        endif;
        if (filemtime($cacheFilePath) < (time() - $expiration)):
            self::delete($key);
            return false;
        endif;
        if (!$fp = @fopen($cacheFilePath, 'rb')):
            return false;
        endif;
        flock($fp, LOCK_SH);
        $cache = unserialize(fread($fp, filesize($cacheFilePath)));
        flock($fp, LOCK_UN);
        fclose($fp);
        return $cache;
    }

    public static function delete($key) {
        $cache_path = self::cacheFile($key);
        if (file_exists($cache_path)):
            unlink($cache_path);
            return true;
        endif;
        return false;
    }
    
}