<?php

namespace fmihel\config;


/** 
 * загрузка файла конфигурации
*/
class ConfigCore{

    public $param=[];
    private $_test = false;

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
            $this->loadFromFile($this->settings['fileName']);
        if (file_exists($this->settings['template']))
            $this->test();
    }
    /** 
     * загрузка конфига из файла (добавит или перезапишет текущую конфигурацию) 
     * 
    */
    public function loadFromFile(string $file){
        $configVarName = $this->settings['configVarName'];
        require_once $file;
        $this->param = array_merge($this->param,${$configVarName});
    }
    /** сравнение шаблона и загруженного конфига */
    public function test(string $templateFileName=''){

        if ($templateFileName==='')
            $templateFileName = $this->settings['template'];
        
        if (file_exists($templateFileName)){

            $templateVarName = $this->settings['templateVarName'];
            require_once $templateFileName;
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
                $this->stop(['errors'=>$errors,'warns'=>$warns]);
            };

            if (count($warns)>0){
                foreach($warns as $warn)
                error_log('Warn: '.$warn);
            }
        }else{
            $this->stop(['msg'=>'not exists template file: '.$templateFileName]);
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
    private function stop($params=[]){
        $params=array_merge([
            'caption'=>'Config test stop webapp:',
            'errors'=>[],
            'warns'=>[],
            'msg'=>'',
        ],$params);
        echo '<body style="background:white;color:black;font-family:Courier;font-size:12px">';
        echo '<div style="background:gray;color:white;padding:2px">'.$params['caption'].'</div>';
        if ($params['msg']!=='')
            echo '<span style="color:red">'.$params['msg']."</span><br>";

        foreach($params['errors'] as $error)
            echo '<span style="color:red">Error: '.$error."</span><br>";
        foreach($params['warns'] as $warn)
            echo '<span style="color:#F09A4D">Warn : '.$warn."</span><br>";
        echo '</body>';
        exit(0);
    }
}
