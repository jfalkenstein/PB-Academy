<?php /* @var $this PbAcademyViewAdminHome */ ?>
<script>
    /* This is the section where PHP passes values to the JavaScript. */
    PB.adminHome.Token = "<?php echo JSession::getFormToken(); ?>";
    PB.adminHome.ajaxLinks.lessons = "<?php echo AdminUrlMaker::AjaxAllLessons()?>";
    PB.adminHome.ajaxLinks.series = "<?php echo AdminUrlMaker::AJaxAllSeries() ?>";
    PB.adminHome.ajaxLinks.schools = "<?php echo AdminUrlMaker::AJaxAllCategories() ?>";
</script>
<div id="topMenu">
    <button class="menuBarButton btn btn-default" id="manageLessons" onclick="PB.adminHome.tabToggle('manageLessons')">Manage Lessons</button>
    <button class="menuBarButton btn btn-default" id="manageSeries" onclick="PB.adminHome.tabToggle('manageSeries')">Manage Series</button>
    <button class="menuBarButton btn btn-default" id="manageSchools" onclick="PB.adminHome.tabToggle('manageSchools')">Manage Schools</button>
</div>
<!--Manage Lessons-->
<div class="manageTab" id="lessonsDiv">
<?php 
$table = $this->WhichTableEnum["Lessons"];
$fieldNames = ['title', 'date','school','series'];
$this->getManageSection(AdminUrlMaker::AddEditLesson(), $table, $fieldNames);
?>
</div>
<!--Manage Lesson Series-->
<div class="manageTab" id="seriesDiv">
<?php 
$table = $this->WhichTableEnum["Series"];
$fieldNames = ['name', 'lessons'];
$this->getManageSection(AdminUrlMaker::AddEditSeries(), $table, $fieldNames);
?>
</div>
<!--Manage Schools-->
<div class="manageTab" id="schoolsDiv">
<?php 
$table = $this->WhichTableEnum["Schools"];
$fieldNames = ['name', 'lessons'];
$this->getManageSection(AdminUrlMaker::AddEditCategory(), $table, $fieldNames);
?>
</div>
