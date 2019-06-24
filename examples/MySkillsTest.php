<?php
/**
 * WURFL Cloud Client - Simple example using MyWurfl
 * @package WurflCloud_Client
 * @subpackage Examples
 *
 * This example uses the included MyWurfl class to get device capabilities.
 * If you prefer to use the WURFL Cloud Client directly, see show_capabilities.php
 *
 * For this example to work properly, you must put your API Key in MyWurfl.php
 * and ensure that you have the following capabilities in your account:
 *  - ux_full_desktop
 *  - brand_name
 *  - model_name
 *
 * (see below to run this example without the above capabilities)
 */
/**
 * Include the MyWurfl.php file
 */
require_once __DIR__.'/MyWurfl.php';


class MySkillsTest
{

    private static $file;
    private static $deviceData;
    private static $rows;
    private static $fh;
    /**
     * Initialize static instance
     */
    public static function init() {
        self::$file = "/var/tmp/output.tsv";
        self::$deviceData = array();
            self::$rows = array();
    }

    /**
     * Get device data
     */
    public static function getDevice()
    {
        try {
        // Check if the device is mobile
        if (MyWurfl::get('is_mobile')) {
            $is_mobile = "This is a mobile device";
        } else {
            $is_mobile = "This is a desktop browser";
        // If you don't have 'brand_name' and 'model_name', you can comment out this line to run the example
        }
        $complete_device_name = MyWurfl::get('complete_device_name');
        $form_factor          = MyWurfl::get('form_factor');
        }
        catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }

        self::$deviceData = array($is_mobile, $complete_device_name, $form_factor);
    }

    /**
     * Open TSV file to read the data
     */
    public static function openTsvFileToRead()
    {
        try {
            self::$fh = fopen(self::$file, "r");
            if(! self::$fh) {
                throw new Exception("Can't not open " . "'" . self::$file . "'". "file!");
            }
        }
        catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }  
    }

    /**
     * Open TSV file to write the data
     */
    public static function openTsvFileToWrite()
    {
        try {
            self::$fh = fopen(self::$file, "a+");
            if(! self::$fh) {
                throw new Exception("Can't not open " . "'" . self::$file . "'". "file!");
            }
        }
        catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }  
    }

    /**
     * Save one record of device data to TSV file
     */
    public static function saveRecord()
    {
        self::openTsvFileToWrite();
        $row = implode("\t", self::$deviceData) . "\n";
        fwrite(self::$fh, $row);
        fclose(self::$fh);
    }
    
    /**
     * Read all data from TSV file
     */
    public static function readTsvFile()
    {
        self::openTsvFileToRead();
        self::$rows = array();  
        while(! feof(self::$fh)){
            $row = explode("\t", fgets(self::$fh));
            //print_r($result);
                if(count($row) > 1){
                    array_push(self::$rows, $row);
                }
        }
        fclose(self::$fh);
    }

    /**
     * Draw HTML table from TSV file
     */
    public static function drawHtmlTable()
    {
        echo '<h3>' . "WURFL" .'</h3>';
        echo '<table style="width:50%" border="1">';
        echo '<tr ><th>Type</th><th>Name</th><th>Factor</th></tr>';
            foreach (self::$rows as $row) {
              echo '<tr>';
              echo '<td style="text-align:center">' . $row[0] . '</td><td style="text-align:center">' . $row[1] . '</td><td style="text-align:center">' . $row[2] . '</td>';
              echo '</tr>';
            }
        echo '</table><br />';
    }

}

MySkillsTest::init();
MySkillsTest::getDevice();
MySkillsTest::openTsvFileToWrite();
MySkillsTest::saveRecord();
MySkillsTest::readTsvFile();
MySkillsTest::drawHtmlTable();

?>
