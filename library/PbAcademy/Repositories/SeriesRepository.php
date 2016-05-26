<?php

/**
 * The repository for series
 * @author jfalkenstein
 */
class SeriesRepository extends BaseJoomlaRepository implements ISeriesRepository{
    /**
     * This function will pull a series object from the DB and return a full
     * series object.
     * 
     * @param int $id
     * @param mixed $default
     * @return LessonSeries A fully-loaded LessonSeries Object.
     */
    public function GetById($id, $default = null) {
        if(is_null($id)){
            return $default;
        }
        $rawSeries = $this->getRawSeriesFromDb($id);
        if(isset($rawSeries)){
            return $this->convertRawSeries($rawSeries);
        }
        return $default;
    }
    
    /**
     * This will obtain a list of all raw series from the DB, convert each to a 
     * LessonSeries, then it will return an array of LessonSeries.
     * @return []LessonSeries
     */
    public function GetAllSeries() {
        $allSeries = [];
        $rawSeries = $this->getRawSeriesFromDb();
        foreach($rawSeries as $series){
            $allSeries[$series->Id] = $this->convertRawSeries($series);
        }
        return $allSeries;
    }
    
    /**
      * This will pull either an array of raw series objects or a single raw series
      * object, depending on whether an $id is passed in.
      * @param int $id This determines whether or not the db will return a single object
      * with this id or if it will be an array (if its left null).
      */
    private function getRawSeriesFromDb($id = null){
        $db = $this->db;
        $query = $db->getQuery(true);
        $query->select('*')
                ->from($db->quoteName('#__CBUSeries'));
        if(isset($id)){
            $query->where($db->quoteName('Id') . ' = ' . $id);
            $db->setQuery($query);
            return $db->loadObject();
        }else{
            $db->setQuery($query);
            return $db->loadObjectList();
        }
    }
    
    /**
     * This will receive the raw series object from the db and return a fully/loaded
     * LessonSeries.
     * @param stdClass $rawSeries
     * @return \LessonSeries
     */
    private function convertRawSeries(stdClass $rawSeries){
        return new LessonSeries($rawSeries->SeriesName,
                                $rawSeries->ImagePath,
                                $rawSeries->Id,
                                $rawSeries->Description);
    }
    
    /**
     * Saves/updates an Series and returns a success boolean.
     * @param LessonSeries $item
     * @param bool $update
     * @return bool
     */
    public function Save($item, $update = FALSE) {
        $db = $this->db;
        $query = $db->getQuery(true);
        $object = new stdClass();
        $object->Id = $item->Id;
        $object->SeriesName = $item->SeriesName;
        $object->ImagePath = $item->ImagePath;
        $object->Description = $item->Description;
        $success;
        if($update){
            $success = $db->updateObject('#__CBUSeries', $object, 'Id');
        }else{
            $success = $db->insertObject('#__CBUSeries', $object, 'Id');
        }
        return $success;
    }
    /**
     * Deletes a series and returns a success boolean.
     * @param int $id
     * @return bool
     */
    public function Delete($id) {
        $db = $this->db;
        $query = $db->getQuery(true);
        $query->delete($db->quoteName('#__CBUSeries'))
                ->where($db->quoteName('Id') . ' = ' . $id);
        $db->setQuery($query);
        $success = $db->execute();
        return $success;
    }
}
