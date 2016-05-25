<?php 
/* @var $this PbAcademyViewAddEditLesson */ 
$ThisLesson = $this->Lesson;
$ContentTypes = $this->ContentTypes;
$AllCategories = $this->AllCategories;
$AllSeries = $this->AllSeries;
?>

<script>
    //Load relevant variables into the PB namespace.
    PB.editLesson.ThisLesson = <?php echo json_encode($ThisLesson); ?>;
    PB.editLesson.AllSeries = <?php echo json_encode($AllSeries); ?>;
    PB.editLesson.PostUrl = '<?php echo AdminUrlMaker::AjaxPostLesson()?>';
    PB.editLesson.Content = '<?php echo(($ThisLesson) ? addslashes(str_replace(["\r", "\n"],'',$ThisLesson->Content)) : ''); ?>';
    PB.editLesson.PreviewLink = '<?php echo AdminUrlMaker::AjaxPreviewLesson()?>';
    PB.AddNewLink = '<?php echo AdminUrlMaker::AddEditLesson(); ?>';
</script>
<div id="formContainer">
    <form id="editLessonForm">
        <input type="hidden" id="token" name="<?php echo JSession::getFormToken(); ?>" value="1">
        <input type="hidden" id="lessonId" value="<?php echo(($ThisLesson) ? $ThisLesson->Id : ''); ?>">
        <fieldset id="basicInfo">
            <legend>Basic Information:</legend>
            <label class="switch">
                <span id="switchLabel" class="unPublished">Not Published</span>
                <input 
                    type="checkbox" 
                    id="publish" 
                    <?php 
                    if($ThisLesson){
                        if($ThisLesson->Published){
                            echo ' checked';
                        }
                    }?>>
                <div class="slider"></div>
            </label>
            <label for="title">Title:</label>
            <input 
                id="Title" 
                type="text" 
                maxlength="250" 
                value="<?php echo(($ThisLesson) ? $ThisLesson->Title : ''); ?>" 
                required>
            <label for="description">Description:</label>
            <textarea 
                id="Description" 
                rows="5" cols="50" 
                maxlength="250" 
                required><?php echo(($ThisLesson) ? trim(htmlspecialchars($ThisLesson->Description,ENT_QUOTES)) : '');?></textarea>
            <label for="credit">Source Credit (optional):</label>
            <input 
                type="text" 
                id="SourceCredit" 
                maxlength="250" 
                value="<?php echo(($ThisLesson) ? htmlspecialchars($ThisLesson->SourceCredit, ENT_QUOTES) : ''); ?>">
            <div class="imagePreview"></div>
            <label for="imagePath">Path to Image:</label>
            <ul>
                <li>Use image with aspect ratio of 16:9 (preferably 600px x 337px).</li>
                <li>Place the image in /images/pbacademy/lessons/.</li>
            </ul>
            <input 
                type="text" 
                id="imagePath" 
                name="imagePath" 
                value="<?php echo(($ThisLesson) ? $ThisLesson->ImagePath : ''); ?>"
                required>
        </fieldset>
        <fieldset>

        </fieldset>
        <fieldset>
            <legend>Lesson Content</legend>
            <div id="contentPreview"></div>
            <label for="contentTypeId">Select the type of content you desire:</label>
            <select id="contentTypeDropDown" name="contentTypeId" required>
                <option value="<?php echo((isset($ThisLesson)) ? '' : 'selected'); ?>">Select a Content Type</option>
                <?php foreach($ContentTypes as $type) :
                    /* @var $type ContentType */?>
                <option 
                    value ="<?php echo $type->Id; ?>"
                    <?php 
                    if($ThisLesson){
                        if($ThisLesson->ContentType()->Id === $type->Id){
                            echo 'selected';
                        }
                    }?>
                    >
                        <?php echo $type->Name; ?>
                </option>
                <?php endforeach; ?>
            </select>
            <button class="btn btn-default" id="previewButton">Preview</button>
            <div class="contentForm" id="content-1">
                <!--BrainShark Embed-->
                <label for="content">Please copy below the BrainShark address.
                    <br/> 
                    Example: <span class="example">https://www.brainshark.com/pbusa/retirement_transition</span>
                    <br/>
                </label>
                <input type="text" name="content" value="">
            </div>

            <div class="contentForm" id="content-2">
                <!--Generic Video Embed-->
                <label for="content">Please copy below the embed code for the video you desire.
                    <br/> 
                </label>
                <textarea rows="5" cols="4" name="content"></textarea>
            </div>

            <div class="contentForm" id="content-3">
                <!--Download Link Embed-->
                <label for="content">Please enter below the link to the file you desire to make available for download.
                    <br/> 
                    Example: <span class="example">/resources/news/mydocument.pdf</span>
                    <br/>
                </label>
                <input type="text" name="content" value="">
            </div>

            <div class="contentForm" id="content-4">
                <!--Youtube Link Embed-->
                <label for="content">Please enter below the full YouTube video url.
                    <br/> 
                    Example: <span class="example">https://www.youtube.com/watch?v=z_ZuDHD2FHo</span>
                    <br/>
                </label>
                <input type="text" name="content" value="">
            </div>

            <div class="contentForm" id="content-6">
                <!--Naz Media Library Link Embed-->
                <label for="content">Please enter below the "permalink" from the Nazarene Media Library video you wish to post.
                    <br/> 
                    Example: <span class="example">http://medialibrary.nazarene.org/media/nyc-2015-kingdom</span>
                    <br/>
                </label>
                <input type="text" name="content" value="">
            </div>
            <div class="contentForm" id="content-7">
                <!--External Url Embedded in Iframe-->
                <label for="content">Please enter below the "permalink" from the Nazarene Media Library video you wish to post.
                    <br/> 
                    Example: <span class="example">http://www.nazarene.org</span>
                    <br/>
                </label>
                <input type="text" name="content" value="">
            </div>

            <div class="contentForm" id="content-5">
                <!--Raw html Embed-->
                <label for="content">Use the editor below to add the html content you desire.</label>
                <?php
                $editor = JFactory::getEditor();
                echo $editor->display(
                        'content', //editor control name
                        '', //Content of the field
                        '400', //width of the editor
                        '400', //height of the editor
                        '20', //# of columns in the text area
                        '20', //# of rows in the text area
                        false, //Whether or not to show the usual buttons.
                        'htmlContentBox' //The textarea id
                        ); 
                ?>
            </div>
        </fieldset>
        <fieldset>
            <legend>School (Required):</legend>
            <label for="category">Select the school this lesson belongs to.</label>
            <select name="category" id="categoryId" required>
                <?php foreach($AllCategories as $cat) : 
                /* @var $cat Category */ ?>
                <option 
                    value="<?php echo $cat->Id ?>" 
                    <?php 
                    if($ThisLesson):
                    if($cat->Id == $ThisLesson->Category->Id): ?>
                    selected
                    <?php 
                    endif;
                    endif; ?>>
                        <?php echo $cat->Name;?>
                </option>
                <?php endforeach; ?>
            </select>
        </fieldset>
        <fieldset>
            <legend>Series (Optional):</legend>
            <label for="series">Select the series (if any) that this belongs to.</label>
            <select id="seriesDrop" name="series">
                <option value="" 
                    <?php 
                    if($ThisLesson){
                        if(is_null($ThisLesson->Series)){
                            echo 'selected';
                        }
                    }else{
                        echo 'selected';
                    }  
                    ?>
                >
                    No Series
                </option>
                <?php foreach($AllSeries as $ser) : 
                /* @var $ser LessonSeries */?>
                <option
                    value="<?php echo $ser->Id; ?>"
                    <?php 
                    if($ThisLesson):
                    if($ser->Id == $ThisLesson->Series->Id) : ?>
                    selected 
                    <?php 
                    endif;
                    endif;?>
                >
                        <?php echo $ser->SeriesName; ?>
                </option>
                <?php endforeach;?>
            </select>
            <div id="SeriesOrder">
                <label for="position">Select the position in the series.</label>
                <select id="seriesPosition" name='position'></select>
            </div>
        </fieldset>
        <input type="submit" value="Save Lesson">
    </form>
    <?php include __DIR__ . '/../../sharedViewResources/tmpl/EditFeedbackDivs.php'; ?>
</div>



