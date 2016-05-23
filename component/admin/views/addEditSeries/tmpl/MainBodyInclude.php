<?php
/* @var $this PBAcademyViewAddEditSeries */
$ThisSeries = $this->Series;
?>
<script>
    PB.editSeries.PostUrl = '<?php echo AdminUrlMaker::AjaxPostSeries();?>';
    PB.AddNewLink = '<?php echo AdminUrlMaker::AddEditSeries(); ?>';
</script>
<div id="formContainer">
    <form id="editSeriesForm">
        <input type="hidden" id="token" name="<?php echo JSession::getFormToken(); ?>" value="1">
        <input type="hidden" id="seriesId" value="<?php echo(($ThisSeries) ? $ThisSeries->Id : ''); ?>">
        <fieldset id="basicInfo">
            <legend>Series Information:</legend> 
                <label for="title">Series Name:</label>
                <input 
                    id="SeriesName" 
                    type="text" 
                    maxlength="250" 
                    value="<?php echo(($ThisSeries) ? trim(htmlspecialchars($ThisSeries->SeriesName,ENT_QUOTES)) : ''); ?>" 
                    required>
                <label for="description">Description:</label>
                <textarea id="Description" rows="5" cols="50" maxlength="250" required><?php echo(($ThisSeries) ? trim(htmlspecialchars($ThisSeries->Description,ENT_QUOTES)) : '');?></textarea>
                <div class="imagePreview"></div>
                <label for="imagePath">Path to Image:</label>
                <ul>
                    <li>Use image with aspect ratio of 16:9 (preferably 600px x 337px).</li>
                    <li>Place the image in /images/pbacademy/series/.</li>
                </ul>
                <input
                    type="text" 
                    id="imagePath" 
                    value="<?php echo(($ThisSeries) ? $ThisSeries->ImagePath : ''); ?>" 
                    required>
        </fieldset>
        <input type="submit" value="Save Series">
    </form>
    <?php include __DIR__ . '/../../sharedViewResources/tmpl/EditFeedbackDivs.php'; ?>
</div>
