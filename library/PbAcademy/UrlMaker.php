<?php

/**
 * This class provides centralized access to URL routes. A change to any of these
 * will be enacted system-wide.
 *
 * @author jfalkenstein
 */
class UrlMaker {
    public static $PB_ACADEMY = 'index.php?option=com_pbacademy';
    
    public static function Category($id){
        return JRoute::_(self::$PB_ACADEMY
                        . '&section=category'
                        . '&c_id='. $id);
    }
    public static function AllSeries(){
        return JRoute::_(self::$PB_ACADEMY
                        . '&section=series');
    }
    public static function Categories(){
        return JRoute::_(self::$PB_ACADEMY
                        . '&section=category');
    }
    public static function Home(){
        return JRoute::_(self::$PB_ACADEMY);
    }
    public static function Lesson($id){
        return JRoute::_(self::$PB_ACADEMY
                        . '&section=lesson'
                        . '&l_id='. $id);
    }
    public static function AllLessons(){
        return JRoute::_(self::$PB_ACADEMY
                        . '&section=lesson');
    }
    public static function Series($id){
        return JRoute::_(self::$PB_ACADEMY
                        . '&section=series'
                        . '&s_id='. $id);
    }
}
