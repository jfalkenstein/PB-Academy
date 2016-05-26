<?php

/**
 * The Joomla repository for all categories.
 * @author jfalkenstein
 */
class CategoriesRepository extends BaseJoomlaRepository implements ICategoriesRepository{
    
    /**
     * Obtains ALL categories from the DB.
     * @return Category[]
     */
    public function GetAll() {
        $cats = [];
        $rawCats = $this->getRawCategoriesFromDb();
        foreach($rawCats as $cat){
            $cats[$cat->Id] = $this->convertRawCategory($cat);
        }
        return $cats;
    }
    
    /**
     * This establishes and executes the db query to obtain a stdClass array
     * of categories.
     * @param int $id Optional value. If specified, only returns single object.
     * @return stdClass[]/stdClass
     */
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
    
    /**
     * Converts a stdClass object to a Category.
     * @param stdClass $rawCat
     * @return \Category
     */
    private function convertRawCategory (stdClass $rawCat){ 
        $cat = new Category(
                    $rawCat->Name,
                    $rawCat->Description,
                    $rawCat->Id,
                    $rawCat->ImagePath);
        return $cat;
    }
    
    /**
     * Obtains a single Category, identified by its Id.
     * @param int $id
     * @param null $default The value to return if not found.
     * @return Category
     */
    public function GetById($id, $default = null) {
        $rawCat = $this->getRawCategoriesFromDb($id);
        if(is_null($rawCat)) return $default;
        return $this->convertRawCategory($rawCat);
    }
    
    /**
     * Saves a Category to the DB. Returns a success boolean.
     * @param Category $item
     * @param bool $update Whether or not this is an update or a new category
     * @return bool
     */
    public function Save($item, $update = FALSE) {
        $db = $this->db;
        $query = $db->getQuery(true);
        //Convert the Category to a stdClass object.
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
    /**
     * Deletes a category from the DB. Returns a success boolean.
     * @param int $id
     * @return bool
     */
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
