<?php

////////////////////////////////////////////////////////////////////////////////
//                                                                            //
//   Copyright (C) 2007  Phorum Development Team                              //
//   http://www.phorum.org                                                    //
//                                                                            //
//   This program is free software. You can redistribute it and/or modify     //
//   it under the terms of either the current Phorum License (viewable at     //
//   phorum.org) or the Phorum License that was distributed with this file    //
//                                                                            //
//   This program is distributed in the hope that it will be useful,          //
//   but WITHOUT ANY WARRANTY, without even the implied warranty of           //
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.                     //
//                                                                            //
//   You should have received a copy of the Phorum License                    //
//   along with this program.                                                 //
////////////////////////////////////////////////////////////////////////////////

if(!defined("PHORUM_ADMIN")) return;

function phorum_admin_error($error)
{
    echo "<div class=\"PhorumAdminError\">$error</div>\n";
}

function phorum_admin_okmsg($error)
{
    echo "<div class=\"PhorumAdminOkMsg\">$error</div>\n";
}
// phorum_get_language_info and phorum_get_template_info moved to common.php (used in the cc too)

function phorum_get_folder_info()
{
    $folders=array();
    $folder_data=array();

    $forums = phorum_db_get_forums();

    foreach($forums as $forum){
        if($forum["folder_flag"]){
            $path = $forum["name"];
            $parent_id=$forum["parent_id"];
            while($parent_id!=0  && $parent_id!=$forum["forum_id"]){
                $path=$forums[$parent_id]["name"]."::$path";
                $parent_id=$forums[$parent_id]["parent_id"];
            }
            $folders[$forum["forum_id"]]=$path;
        }
    }

    asort($folders);

    $tmp=array("--None--");

    foreach($folders as $id => $folder){
        $tmp[$id]=$folder;
    }

    $folders=$tmp;

    return $folders;

}

/*
*
* $forums_only can be 0,1,2,3
* 0 = all forums / folders
* 1 = all forums
* 2 = only forums + vroot-folders (used in banlists)
* 3 = only vroot-folders
*
* $vroot can be -1,0 or > 0
* -1 works as told above
* 0 returns only forums / folders with vroot = 0
* > 0 returns only forums / folders with the given vroot
*
*/

function phorum_get_forum_info($forums_only=0,$vroot = -1)
{
    $folders=array();
    $folder_data=array();

    $forums = phorum_db_get_forums();

    foreach($forums as $forum){

        if( (
        $forums_only == 0 ||
        ($forum['folder_flag'] == 0 && $forums_only != 3) ||
        ($forums_only==2 && $forum['vroot'] > 0 && $forum['vroot'] == $forum['forum_id']) ||
        ($forums_only==3 && $forum['vroot'] == $forum['forum_id'] )
        ) && ($vroot == -1 || $vroot == $forum['vroot']) )  {


            $path = $forum["name"];
            $parent_id=$forum["parent_id"];

            while( $parent_id!=0 ){

                $path=$forums[$parent_id]["name"]."::$path";

                $parent_id=$forums[$parent_id]["parent_id"];

            }

            if($forums_only!=3 && $forum['vroot'] && $forum['vroot']==$forum['forum_id']) {
                $path.=" (Virtual Root)";
            }
            $folders[$forum["forum_id"]]=$path;
        }
    }

    asort($folders,SORT_STRING);

    return $folders;

}


/*
* Sets the given vroot for the descending forums / folders
* which are not yet in another descending vroot
*
* $folder = folder from which we should go down
* $vroot  = virtual root we set the folders/forums to
* $old_vroot = virtual root which should be overrideen with the new value
*
*/
function phorum_admin_set_vroot($folder,$vroot=-1,$old_vroot=0) {
    // which vroot
    if($vroot == -1) {
        $vroot=$folder;
    }

    // get the desc forums/folders
    $descending=phorum_admin_get_descending($folder);
    $valid=array();

    // collecting vroots
    $vroots=array();
    foreach($descending as $id => $data) {
        if($data['folder_flag'] == 1 && $data['vroot'] != 0 && $data['forum_id'] == $data['vroot']) {
            $vroots[$data['vroot']]=true;
        }
    }

    // getting forums which are not in a vroot or not in *this* vroot
    foreach($descending as $id => $data) {
        if($data['vroot'] == $old_vroot || !isset($vroots[$data['vroot']])) {
            $valid[$id]=$data;
        }
    }

    // $valid = forums/folders which are not in another vroot
    $set_ids=array_keys($valid);
    $set_ids[]=$folder;

    $new_forum_data=array('forum_id'=>$set_ids,'vroot'=>$vroot);
    $returnval=phorum_db_update_forum($new_forum_data);

    return $returnval;
}

function phorum_admin_get_descending($parent) {

    $ret_data=array();
    $arr_data=phorum_db_get_forums(0,$parent);
    foreach($arr_data as $key => $val) {
        $ret_data[$key]=$val;
        if($val['folder_flag'] == 1) {
            $more_data=phorum_db_get_forums(0,$val['forum_id']);
            $ret_data=$ret_data + $more_data; // array_merge reindexes the array
        }
    }
    return $ret_data;
}

function phorum_admin_build_path_array($only_forum = -1)
{

    $folders=array();
    $folder_data=array();

    $forums = phorum_db_get_forums();
    $forums[0]=array('vroot'=>0,'forum_id'=>0,'name'=>$GLOBALS['PHORUM']['title']);

    //print_var($forums['20428']);

    foreach($forums as $forum){

        if($only_forum == -1 || $forum['forum_id'] == $only_forum)  {

            $path = array();
            $path[$forum['forum_id']] = $forum["name"];
            $parent_id=$forum["parent_id"];

            if($forum['forum_id'] != $forum['vroot']) {

                while( true ){

                    $path[$parent_id]=$forums[$parent_id]["name"];

                    // get-out condition
                    if($parent_id == 0 || $forums[$parent_id]["vroot"] == $forums[$parent_id]["forum_id"]) {
                        break;
                    }

                    $parent_id=$forums[$parent_id]["parent_id"];

                }
            }
        }
        $folders[$forum["forum_id"]]=$path;
    }

    $folders = array_reverse($folders, true);

    return $folders;

}
?>