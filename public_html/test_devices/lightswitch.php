<?php

require_once '../../lib/Growl/Autoload.php';
/**
 * /command
 *
 * /command/on          (bool) success/failure
 * /command/off         (bool) success/failure
 * /command/toggle      (string) status "on" or "off"
 */

/**
 * /status
 *
 * /status/get          (string) status "on" or "off"
 * /status/isOn         (bool)
 * /status/isOff        (bool)
 */

$lightSwitch = new LightSwitch();

switch($_GET['action']) {
    default:
        header('HTTP/1.0 404 Not Found');
        exit();

    case 'command':
        $result = $lightSwitch->command($_GET['type']);
        break;

    case 'status':
        $result = $lightSwitch->status($_GET['type']);
        break;
}

// Output Result
header('Content-Type: application/json');
echo json_encode($result);

class LightSwitch {
    const STATUS_ON = "on";
    const STATUS_OFF = "off";

    const COMMAND_ON = "on";
    const COMMAND_OFF = "off";
    const COMMAND_TOGGLE = "toggle";

    protected $_status = self::STATUS_OFF;
    protected $_growl;

    /**
     * Initializes a new light switch virtual device.
     */
    public function __construct($loadFromCache = true) {
        if($loadFromCache) {
            $this->_loadFromCache();
        }

        // Initialize Growl Notifications
        $name = 'Light Switch';
        $this->_growl = Net_Growl::singleton(
            $name,
            array(
                'GROWL_NOTIFY_STATUS' => array(
                    'display' => 'Status',
                ),
                'GROWL_NOTIFY_PHPERROR' => array(
                    'display' => 'Error-Log'
                )
            ),
            '',
            array(
                'protocol' => 'gntp',
                'timeout'  => 15,
            )
        );

        $this->_growl->register();
    }

    /**
     * Writes the current status to a persistent cache.
     */
    public function __destruct() {
        file_put_contents('cache', json_encode(array('status' => $this->_status)));
    }

    /**
     * Loads cached state from persistent resource.
     *
     * @return void
     */
    protected function _loadFromCache() {
        $cache = json_decode(file_get_contents('cache'));

        $this->_status = $cache->status;
    }

    /**
     * Sents the specified type of command to the switch.
     *
     * @param string $type the type of command received
     * @param array $args optional additional arguments
     *
     * @return bool|string whether or not the command was successful, or the new status if toggled
     */
    public function command($type, $args = array()) {
        switch($type) {
            default:
                header('HTTP/1.0 404 Not Found');
                return false;

            case 'toggle':
                if($this->_status == self::STATUS_ON) {
                    return $this->command(self::STATUS_OFF);
                }
                else {
                    return $this->command(self::STATUS_OFF);
                }

            case self::STATUS_ON:
                $this->_status = self::STATUS_ON;
                $this->_growl->publish('GROWL_NOTIFY_STATUS', '', 'Turning On!');
                break;

            case self::STATUS_OFF:
                $this->_status = self::STATUS_OFF;
                $this->_growl->publish('GROWL_NOTIFY_STATUS', '', 'Turning Off!');
                break;
        }

        return true;
    }

    /**
     * Retrieves the current status of the switch.
     *
     * @param $type the type of status request
     * @param array $args optional additional arguments
     *
     * @return string the current status
     */
    public function status($type, $args = array()) {
        switch($type) {
            case 'get':
                return $this->_status;

            case 'isOn':
                return $this->_status == self::STATUS_ON;

            case 'isOff':
                return $this->_status == self::STATUS_OFF;
        }
    }
}