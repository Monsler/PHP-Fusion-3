<?php
namespace phpfusionthree\forms;

use facade\Json;
use std, gui, framework, phpfusionthree;


class EditorForm extends AbstractForm
{

    /**
     * @event show 
     */
    function doShow(UXWindowEvent $e = null)
    {    
        global $_cp, $PD;
        $this->form(MainForm)->title = 'PHP Fusion 3 - '.$_cp;
        $d = scandir($PD.'/'.$_cp);
        foreach ($d as $v){
            if($v != '.' && $v != '..' && $v != 'dat' && $v != 'img' && $v != 'other' && $v != 'code_blocks.json'){
                $this->listView->items->add($v);
            }
        }
        $d = scandir($PD.'/'.$_cp.'/img');
        foreach ($d as $v){
            if($v != '.' && $v != '..'){
                    $this->media->items->add($v);
            }
        }
        global $___editor; $___editor = $this;
         $d = scandir($PD.'/'.$_cp.'/other');
        foreach ($d as $v){
          if($v != '.' && $v != '..'){
               $this->other->items->add($v);
          }
        }
        
        global $list, $__builded;
        $list = $this->listme->itemsText;
        $__builded = false;
        
        global $___params;
        $___params = json_decode(file_get_contents('config_pfs.json'), 1);
        
        global $__customfield, $__datafield; $__customfield = $this->customs; $__datafield = $this->listViewAlt;
        
        
        
        //global $list1; $list1 = $__customfield->itemsText;
    }

    /**
     * @event button.click-Left 
     */
    function doButtonClickLeft(UXMouseEvent $e = null)
    {    
        if(UXDialog::confirm('Do you really want to go back?')){
            $this->form(MainForm)->fragment->content = MenuForm;
            $this->free();
        }
    }

    /**
     * @event other.click-2x 
     */
    function doOtherClick2x(UXMouseEvent $e = null)
    {    
        if($this->other->selectedItem){
            global $PD, $_cp;
            open($PD.'/'.$_cp.'/other/'.$this->other->selectedItem);
        }
    }

    /**
     * @event media.click-2x 
     */
    function doMediaClick2x(UXMouseEvent $e = null)
    {    
        if($this->media->selectedItem){
            global $PD, $_cp;
            open($PD.'/'.$_cp.'/img/'.$this->media->selectedItem);
        }
        //alert($PD.'/'.$_cp.'/img/'.$this->media->selectedItem);
    }

    /**
     * @event listView.click-2x 
     */
    function doListViewClick2x(UXMouseEvent $e = null)
    {    
        global $_s, $PD, $_cp;
        $_s = $this->listView->selectedItem;
        global $_os;
        $_os = $_s;
        if(!fs::isDir($PD.'/'.$_cp.'/'.$_s)){
            $this->ep->show();
            global $___ctable;
            $this->listme->show();
            $this->edit->show();
            $this->ep->title = $_s;
            $this->listViewAlt->items->clear();
            $r = json_decode(file_get_contents($PD.'/'.$_cp.'/code_blocks.json'), 1)[$_s];
            $___ctable = $r;
            //alert(json_encode($r));
           // foreach ($r as $v){
              //  $i += 1;
           //     $this->listViewAlt->items->add("$i    ".$v['name'] );
           // }
           $this->renderBlocks();
        }
    }

    /**
     * @event listViewAlt.click-2x 
     */
    function doListViewAltClick2x(UXMouseEvent $e = null)
    {
        
        GLOBAL $vsel_, $___ctable, $_cp, $PD, $_os;
        $___ctable = json_decode(file_get_contents($PD.'/'.$_cp.'/code_blocks.json'), 1)[$_os];
        
         $vsel_ = $this->listViewAlt->selectedIndex;
         $vs = $vsel_ + 1;
         $vs = "block$vs";
         if($___ctable[$vs]['uneditable']){
             UXDialog::show('This block is uneditable', 'ERROR');
         }else{
            app()->form(ValueEForm)->show(); 
         }
         
    }

