<?php

/**
 * These are the required repository functions.
 * @author jfalkenstein
 */
interface IRepository {
    public function Save($item, $update = FALSE);
    public function Delete($id);
}
