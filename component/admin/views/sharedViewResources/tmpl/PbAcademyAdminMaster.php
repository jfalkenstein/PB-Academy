<?php 
/* @var $this BaseViewMaster */ 
$doc = JFactory::getDocument();
$doc->addScript('/administrator/components/com_pbacademy/js/pbacademyadmin.js');
$doc->addScript('/administrator/components/com_pbacademy/js/list.min.js');
$doc->addScript('/administrator/components/com_pbacademy/js/list.pagination.min.js');
$doc->addStyleSheet('/administrator/components/com_pbacademy/css/pbacademyadmin.css');
$doc->addStyleSheet('/media/jui/css/icomoon.css');

if(!is_a($this,'PbAcademyViewAdminHome')) :
?>

<div class="adminMenu">
    <ul>
        <li class="btn btn-default"><a href="<?php echo AdminUrlMaker::ManageHome()?>">Back to Manage Items</a></li>
    </ul>
</div>
<?php endif; ?>
<div class="mainBody">
    <?php include $this->MainBodyInclude; ?>
</div>

