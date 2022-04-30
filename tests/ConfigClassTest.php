<?php
namespace fmihel\config\test;

use PHPUnit\Framework\TestCase;
use fmihel\config\ConfigCore;

$config = null;
function getConfig(){
    global $config;
    if ($config === null){
        $config = new ConfigCore(['template'=>__DIR__.'/data/config.template.php']);
        $config->loadFromFile(__DIR__.'/data/config.php',true);
    }
    return $config;
}

final class ConfigTest extends TestCase{

    public function test_get(){
        // --------------------------------------------
        $config = getConfig();
        // --------------------------------------------
        $res = $config->get('var1');
        //error_log($res);
        self::assertTrue($res === true);
        // --------------------------------------------
        $res = $config->get('noexists','default');
        self::assertTrue($res === 'default');
        // --------------------------------------------
        $this->expectException(\Exception::class);
        $res = $config->get('noexists');
        // --------------------------------------------
    }

    public function notest_define(){
        // --------------------------------------------
        $config = getConfig();
        // --------------------------------------------
        $config->define('load','newloadmean');
        $res = $config->get('load');
        self::assertTrue($res === 'ok');
        // --------------------------------------------
        $config->define('load2','newload2mean');
        $res = $config->get('load2');
        self::assertTrue($res === 'newload2mean');
        // --------------------------------------------
        $res = $config->get('load2');
        self::assertTrue($res === 'newload2mean');
    }
    

}

?>