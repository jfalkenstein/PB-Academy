<?php

/**
 * Description of LessonsRepository
 *
 * @author jfalkenstein
 * @property JDatabaseDriver $db
 */
class LessonsRepository extends BaseJoomlaRepository implements ILessonsRepository {

    public function GetAllLessons($publishedOnly = true) {
        return $this->GetRecentLessons(0, null, $publishedOnly);
    }
    /**
     * 
     * @param int $id
     * @param mixed $default
     * @return Lesson
     */
    public function GetLessonById($id, $default = null) {
        //1. Get lesson record joined with its content type from the db.
        $rawLesson = $this->getRawLessonFromDb($id);
        if(is_null($rawLesson)){
            return $default;
        }else{
            //2. Instantiate the lesson, also instantiating and assigning its 
            //category and ContentType
            $les = self::convertRawLesson($rawLesson);
            return $les;
        };
    }
    
    /**
     * This will receive a raw Lesson object from the DB and will convert it into
     * a fully-loaded Lesson object and return that.
     * @param stdClass $rawObjectFromDb
     * @return \Lesson
     */   
    private function convertRawLesson(stdClass $rawObjectFromDb){
        $mgr = PBAcademyManager::GetInstance();
        $cat = new Category(
                    $rawObjectFromDb->Name,
                    $rawObjectFromDb->CatDescription,
                    $rawObjectFromDb->CategoryId,
                    $rawObjectFromDb->CatImagePath);
        $ct = new ContentType(
                $rawObjectFromDb->TypeName, 
                $rawObjectFromDb->ContentTypeId);
        $ser = $mgr->GetSeries($rawObjectFromDb->SeriesId);
        $isPublished = ($rawObjectFromDb->Published === "1") ? true : false;
        $les = new Lesson(
                $ct,
                $rawObjectFromDb->Title,
                $cat,
                $rawObjectFromDb->Description,
                $rawObjectFromDb->Id,
                $rawObjectFromDb->Content,
                $ser,
                $rawObjectFromDb->ImagePath,
                $rawObjectFromDb->SourceCredit,
                $rawObjectFromDb->DatePublished,
                $rawObjectFromDb->SeriesOrder,
                $isPublished);
        return $les;
    }
    private function getRawLessonFromDb($id){
        $db = $this->db;
        $query = self::getQueryForAllLessons($db);
        $query->where($db->quoteName('l.Id') . ' = ' . $id);
        $db->setQuery($query);
        $results = $db->loadObject();
        return $results;       
    }
    
    /**
     * getQueryForAllLessons:
     * This method is a shortcut for the complicated join query required to pull
     * all lessons. 
     * @return JDatabaseQuery - The loaded query object
     */
    private function getQueryForAllLessons(){
        $db = $this->db;
        $query = $db->getQuery(true);
        $query->select([
                    'l.*',
                    'ct.TypeName',
                    'c.Name',
                    'c.Description AS CatDescription',
                    'c.ImagePath AS CatImagePath'
                ])
                ->from($db->quoteName('#__CBULessons','l'))
                ->leftJoin(
                        $db->quoteName('#__CBUContentTypes','ct') . 
                        ' ON (' .
                            $db->quoteName('l.ContentTypeId') . 
                            ' = ' .
                            $db->quoteName('ct.Id') . 
                            ')'
                )
                ->leftJoin(
                        $db->quoteName('#__CBUCategories','c') .
                        ' ON (' .
                            $db->quoteName('l.CategoryId') .
                            ' = ' .
                            $db->quoteName('c.Id') .
                            ')'
                );
        return $query;
    }
    
    /**
     * GetLessonsForCategory:
     * This will return an array of all the lessons for a given categoryID.
     * @param int $catId
     * @return []Lesson
     */
    public function GetLessonsForCategory($catId, $publishedOnly = true) {
        $lessons = [];
        $db = $this->db;
        $query = $this->getQueryForAllLessons($db);
        $publishedFilter = ($publishedOnly) ? ' AND ' . $db->quoteName('l.Published') . ' = 1' : '';
        $query->where($db->quoteName('l.CategoryId'). ' = ' . $catId . $publishedFilter);
        $query->order($db->quoteName('l.DatePublished') . ' DESC');
        $db->setQuery($query);
        $results = $db->loadObjectList();
        foreach($results as $les){
            $lessons[] = $this->convertRawLesson($les);
        }
        return $lessons;
    }
    
