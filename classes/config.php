<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * this file contains the tool config variables.
 *
 * File         CONFIG.php
 * Encoding     UTF-8
 * @copyright   Sebsoft.nl
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace tool_usersuspension;

defined('MOODLE_INTERNAL') || die;

/**
 * tool_usersuspension\config
 *
 * @package     tool_usersuspension
 *
 * @copyright   Sebsoft.nl
 * @author      R.J. van Dongen <rogier@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class config {

    /**
     *
     * @var whether or not config has been loaded yet
     */
    static private $loaded = false;

    /**
     *
     * @var \stdClass the tool configuration
     */
    static private $config = null;

    /**
     * initialize the tool configuration
     */
    static private function init() {
        if (!self::$loaded) {
            self::$config = get_config('tool_usersuspension');
        }
    }

    /**
     * get a configuration value
     *
     * @param string $name config name
     * @return mixed
     */
    public static function get($name) {
        self::init();
        if (isset(self::$config->$name)) {
            return self::$config->$name;
        }
        return null;
    }

    /**
     * set a configuration value
     *
     * @param string $name config name
     * @param mixed $value config value
     * @param bool $force force insert if this value does not exist
     * @return mixed
     */
    public static function set($name, $value, $force = true) {
        self::init();
        if (isset(self::$config->$name) || $force) {
            self::$config->$name = $value;
            set_config($name, $value, 'tool_usersuspension');
        }
        return self::$config->$name;
    }

    /**
     * invalidates the cached configuration and reloads from database
     */
    public static function invalidate() {
        self::$loaded = false;
        self::$config = null;
        self::init();
    }

}