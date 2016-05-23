<?php 
/* To use this include, you must implement IAllCategoriesView in the view.*/
/* @var $this IAllCategoriesView */

if(is_a($this, 'IAllCategoriesView')){
    $class = ($this->ShowDescriptions()) ? 'catThumbBig' : 'catThumb'; 

    foreach($this->Categories as $cat) :
        /* @var $cat Category */
?> 
        <div class="<?php echo $class ?>">
            <div class="shadowbox">
                <a href="<?php echo $cat->GetLink(); ?>">
                    <img src="<?php echo $cat->ImagePath; ?>">
                </a>
            </div>
            <div class="catFooter">
                <a href="<?php echo $cat->GetLink();?>">
                    <span class="detLabel">
                        <?php echo $cat->Name; ?>
                    </span>
                </a>
                <?php if($this->ShowDescriptions()) : ?>
                <div class ="catThumbDescription">
                    <?php 
                        echo substr($cat->Description, 0, 100);
                        if(strlen($cat->Description) > 100) echo '...';
                        echo ' (' . $cat->LessonCount() . ')';
                    ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach;?>
<?php }?>