    function renderBlocks(){
        $this->listViewAlt->items->clear();
        GLOBAL $vsel_, $_cp, $PD, $_os;
        $t = json_decode(file_get_contents($PD.'/'.$_cp.'/code_blocks.json'), 1)[$_os];
        $tbl = json_decode(file_get_contents($PD.'/'.$_cp.'/code_blocks.json'), 1);
            //alert(json_encode($r));
            foreach ($tbl[$_os] as $v){
                $i += 1;
                if($v['id'] == 'if' || $v['id'] == 'elseif' || $v['id'] == 'fullscr' || $v['id'] == 'stptime' || $v['id'] == 'sform' || $v['id'] == 'lscp' ||$v['id'] == 'cfscr' || $v['id'] == 'alert' || $v['id'] == 'crform' || $v['id'] == 'mkbtn' || $v['id'] == 'crtv'){
                    if(!$v['enable']){
                       $this->listViewAlt->items->add("$i    ".$v['name'].' '.$v['param1'] );
                    }else{
                        $this->listViewAlt->items->add("$i    ".$v['name'].' '.$v['param1'].' [Disabled]' );
                    }
                }elseif($v['id'] == 'fevent' || $v['id'] == 'fmbg'){
                    if(!$v['enable']){
                        $this->listViewAlt->items->add("$i    ".$v['name'].' for '.$v['param1'].': '.$v['param2'] );
                    }else{
                        $this->listViewAlt->items->add("$i    ".$v['name'].' for '.$v['param1'].': '.$v['param2'].' [Disabled]' );
                    }
                }elseif($v['id'] == 'loop'){
                    if(!$v['enable']){
                        $this->listViewAlt->items->add("$i    ".$v['name'].' (from '.$v['param1'].' to '.$v['param2'].')' );
                    }else{
                         $this->listViewAlt->items->add("$i    ".$v['name'].' (from '.$v['param1'].' to '.$v['param2'].')'.' [Disabled]' );
                    }
                }elseif($v['id'] == 'addo' || $v['id'] == 'formic' || $v['id'] == 'anydlg'){
                    if(!$v['enable']){
                        $this->listViewAlt->items->add("$i    ".$v['name'].' ('.$v['param1'].', '.$v['param2'].')' );
                    }else{
                        $this->listViewAlt->items->add("$i    ".$v['name'].' ('.$v['param1'].', '.$v['param2'].')'.' [Disabled]' );
                    }
                }elseif($v['id'] == 'crvar'){
                    if(!$v['enable']){
                        $this->listViewAlt->items->add("$i    ".$v['name'].' '.$v['param1'].' to '.$v['param2'] );
                    }else{
                        $this->listViewAlt->items->add("$i    ".$v['name'].' '.$v['param1'].' to '.$v['param2'].' [Disabled]' );
                    }
                }else{
                    if(!$v['enable']){
                        $this->listViewAlt->items->add("$i    ".$v['name'] );
                    }else{
                           $this->listViewAlt->items->add("$i    ".$v['name'].' [Disabled]' ); 
                    }
                }
                
            }
    }


    /**
     * @event edit.keyUp 
     */
    function doEditKeyUp(UXKeyEvent $e = null)
    {    
        $this->script->call();
    }

    /**
     * @event image.click-Left 
     */
    function doImageClickLeft(UXMouseEvent $e = null)
    {    
        global $___sim, $___S_MSG;
        $___S_MSG = true;
        $___sim = true;
        //$this->build->call();
        $this->build->call();
    }

    /**
     * @event imageAlt.click-Left 
     */
    function doImageAltClickLeft(UXMouseEvent $e = null)
    {
        global $__builded, $PD, $_cp, $___params, $objs;
        //if($objs){unset($objs);}
        if(!$___params['autobuild']){
        if($__builded){
            eval(str_ireplace('$project_path', $PD."/".$_cp, file_get_contents($PD.'/'.$_cp.'/main.script')));
        }else{
            UXDialog::show('You need to build the program first. Hit previous button to do it.', "ERROR");
        }
        $__builded = false;
        }else{
            global $___sim, $___S_MSG;
            $___sim = true;
            $___S_MSG = false;
            $this->build->call();
            //eval(str_ireplace('$project_path', "$PD/$_cp", file_get_contents($PD.'/'.$_cp.'/main.script')));
            eval(file_get_contents($PD.'/'.$_cp.'/main.script'));
        }
        
    }

    /**
     * @event image3.click-Left 
     */
    function doImage3ClickLeft(UXMouseEvent $e = null)
    {    
        global $_os, $PD, $_cp;
        $r = json_decode(file_get_contents($PD.'/'.$_cp.'/code_blocks.json'), 1)[$_os];
        $std = json_decode(file_get_contents($PD.'/'.$_cp.'/code_blocks.json'), 1);
        $s = $this->listViewAlt->selectedIndex; 
        if($s+1){
        $seler = $s + 1;
        $seler = "block$seler";
        //var_dump($seler);
        unset($r[$seler]);
        $ud = '';
        for($i = $s+1; $i < count($r) + 1; $i++){
            $ud = "block";
            $sz = $i + 1;
            $ud = "block$sz";
            $r["block$i"] = $r[$ud];
            unset($r[$ud]);
        }
        $std[$_os] = $r;
        file_put_contents($PD.'/'.$_cp.'/code_blocks.json', json_encode($std));
        $this->renderBlocks();
        }
    }

