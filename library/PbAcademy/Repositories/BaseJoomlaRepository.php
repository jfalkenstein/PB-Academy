<?php

/**
 * Description of BaseRepository
 *
 * @author jfalkenstein
 */
class BaseJoomlaRepository {
    protected $db;
    public function __construct() {
        $this->db = JFactory::getDbo();
    }
}
