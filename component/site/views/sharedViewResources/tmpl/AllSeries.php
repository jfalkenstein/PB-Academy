<?php 
/* To use this include, you must implement IAllSeriesView in the view.*/
/* @var $this BaseViewMaster */

if(is_a($this, 'IAllSeriesView')){
    $class = ($this->ShowDescriptions()) ? 'serBigThumb' : 'serThumb'; 

    foreach($this->AllSeries as $ser) :
        /* @var $ser LessonSeries */
?> 
        <div class="<?php echo $class ?>">
            <div class="shadowbox">
                <a href="<?php echo $ser->GetLink(); ?>">
                    <img src="<?php echo $ser->ImagePath; ?>">
                </a>
            </div>
            <div class="serFooter">
                <a href="<?php echo $ser->GetLink();?>">
                    <span class="detLabel">
                        <?php echo $ser->SeriesName; ?>
                    </span>
                </a>
                <?php if($this->ShowDescriptions()) : ?>
                <div class ="serThumbDescription">
                    <?php 
                        echo substr($ser->Description, 0, 100);
                        if(strlen($ser->Description) > 100) echo '...';
                        echo ' (' . $ser->LessonCount() . ')';
                    ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach;?>
<?php }?>