<?php

/**
 * Description of BaseRepository
 * This is the base Joomla Repository. So long as we use Joomla for a framework,
 * This provides the access to the DB for all derived repositories.
 * @author jfalkenstein
 */
class BaseJoomlaRepository {
    protected $db;
    public function __construct() {
        $this->db = JFactory::getDbo();
    }
}
