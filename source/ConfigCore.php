<?php

namespace fmihel\config;


/** 
 * загрузка файла конфигурации
*/
class ConfigCore{

    public $param=[];
    
    private $settings=[
        'fileName'=>'config.php',
        'configVarName'=>'config',
        'templateVarName'=>'configTemplate',
        'appPath'=>'',
        'template'=>'',
    ];
    
    function __construct(Array $settings=[]){
        $appPath = dirname($_SERVER['SCRIPT_FILENAME']).'/';
        $template = $appPath.'config.template.php';
        $this->settings = array_merge(
            $this->settings,
            [
                'appPath'=>$appPath,
                'template'=>$template,
            ],
            $settings
        );

        if (file_exists($this->settings['fileName']))
            $this->loadFromFile($this->settings['fileName'],true);

    }
    /** 
     * загрузка конфига из файла ( объединяется с текущей конфигурацией) 
     * 
    */
    public function loadFromFile(string $file,bool $testTemplate=false){
        $configVarName = $this->settings['configVarName'];
        require_once $file;
        $this->param = array_merge_recursive($this->param,${$configVarName});
        if ($testTemplate)
            $this->testTemplate();
        
    }
    /** сравнение шаблона и загруженного конфига */
    private function testTemplate(){
        if (file_exists($this->settings['template'])){
            $templateVarName = $this->settings['templateVarName'];
            require_once $this->settings['template'];
            $template = ${$templateVarName};
            //error_log(print_r($template,true));
            $keys = array_keys($template);
            $errors = [];
            $warns  = [];
            foreach($keys as $key){
                if (!isset($this->param[$key])){
                    $errors[] = "config var [$key] not exists, need define [$key] as ".$template[$key];
                }
            };
            $keys = array_keys($this->param);
            foreach($keys as $key){
                if (!isset($template[$key])){
                    $warns[] = "[$key] is define in config,but not defined in template";
                }
            };

            if (count($errors)>0){
                echo '<body style="background:white;color:black;font-family:Courier;font-size:12px">';
                echo '<div style="background:gray;color:white;padding:2px">Config test stop webapp:</div>';
                foreach($errors as $error)
                    echo 'Error: '.$error."<br>";
                foreach($warns as $warn)
                    echo 'Warn  : '.$warn."<br>";
                echo '</body>';
                exit(0);
            };
            
            if (count($warns)>0){
                foreach($warns as $warn)
                error_log('Warn: '.$warn);
            }
        }
    }
    
    public function define($name,$mean){
        if (!isset($this->param[$name])){
            $this->param[$name] = $mean;
        }
    }

    /** 
     * name || name ,default
    */
    public function get(...$param){
        
        $count = count($param);
        if ($count === 0)
            throw new \Exception("need set one or two params", 0);
            
        $name = $param[0];        

        if ( !isset($this->param[$name]) ){
            if ($count === 1)    
                throw new \Exception("param [$name] is not exists", 0);
            return $param[1];
        };

        return $this->param[$name];
    }

}