    /**
     * @event image4.click-Left 
     */
    function doImage4ClickLeft(UXMouseEvent $e = null)
    {    
        global $_os, $PD, $_cp;
            if($this->listViewAlt->selectedItem){
                $sel = $this->listViewAlt->selectedIndex+1;
                $sel = "block$sel";
                $r = json_decode(file_get_contents($PD.'/'.$_cp.'/code_blocks.json'), 1)[$_os];
                $std = json_decode(file_get_contents($PD.'/'.$_cp.'/code_blocks.json'), 1);
                $cln = $r[$sel];
                $b = count($r)+1;
                $b = "block$b";
                $std[$_os][$b] = $cln;
                file_put_contents($PD.'/'.$_cp.'/code_blocks.json', json_encode($std));
                $this->renderBlocks();
        }
    }

    /**
     * @event buttonAlt.action 
     */
    function doButtonAltAction(UXEvent $e = null)
    {    
        $dlg = new FileChooserScript;
        $dlg->filterExtensions = '*.png*';
        if($dlg->execute()){
            $in = UXDialog::input('New file name:');
            if($in != '' && $in != null){
                global $PD, $_cp;
                fs::copy($dlg->file, $PD.'/'.$_cp.'/img/'.$in);
            }
            $this->media->items->clear();
            $d = scandir($PD.'/'.$_cp.'/img');
        foreach ($d as $v){
            if($v != '.' && $v != '..'){
                    $this->media->items->add($v);
            }
        }
        }
    }

    /**
     * @event image5.click-Left 
     */
    function doImage5ClickLeft(UXMouseEvent $e = null)
    {    
        global $PD, $_cp;
        $in = UXDialog::input('Script name');
        if($in != ''){
            file_put_contents($PD.'/'.$_cp.'/'.$in.'.script', '');
            $red = json_decode(file_get_contents($PD.'/'.$_cp.'/code_blocks.json'), 1);
            $red["$in.script"] = array();
            file_put_contents($PD.'/'.$_cp.'/code_blocks.json', json_encode($red));
            $this->listView->items->clear();
            $d = scandir($PD.'/'.$_cp);
        foreach ($d as $v){
            if($v != '.' && $v != '..' && $v != 'img' && $v != 'dat' && $v != 'other' && $v != 'code_blocks.json'){
                $this->listView->items->add($v);
            }
        }
        
        }
    }

    /**
     * @event image6.click-Left 
     */
    function doImage6ClickLeft(UXMouseEvent $e = null)
    {
        global $PD, $_cp;
        $in = $this->listView->selectedItem;
        if($in != ''){
            fs::delete($PD.'/'.$_cp.'/'.$in);
            $red = json_decode(file_get_contents($PD.'/'.$_cp.'/code_blocks.json'), 1);
            unset($red[$in]);
            file_put_contents($PD.'/'.$_cp.'/code_blocks.json', json_encode($red));
            $this->listView->items->clear();
            $d = scandir($PD.'/'.$_cp);
            $this->listme->hide();
            $this->edit->hide();
            $this->ep->hide();
        foreach ($d as $v){
            if($v != '.' && $v != '..' && $v != 'dat' && $v != 'img' && $v != 'other' && $v != 'code_blocks.json'){
                $this->listView->items->add($v);
            }
        }
        
        }
    }

    /**
     * @event image7.click-Left 
     */
    function doImage7ClickLeft(UXMouseEvent $e = null)
    {
        $this->form(BuildForm)->show();
    }

    /**
     * @event image8.click-Left 
     */
    function doImage8ClickLeft(UXMouseEvent $e = null)
    {
        global $_os, $PD, $_cp;
        $sel = $this->listViewAlt->selectedIndex+1;
        $sel = "block$sel";
        $r = json_decode(file_get_contents($PD.'/'.$_cp.'/code_blocks.json'), 1)[$_os];
        $std = json_decode(file_get_contents($PD.'/'.$_cp.'/code_blocks.json'), 1);
        $ask1 = intval(UXDialog::input('Block index to move:'));
        $ask2 = intval(UXDialog::input('Move goal:'));
        if($ask1 != 0){
            if($ask2 != 0){
                $copyMov = $r["block$ask1"];
                $copyTwo = $r["block$ask2"];
                
                $r["block$ask1"] = $copyTwo;
                $r["block$ask2"] = $copyMov;
                $std[$_os] = $r;
                file_put_contents($PD.'/'.$_cp.'/code_blocks.json', json_encode($std));
                $this->renderBlocks();
                }
        }
        
    }

    /**
     * @event button4.action 
     */
    function doButton4Action(UXEvent $e = null)
    {
        global $PD, $_cp;
        $s = $this->media->selectedItem;
        if($s != ''){
            fs::delete($PD.'/'.$_cp.'/img/'.$s);
            $this->media->items->clear();
            $d = scandir($PD.'/'.$_cp.'/img');
            foreach ($d as $v){
                if($v != '.' && $v != '..'){
                        $this->media->items->add($v);
                }
            }
        }
    }


