<?php

defined('_JEXEC') or die('Restricted Access');

class PlgSearchPbacademySearch extends JPlugin
{
    public function __construct(&$subject, $config = array()) {
        parent::__construct($subject, $config);
    }
    
    public function onContentSearchAreas(){
        static $areas = array(
            'pbacademysearch' => 'Pbacademysearch'
        );
        return $areas;
    }
    
    /**
     * The sql must return the following fields that are used in a common display
     * routine: href, title, section, created, text, browsernav
     * 
     * @param string $text Target search string
     * @param string $phrase Matching option, exact|any|all
     * @param string $ordering Ordering option, newest|oldest|popular|alpha|category
     * @param []mixed $areas An array if the search is to be restricted to areas, null if search all
     */
    public function onContentSearch($text, $phrase = '', $ordering='',$areas = null){
        $user = JFactory::getUser();
        $groups = implode(',', $user->getAuthorisedViewLevels());
        
        if(is_array($areas)){
            if (!array_intersect($areas, array_keys($this->onContentSearchAreas()))){
                return [];
            }
        }
        $text = trim($text);

        if($text == ''){
            return [];
        }
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select($db->quoteName('Id') . ',' .
                        $db->quoteName('Title','title').',' .
                        $db->quoteName('Description','text'). ','.
                        $db->quoteName('DatePublished','created'))
                ->from('#__CBULessons');
        switch($phrase){
            case 'exact':
                $text = $db->quote( '%' . $db->escape($text,true). '%',false);
                $query->where($db->quoteName('title') . ' LIKE '. $text);
                break;
            case 'all':
            case 'any':
            default:
                $words = explode(' ',$text);
                $wheres = [];
                foreach($words as $word){
                    $word = $db->quote( '%' . $db->escape($word,true). '%',false);
                    $wheres2 = [];
                    $wheres2[] = 'LOWER(' . $db->quoteName('Title') . ') LIKE LOWER(' . $word . ')';
                    $wheres2[] = 'LOWER(' . $db->quoteName('Description') . ') LIKE LOWER(' . $word . ')';
                    $wheres[] = implode(' OR ', $wheres2);
                }
                $query->where(
                            '(' 
                            . implode( 
                                ($phrase == 'all' ? 
                                        ') AND (' : 
                                        ') OR ('
                                )
                                ,$wheres 
                                )
                            .')'
                        );
                break;        
        }
        
        switch($ordering){
            case 'alpha':
                $query->order($db->quoteName('title'));
                break;
            case 'oldest':
                $query->order($db->quoteName('created') . ' ASC');
                break;
            case 'newest':
                $query->order($db->quoteName('created') . ' DESC');
                break;
            default:
                $query->order($db->quoteName('title') . ' ASC');
                break;
        }
        
        $db->setQuery($query);
        $rows = $db->loadObjectList();
        
        foreach($rows as $key=>$row){
            $rows[$key]->href = JRoute::_('index.php?option=com_pbacademy'
                                        . '&section=lesson'
                                        . '&l_id=' . $row->Id);
            $rows[$key]->section = "P&B Academy";
            $rows[$key]->browsernav = '1';
        }
        return $rows;
    }   
}