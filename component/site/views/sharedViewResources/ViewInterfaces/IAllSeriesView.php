<?php

/**
 * This interface allows the AllSeries view to be used.
 * @author jfalkenstein
 */
interface IAllSeriesView{
    const All_SERIES_INCLUDE = '/../../sharedViewResources/tmpl/AllSeries.php';
    
    public function ShowDescriptions();
}
