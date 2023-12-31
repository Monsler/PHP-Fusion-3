<?php
namespace phpfusionthree\forms;

use game;
use std, gui, framework, phpfusionthree;


class BuildForm extends AbstractForm
{

// Encode a string with a key
function encode_string($string, $key) {
    $encoded_string = base64_encode($string);
    $encoded_string = $this->xor_encrypt($encoded_string, $key);
    return $encoded_string;
}

function decode_string($encoded_string, $key) {
    $decoded_string = $this->xor_decrypt($encoded_string, $key);
    $decoded_string = base64_decode($decoded_string);
    return $decoded_string;
}

function xor_encrypt($string, $key) {
    $key_length = strlen($key);
    $string_length = strlen($string);
    $encrypted_string = '';

    for ($i = 0; $i < $string_length; $i++) {
        $encrypted_string .= $string[$i] ^ $key[$i % $key_length];
    }

    return $encrypted_string;
}

function xor_decrypt($string, $key) {
    return xor_encrypt($string, $key);
}


    /**
     * @event show 
     */
    function doShow(UXWindowEvent $e = null)
    {    
        global $PD, $_cp, $___sim;
        $___sim = false;
        $this->addStylesheet('/.theme/dark.css');
        app()->module(Editor)->build->call();
        $currp = $PD.'/'.$_cp;
        $sel = new DirectoryChooserScript();
        $r = json_decode(file_get_contents('config_pfs.json'), 1) ?: '{"developerKey":"@PHP_FUSION_$","autobuild":true,"type":".exe"}';
        $key = $r['developerKey'];
        if($sel->execute()){
            $full = fs::abs($sel->file);
            fs::clean($full);
            mkdir($full.'/Assets');
            $dir = scandir($currp);
            foreach ($dir as $v){
                if($v != '.' && $v != '..' && !fs::isDir($currp.'/'.$v) && $v != 'code_blocks.json'){
                    
                    $file = file_get_contents($currp.'/'.$v);
                    file_put_contents($full.'/Assets/'.$v, $this->encode_string($file, $key));
                    
                }else{
                    if($v != 'code_blocks.json' && $v != 'dat' && $v != '..' && $v != '.'){
                        mkdir($full.'/Assets/'.$v);
                        $dir = $v;
                        $dd = scandir($currp.'/'.$dir);
                        foreach ($dd as $b){
                            if($b != '.' && $b != '..'){
                                fs::copy($currp.'/'.$dir.'/'.$b, $full.'/Assets/'.$dir.'/'.$b);
                            }
                        }
                    }
                }
            }
            file_put_contents($full.'/Assets/conf.pfa', $this->encode_string(json_encode($r), '@pfa_manifest_file'));
            rmdir($full.'/Assets/Array');
            //rmdir($full.'/Assets/dat');
            mkdir($full.'/Assets/dat');
            if($r['type'] == '.exe'){
                fs::copy('res://.data/PHARuntime.exe', $full.'/'.$_cp.'.exe');
            }else{
                fs::copy('res://.data/PHARuntime.jar', $full.'/'.$_cp.'.jar');
            }
            fs::delete($full.'/Assets/code_blocks.json');
        
        
            alert("Build finished! $full");
        }
        
        
        $this->free();
    }

    /**
     * @event showing 
     */
    function doShowing(UXWindowEvent $e = null)
    {    
        global $PD, $_cp, $___sim;
        $this->title = "Building $_cp...";
        $this->icons->add(new UXImage('res://.data/img/logo.png'));
    }

    /**
     * @event progressIndicator.construct 
     */
    function doProgressIndicatorConstruct(UXEvent $e = null)
    {    
        
    }

}