    /**
     * @event button5.action 
     */
    function doButton5Action(UXEvent $e = null)
    {
        global $PD, $_cp;
        $s = $this->other->selectedItem;
        if($s != ''){
            fs::delete($PD.'/'.$_cp.'/other/'.$s);
            $this->other->items->clear();
            $d = scandir($PD.'/'.$_cp.'/other');
            foreach ($d as $v){
                if($v != '.' && $v != '..'){
                        $this->other->items->add($v);
                }
            }
        }
    }

    /**
     * @event button3.action 
     */
    function doButton3Action(UXEvent $e = null)
    {
        $dlg = new FileChooserScript;
        if($dlg->execute()){
            $in = UXDialog::input('New file name:');
            if($in != '' && $in != null){
                global $PD, $_cp;
                fs::copy($dlg->file, $PD.'/'.$_cp.'/other/'.$in);
            }
            $this->other->items->clear();
            $d = scandir($PD.'/'.$_cp.'/other');
        foreach ($d as $v){
            if($v != '.' && $v != '..'){
                    $this->other->items->add($v);
            }
        }
        }
    }

    /**
     * @event image9.click-Left 
     */
    function doImage9ClickLeft(UXMouseEvent $e = null)
    {
        global $PD, $_cp;
        $in = $this->listView->selectedItem;
        if($in != ''){
            $inp = UXDialog::input('Copied script name:');
            if($inp != ''){
                fs::copy("$PD/$_cp/$in", "$PD/$_cp/$inp.script");
                $std = json_decode(file_get_contents($PD.'/'.$_cp.'/code_blocks.json'), 1);
                $std[$inp.'.script'] = $std[$in];
                file_put_contents($PD.'/'.$_cp.'/code_blocks.json', json_encode($std));
                $this->listView->items->add("$inp.script");
            }
       }
    }

    /**
     * @event image10.click-Left 
     */
    function doImage10ClickLeft(UXMouseEvent $e = null)
    {
         global $PD, $_cp;
         $in = $this->listView->selectedItem;
         if($in != ''){
             $inp = UXDialog::input("New script's name:");
             if($inp != ''){
                 fs::rename("$PD/$_cp/$in", "$inp.script");
                 $this->listView->items->clear();
                 $std = json_decode(file_get_contents($PD.'/'.$_cp.'/code_blocks.json'), 1);
                 $c = $std[$in];
                 $std["$inp.script"] = $c;
                 unset($std[$in]);
                 file_put_contents($PD.'/'.$_cp.'/code_blocks.json', json_encode($std));
                 $d = scandir("$PD/$_cp");
                 foreach ($d as $v){
                    if($v != '.' && $v != '..' && $v != 'img' && $v != 'dat' && $v != 'other' && $v != 'code_blocks.json'){
                        $this->listView->items->add($v);
                    }
                }
             }
        }
    }

    /**
     * @event image11.click-Left 
     */
    function doImage11ClickLeft(UXMouseEvent $e = null)
    {    
        global $PD, $_cp;
        $s = $this->listViewAlt->selectedIndex+1 ?: 0;
        $std = json_decode(file_get_contents($PD.'/'.$_cp.'/code_blocks.json'), 1);
        if($s != 0){
            global $_os;
            $ct = $std[$_os];
            $block = 'block'.$s;
            if(!$ct[$block]['enable']){
                $ct[$block]['enable'] = true;
            }else{
                unset($ct[$block]['enable']);
            }
            $std[$_os] = $ct;
            file_put_contents($PD.'/'.$_cp.'/code_blocks.json', json_encode($std));
            $this->renderBlocks();
        }
    }

