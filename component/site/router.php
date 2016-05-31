<?php

defined('_JEXEC') or die('Restricted Access');

/**
 * This is the class responsible for making the urls for this site
 * "Search Engine Friendly" -- i.e. human-readible. 
 * 
 * The basic url structure this router creates is this:
 * 
 * index.php/[menu alias]/pbacademy/[section]/[id]
 * 
 * section will be either school, series, or lesson.
 */
class PbacademyRouter extends JComponentRouterBase
{
    /**
     * This takes an associative array of variables and converts them into
     * an array of segments for the SEF url.
     * @param array $query
     * @return array
     */
    public function build(&$query) {
        $segs = [];
        if(isset($query['section'])){ //If a section is specified...
            if($query['section'] === "series"){//If it's a series...
                $segs[] = "series";
                if(isset($query['s_id'])){//If an id is specified...
                    $segs[] = $query['s_id'];
                    unset($query['s_id']);
                }
            }elseif($query['section'] === "category"){//If it's a category...
                $segs[] = "schools";
                if(isset($query['c_id'])){//If an id is specified...
                    $segs[] = $query['c_id'];
                    unset($query['c_id']);
                }
            }elseif(isset($query['l_id']) && $query['section'] === "lesson"){
                $segs[] = "lessons";
                $segs[] = $query['l_id'];
                unset($query['l_id']);
            }
            unset($query['section']);
        }
        return $segs;
    }

    /**
     * This takes an array of url segments and parses them into an associative
     * array of meaningful variables that the PB Academy can use.
     * @param array $segments
     * @return array
     */
    public function parse(&$segments) {
        $vars = [];
        $count = count($segments);
        switch ($segments[0]) {
            case 'series':
                $vars['section'] = 'series';
                if($count === 2){
                    $vars['s_id'] = $segments[1];
                }
                break;
            case 'schools':
                $vars['section'] = 'category';
                if($count === 2){
                    $vars['c_id'] = $segments[1];
                }
                break;
            case 'lessons':
                if($count === 1){
                    $vars['section'] = 'home';
                }elseif($count === 2){
                    $vars['section'] = 'lesson';
                    $vars['l_id'] = $segments[1];
                }
                break;
        }
        return $vars;
    }
}