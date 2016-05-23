
/**
 * PB NAMESPACE:
 * This is designed using the Revealing Module pattern, making use of
 * Immediately Invoked Function Expresions (IIFEs), creating an externally
 * accessible interface and inaccessible private and internal spaces.
 * 
 * There are internal namespaces (only accessible from within the PB namespace 
 *   and inaccessible outside of it).
 *   
 * There are also public namespaces (accessible from outside the PB namespace).
 * 
 * Within each namespace, certain elements are revealed for external access within
 * the return bock. The rest of the code is only called from within the individual
 * namespace.
 * 
 */
var PB = (function(){
    "use strict";
    
    /**
     * INTERNAL NAMESPACE: ajax
     *  This is the code supporting ajax functionality. 
     */
    var ajax = (function(){
        //Private members
        function successCB(data,textStatus,jqXHR){
            ajax.hideLoadingImage('formContainer');
            if(data.success === true){
                editorResources.showSuccessfulMessage();
            }else{
                editorResources.showErrorMessage();
            }
        };
        function errorCB(jqXHR, textStatus, errorThrown){
            ajax.hideLoadingImage('formContainer');
            editorResources.showErrorMessage('Error: ' + errorThrown);
        };
        //Internal members:
        return {      
            loadJSON: function(path,success,error){
                jQuery.ajax({
                    url: path,
                    async: true,
                    dataType: 'json',
                    success: success,
                    error: error
                    });
            },
            sendForm: function(url, dataObject, success, error){
                jQuery.ajax({
                    url: url,
                    dataType: 'json',
                    data: dataObject,
                    async: true,
                    success: (success) ? success : successCB,
                    error: (error) ? error : errorCB,
                    type: 'POST'
                });
            },
            displayLoadingImage: function(divId){
                var div = document.getElementById(divId);
                var img = document.createElement('img');
                img.src = '/administrator/components/com_pbacademy/images/loading.gif';
                img.id = 'loading';
                img.style.width = '50px';
                div.appendChild(img);
            },
            hideLoadingImage: function(){
                jQuery('#loading').remove();
            }
        };
    }());
    
    /**
     * INTERNAL NAMESPACE: lightBoxMaker
     *  This is the code supporting the modal lightbox functionality. It is only called from within
     *  the PB namespace and in inaccessible outside of it.
     */
    var lightBoxMaker = (function(){
        //Private members
        
        /**
         * Produces the base light box.
         * @returns {jQuery}
         */
        function makeLightBox(){
            var box = jQuery('<div id="lightBox"></div>').addClass("white_content");
            return box;
        };
        /**
         * This will display the light box passed in.
         * @param {jQuery} box
         * @returns {undefined}
         */
        function showLightBox(box){
            var black = jQuery('<div></div>').addClass("black_overlay");
            jQuery('body').append(black);
            jQuery('body').append(box);
        };
        /**
         * This will make a lightbox prompt.
         * @param {jQuery} promptBoxContent a jQuery object/collection to be used as the main body of the prompt.
         * @param {[]ButtonDef} buttonDefs The buttons to include
         * @param {string} promptTitle
         * @returns {undefined}
         */
        function makePromptBox(promptBoxContent, buttonDefs, promptTitle){
            var box = makeLightBox();
            if(promptTitle){ 
                var title = jQuery('<h3>' + promptTitle + '</h3>').addClass("lightBoxTitle");
                box.append(title);
            }
            var content = jQuery('<div id="lightBoxContent"></div>');
            content.append(promptBoxContent);
            box.append(content);
            var buttonsDiv = jQuery('<div id="lightBoxButtonsDiv"></div>');
            buttonsDiv = addButtons(buttonDefs, buttonsDiv);
            box.append(buttonsDiv);
            showLightBox(box);
        };
        function closePromptBox(){
            jQuery('.black_overlay, .white_content').remove();        
        };
        function updatePromptBox(buttonDefs, text){
            jQuery('#lightBoxContent').text(text);
            jQuery('.lightBoxButton').remove();
            addButtons(buttonDefs);
        };
        /**
         * This receives the button definitions and optionally the buttonsDiv and adds the buttons to them.
         * @param {type} buttonDefs
         * @param {type} buttonsDiv
         * @returns {window.jQuery|jQuery}
         */
        function addButtons(buttonDefs, buttonsDiv){
            if(!buttonsDiv){
                buttonsDiv = jQuery('#lightBoxButtonsDiv');
            }
            if(buttonDefs.constructor === Array){
                for(var key in buttonDefs){
                    /**
                     * @type buttonDef|ButtonDef
                     */
                    var buttonDef = buttonDefs[key];
                    var button = jQuery('<div id="'+ buttonDef.ButtonId + '">' 
                                        + buttonDef.ButtonText
                                        + '</div>')
                                        .addClass("btn btn-default lightBoxButton");
                    button.click(buttonDef.ButtonData, buttonDef.ButtonAction);
                    buttonsDiv.append(button);
                }
            }
            return buttonsDiv;
        };
        /**
         * Makes a simple OK prompt box with a singular "Ok" button.
         * @param {jQuery} message area content
         * @returns {undefined}
         */
        function makeOkBox(message){
            var buttons = [
                new ButtonDef('ok', 'Ok', function(){
                    closePromptBox();
                })
            ];
            if(jQuery('#lightBox').length > 0){
                updatePromptBox(buttons, message);
            }else{
                makePromptBox(message,buttons,"");
            }
        };
        
        //internally exposed methods
        return{
            makeLightBox: makeLightBox,
            makePromptBox: makePromptBox,
            closePromptBox: closePromptBox,
            showLightBox: showLightBox,
            makeOkBox: makeOkBox
        };
    }());
    
    //Internally accessible function definition, but not exposed beyond the PB namespace.
    function ButtonDef(buttonId, buttonText,buttonAction, buttonData){
        this.ButtonId = buttonId;    
        this.ButtonText = buttonText;
        this.ButtonAction = buttonAction;
        this.ButtonData = buttonData;
    }

    /**
     * INTERNAL NAMESPACE: selectionTool
     * This is the code supporting the select to delete functionality. 
     */
    var selectionTool = (function(){
        function addDeleteCheckBoxes(jqListBody, whichTableEnum){
            var rows = jqListBody.find('tr');
            rows.each(function(index, el){
                var row = jQuery(el);
                var td = jQuery('<td></td>');
                var box = jQuery('<input type="checkbox" class="selectCheck">');
                if(!row.data('deleteBlocked')){
                    box.change(function(e){
                        var row = jQuery(e.delegateTarget).parents('tr');
                        var tableRef = whichTableEnum;
                        var itemRef = row.data('id'); 
                        if(e.delegateTarget.checked){
                            PB.adminHome.SelectedItems.push({
                                Table: tableRef,
                                IdToDelete: itemRef,
                                Name: row.data('name'),
                                DeleteLink: row.data('deleteLink')
                            });
                        }else{
                            var items = PB.adminHome.SelectedItems;
                            var newArray = [];
                            var count = items.length;
                            for(var i = 0; i < count; i++){
                                if(!(items[i].Table === tableRef && items[i].IdToDelete === itemRef)){
                                    newArray.push(items[i]);
                                }
                            }
                            PB.adminHome.SelectedItems = newArray;
                        }
                        evaluateSelections();
                    });
                    td.append(box);                
                    row.append(td);
                };
            });
        };

        function evaluateSelections(){
            var name = "start", i = 0, sections = [];
            while (name){
                var divName = adminHome.getSectionDivName(i);
                if(divName){
                    sections[i] = [];
                }
                i++;
                name = divName;
            }

            for(var i in PB.adminHome.SelectedItems){
                var item = PB.adminHome.SelectedItems[i];
                sections[item.Table].push(item.IdToDelete);
            }
            for(var i in sections){
                if(sections[i].length > 0){
                    showDeleteSelectedButton(i);
                }else{
                    hideDeleteSelectedButton(i);
                }
            }
        };

        function showDeleteSelectedButton(whichTableEnum){
            var divName = adminHome.getSectionDivName(whichTableEnum);
            if(jQuery('#' + divName + ' .deleteSelected').length === 0){
                var btn = jQuery('<button class="btn btn-default deleteSelected">Delete Selected</button>');
                btn.click(function(){
                    showDeleteSelectionsLightBox(whichTableEnum);
                });
                jQuery('#' + divName + ' .tablePlace').before(btn);
            } 
        };

        function hideDeleteSelectedButton(whichTableEnum){
            var divName = adminHome.getSectionDivName(whichTableEnum);
            jQuery('#' + divName + ' .deleteSelected').remove();
        };

        function showDeleteSelectionsLightBox(whichTableEnum){
            var contentDiv = jQuery('<div></div>');
            var promptText = jQuery('<p>Are you sure you want to delete these items? This cannot be undone.</p>');
            contentDiv.append(promptText);
            var selectedItems = jQuery.grep(adminHome.SelectedItems, function(o){
                return (parseInt(o.Table) === parseInt(whichTableEnum));
            });
            var list = jQuery('<ul></ul>');
            for(var i in selectedItems){
                list.append('<li>' + selectedItems[i].Name + ' (#' + selectedItems[i].IdToDelete + ')</li>');
            }
            contentDiv.append(list);
            var yesDeleteBtn = new ButtonDef(
                "yesDelete", 
                "Yes, delete them all.", 
                function(e){
                    var tableEnum = e.data;
                    deleteAllSelected(tableEnum);
                },
                whichTableEnum
            );
            var noBtn = new ButtonDef("noDelete", "Never mind. Don't delete them.", function (){
                lightBoxMaker.closePromptBox();
                selectNone();
            });
            var btnArray = [yesDeleteBtn, noBtn];
            lightBoxMaker.makePromptBox(contentDiv, btnArray, "Delete all selected items?");
        };

        function deleteAllSelected(whichTableEnum){
            var selectedItems = jQuery.grep(adminHome.SelectedItems, function(o){
                return (parseInt(o.Table) === parseInt(whichTableEnum));
            });
            PB.adminHome.ReturnedCalls = 0;
            PB.adminHome.CallGoal = selectedItems.length;
            ajax.displayLoadingImage('lightBox');
            for(var i in selectedItems){
                adminHome.deleteItem(
                    selectedItems[i].DeleteLink, 
                    selectedItems[i].IdToDelete, 
                    function(data, textStatus, jqXHR){
                        ajax.hideLoadingImage();
                        if(!data.success){
                            PB.adminHome.Error = true;
                        }
                        PB.adminHome.ReturnedCalls++;
                        finishAjaxBlast(whichTableEnum);
                    },
                    function(jqXHR, textStatus, errorThrown){
                        PB.adminHome.Error = true;
                        PB.adminHome.ReturnedCalls++;
                        finishAjaxBlast(whichTableEnum);
                    }
                );
            }
        };

        function finishAjaxBlast(whichTableEnum){
            if(PB.adminHome.ReturnedCalls === PB.adminHome.CallGoal){
                ajax.hideLoadingImage();
                if(PB.adminHome.Error){
                    lightBoxMaker.makeOkBox("There was an error with the deletion. Not all may have been deleted.");
                }else{
                    lightBoxMaker.makeOkBox("All items have been deleted.");
                }
                selectNone();
                PB.adminHome.refreshTable(whichTableEnum);
            }
        }
        
        function selectNone(){
            jQuery('.selectCheck').prop('checked',false);
            PB.adminHome.SelectedItems = [];
        };
        return {
            addDeleteCheckBoxes: addDeleteCheckBoxes,
            selectNone: selectNone,
            evaluateSelections: evaluateSelections
        };
    }());
    
    //See description in the return block.
    var adminHome = (function(){
        //PRIVATE MEMBERS

        /**
        * Based upon the enum, it will return the section name.
        * @param {int} WhichTableEnum
        * @returns {String}
        */
        function getSectionDivName(whichTableEnum){
            whichTableEnum = parseInt(whichTableEnum);
            switch(whichTableEnum){
                case WhichTableEnum.Lessons:
                   return "lessonsDiv";
                case WhichTableEnum.Schools:
                   return "schoolsDiv";
                case WhichTableEnum.Series:
                   return "seriesDiv";
                case WhichTableEnum.Modal:
                   return "lightBox";
           }
       };

        /**
        * This will receive the pseudo-enum and, based upon the table,
        * call ajax.loadJSON with the applicable values.
        * @param {int} WhichTableEnum
        */
        function getTableContent(whichTableEnum){
            whichTableEnum = parseInt(whichTableEnum);
            var link;
            var getAction;
            var failAction;
            switch(whichTableEnum){
                case WhichTableEnum.Lessons:
                    link = ajaxLinks.lessons;
                    getAction = makeLessonsTable;
                    break;
                case WhichTableEnum.Schools:
                    link = ajaxLinks.schools;
                    getAction = makeSchoolsTable;
                    break;
                case WhichTableEnum.Series:
                    link = ajaxLinks.series;
                    getAction = makeSeriesTable;
                    break;
           };
           ajax.loadJSON(link, getAction, failAction);
       };
        
        /**
        * Submits the delete ajax request.
        * @param {string} link The the ajax request is posted to.
        * @param {int} id The id sent in the delete post request.
        * @param {function} successAction The callback to be performed on success.
        * @param {function} ErrorAction The callback to be performed on error.
        */
        function deleteItem(link, id, successAction, ErrorAction){
           var request = makeDeleteRequest(id);
           ajax.sendForm(
                   link, 
                   request,
                   successAction,
                   ErrorAction);
        };

        /**
        * Prepares the delete request to be posted for the delete ajax call.
        * @param {int} id The Id to be deleted.
        * @returns {adminHome.makeDeleteRequest.request}
        */
        function makeDeleteRequest(id){
            var request = {};
            var token = PB.adminHome.Token;
            request['IdToDelete'] = id;
            request[token] = "1";
            return request;
        }; 

        /**
        * This is a CallBack sent with the AJAX pull request for lessons.
        * @param {[]Lesson} lessons
        */
        function makeLessonsTable(lessons){
            var e = WhichTableEnum.Lessons;
            var headings = ["Lesson Title", "Published" ,"Id", "Date Published", "School Name", "Series Name", "Edit", "Delete"];
            makeTable(lessons, e, headings);
            var valuesArray = ['title','id', 'date','school','series'];
            makeList(valuesArray, e);
            ajax.hideLoadingImage();
        };

        /**
        * This is a CallBack sent with the AJAX pull request for lessons. Instead of
        * ultimately appending the table to an existing div, it returns the table to be 
        * populated within a modal window.
        * @param {type} lessons
        */
        function makePopupLessonsTable(lessons){
            var headings = ["Lesson Title", "Id", "Date Published", "School Name", "Series Name", "Edit", "Delete"];
            var table = makeTableBase(headings).addClass('tablePlace');
            var listBody = table.find('.list');
            listBody = appendLessons(listBody, lessons, true);
            selectionTool.addDeleteCheckBoxes(listBody, WhichTableEnum.Modal);
            var valuesArray = ['title','id', 'date','school','series'];
            showPopupLessonsTable(table);
            makeList(valuesArray, WhichTableEnum.Modal);
            ajax.hideLoadingImage();
        };
        
        /**
        * This will display a table in a new window.
        * @param {jQuery} table jQuery object
        * @returns {undefined}
        */
        function showPopupLessonsTable(table){
            var box = lightBoxMaker.makeLightBox();
            var closeBtn = jQuery('<div class="closeBtn">X</div>').click(function(){
                lightBoxMaker.closePromptBox();
            });
            box.append(closeBtn);
            box.append('<input type="text" class="search form-control" placeholder="Search Lessons" /><br>');
            var lessonsDiv = getSectionDivName(PB.adminHome.WhichTableEnum.Lessons);
            var sortButtons = jQuery('#' + lessonsDiv + ' .sort').clone();
            box.append(sortButtons);
            box.append('<ul class="pagination paginationTop"></ul>');
            box.append(table);
            box.append('<ul class="pagination paginationBottom"></ul>');
            lightBoxMaker.showLightBox(box);
            jQuery('.tempHide').show();
            jQuery('#loadMe').remove();
        };

        /**
        * This is a CallBack sent with the AJAX pull request for series.
        * @param {[]Lesson} lessons
        */
        function makeSeriesTable(series){
           var e = WhichTableEnum.Series;
           var headings = ["Series Name", "Id", "# Lessons","View Lessons", "Edit", "Delete"];
           makeTable(series, e, headings);
           var valuesArray = ['name','lessons'];
           makeList(valuesArray, e);
        };

        /**
         * This is a CallBack sent with the AJAX pull request for schools.
         * @param {[]Lesson} lessons
         */
        function makeSchoolsTable(schools){
            var e = WhichTableEnum.Schools;
            var headings = ["School Name", "Id", "# Lessons","View Lessons", "Edit", "Delete"];
            makeTable(schools, e, headings);
            var valuesArray = ['name','lessons'];
            makeList(valuesArray, e);
        };

        /**
         * This will create the table for display, switching on WhichTableEnum.
         * @param {[]object} data
         * @param {int} WhichTableEnum
         * @param {[]string} headingsArray An array of headings to be used for the table.
         */
        function makeTable(data, whichTableEnum, headingsArray){
            var div = jQuery('#' + getSectionDivName(whichTableEnum)).find('.tablePlace');
            var table = makeTableBase(headingsArray);
            var listBody = table.find('.list');
            listBody = appendData(whichTableEnum,data,listBody);
            jQuery(listBody).find('tr').data('table', whichTableEnum);
            selectionTool.addDeleteCheckBoxes(listBody, whichTableEnum);
            ajax.hideLoadingImage();
            div.append(table);        
        };

        /**
         * This creates the basic structure of the table, with the header row
         * populated by the passed-in array. It will then return that table.
         * @param {[]string} rowHeadingsArray
         * @returns {jQuery}
         */
        function makeTableBase(rowHeadingsArray){
            var table = jQuery('<table></table>').addClass('listTable');
            var tHead = jQuery('<thead></thead>');
            var tBody = jQuery('<tbody></tbody>').addClass('list');
            var headRow = jQuery('<tr></tr>').addClass('listTableHead');
            for(var i in rowHeadingsArray){
                headRow.append('<th>' + rowHeadingsArray[i] + '</th>');   
            }
            tHead.append(headRow);
            table.append(tHead);
            table.append(tBody);
            return table;
        };

        /**
         * This will switch on whichever table is being populated and then call
         * the appropriate append method. It acts upon the listBody and then returns
         * it.
         * @param {int} whichTableEnum
         * @param {[]object} data
         * @param {jQuery} listBody
         * @returns {jQuery}
         */
        function appendData(whichTableEnum, data, listBody){
            switch(whichTableEnum){
                case WhichTableEnum.Lessons:
                    return appendLessons(listBody, data);
                case WhichTableEnum.Schools:
                    return appendSchools(listBody,data);
                case WhichTableEnum.Series:
                    return appendSeries(listBody,data);
            }
        };
        
        /**
         * This will append rows for each lesson to the passed in listBody,
         * then return that listBody.
         * @param {jQuery} listBody
         * @param {[]Lesson} lessons
         * @param {bool} modal specifies whether or not this parameter is a modal window.
         * @returns {jQuery}
         */
        function appendLessons(listBody, lessons, modal){
            var modal = typeof modal !== 'undefined' ? modal : false;
            var table = modal ? WhichTableEnum.Modal : WhichTableEnum.Lessons;
            for(var key in lessons){
                var lesson = lessons[key];
                var publishedIcon = (lesson.Published) ? '<span class="icon-publish"></span>Yes' : '<span class="icon-unpublish"></span>No';
                var cells = [];
                var row = jQuery('<tr></tr>')
                        .addClass('listRow')
                        .data('id', lesson.Id)
                        .data('name', lesson.Title)
                        .data('deleteLink',lesson.DeleteLink);
                cells[0] = jQuery('<td>' + lesson.Title + '</td>').addClass("title");
                cells[1] = jQuery('<td>' + publishedIcon + '</td>').addClass('published');
                cells[2] = jQuery('<td>' + lesson.Id + '</td>').addClass("id");
                cells[3] = jQuery('<td>' + lesson.Date + '</td>').addClass("date");
                cells[4] = jQuery('<td>' + lesson.CategoryName + '</td>').addClass("school");
                cells[5] = jQuery('<td>' 
                        + lesson.SeriesName 
                        + ((lesson.SeriesName !== "") ? ' (#' + lesson.TruePosition + ')' : "") 
                        + '</td>').addClass("series");
                cells[6] = makeEditTd(lesson.EditLink, modal);
                cells[7] = makeDeleteTd();
                addDeleteClickEvent(
                    cells[7], 
                    lesson.DeleteLink, 
                    lesson.Id, 
                    lesson.Title, 
                    table,
                    'lesson'
                );
                for(var i in cells){
                    row.append(cells[i]);
                };
                listBody.append(row);
            };
            return listBody;
        };
        
        /**
         * This will append rows for each series to the passed in listBody,
         * then return that listBody.
         * @param {jQuery} listBody
         * @param {[]LessonSeries} allSeries
         * @returns {jQuery}
         */
        function appendSeries(listBody, allSeries){
            for(var key in allSeries){
                var series = allSeries[key];
                var cells = [];
                var row = jQuery('<tr></tr>')
                        .addClass('listRow')
                        .data('id', series.Id)
                        .data('name', series.SeriesName)
                        .data('deleteLink',series.DeleteLink);
                cells[0] = jQuery('<td>' + series.SeriesName + '</td>').addClass("name");
                cells[1] = jQuery('<td>' + series.Id + '</td>').addClass("id");
                cells[2] = jQuery('<td>' + series.LessonCount + '</td>').addClass("lessons");
                cells[3] = makeViewLessonsTd();
                cells[4] = makeEditTd(series.EditLink);
                cells[5] = makeDeleteTd();
                addDeleteClickEvent(
                    cells[5], 
                    series.DeleteLink, 
                    series.Id, 
                    series.SeriesName, 
                    WhichTableEnum.Series,
                    'series'
                );
                addViewLessonsClickEvent(
                    cells[3],
                    series.ViewLessonsLink
                );
                for(var i in cells){
                    row.append(cells[i]);
                };
                listBody.append(row);
            };
            return listBody;
        };
        
        /**
         * This will append rows for each school to the passed in listBody,
         * then return that listBody.
         * @param {jQuery} listBody
         * @param {[]Category} schools
         * @returns {jQuery}
         */
        function appendSchools(listBody, schools){

            for(var key in schools){
                var school = schools[key];
                var cells = [];
                var row = jQuery('<tr></tr>')
                        .addClass('listRow')
                        .data('id', school.Id)
                        .data('name', school.Name)
                        .data('deleteLink',school.DeleteLink);
                cells[0] = jQuery('<td>' + school.Name + '</td>').addClass("name");
                cells[1] = jQuery('<td>' + school.Id + '</td>').addClass("id");
                cells[2] = jQuery('<td>' + school.LessonCount + '</td>').addClass("lessons");
                cells[3] = makeViewLessonsTd();
                cells[4] = makeEditTd(school.EditLink);
                if(school.LessonCount > 0){
                    cells[5] = jQuery('<td> Has Lessons</td>').addClass('deletePrevented');
                    row.data('deleteBlocked', true);
                }else{
                    cells[5] = makeDeleteTd();
                    addDeleteClickEvent(
                        cells[5], 
                        school.DeleteLink, 
                        school.Id, 
                        school.Name, 
                        WhichTableEnum.Schools,
                        'school'
                    );
                }
                addViewLessonsClickEvent(
                    cells[3],
                    school.ViewLessonsLink
                );
                for(var i in cells){
                    row.append(cells[i]);
                };
                listBody.append(row);
            };
            return listBody;
        };

        /**
         * This creates the sortable, filterable list.
         * This is defined by list.js. For information on these values, 
         * see http://www.listjs.com/docs/options.
         */
        function makeList(valueNamesArray, WhichTableEnum){
            var sectionName = getSectionDivName(WhichTableEnum);
            var paginationTop = {
                name: "paginationTop",
                paginationClass: "paginationTop"
            };
            var paginationBottom = {
                name: "paginationBottom",
                paginationClass: "paginationBottom"
            };

            var options = {
                valueNames: valueNamesArray, //The sortable/filterable items.
                page: 30, //# of items per page.
                plugins: [
                    ListPagination(paginationTop), //The plugin that enables easy pagination.
                    ListPagination(paginationBottom) 
                ]
            };
            var list = new List(sectionName,options); //This initializes the list plugin.
        };
        
        /**
         * This will return a td for the passed in edit link, with edit icon.
         * @param {string} editLink
         * @param {bool} modal Specifies whether this is for a modal window.
         * @returns {jQuery}
         */
        function makeEditTd(editLink, modal){
            var target = modal ? 'target="_blank"' : '';
            return jQuery(
                '<td><a href="' + editLink +'" ' + target + '>' +
                '<span class="icon-edit"></span></a></td>'
            ).addClass("editButton");
        };
        
        /**
         * This will return a td for the passed in delete link, with delete icon.
         * @param {string} editLink
         * @returns {jQuery}
         */
        function makeDeleteTd(){
            return jQuery('<td><a href="#"><span class="icon-delete"></span></a></td>'
                          ).addClass("deleteButton");
        };
        
        function makeViewLessonsTd(){
            return jQuery('<td><a href="#"><span class="icon-list"></span></a></td>'
                          ).addClass("viewButton");
        };

        /**
         * This will add the delete click event, with pop-up warning, to the passed in jQueryObject.
         * @param {jQuery} jqueryObject The object the click even will be added to.
         * @param {string} deleteLink The delete ajax link to be called.
         * @param {int} Id The Id to be sent in the delete ajax request.
         * @param {string} The human-readable name of the item to be deleted, displayed in the
         *                  the popup window.
         * @param {int} WhichTableEnum the table to be refreshed upon successful deletion.
         * @param {string} whatToCallIt
         */
        function addDeleteClickEvent(jqueryObject, deleteLink, Id, name, WhichTableEnum, whatToCallIt){
            jqueryObject.click(function(e){
                var deleteButton = new ButtonDef('deleteBtn','Yes, delete this ' + whatToCallIt + '.',function(){
                    ajax.displayLoadingImage('lightBox');
                    deleteItem(
                        deleteLink, 
                        Id, 
                        function(data, textStatus, jqXHR){
                            ajax.hideLoadingImage();
                            if(data.success){
                                lightBoxMaker.makeOkBox('The ' + whatToCallIt + ' was successfully deleted.');
                                refreshTable(WhichTableEnum);
                            }else{
                                lightBoxMaker.makeOkBox('There was a problem deleting the ' + whatToCallIt + '.');
                            }
                        },
                        function(jqXHR, textStatus, errorThrown){
                            ajax.hideLoadingImage();
                            lightBoxMaker.makeOkBox('There was a problem deleting the ' + whatToCallIt + '.');
                        }
                    );
                });
                var noButton = new ButtonDef('noBtn','Never mind.', function(){
                    lightBoxMaker.closePromptBox();
                });
                var buttons = [deleteButton, noButton];
                lightBoxMaker.makePromptBox(
                    'Are you sure you want to delete <strong>' + name + '</strong> ? This action cannot be undone.',
                    buttons,
                    'Are you sure you want to delete this ' + whatToCallIt + '?'
                );
            }); 
        };
        
        /**
         * This will add an onclick event to the passed in jQueryObject to view all
         * lessons associated with the passed in id.
         * @param {jQuery} jqueryObject
         * @param {string} viewLink
         * @returns {undefined}
         */
        function addViewLessonsClickEvent(jqueryObject, viewLink){
            jqueryObject.click(function(e){
                var el = jQuery(e.delegateTarget);
                el.addClass('tempHide');
                el.hide();
                el.after('<div id="loadMe"></div>');
                ajax.displayLoadingImage('loadMe');
                ajax.loadJSON(
                    viewLink,
                    makePopupLessonsTable
                );

            });
        };

        /**
        * This will hide the view table link.
        * @param {type} divId
        * @returns {undefined}
        */
        function hideLoadLink(divId){
            var div = jQuery('#' + divId + ' .loadLink').hide();
        };

        var ajaxLinks =  {}; //These are populated by the PHP dynamically.

        /**
          * This will take the pseudo-enum value from WhichTableEnum and use
          * that to fill out the table contents.
          * @param {int} WhichTableEnum
          */
         function fillTable(WhichTableEnum){
             var sectionDivName = getSectionDivName(WhichTableEnum);
             jQuery('#' + sectionDivName).find('.search').show();
             jQuery('#' + sectionDivName).find('.sort').show();
             hideLoadLink(sectionDivName);
             jQuery('#' + sectionDivName + ' .refreshLink').css('display','inline-block');
             ajax.displayLoadingImage(sectionDivName);
             getTableContent(WhichTableEnum);
         };

         /**
          * This is a pseudo-enum to make determining the table type easier.
          */
         var WhichTableEnum = {
             Lessons: 0,
             Schools: 1,
             Series: 2,
             Modal: 3
         };

         /**
          * This will remove and repopulate the table specified by the enum.
          * @param {int} WhichTableEnum
          */
         function refreshTable(WhichTableEnum){
             var divName = getSectionDivName(WhichTableEnum);
             selectionTool.selectNone();
             selectionTool.evaluateSelections();
             jQuery("#" + divName + ' table').remove();
             fillTable(WhichTableEnum);
         };

         /**
          * This controls the main manage sections buttons at the top
          * menu bar.
          * @param {string} idClicked
          */
         function tabToggle(idClicked){
             var buttons = jQuery('.menuBarButton');
             for(var i=0; i < buttons.length; i++){
                 if(buttons[i].id === idClicked){
                     jQuery(buttons[i]).css('font-weight','bold');
                 }else{
                     jQuery(buttons[i]).css('font-weight','normal');
                 }
             }
             var div;
             switch(idClicked){
                 case 'manageLessons':
                     div = jQuery('#lessonsDiv');
                     break;
                 case 'manageSeries':
                     div = jQuery('#seriesDiv');
                     break;
                 case 'manageSchools':
                     div = jQuery('#schoolsDiv');
                     break;
                 default:
                     break;
             }
             var divs = jQuery('.manageTab');
             for(i=0; i < divs.length; i++){
                 if(divs[i].id === div.attr('id')){
                     jQuery(divs[i]).css('display','block');
                 }else{
                     jQuery(divs[i]).css('display','none');
                 }
             }
             jQuery('.search').hide();
        };


        //PUBLIC adminHome MEMBERS
        return {
            ajaxLinks: ajaxLinks,
            Token: "",
            WhichTableEnum: WhichTableEnum,
            tabToggle: tabToggle,
            refreshTable: refreshTable,
            fillTable: fillTable,
            SelectedItems: [],
            getSectionDivName: getSectionDivName,
            deleteItem: deleteItem
        };  
    }());
    
    //see description in the return block.
    var editLesson = (function(){
            //private members
            var FirstLoad = true;
            var ctId = "";
            var form = {
                fields: {},
                selects: {},
                values: {}
            };
            
            function setContent(){
                var ctDropDown = jQuery('#contentTypeDropDown option:selected');
                ctId = ctDropDown.val();
                var allSpots = jQuery('.contentForm');
                allSpots.hide();
                if(ctId !== ""){            
                    var form = jQuery('#content-' + ctId);
                    if(FirstLoad){
                        var textPlace = form.find('input, textarea');
                        textPlace.val(this.Content);
                    }
                    form.show();
                    FirstLoad = false;
                }
            };
            function setSeriesPositions(){
                var selectedId = jQuery('#seriesDrop option:selected').val();
                jQuery('#seriesPosition option').remove();
                if(selectedId === "" || selectedId === null){
                    return;
                }
                PB.editLesson.HighestSeriesOrder = 0;
                jQuery.each(PB.editLesson.AllSeries[selectedId].Lessons, function(index, value){
                    PB.editLesson.HighestSeriesOrder = 
                            (parseInt(value.SeriesOrder) > PB.editLesson.HighestSeriesOrder) ? 
                            parseInt(value.SeriesOrder) : 
                            PB.editLesson.HighestSeriesOrder;
                    var lesson = PB.editLesson.ThisLesson;
                    if(parseInt(index) - 1 !== PB.editLesson.CurrentPositionIndex){
                        jQuery('#seriesPosition').append(jQuery('<option>',{
                            value: value.SeriesOrder,
                            text: 'Before #' + (index + 1) + ': ' + value.Title
                        }));
                    }
                    if(typeof(lesson) !== 'undefined' && lesson !== null){
                        if(lesson.hasOwnProperty('TruePosition')){
                           if(index + 1 === lesson.TruePosition && lesson.Series.Id === selectedId){
                                jQuery('#seriesPosition option[value=' + lesson.SeriesOrder + ']').prop('selected',true);
                                jQuery('#seriesPosition option[value=' + lesson.SeriesOrder + ']')
                                        .text('Current Position (#' + (index + 1) + ')')
                                        .css('font-weight', 'bold');
                                PB.editLesson.CurrentPositionIndex = parseInt(index);
                            } 
                        }           
                    }
                });
                if(PB.editLesson.CurrentPositionIndex !== PB.editLesson.AllSeries[selectedId].Lessons - 1){
                    jQuery('#seriesPosition').append(jQuery('<option>',{
                        value: PB.editLesson.HighestSeriesOrder + 1,
                        text: 'At end of series'
                    }));
                }
                if(typeof(PB.editLesson.ThisLesson) === 'undefined'){
                    jQuery('#seriesPosition option[value="' + PB.editLesson.HighestSeriesOrder + '"]').prop('selected',true);
                }
            };
            function captureForm(){
                var set = function(id){
                    form.values[id] = jQuery('#' + id).val();
                    form.fields[id] = jQuery('#' + id);
                };
                var setSelect = function(id){
                    form.values[id] = jQuery('#' + id + ' option:selected').val();
                    form.selects[id] = jQuery('#' + id);
                };
                var setUpdateValue = function(){
                    if(form.values['lessonId'] === ""){
                        form.values['update'] = false;
                    }else{
                        form.values['update'] = true;
                    }
                };
                set('lessonId');
                setUpdateValue();
                set('Title');
                set('Description');
                set('SourceCredit');
                set('imagePath');
                setSelect('categoryId');
                setSelect('seriesDrop');
                setSelect('seriesPosition');
                var isPublished = jQuery('#publish').is(':checked');
                form.values['Published'] = isPublished;
                form.values['contentTypeId'] = ctId;
                form.selects['contentTypeId'] = jQuery('#contentTypeDropDown');
                var contentForm = jQuery('#content-' + ctId + ' [name="content"]');
                if(ctId === "5" && tinyMCE.activeEditor !== null){
                    form.values['content'] = tinyMCE.activeEditor.getContent();
                }else{
                    form.values['content'] = contentForm.val();
                }
                form.fields['content'] = contentForm;
                form.formElement = jQuery('#editLessonForm');
            };
            
            function getContent(){
                if(ctId === '5' & tinyMCE.activeEditor !== null){
                    return tinyMCE.activeEditor.getContent();
                }
                var contentForm = jQuery('#content-' + ctId + ' [name="content"]');
                return contentForm.val().trim();
            };
            
            function previewContent(){
                var content = getContent();
                jQuery('#contentPreview').html('');
                if(content === ""){
                    return;
                }
                var imagePath = jQuery('#imagePath').val();
                ajax.displayLoadingImage('contentPreview');
                jQuery.get(
                    PB.editLesson.PreviewLink,
                    {
                        ctId: ctId,
                        content: content,
                        imagePath: imagePath
                    },
                    function(data){
                        ajax.hideLoadingImage();
                        jQuery('#contentPreview').append('<p><strong>Preview:</strong></p>');
                        jQuery('#contentPreview').append(data.embedString);
                    },
                    'json'
                );
            };
            
            function makeRequest(){
                var request = {};
                var token = editorResources.getToken();
                request['values'] = JSON.stringify(form.values);
                request[token] = "1";
                return request;
            };
            
            function submitForm(){
                captureForm();
                form.formElement.hide();
                ajax.displayLoadingImage('formContainer');
                ajax.sendForm(
                    this.PostUrl,
                    makeRequest()
                );
            };
            function togglePublished(){
                var pubSwitch = jQuery('#publish');
                var switchLabel = jQuery('#switchLabel');
                if(pubSwitch.is(':checked')){
                    switchLabel.text("Published");
                    switchLabel.removeClass('unPublished');
                    switchLabel.addClass('Published');
                }else{
                    switchLabel.text("Not Published");
                    switchLabel.removeClass('Published');
                    switchLabel.addClass('unPublished');
                }
            }
            
            //Publically revealed members.
            return{
                ajaxLinks: {},
                ThisLesson: {},
                AllSeries: [],
                PostUrl: "",
                Content: "",
                PreviewLink: "",
                submitForm: submitForm,
                setSeriesPositions: setSeriesPositions,
                setContent: setContent,
                previewContent: previewContent,
                togglePublished: togglePublished
            };
        }());
    
    //see description in the return block
    var editSeries = (function(){
        var form = {
            fields: {},
            values: {}
        };
        function makeRequest(){
            var request = {};
            var token = editorResources.getToken();
            request['values'] = JSON.stringify(form.values);
            request[token] = "1";
            return request;
        };    
        function captureForm(){
            var set = function(id){
                form.values[id] = jQuery('#' + id).val();
                form.fields[id] = jQuery('#' + id);
            };
            var setUpdateValue = function(){
                if(form.values['seriesId'] === ""){
                    form.values['update'] = false;
                }else{
                    form.values['update'] = true;
                }
            };
            set('seriesId');
            setUpdateValue();
            set('SeriesName');
            set('Description');
            set('imagePath');
            form.formElement = jQuery('#editSeriesForm');
        };     
        function submitForm(){
                captureForm();
                form.formElement.hide();
                ajax.displayLoadingImage('formContainer');
                ajax.sendForm(
                    this.PostUrl,
                    makeRequest()
                );
            };
        return {
            PostUrl: "",
            submitForm: submitForm
        };
    }());
    
    //see description in the return block
    var editCategory = (function(){
        var form = {
            fields: {},
            values: {}
        };
        function makeRequest(){
            var request = {};
            var token = editorResources.getToken();
            request['values'] = JSON.stringify(form.values);
            request[token] = "1";
            return request;
        };    
        function captureForm(){
            var set = function(id){
                form.values[id] = jQuery('#' + id).val();
                form.fields[id] = jQuery('#' + id);
            };
            var setUpdateValue = function(){
                if(form.values['categoryId'] === ""){
                    form.values['update'] = false;
                }else{
                    form.values['update'] = true;
                }
            };
            set('categoryId');
            setUpdateValue();
            set('categoryName');
            set('Description');
            set('imagePath');
            form.formElement = jQuery('#editCategoriesForm');
        };
        function submitForm(){
                captureForm();
                form.formElement.hide();
                ajax.displayLoadingImage('formContainer');
                ajax.sendForm(
                    this.PostUrl,
                    makeRequest()
                );
            };
        
        return {
            PostUrl: "",
            submitForm: submitForm
        };
    }());
    
    /**
     * INTERNAL NAMESPACE: editor Resources
     * This is the code supporting all editors, not specific to any.
     */
    var editorResources = (function(){
        //Private members
        
        /**
         * Obtains the form token placed by the PHP.
         * @returns {string}
         */
        function getToken(){
                var element = document.getElementById('token');
                var token = element.name;
                return token;
        };
        
        function loadPreviewImage(){
            jQuery('.imagePreview').children().remove();
            var imgSrc = jQuery('#imagePath').val();
            if(imgSrc !== ''){
                jQuery('.imagePreview').append('<p>Image Preview:</p>');
                jQuery('.imagePreview').append('<img id="preview" src="' + imgSrc + '">');
            }
        };
        
        function showSuccessfulMessage(){
            jQuery('.feedback').hide();
            var newItem = (location.href.indexOf('&id=') > -1) ? false : true;
            if(newItem){
                jQuery('#successfulMessage').text('This item was successfully added.');
            }else{
                jQuery('#successfulMessage').text('This item was successfully saved.');
            }
            jQuery('#successfullySubmitted').show();
        };
        function showErrorMessage(errorMessage){
            jQuery('.feedback').hide();
            if(errorMessage){
                jQuery('#errorMessage').text(errorMessage);
            }
            jQuery('.errorInSubmission').show();
        };
        
        //publically exposed members
        return {
            getToken: getToken,
            loadPreviewImage: loadPreviewImage,
            showSuccessfulMessage: showSuccessfulMessage,
            showErrorMessage: showErrorMessage
        };
    }());

    //Publically accessbile namespaces
    return {
        
        /**
         * PUBLIC NAMESPACE: adminHome
         * This is where all the code supporting the adminHome view is located.
         */
        adminHome: adminHome,
        
        /**
         * PUBLIC NAMESPACE: editLesson
         * This is where all the code supporting the addEditLesson view is located.
         */
        editLesson: editLesson,
        
        /**
         * PUBLIC NAMESPACE: editSeries
         * This is where all the code supporting the addEditSeries view is located.
         */
        editSeries: editSeries,
        
        editCategory: editCategory,
        
        /**
         * This exposes a single function out of the editorResources namespace.
         */
        loadPreviewImage: editorResources.loadPreviewImage
    };
}());