    /**
     * @event listme.click-2x 
     */
    function doListmeClick2x(UXMouseEvent $e = null)
    {
        $_s = $this->listme->selectedItem;
        GLOBAL $vsel_, $_cp, $PD, $_os;
        $t = json_decode(file_get_contents($PD.'/'.$_cp.'/code_blocks.json'), 1)[$_os];
        $tbl = json_decode(file_get_contents($PD.'/'.$_cp.'/code_blocks.json'), 1);
        $c = count($t) + 1;
        if($_s){
            if($_s == 'Alert'){
                $c = count($this->listViewAlt->items) + 1;
                
                $t["block$c"] = array();
                $t["block$c"]['name'] = 'Alert';
                $t["block$c"]['id'] = 'alert';
                $t["block$c"]['pcount'] = 1;
                $t["block$c"]['param1'] = '"Text"';
                $tbl[$_os] = $t;
                
                  
            }elseif($_s == 'Hide object'){
                $c = count($this->listViewAlt->items) + 1;
                
                $t["block$c"] = array();
                $t["block$c"]['name'] = 'Hide object';
                $t["block$c"]['id'] = 'cform';
                $t["block$c"]['pcount'] = 1;
                $t["block$c"]['param1'] = '"Name"';
                $tbl[$_os] = $t;
                
                  
            }elseif($_s == 'Create form'){
                $c = count($this->listViewAlt->items) + 1;
                
                $t["block$c"] = array();
                $t["block$c"]['name'] = 'Create form';
                $t["block$c"]['id'] = 'crform';
                $t["block$c"]['pcount'] = 4;
                $t["block$c"]['param1'] = '"Form name"';
                $t["block$c"]['param2'] = '"Title"';
                $t["block$c"]['param3'] = 'width';
                $t["block$c"]['param4'] = 'height';
                $tbl[$_os] = $t;
                
                  
            }elseif($_s == 'Show object'){
                $c = count($this->listViewAlt->items) + 1;
                
                $t["block$c"] = array();
                $t["block$c"]['name'] = 'Show object';
                $t["block$c"]['id'] = 'sform';
                $t["block$c"]['pcount'] = 1    ;
                $t["block$c"]['param1'] = '"Name"';
                $tbl[$_os] = $t;
                
                  
            }elseif($_s == 'Set form icon'){
                $c = count($this->listViewAlt->items) + 1;
                
                $t["block$c"] = array();
                $t["block$c"]['name'] = 'Set form icon';
                $t["block$c"]['id'] = 'formic';
                $t["block$c"]['pcount'] = 2;
                $t["block$c"]['param1'] = '"Form name"';
                $t["block$c"]['param2'] = '"$project_path/img/project_icon.png"';
                $tbl[$_os] = $t;
                
                  
            }elseif($_s == 'Load script'){
                $c = count($this->listViewAlt->items) + 1;
                
                $t["block$c"] = array();
                $t["block$c"]['name'] = 'Load script';
                $t["block$c"]['id'] = 'lscp';
                $t["block$c"]['pcount'] = 1;
                $t["block$c"]['param1'] = '"Script name"';
                $tbl[$_os] = $t;
                
                  
            }elseif($_s == 'Insert PHP'){
                $c = count($this->listViewAlt->items) + 1;
                
                $t["block$c"] = array();
                $t["block$c"]['name'] = 'Insert PHP';
                $t["block$c"]['id'] = 'eval';
                $t["block$c"]['pcount'] = 1;
                $t["block$c"]['param1'] = '"alert(\"PHP\");"';
                $tbl[$_os] = $t;
                
                  
            }elseif($_s == 'Event'){
                $c = count($this->listViewAlt->items) + 1;
                
                $t["block$c"] = array();
                $t["block$c"]['name'] = 'Event';
                $t["block$c"]['id'] = 'fevent';
                $t["block$c"]['pcount'] = 2;
                $t["block$c"]['param1'] = '"Component name"';
                $t["block$c"]['param2'] = '"event.name"';
                $tbl[$_os] = $t;
                
                  
            }elseif($_s == 'End event'){
                $c = count($this->listViewAlt->items) + 1;
                
                $t["block$c"] = array();
                $t["block$c"]['name'] = 'End event';
                $t["block$c"]['id'] = 'eevent';
                $t["block$c"]['uneditable'] = true;
                $tbl[$_os] = $t;
                
                  
            }elseif($_s == 'Loop'){
                $c = count($this->listViewAlt->items) + 1;
                
                $t["block$c"] = array();
                $t["block$c"]['name'] = 'Loop';
                $t["block$c"]['id'] = 'loop';
                $t["block$c"]['pcount'] = 2;
                $t["block$c"]['param1'] = 'from';
                $t["block$c"]['param2'] = 'to';
                $tbl[$_os] = $t;
                
                  
            }elseif($_s == 'Closure'){
                $c = count($this->listViewAlt->items) + 1;
                
                $t["block$c"] = array();
                $t["block$c"]['name'] = 'Closure';
                $t["block$c"]['id'] = 'closure';
                $t["block$c"]['uneditable'] = true;
                $tbl[$_os] = $t;
                
                  
            }elseif($_s == 'Set form bg'){
                $c = count($this->listViewAlt->items) + 1;
                
                GLOBAL $vsel_, $_cp, $PD, $_os;
                $t = json_decode(file_get_contents($PD.'/'.$_cp.'/code_blocks.json'), 1)[$_os];
                $tbl = json_decode(file_get_contents($PD.'/'.$_cp.'/code_blocks.json'), 1);
                $c = count($t) + 1;
                $t["block$c"] = array();
                $t["block$c"]['name'] = 'Set form bg';
                $t["block$c"]['id'] = 'fmbg';
                $t["block$c"]['pcount'] = 2;
                $t["block$c"]['param1'] = 'Form name';
                $t["block$c"]['param2'] = '"#FFFFFF"';
                $tbl[$_os] = $t;
                
                  
            }elseif($_s == 'Set variable'){
                $c = count($this->listViewAlt->items) + 1;
                
                $t["block$c"] = array();
                $t["block$c"]['name'] = 'Set variable';
                $t["block$c"]['id'] = 'crvar';
                $t["block$c"]['pcount'] = 2;
                $t["block$c"]['param1'] = '"Variable name"';
                $t["block$c"]['param2'] = '"value"';
                $tbl[$_os] = $t;
                
                  
            }elseif($_s == 'Toggle resizable'){
                $c = count($this->listViewAlt->items) + 1;
                
                $t["block$c"] = array();
                $t["block$c"]['name'] = 'Toggle resizable';
                $t["block$c"]['id'] = 'formrz';
                $t["block$c"]['pcount'] = 2;
                $t["block$c"]['param1'] = '"Form name"';
                $t["block$c"]['param2'] = 'true';
                $tbl[$_os] = $t;
                
                  
            }elseif ($_s == 'Create function'){
                $c = count($this->listViewAlt->items) + 1;
                
                $t["block$c"] = array();
                $t["block$c"]['name'] = 'Create function';
                $t["block$c"]['id'] = 'cfunc';
                $t["block$c"]['pcount'] = 1;
                $t["block$c"]['param1'] = 'func_name';
                $tbl[$_os] = $t;
                
                     
            }elseif($_s == 'Call function'){
                $c = count($this->listViewAlt->items) + 1;
                
                $t["block$c"] = array();
                $t["block$c"]['name'] = 'Call function';
                $t["block$c"]['id'] = 'clfunc';
                $t["block$c"]['pcount'] = 1;
                $t["block$c"]['param1'] = 'func_name';
                $tbl[$_os] = $t;
                
                   
            }elseif($_s == 'Create sound'){
                $c = count($this->listViewAlt->items) + 1;
                
                $t["block$c"] = array();
                $t["block$c"]['name'] = 'Create sound';
                $t["block$c"]['id'] = 'crsnd';
                $t["block$c"]['pcount'] = 2;
                $t["block$c"]['param1'] = '"soundName"';
                $t["block$c"]['param2'] = '"path/to/sound"';
                $tbl[$_os] = $t;
                
                   
            }elseif($_s == 'Play sound'){
                $c = count($this->listViewAlt->items) + 1;
                
                $t["block$c"] = array();
                $t["block$c"]['name'] = 'Play sound';
                $t["block$c"]['id'] = 'psnd';
                $t["block$c"]['pcount'] = 1;
                $t["block$c"]['param1'] = '"soundName"';
                $tbl[$_os] = $t;
                
                   
            }elseif($_s == 'Stop sound'){
                $c = count($this->listViewAlt->items) + 1;
                
                $t["block$c"] = array();
                $t["block$c"]['name'] = 'Stop sound';
                $t["block$c"]['id'] = 'ssnd';
                $t["block$c"]['pcount'] = 1;
                $t["block$c"]['param1'] = '"soundName"';
                $tbl[$_os] = $t;
                
                   
            }if($_s == 'Create button'){
                $c = count($this->listViewAlt->items) + 1;
                
                $t["block$c"] = array();
                $t["block$c"]['name'] = 'Create button';
                $t["block$c"]['id'] = 'mkbtn';
                $t["block$c"]['pcount'] = 4;
                $t["block$c"]['param1'] = '"Name"';
                $t["block$c"]['param2'] = '"Title"';
                $t["block$c"]['param3'] = 'width';
                $t["block$c"]['param4'] = 'height';
                $tbl[$_os] = $t;
                
                   
            }elseif($_s == 'Add obj to form'){
                $c = count($this->listViewAlt->items) + 1;
                
                $t["block$c"] = array();
                $t["block$c"]['name'] = 'Add obj to form';
                $t["block$c"]['id'] = 'addo';
                $t["block$c"]['pcount'] = 2;
                $t["block$c"]['param1'] = '"Form Name"';
                $t["block$c"]['param2'] = '"Object Name"';
                $tbl[$_os] = $t;
                
                   
            }elseif($_s == 'Set obj pos'){
                $c = count($this->listViewAlt->items) + 1;
                
                $t["block$c"] = array();
                $t["block$c"]['name'] = 'Set obj pos';
                $t["block$c"]['id'] = 'setpos';
                $t["block$c"]['pcount'] = 3;
                $t["block$c"]['param1'] = '"Object Name"';
                $t["block$c"]['param2'] = 'x';
                $t["block$c"]['param3'] = 'y';
                $tbl[$_os] = $t;
                
                   
            }elseif($_s == 'Create textview'){
                $c = count($this->listViewAlt->items) + 1;
                
                $t["block$c"] = array();
                $t["block$c"]['name'] = 'Create textview';
                $t["block$c"]['id'] = 'crtv';
                $t["block$c"]['pcount'] = 4;
                $t["block$c"]['param1'] = '"Name"';
                $t["block$c"]['param2'] = '"Title"';
                $t["block$c"]['param3'] = 'x';
                $t["block$c"]['param4'] = 'y';
                $tbl[$_os] = $t;
                
                  
            }elseif($_s == 'Create image'){
                $c = count($this->listViewAlt->items) + 1;
                
                $t["block$c"] = array();
                $t["block$c"]['name'] = 'Create image';
                $t["block$c"]['id'] = 'crimg';
                $t["block$c"]['pcount'] = 4;
                $t["block$c"]['param1'] = '"Name"';
                $t["block$c"]['param2'] = '"$project_path/img/project_icon.png"';
                $t["block$c"]['param3'] = 'x';
                $t["block$c"]['param4'] = 'y';
                $tbl[$_os] = $t;
                
                  
            }elseif($_s == 'Set object size'){
                $c = count($this->listViewAlt->items) + 1;
                
                $t["block$c"] = array();
                $t["block$c"]['name'] = 'Set object size';
                $t["block$c"]['id'] = 'osize';
                $t["block$c"]['pcount'] = 3;
                $t["block$c"]['param1'] = '"Name"';
                $t["block$c"]['param2'] = 'width';
                $t["block$c"]['param3'] = 'height';
                $tbl[$_os] = $t;
                
                  
            }elseif($_s == 'Set object text'){
                $c = count($this->listViewAlt->items) + 1;
                
                $t["block$c"] = array();
                $t["block$c"]['name'] = 'Set object text';
                $t["block$c"]['id'] = 'tvtxt';
                $t["block$c"]['pcount'] = 2;
                $t["block$c"]['param1'] = '"Name"';
                $t["block$c"]['param2'] = '"Text"';
                $tbl[$_os] = $t;
                
                  
            }elseif($_s == 'Set form theme'){
                $c = count($this->listViewAlt->items) + 1;
                
                $t["block$c"] = array();
                $t["block$c"]['name'] = 'Set form theme';
                $t["block$c"]['id'] = 'formthem';
                $t["block$c"]['pcount'] = 2;
                $t["block$c"]['param1'] = '"Name"';
                $t["block$c"]['param2'] = '"/.theme/dark.css"';
                $tbl[$_os] = $t;
                
                  
            }elseif($_s == 'Set font size'){
                $c = count($this->listViewAlt->items) + 1;
                
                $t["block$c"] = array();
                $t["block$c"]['name'] = 'Set font size';
                $t["block$c"]['id'] = 'fontsz';
                $t["block$c"]['pcount'] = 2;
                $t["block$c"]['param1'] = '"Name"';
                $t["block$c"]['param2'] = '15';
                $tbl[$_os] = $t;
                
                  
            }elseif($_s == 'Create timer'){
                $c = count($this->listViewAlt->items) + 1;
                
                $t["block$c"] = array();
                $t["block$c"]['name'] = 'Create timer';
                $t["block$c"]['id'] = 'crtm';
                $t["block$c"]['pcount'] = 2;
                $t["block$c"]['param1'] = '"Name"';
                $t["block$c"]['param2'] = 'delay (s)';
                $tbl[$_os] = $t;
                
                  
            }elseif($_s == 'Enable timer'){
                $c = count($this->listViewAlt->items) + 1;
                
                $t["block$c"] = array();
                $t["block$c"]['name'] = 'Enable timer';
                $t["block$c"]['id'] = 'entmr';
                $t["block$c"]['pcount'] = 1;
                $t["block$c"]['param1'] = '"Name"';
                $tbl[$_os] = $t;
            }elseif($_s == 'Disable timer'){
                $c = count($this->listViewAlt->items) + 1;
                
                $t["block$c"] = array();
                $t["block$c"]['name'] = 'Disable timer';
                $t["block$c"]['id'] = 'distmr';
                $t["block$c"]['pcount'] = 1;
                $t["block$c"]['param1'] = '"Name"';
                $tbl[$_os] = $t;
            }elseif($_s == 'Timer repeatable'){
                $c = count($this->listViewAlt->items) + 1;
                
                $t["block$c"] = array();
                $t["block$c"]['name'] = 'Timer repeatable';
                $t["block$c"]['id'] = 'tmrrep';
                $t["block$c"]['pcount'] = 2;
                $t["block$c"]['param1'] = '"Name"';
                $t["block$c"]['param2'] = 'false';
                $tbl[$_os] = $t;
            }elseif($_s == 'Load fxml form'){
                $c = count($this->listViewAlt->items) + 1;
                
                $t["block$c"] = array();
                $t["block$c"]['name'] = 'Load fxml form';
                $t["block$c"]['id'] = 'fxml';
                $t["block$c"]['pcount'] = 2;
                $t["block$c"]['param1'] = '"Form Name"';
                $t["block$c"]['param2'] = '"$project_path/other/name"';
                $tbl[$_os] = $t;
            }elseif($_s == 'Get fxml object'){
                $c = count($this->listViewAlt->items) + 1;
                
                $t["block$c"] = array();
                $t["block$c"]['name'] = 'Get fxml object';
                $t["block$c"]['id'] = 'fxobj';
                $t["block$c"]['pcount'] = 3;
                $t["block$c"]['param1'] = '"Form Name"';
                $t["block$c"]['param2'] = '"New object name"';
                $t["block$c"]['param3'] = 'id';
                $tbl[$_os] = $t;
            }elseif($_s == 'Create save'){
                $c = count($this->listViewAlt->items) + 1;
                
                $t["block$c"] = array();
                $t["block$c"]['name'] = 'Create save';
                $t["block$c"]['id'] = 'crsv';
                $t["block$c"]['pcount'] = 2;
                $t["block$c"]['param1'] = '"Filename.txt"';
                $t["block$c"]['param2'] = '"Data"';
                $tbl[$_os] = $t;
            }elseif($_s == 'GET Request'){
                $c = count($this->listViewAlt->items) + 1;
                
                $t["block$c"] = array();
                $t["block$c"]['name'] = 'GET Request';
                $t["block$c"]['id'] = 'getr';
                $t["block$c"]['pcount'] = 2;
                $t["block$c"]['param1'] = '"Variable name"';
                $t["block$c"]['param2'] = '"https://google.com"';
                $tbl[$_os] = $t;
            }elseif($_s == 'Get pressed mouse button'){
                $c = count($this->listViewAlt->items) + 1;
                
                $t["block$c"] = array();
                $t["block$c"]['name'] = 'Get pressed mouse button';
                $t["block$c"]['id'] = 'gpmb';
                $t["block$c"]['pcount'] = 1;
                $t["block$c"]['param1'] = '"Variable name"';
                $tbl[$_os] = $t;
            }elseif($_s == 'If'){
                $c = count($this->listViewAlt->items) + 1;
                
                $t["block$c"] = array();
                $t["block$c"]['name'] = 'If';
                $t["block$c"]['id'] = 'if';
                $t["block$c"]['pcount'] = 1;
                $t["block$c"]['param1'] = '1 == 1';
                $tbl[$_os] = $t;
            }elseif($_s == 'Else if'){
                $c = count($this->listViewAlt->items) + 1;
                
                $t["block$c"] = array();
                $t["block$c"]['name'] = 'Else if';
                $t["block$c"]['id'] = 'elseif';
                $t["block$c"]['pcount'] = 1;
                $t["block$c"]['param1'] = '1 == 1';
                $tbl[$_os] = $t;
            }elseif($_s == 'Center form on screen'){
                $c = count($this->listViewAlt->items) + 1;
                
                $t["block$c"] = array();
                $t["block$c"]['name'] = 'Center form on screen';
                $t["block$c"]['id'] = 'cfscr';
                $t["block$c"]['pcount'] = 1;
                $t["block$c"]['param1'] = '"Form name"';
                $tbl[$_os] = $t;
            }elseif($_s == 'Show dialog'){
                $c = count($this->listViewAlt->items) + 1;
                
                $t["block$c"] = array();
                $t["block$c"]['name'] = 'Show dialog';
                $t["block$c"]['id'] = 'anydlg';
                $t["block$c"]['pcount'] = 2;
                $t["block$c"]['param1'] = '"Text"';
                $t["block$c"]['param2'] = '"INFORMATION"';
                $tbl[$_os] = $t;
            }elseif($_s == 'Shutdown app'){
                $c = count($this->listViewAlt->items) + 1;
                
                $t["block$c"] = array();
                $t["block$c"]['name'] = 'Shutdown app';
                $t["block$c"]['id'] = 'shtdapp';
                $t["block$c"]['uneditable'] = true;
                $tbl[$_os] = $t;
            }elseif($_s == 'Get app start time'){
                $c = count($this->listViewAlt->items) + 1;
                
                $t["block$c"] = array();
                $t["block$c"]['name'] = 'Get app start time';
                $t["block$c"]['id'] = 'stptime';
                $t["block$c"]['pcount'] = 1;
                $t["block$c"]['param1'] = '"Variable name"';
                $tbl[$_os] = $t;
            }elseif($_s == 'Toggle fullscreen'){
                $c = count($this->listViewAlt->items) + 1;
                $t["block$c"] = array();
                $t["block$c"]['name'] = 'Toggle fullscreen';
                $t["block$c"]['id'] = 'fullscr';
                $t["block$c"]['pcount'] = 1;
                $t["block$c"]['param1'] = '"Form Name"';
                $tbl[$_os] = $t;
            }
            file_put_contents($PD.'/'.$_cp.'/code_blocks.json', json_encode($tbl));
            $this->renderBlocks();
        }
    }







}
