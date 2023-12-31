<?php
namespace phpfusionthree\forms;

use bundle\zip\ZipFileScript;
use std, gui, framework, phpfusionthree;


class MenuForm extends AbstractForm
{
    function langGet($elem){
        global $currLang;
        if(!$currLang){
            return json_decode(file_get_contents('res://.data/ru.json'), 1)[$elem];
        }else{
            return json_decode(file_get_contents('res://.data/ru.json'), 1)[$elem];
        }
    }


    /**
     * @event show 
     */
    function doShow(UXWindowEvent $e = null)
    {    
        global $_LOCALE;
        $this->minWidth = 792;
        $this->minHeight = 504;
        $d = scandir('.pf3');
        foreach ($d as $v){
            if($v != '.' && $v != '..'){
                $this->listView->items->add($v);
            }
        }
        if(!file_exists('config_pfs.json')){
            file_put_contents('config_pfs.json', '{"lang": "ru","developerKey": "@PHP_FUSION_DEBUG_key", "autobuild": true, "type": ".exe"}');
        }
        /*$this->form(MainForm)->title = 'PHP Fusion 3';
        $this->ttl->text = $_LOCALE['yourProj'];
        $this->link->text = $_LOCALE["about"];
        $this->linkAlt->text = $_LOCALE["exit"];
        $this->link3->text = $_LOCALE["params"];*/
    }

    /**
     * @event button.click-Left 
     */
    function doButtonClickLeft(UXMouseEvent $e = null)
    {    
        $ask = UXDialog::input('Project name:');
        if($ask && $ask != ''){
            $this->listView->items->add($ask);
            global $PD;
            mkdir($PD.'/'.$ask);
            mkdir($PD.'/'.$ask.'/img');
            mkdir($PD.'/'.$ask.'/other');
            mkdir($PD.'/'.$ask.'/dat');
            fs::copy("res://.data/img/logo.png", $PD.'/'.$ask.'/img/project_icon.png');
            file_put_contents($PD.'/'.$ask.'/code_blocks.json', '{"main.script":{"block1":{"name":"Create form","id":"crform","pcount":4,"param1":"\"MainForm\"","param2":"\"Form Caption\"","param3":"500","param4":"400"},"block2":{"name":"Show object","id":"sform","pcount":1,"param1":"\"MainForm\""},"block3":{"name":"Set form icon","id":"formic","pcount":2,"param1":"\"MainForm\"","param2":"\"$project_path/img/project_icon.png\""}}}');
            file_put_contents($PD.'/'.$ask.'/main.script', 'alert("Hello world");');
            //fs::copy("res://.theme/dark.css", "$PD/$ask/other/dark_theme.css");
            
        }
    }

    /**
     * @event buttonAlt.click-Left 
     */
    function doButtonAltClickLeft(UXMouseEvent $e = null)
    {    
        if($this->listView->selectedItem){
            $s = $this->listView->selectedItem;
            fs::clean('.pf3/'.$s);
            rmdir('.pf3/'.$s);
            $this->listView->items->remove($s);
            alert('Project '.$s.' deleted');
        }
    }

    /**
     * @event listView.click-2x 
     */
    function doListViewClick2x(UXMouseEvent $e = null)
    {    
        if($this->listView->selectedItem){
            global $_cp;
            $_cp = $this->listView->selectedItem;
            $this->form(MainForm)->fragment->content = EditorForm;
            $this->free();
        }
        
    }

    /**
     * @event link.click-Left 
     */
    function doLinkClickLeft(UXMouseEvent $e = null)
    {    
        $this->imageAlt->show();
        $this->vvv->show();
    }

    /**
     * @event linkAlt.click-Left 
     */
    function doLinkAltClickLeft(UXMouseEvent $e = null)
    {
        app::shutdown();
    }

    /**
     * @event link3.click-Left 
     */
    function doLink3ClickLeft(UXMouseEvent $e = null)
    {
        $this->form(MainForm)->fragment->content = SettingsForm;
        
        $this->free();
    }

    /**
     * @event button4.action 
     */
    function doButton4Action(UXEvent $e = null)
    {    
        global $PD;
        $ux = new FileChooserScript;
        $ux->saveDialog = true;
        $sel = $this->listView->selectedItem;
        if($sel != ''){
        $ux->initialFileName = "$sel.pfa";
        if($ux->execute()){
            $f = new ZipFileScript;
            $f->path = $ux->file;
            $pth = $PD.'/'.$sel;
            $scan = scandir($pth);
            $f->addDirectoryAsync($pth);
            $f->zipFile();
        }
        }
    }

    /**
     * @event button5.action 
     */
    function doButton5Action(UXEvent $e = null)
    {    
        global $PD;
         $ux = new FileChooserScript;
         $ux->filterExtensions = '*.pfa*';
        $sel = $this->listView->selectedItem;
        if($ux->execute()){
            $f = new ZipFileScript;
            $f->path = $ux->file;
            //mkdir(fs::nameNoExt($ux->file));
            $f->unpack($PD.'/'.fs::nameNoExt($ux->file));
            $this->listView->items->add(fs::nameNoExt($ux->file));
        }
    }

    /**
     * @event button3.action 
     */
    function doButton3Action(UXEvent $e = null)
    {    
        global $PD;
        $var = $this->listView->selectedItem;
        if($var != ''){
            $n = UXDialog::input('New name:');
            if($n != ''){
                fs::rename($PD.'/'.$var, $n);
                $d = scandir('.pf3');
                $this->listView->items->clear();
                foreach ($d as $v){
                if($v != '.' && $v != '..'){
                    $this->listView->items->add($v);
                }
        }
            }
        }
    }

    /**
     * @event imageAlt.click-Left 
     */
    function doImageAltClickLeft(UXMouseEvent $e = null)
    {    
        $this->imageAlt->visible = false;
        $this->vvv->hide();
    }

    /**
     * @event image3.click-Left 
     */
    function doImage3ClickLeft(UXMouseEvent $e = null)
    {    
        browse('https://discord.gg/9M4UB68BWN');
    }

}
