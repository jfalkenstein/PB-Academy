<?php

/**
 * This interface enables all the all-categories template to be used.
 * @author jfalkenstein
 */
interface IAllCategoriesView{
    const All_CATEGORIES_INCLUDE = '/../../sharedViewResources/tmpl/AllCategories.php';
    
    public function ShowDescriptions();
}
