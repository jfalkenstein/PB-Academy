<?php

/**
 * This is the repository for Content Types.
 * Because there are not many content types, all types will be returned and then
 * they will be filtered as needed higher up the chain.
 * @author jfalkenstein
 */
class ContentTypesRepository extends BaseJoomlaRepository implements IContentTypesRepository {
    /**
     * This obtains an array of ContentTypes from the db and returns it.
     * @return \ContentType
     */
    public function GetAll() {
        $db = $this->db;
        $query = $db->getQuery(true);
        $query->select('*')->from($db->quoteName('#__CBUContentTypes'));
        $db->setQuery($query);
        $results = $db->loadObjectList();
        $types = [];
        foreach($results as $ct){
            $types[$ct->Id] = new ContentType($ct->TypeName, $ct->Id); 
        }
        return $types;
    }
}
