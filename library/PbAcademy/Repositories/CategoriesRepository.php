<?php

/**
 * Description of CategoriesRepository
 *
 * @author jfalkenstein
 */
class CategoriesRepository extends BaseJoomlaRepository implements ICategoriesRepository{
    
    public function GetAll() {
        $cats = [];
        $rawCats = $this->getRawCategoriesFromDb();
        foreach($rawCats as $cat){
            $cats[$cat->Id] = $this->convertRawCategory($cat);
        }
        return $cats;
    }
    
    private function getRawCategoriesFromDb($id = null){
        $db = $this->db;
        $query = $db->getQuery(true);
        $query->select('*')
                ->from($db->quoteName('#__CBUCategories'));
        if(isset($id)){
            $query->where($db->quoteName('Id') . ' = ' . $id);
            $db->setQuery($query);
            return $db->loadObject();
        }
        $db->setQuery($query);
        return $db->loadObjectList();
    }
    
    private function convertRawCategory (stdClass $rawCat){ 
        $cat = new Category(
                    $rawCat->Name,
                    $rawCat->Description,
                    $rawCat->Id,
                    $rawCat->ImagePath);
        return $cat;
    }
    
    public function GetById($id, $default = null) {
        $rawCat = $this->getRawCategoriesFromDb($id);
        if(is_null($rawCat)) return $default;
        return $this->convertRawCategory($rawCat);
    }
    public function Save($item, $update = FALSE) {
        $db = $this->db;
        $query = $db->getQuery(true);
        $object = new stdClass();
        $object->Id = $item->Id;
        $object->Name = $item->Name;
        $object->Description = $item->Description;
        $object->ImagePath = $item->ImagePath;
        $success;
        if($update){
            $success = $db->updateObject('#__CBUCategories',$object,'Id');
        }else{
            $success = $db->insertObject('#__CBUCategories',$object, 'Id');
        }
        return $success;
    }
    
    public function Delete($id) {
        $db = $this->db;
        $query = $db->getQuery(true);
        $query->delete($db->quoteName('#__CBUCategories'))
                ->where($db->quoteName('Id') . ' = ' . $id);
        $db->setQuery($query);
        $success = $db->execute();
        return $success;
    }
}
