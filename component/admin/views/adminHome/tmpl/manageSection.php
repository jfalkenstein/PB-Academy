<?php 
/*
 * This section is included through the getManageSection method within the adminHome view.
 */
?>
<a 
    class="addButton btn btn-default" 
    href="<?php echo $addEditLink ?>">
        <span class="icon-plus"></span>
        Add New
</a>
<button 
    class="loadLink btn btn-default" 
    onclick="PB.adminHome.fillTable(<?php echo $whichTableEnumValue ?>)">
    <span class="icon-list"></span>View All
</button>
<button 
    class="refreshLink btn btn-default" 
    onclick="PB.adminHome.refreshTable(<?php echo $whichTableEnumValue ?>)">
    <span class="icon-list"></span>Refresh All
</button>
<input type="text" class="search form-control" placeholder="Search" />
<br/>
<?php foreach($sortFieldNames as $field) : ?>
<button class="sort btn btn-default" data-sort="<?php echo strtolower($field)?>">Sort by <?php echo $field ?></button>
<?php endforeach; ?>
<ul class="pagination paginationTop"></ul>
<div class="tablePlace"></div>
<ul class="pagination paginationBottom"></ul>