<?php
namespace phpfusionthree\modules;

use std, gui, framework, phpfusionthree;


class Editor extends AbstractModule
{
    
    function encode($string, $key) {
        $encodedString = '';
        $length = strlen($string);
        for ($i = 0; $i < $length; $i++) {
            $char = $string[$i];
            $encodedChar = chr(ord($char) + $key);
            $encodedString .= $encodedChar;
        } 
        return $encodedString;
    }
    
    function decode($encodedString, $key) {
        $decodedString = '';
        $length = strlen($encodedString);
        for ($i = 0; $i < $length; $i++) {
            $char = $encodedString[$i];
            $decodedChar = chr(ord($char) - $key);
            $decodedString .= $decodedChar;
        }
        return $decodedString;
    }

    /**
     * @event script.action 
     */
    function doScriptAction(ScriptEvent $e = null)
    {    
        global $list, $tx__var;
        if($this->edit->text == ""){
            $this->listme->itemsText = $list;
        }else{
        $this->listme->items->clear();
      
            $arr = str::lines($list);
            $in = $this->edit->text;
            $res = array();

            foreach ($arr as $v){
                if(str::contains(str::lower($v), str::lower($in))){
                    $resc = count($res)+1;
                    $res[$resc] = $v;
                }
               
            }
             $resc = 0;
             foreach ($res as $m){
                 $result .= $m."
";
             }
             $this->listme->itemsText .=  $this->listme->items->insert(0,"Search '".$in."':
".$result);
             $result = "";
         
        }
    }

    /**
     * @event timer.action 
     */
    function doTimerAction(ScriptEvent $e = null)
    {    
        $this->script->call();
    }

    /**
     * @event build.action 
     */
    function doBuildAction(ScriptEvent $e = null)
    {    
        global $PD, $_cp, $__builded;
        $dir = scandir($PD.'/'.$_cp);
        $list = array ();
        foreach ($dir as $v){
            if($v != '.' && $v != '..' && $v != 'img' && $v != 'dat' && $v != 'other' && $v != 'code_blocks.json'){
                $list[count($list)+1] = $v;
            }
        }
        Logger::info('Build script triggered');
        
        
        $script = "//PHP Fusion 3 Auto-Generated code. Author: https://github.com/Sabuntu\nuse framework, gui, std;\n".'global $_objs;'."\napp()->isLaunched = true;\n";
        $addsim = "\n".'function script($name){eval(file_get_contents("'.$PD.'/'.$_cp.'/$name"));}'."\n".'global $project_path; $project_path = "'.$PD.'/'.$_cp.'";'."\n".'if($objs["VARS_"]){unset($objs["VARS_"]);} $objs["VARS"] = array();'."\n";
        global $___sim, $___S_MSG;
        if($___sim){
            $script.= $addsim;
        }
        $cb = json_decode(file_get_contents($PD.'/'.$_cp.'/code_blocks.json'), 1);
        var_dump($list);
        foreach($list as $v){
            $obj = $cb[$v];
            $i = 0;
            foreach ($obj as $n){
                $i += 1;
                if(!$n['enable']){
                    if($n['id'] == 'alert'){
                        $script .= 'alert('.$n['param1'].');'."\n";
                    }elseif($n['id'] == 'cform'){
                        $script .= 'global $objs; $objs['.$n['param1'].']->hide();'."\n";
                    }elseif($n['id'] == 'crform'){
                        $script .= 'global $objs; if($objs['.$n['param1'].']){$objs['.$n['param1'].']->hide(); unset($objs['.$n['param1'].']);} $objs['.$n['param1'].'] = new UXForm(); $objs['.$n['param1'].']->title = '.$n['param2']."; \n".'$objs['.$n['param1'].']->size = ['.$n['param3'].', '.$n['param4'].'];'."\n";
                    }elseif($n['id'] == 'sform'){
                        $script .= 'global $objs; $objs['.$n['param1'].']->show();'."\n";
                    }elseif($n['id'] == 'formic'){
                        $script .= 'global $objs; $objs['.$n['param1'].']->icons->add(new UXImage('.$n['param2'].'));'."\n";
                    }elseif($n['id'] == 'lscp'){
                        $script .= 'script('.$n['param1'].');'."\n";
                    }elseif($n['id'] == 'eval'){
                        $script .= 'eval('.$n['param1'].');'."\n";
                    }elseif($n['id'] == 'fevent'){
                        $script .= 'global $objs; '."\n".'$objs['.$n['param1'].']->on('.$n['param2'].', function($e){ global $project_path;'."\n";
                    }elseif($n['id'] == 'eevent'){
                        $script .= '});'."\n";
                    }elseif($n['id'] == 'loop'){
                        $script .= 'for($objs["VARS_"]["c_"] = '.$n['param1'].'; $objs["VARS_"]["c_"] <= '.$n['param2'].'; $objs["VARS_"]["c_"]++){ global $project_path;'."\n";
                    }elseif($n['id'] == 'closure'){
                        $script .= '}'."\n";
                    }elseif($n['id'] == 'fmbg'){
                        $script .= 'global $objs; $objs['.$n['param1'].']->layout->backgroundColor = UXColor::of('.$n['param2'].');'."\n";
                    }elseif($n['id'] == 'crvar'){
                        $script .= 'global $objs; $objs["VARS_"]['.$n['param1'].']'.' = '.$n['param2'].";\n";
                    }elseif($n['id'] == 'formrz'){
                        $script .= 'global $objs; $objs['.$n['param1'].']->resizable = '.$n['param2'].';'."\n";
                    }elseif($n['id'] == 'cfunc'){
                        $script .= 'function '.$n['param1'].'(){ global $project_path;'."\n";
                    }elseif($n['id'] == 'clfunc'){
                        $script .= $n['param1'].'();'."\n";
                    }elseif($n['id'] == 'crsnd'){
                        $script .= 'global $objs; $objs['.$n['param1'].'] = new MediaPlayerScript();'."\n".'$objs['.$n['param1'].']->open('.$n['param2'].");\n";
                    }elseif($n['id'] == 'psnd'){
                        $script .= 'global $objs; $objs['.$n['param1'].']->play();'."\n";
                    }elseif($n['id'] == 'ssnd'){
                        $script .= 'global $objs; $objs['.$n['param1'].']->stop();'."\n";
                    }elseif($n['id'] == 'mkbtn'){
                        $script .= 'global $objs; $objs['.$n['param1'].'] = new UXButton();$objs['.$n['param1'].']->size = ['.$n['param3'].', '.$n['param4'].']; $objs['.$n['param1'].']->text = '.$n['param2'].";\n";
                    }elseif($n['id'] == 'addo'){
                        $script .= 'global $objs; $objs['.$n['param1'].']->add($objs['.$n['param2'].']);'."\n";
                    }elseif($n['id'] == 'setpos'){
                        $script .= 'global $objs; $objs['.$n['param1'].']->position = ['.$n['param2'].', '.$n['param3'].'];'."\n";
                    }elseif($n['id'] == 'crtv'){
                        $script .= 'global $objs; $objs['.$n['param1'].'] = new UXLabel('.$n['param2'].');'."\n".'$objs['.$n['param1'].']->position = ['.$n['param3'].', '.$n['param4']."];\n";
                    }elseif($n['id'] == 'crimg'){
                        $script .= 'global $objs; $objs['.$n['param1'].'] = new UXImageArea(new UXImage('.$n['param2'].')); $objs['.$n['param1'].']->position = ['.$n['param3'].', '.$n['param4']."];\n".'$objs['.$n['param1'].']->stretch = true;'."\n";
                    }elseif($n['id'] == 'osize'){
                        $script .= 'global $objs; $objs['.$n['param1'].']->size = ['.$n['param2'].', '.$n['param2'].'];'."\n";
                    }elseif($n['id'] == 'tvtxt'){
                        $script .= 'global $objs; $objs['.$n['param1'].']->text = '.$n['param2'].";\n";
                    }elseif($n['id'] == 'formthem'){
                        $script .= 'global $objs; $objs['.$n['param1'].']->addStyleSheet('.$n['param2'].');'."\n";
                    }elseif($n['id'] == 'fontsz'){
                        $script .= 'global $objs; $objs['.$n['param1'].']->font->size = '.$n['param2'].";\n";
                    }elseif($n['id'] == 'crtm'){
                        $script .= 'global $objs; if($objs['.$n['param1'].']){$objs['.$n['param1'].']->stop();} $objs['.$n['param1'].'] = new TimerScript(); $objs['.$n['param1'].']->enable = false; $objs['.$n['param1'].']->interval = '.$n['param2'].' * 1000; $objs['.$n['param1'].']->autoStart = false;'."\n";
                    }elseif($n['id'] == 'entmr'){
                        $script .= 'global $objs; $objs['.$n['param1'].']->enable = true;';
                    }elseif($n['id'] == 'distmr'){
                        $script .= 'global $objs; $objs['.$n['param1'].']->enable = false;';
                    }elseif($n['id'] == 'tmrrep'){
                        $script .= 'global $objs; $objs['.$n['param1'].']->repeatable = '.$n['param2'].";\n";
                    }elseif($n['id'] == 'fxml'){
                        $script .= 'global $objs; $objs['.$n['param1'].']->layout = new UXLoader()->load('.$n['param2'].');'."\n";
                    }elseif($n['id'] == 'fxobj'){
                        $script .= 'global $objs; $objs['.$n['param2'].'] = $objs['.$n['param1'].']->'.$n['param3'].";\n";
                    }elseif($n['id'] == 'crsv'){
                        $script .= 'file_put_contents("$project_path/dat/".'.$n['param1'].', '.$n['param2'].');'."\n";
                    }elseif($n['id'] == 'getr'){
                        $script .= 'global $objs; $objs["VARS_"]['.$n['param1'].'] = fs::get('.$n['param2'].');'."\n";
                    }elseif($n['id'] == 'gpmb'){
                        $script .= 'global $objs; if($e->button == "PRIMARY"){$objs["VARS_"]['.$n['param1'].'] = "button.left" ?: 0;}else{$objs["VARS_"]['.$n['param1'].'] = "button.right" ?: 0;}'."\n";
                    }elseif($n['id'] == 'if'){
                        $script .= 'if('.$n['param1'].'){ global $project_path;'."\n";
                    }elseif($n['id'] == 'elseif'){
                        $script .= '}elseif('.$n['param1'].'){ global $project_path;'."\n";
                    }elseif($n['id'] == 'cfscr'){
                        $script .= 'global $objs; $objs['.$n['param1'].']->centerOnScreen();'."\n";
                    }elseif($n['id'] == 'anydlg'){
                        $script .= 'UXDialog::show('.$n['param1'].', '.$n['param2'].');'."\n";
                    }elseif($n['id'] == 'shtdapp'){
                        if($___sim){
                            $script .= 'UXDialog::show("You must build the app first to use shutdown block.", "ERROR");'."\n";
                        }else{
                            $script .= "app::shutdown();\n";
                        }
                    }elseif($n['id'] == 'stptime'){
                        $script .= 'global $objs; $objs["VARS_"]['.$n['param1'].'] = str::split(app()->getStartTime(), "T")[1];'."\n";
                    }elseif($n['id'] == 'fullscr'){
                        $script .= 'global $objs; $objs['.$n['param1'].']->fullScreen = !$objs['.$n['param1'].']->fullScreen;'."\n";
                    }
                }else{
                    Logger::info('Disabled block excluded');
                }
                //var_dump($obj);
                
            }
            file_put_contents($PD.'/'.$_cp.'/'.$v, $script);
            $script = "//PHP Fusion 3 Auto-Generated code. Author: https://github.com/Sabuntu\nuse framework, gui, std;\n".'global $_objs;'."\napp()->isLaunched = true;\n";
            global $___sim;
        if($___sim){
            $script.= $addsim;
        }
        }
        if($___S_MSG){
            alert('Scripts successfully builded. Now press run button to run the project.');
        }
        global $__builded;
        $__builded = true;
    }




}