jQuery(function(){
    if(jQuery('#editLessonForm').length > 0){ //If the page has the editLessonForm
        var form = jQuery('#editLessonForm');
        form.submit(function(e){
            e.preventDefault();
            PB.editLesson.submitForm();
        });
        PB.editLesson.setSeriesPositions();
        PB.editLesson.setContent();
        PB.editLesson.togglePublished();
        if(PB.editLesson.ThisLesson !== null){
            PB.editLesson.previewContent();
        }
        jQuery('[name="content"]').blur(function(){
           PB.editLesson.previewContent(); 
        });
        jQuery('#previewButton').click(function(e){
           e.preventDefault();
           PB.editLesson.previewContent(); 
        });
        jQuery('#contentTypeDropDown').change(function(){
            PB.editLesson.setContent();
        });
        jQuery('#seriesDrop').change(function(){
            PB.editLesson.setSeriesPositions();
        });
        jQuery('#publish').change(function(){
            PB.editLesson.togglePublished();
        }); 
    }else if(jQuery('#editSeriesForm').length > 0){
        var form = jQuery('#editSeriesForm');
        form.submit(function(e){
            e.preventDefault();
            PB.editSeries.submitForm();
        });
    }else if(jQuery('#editCategoriesForm').length > 0){
        var form = jQuery('#editCategoriesForm');
        form.submit(function(e){
            e.preventDefault();
            PB.editCategory.submitForm();
        });
    };
    if(jQuery('.feedback').length > 0){
        jQuery('#continueEdit').click(function(){
            jQuery('form').show();
            jQuery('.feedback').hide();
        });
        jQuery('#reload').click(function(){
            location.href = PB.AddNewLink;
        });
    };
    jQuery('#imagePath').blur(function(){
        PB.loadPreviewImage();
    });
    PB.loadPreviewImage();
});