    /**
     * GetRecentLessons:
     * This static method will return the specified number of Lessons
     *  most recently published, or the maximum available. If $catId is specified,
     *  it will only return the lessons for the specified category.
     * @param int $numberToGet
     * @param int $catId
     * @return []Lesson - An array of Lesson.
     */
    public function GetRecentLessons($numberToGet = 4, $catId = null, $publishedOnly = true) {
        $recentLessons = [];
        $db = $this->db;
        $query = $this->getQueryForAllLessons();
        $publishedFilter = ($publishedOnly) ? ' AND ' . $db->quoteName('l.Published') . ' = 1' : '';
        if(isset($catId)){
            $query->where($db->quoteName('l.CategoryId') . ' = ' . $catId . $publishedFilter);
        }elseif($publishedOnly){
            $query->where($db->quoteName('l.Published'). ' = 1');
        }
        $query->order($db->quoteName('Id'). ' DESC');
        $db->setQuery($query,0,$numberToGet);
        $results = $db->loadObjectList();
        foreach($results as $les){
            $recentLessons[] = $this->convertRawLesson($les);
        }
        return $recentLessons;
    }
    
    /**
     * This will return all the lessons belonging to the series identified by
     * $seriesId
     * @param int $seriesId
     * @return []Lesson
     */
    public function GetLessonsForSeries($seriesId, $publishedOnly = true) {
        $lessons = [];
        $db = $this->db;
        $query = $this->getQueryForAllLessons($db);
        $publishedFilter = ($publishedOnly) ? ' AND ' . $db->quoteName('l.Published') . ' = 1' : '';
        $query->where($db->quoteName('SeriesId') . ' = ' . $seriesId . $publishedFilter);
        $query->order($db->quoteName('l.SeriesOrder') . ' DESC');
        $db->setQuery($query);
        $results = $db->loadObjectList();
        foreach($results as $les){
            $lessons[] = $this->convertRawLesson($les);
        }
        return $lessons;
    }
    
    public function Save($item, $update = FALSE) {
        if(isset($item->SeriesOrder) && isset($item->Series)){
            $this->bumpSeriesOrders($item->SeriesOrder, $item->Series->Id);
        }
        $db = $this->db;
        $query = $db->getQuery(true);
        $object = new stdClass();
        $object->Id = (isset($item->Id)) ? (int)$item->Id : null;
        $object->Title = $item->Title;
        $object->SeriesId = (isset($item->Series->Id)) ? (int)$item->Series->Id : null;
        $object->CategoryId = (int)$item->Category->Id;
        $object->ContentTypeId = (int)$item->ContentType()->Id;
        $object->ImagePath = $item->ImagePath;
        $object->SourceCredit = $item->SourceCredit;
        $object->Content = $item->Content;
        $object->Description = $item->Description;
        $object->DatePublished = $item->DateForSql();
        $object->SeriesOrder = (int)$item->SeriesOrder;
        $object->Published = ($item->Published) ? 1 : 0;
        $success;
        if($update){
            $success = $db->updateObject('#__CBULessons',$object,'Id');
        }else{
            $success = $db->insertObject('#__CBULessons',$object,'Id');
        }
        return $success;
    }
    
    private function bumpSeriesOrders($positionToBump, $seriesId){
        $db = $this->db;
        $query = $db->getQuery(true);
        $query->update($db->quoteName('#__CBULessons'))
                ->set($db->quoteName('SeriesOrder') . ' = ' . $db->quoteName('SeriesOrder') . ' + 1')
                ->where($db->quoteName('SeriesOrder') . ' >= ' . $positionToBump . 
                        ' AND ' . $db->quoteName('SeriesId') . ' = ' . $seriesId);
        $db->setQuery($query);
        $db->execute();
    }
    
    public function Delete($id){
        $db = $this->db;
        $query = $db->getQuery(true);
        $query->delete($db->quoteName('#__CBULessons'))
                ->where($db->quoteName('Id') . ' = ' . $id);
        $db->setQuery($query);
        $success = $db->execute();
        return $success;
    }
}
