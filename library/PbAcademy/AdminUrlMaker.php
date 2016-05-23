<?php

/**
 * Description of AdminUrlMaker
 *
 * @author jfalkenstein
 */
class AdminUrlMaker {
    public static $PB_ACADEMY = '/administrator/index.php?option=com_pbacademy';
    
    public static function AjaxAllLessons(){
        return self::$PB_ACADEMY
                    . '&controller=lesson'
                    . '&action=pullAll';
    }
    
    public static function AjaxLessonsForSeries($id){
        return self::$PB_ACADEMY
                    . '&controller=lesson'
                    . '&action=pullForSeries'
                    . '&id=' . $id;
    }
    
    public static function AjaxLessonsForCategory($id){
        return self::$PB_ACADEMY
                    . '&controller=lesson'
                    . '&action=pullForCategory'
                    . '&id=' . $id;
    }
    
    public static function AJaxAllCategories(){
        return self::$PB_ACADEMY
                    . '&controller=category'
                    . '&action=pullAll';
    }
    
    public static function AJaxAllSeries(){
        return self::$PB_ACADEMY
                    . '&controller=series'
                    . '&action=pullAll';
    }
    
    public static function AjaxPostLesson(){
        return self::$PB_ACADEMY
                    . '&controller=lesson'
                    . '&action=AddUpdate';
    }
    
    public static function AjaxPostSeries(){
        return self::$PB_ACADEMY
                    . '&controller=series'
                    . '&action=AddUpdate';
    }
    
    public static function AjaxPostCategory(){
        return self::$PB_ACADEMY
                    . '&controller=category'
                    . '&action=AddUpdate';
    }

    public static function ManageHome(){
        return JRoute::_(self::$PB_ACADEMY);
    }
    
    public static function AddEditLesson($id = null){
        $url = self::$PB_ACADEMY . '&section=lesson';
        $url.= (isset($id)) ? '&id=' . $id : '';
        return JRoute::_($url);
    }
    
    public static function AddEditSeries($id = null){
        $url = self::$PB_ACADEMY . '&section=series';
        $url.= (isset($id)) ? '&id=' . $id : '';
        return JRoute::_($url);
    }
    
    public static function AddEditCategory($id = null){
        $url = self::$PB_ACADEMY . '&section=category';
        $url.= (isset($id)) ? '&id=' . $id : '';
        return JRoute::_($url);
    }
    
    public static function AjaxDeleteLesson(){
        return self::$PB_ACADEMY
                    . '&controller=lesson'
                    . '&action=Delete';
    }
    public static function AjaxDeleteCategory(){
        return self::$PB_ACADEMY
                    . '&controller=category'
                    . '&action=Delete';
    }
    
    public static function AjaxDeleteSeries(){
        return self::$PB_ACADEMY
                    . '&controller=series'
                    . '&action=Delete';
    }
    
    public static function AjaxPreviewLesson(){
        return self::$PB_ACADEMY
                    . '&controller=lesson'
                    . '&action=GetPreview';
    }

}
