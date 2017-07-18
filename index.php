<?php
require_once 'config.php';
require_once 'curl.php';

$allMicroservices = executeCurl(API_BASE_URL . 'Mindspark/Creator/GetMicroserviceDetails', ['microservice' => ''])['data'];
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Framework</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <link rel="stylesheet" href="assets/select2/select2.css">

        <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

        <script src="assets/select2/select2.js" defer=""></script>
        <script src="assets/blockUI.js" defer=""></script>
        <style>
            table, form{font-size: smaller;}
            .form-group .select2-container {position: relative !important;z-index: 2 !important;float: left !important;width: 100% !important;margin-bottom: 10px !important;display: table !important;table-layout: fixed !important;}
            .select2-container ul { display: block;}
            .select2-container-multi .select2-choices{height: 65px !important;overflow-y: scroll !important;}
            #api-container .glyphicon{cursor: pointer; }
            #api-list-table tbody {display:block; max-height:490px; overflow-y:scroll; }
            #api-list-table thead, #api-list-table tbody tr, #api-test-container thead, #api-test-container tbody tr {display:table; width:100%; table-layout:fixed; }
            #api-test-container tbody {display:block; max-height:340px; overflow-y:scroll; }
            #api-reponse-container .panel-default{margin-bottom: 0px !important; }
            .panel-body{padding: 5px !important; }
            #api-reponse-body{overflow-y: scroll; /*overflow-x: hidden;*/ max-height: 350px; height: 500px; }
            pre {outline: 1px solid #ccc; padding: 5px;} .string { color: green; } .number { color: darkorange; } .boolean,.text-blue { color: blue; } .null { color: magenta; }
            .key { color: red; }
            .input-xs {height: 22px; padding: 2px 5px; font-size: 12px; line-height: 1.5; /* If Placeholder of the input is moved up, rem/modify this. */ border-radius: 3px; }

            .panel-actions {margin-top: -20px; margin-bottom: 0; text-align: right; }
            .panel-actions a {color:#333; } 
            .panel-fullscreen {display: block; z-index: 9999; position: fixed; width: 100%; height: 100%; top: 0; right: 0; left: 0; bottom: 0; overflow: auto; }
        </style>
    </head>
    <body>
        <div class="container" style="margin-top: 10px; width: 95%">
            <div class="row">
                <div class="col-md-6" style="margin-bottom:15px">
                    <div class="form-group">
                        <label class="control-label col-sm-3">Microservice: </label>
                        <div class="col-sm-9">
                            <select name="microservice" class="form-control select-microservice" required="">
                                <option value="">Select microservice...</option>
                                <?php
                                if (!empty($allMicroservices)) {
                                    foreach ($allMicroservices as $key => $value) {
                                        ?>
                                        <option value="<?php echo $value; ?>"><?php echo $value; ?></option>
                                        <?php
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                </div>
            </div>

            <div class="row hide" id="api-container">

                <ul class="nav nav-tabs">
                    <li class="active"><a data-toggle="tab" href="#api-list-tab">Api list <span class="badge" id="api-number"></span></a></li>
                    <li><a data-toggle="tab" href="#new-api-tab">Create new API</a></li>
                </ul>

                <div class="tab-content">
                    <div id="api-list-tab" class="tab-pane fade in active">
                        <button class="btn btn-xs clearfix hideList" id="hide-list">Hide api list</button>
                        <div class="row" style="margin-top: 10px;">

                            <div class="col-md-4" id="api-list-container">
                                <table class="table table-striped" id="api-list-table">
                                    <thead>
                                        <tr>
                                            <th>Sl. No.</th>
                                            <th>API Name</th>
                                        </tr>
                                    </thead>
                                    <tbody id="api-list-table-body"></tbody>
                                </table>
                            </div>

                            <div class="col-md-8 hide" id="api-test-container">
                                <form class="form-horizontal" id="test-api-form" method="post">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h3 class="panel-title" id="panel-heading"></h3>
                                            <ul class="list-inline panel-actions">
                                                <li><a href="javascript:;" id="panel-fullscreen" role="button" title="Toggle fullscreen"><i class="glyphicon glyphicon-resize-full"></i></a></li>
                                            </ul>
                                        </div>
                                        <div class="panel-body">
                                            <div class="col-md-5" style="padding: 0px !important" id="input-fields-main-container">
                                                <table class="table table-bordered" id="input-fields-table">
                                                    <thead>
                                                        <tr style="text-align:left">
                                                            <th>Key</th>
                                                            <th>Value</th>
                                                            <!--<th>Action</th>-->
                                                        </tr>
                                                    </thead>
                                                    <tbody id="input-fields-table-body"></tbody>
                                                </table>
                                                <center>
                                                    <button class="btn btn-primary btn-sm" id="add-more-field" type="button">
                                                        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>&nbsp;Add more fields
                                                    </button>
                                                </center>
                                            </div>
                                            <div class="col-md-7" id="api-reponse-container">
                                                <div class="panel panel-default">
                                                    <div class="panel-heading">
                                                        Response
                                                        <span class="pull-right hide response-status">
                                                            Status: <span id="response-status" class="text-blue"></span>&nbsp;&nbsp;&nbsp;&nbsp;
                                                            Time: <span id="response-time" class="text-blue"></span>
                                                        </span>
                                                    </div>
                                                    <div class="panel-body">
                                                        <pre id="api-reponse-body"></pre>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="panel-footer">
                                            <div class="text-center">
                                                <button type="button" class="btn btn-primary" id="api-post-button">Send</button>
                                                <button type="button" class="btn btn-default" id="reset-api-post">Reset</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div id="new-api-tab" class="tab-pane fade">
                        <div class="col-md-6" style="margin-top: 10px;">
                            <form class="form-horizontal" id="create-form" method="post">

                                <div class="form-group">
                                    <label class="control-label col-sm-3">API name:</label>
                                    <div class="col-sm-9">
                                        <input type="text" maxlength="200" class="form-control" name="apiName" required="">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-3">Input fields:</label>
                                    <div class="col-sm-9">
                                        <select id="inputFields-select" name="inputFields[]" class="form-control multi-select inputFields-select" required="" multiple="" placeholder="Enter input fields">
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-3">Context (if any):</label>
                                    <div class="col-sm-9">
                                        <select id="context-select" name="contexts[]" class="form-control multi-select context-select" required="" multiple="" placeholder="Enter context">
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-3">Objects (if any):</label>
                                    <div class="col-sm-9">
                                        <select id="object-select" name="objects[]" class="form-control multi-select object-select" required="" multiple="" placeholder="Enter object">
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-3">Tasks:</label>
                                    <div class="col-sm-9">
                                        <select id="task-select" name="tasks[]" class="form-control multi-select task-select" required="" multiple="" placeholder="Enter task">
                                        </select>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <button type="button" class="btn btn-primary" id="create-api-component">Create</button>
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script type="text/javascript">
            $(document).ready(function (e) {
                var ajaxURL = 'ajax-handler.php';
                var microservice = '';
                var selectedApiName = '';
                var API_BASE_URL = '<?php echo API_BASE_URL; ?>';
                var PRODUCT = '<?php echo PRODUCT; ?>';
                var select2Options = {
                    tags: true,
                    tokenSeparators: [',', ' ']
                };


                $(".multi-select").select2(select2Options);

                $(document.body).on('change', '.select-microservice', function (e) {
                    var obj = $(this);
                    microservice = obj.val();
                    if (microservice != '') {
                        $('#api-container').removeClass('hide');
                        $('#api-test-container').addClass('hide');
                        $.ajax({
                            url: ajaxURL,
                            type: 'POST',
                            data: {
                                method: 'getList',
                                microservice: microservice
                            },
                            success: function (result) {
                                var toAppendApiList = '', toAppendObjects = '', toAppendTasks = '', toAppendContext = '';

                                $('#api-list-table-body, #task-select, #object-select, #context-select').empty();
                                $('#api-number').text(result.data.apiList.length);
                                if (result.data.apiList.length) {
                                    $.each(result.data.apiList, function (i, v) {
                                        toAppendApiList += '<tr>';
                                        toAppendApiList += '<td>' + (i + 1) + '</td>';
                                        toAppendApiList += '<td><a href="javascript:;" class="api-name" data-value="' + v + '" title="Click to test \'' + v + '\' api" data-microservice="' + microservice + '">' + v + '</a></td>';
                                        toAppendApiList += '</tr>';
                                    });

                                    $('#api-list-table-body').empty().append(toAppendApiList);
                                }


                                if (result.data.tasks.length) {
                                    $.each(result.data.tasks, function (i, v) {
                                        toAppendTasks += '<option value="' + v + '">' + v + '</option>';
                                    });
                                    $('#task-select').empty().append(toAppendTasks).select2(select2Options);
                                }

                                if (result.data.objects.length) {
                                    $.each(result.data.objects, function (i, v) {
                                        toAppendObjects += '<option value="' + v + '">' + v + '</option>';
                                    });
                                    $('#object-select').empty().append(toAppendObjects).select2(select2Options);
                                }

                                if (result.data.contexts.length) {
                                    $.each(result.data.contexts, function (i, v) {
                                        toAppendContext += '<option value="' + v + '">' + v + '</option>';
                                    });
                                    $('#context-select').empty().append(toAppendContext).select2(select2Options);
                                }
                                // alert(result.message);
                            },
                            error: function (result) {
                                var error = JSON.parse(result.responseText);
                                // alert(error.message);
                            }
                        });
                    }
                });

                $(document.body).on('click', '#create-api-component', function (e) {
                    e.preventDefault();
                    var createForm = $('#create-form');

                    var formData = new FormData(createForm[0]);
                    formData.append('method', 'createApi');
                    formData.append('microservice', microservice);

                    $.ajax({
                        url: ajaxURL,
                        type: 'POST',
                        contentType: false,
                        processData: false,
                        data: formData,
                        success: function (result) {
                            $("#create-api-component-modal").modal('hide');
                            alert(result.message);
                            location.reload();
                        },
                        error: function (result) {
                            var error = JSON.parse(result.responseText);
                            alert(error.message);
                        }
                    });
                    return;
                });


                $("select").on("select2:select", function (evt) {
                    var element = evt.params.data.element;
                    var $element = $(element);

                    $element.detach();
                    $(this).append($element);
                    $(this).trigger("change");
                });

                $(document.body).on('click', '.api-name', function (e) {
                    e.preventDefault();
                    var obj = $(this);
                    selectedApiName = obj.data('value');
                    if (selectedApiName != '') {
                        $('#api-test-container').removeClass('hide');
                        $('#panel-heading').text(selectedApiName);
                        $('#api-reponse-body').empty();
                        $('.response-status').addClass('hide');
                        $.ajax({
                            url: ajaxURL,
                            type: 'POST',
                            data: {
                                method: 'getApiDetails',
                                microservice: microservice,
                                apiName: selectedApiName
                            },
                            success: function (result) {
                                if (result.data.input.post.length) {
                                    var toAppendInputFields = '';
                                    $.each(result.data.input.post, function (i, v) {
                                        toAppendInputFields += '<tr class="input-container">';
                                        toAppendInputFields += '<td class="col-sm-4">';
                                        toAppendInputFields += '<input type="text" class="form-control input-xs" name="post[key][]" value="' + v + '" required="">';
                                        toAppendInputFields += '</td>';
                                        toAppendInputFields += '<td class="col-sm-7">';
                                        toAppendInputFields += '<input type="text" class="form-control input-xs" name="post[value][]" required="">';
                                        toAppendInputFields += '</td>';
                                        toAppendInputFields += '<td class="col-sm-1">';
                                        toAppendInputFields += '<span class="glyphicon glyphicon-remove remove-field" aria-hidden="true"></span>';
                                        toAppendInputFields += '</td>';
                                        toAppendInputFields += '</tr>';
                                    });

                                    $("#api-test-container #input-fields-table-body").empty().append(toAppendInputFields);
                                }
                            },
                            error: function (result) {
                                var error = JSON.parse(result.responseText);
                                alert(error.message);
                            }
                        });
                    }
                });

                $(document.body).on('click', '#add-more-field', function (e) {
                    var toAppendInputFields = '<tr class="input-container">';
                    toAppendInputFields += '<td class="col-sm-4">';
                    toAppendInputFields += '<input type="text" class="field-name form-control input-xs" name="post[key][]" required="">';
                    toAppendInputFields += '</td>';
                    toAppendInputFields += '<td class="col-sm-7">';
                    toAppendInputFields += '<input type="text" class="field-value form-control input-xs" name="post[value][]" required="">';
                    toAppendInputFields += '</td>';
                    toAppendInputFields += '<td class="col-sm-1">';
                    toAppendInputFields += '<span class="glyphicon glyphicon-remove remove-field" aria-hidden="true"></span>';
                    toAppendInputFields += '</td>';
                    toAppendInputFields += '</tr>';
                    $("#api-test-container #input-fields-table-body").append(toAppendInputFields);
                });
                $(document.body).on('click', '#input-fields-table-body .remove-field', function (e) {
                    $(this).parents('tr').remove();
                });


                function apiPost() {
                    console.log("inside function");
                    var testAPIForm = $('#test-api-form');

                    var newField = '';
                    $.each(testAPIForm.find('.input-container'), function (i, v) {
                        var obj = $(this);
                        obj.find(':input').prop('disabled', true);
                        var key = obj.find(':input:eq(0)').val();
                        var value = obj.find(':input:eq(1)').val();
                        newField += '<input class="final-key-value" type="hidden" name="' + key + '"  value="' + value + '" >';
                    });
                    testAPIForm.find('.final-key-value').remove().end().append(newField);


                    var apiURL = API_BASE_URL + PRODUCT + '/' + microservice + '/' + selectedApiName + '/';
                    var ajaxTime = new Date().getTime();
                    $.ajax({
                        url: apiURL,
                        type: 'POST',
                        method: "POST",
                        dataType: 'html',
                        data: testAPIForm.serialize(),
                        beforeSend: function () {
                            // blockUI();
                        },
                        success: function (result, textStatus, xhr) {
                            var totalTime = new Date().getTime() - ajaxTime;
                            $('#response-status').text(xhr.status + ' ' + xhr.statusText);
                            $('#response-time').text(totalTime + ' ms');

                            if (IsJsonString(result)) {
                                result = JSON.parse(result);
                                result = JSON.stringify(result, undefined, 4);
                                result = syntaxHighlight(result);
                            }
                            $('#api-reponse-body').empty().append(result);
                            $('.response-status').removeClass('hide');

                        },
                        error: function (error) {
                            var totalTime = new Date().getTime() - ajaxTime;
                            $('#response-status').text(error.status + ' ' + error.statusText);
                            $('#response-time').text(totalTime + ' ms');
                            error = error.responseText;
                            if (IsJsonString(error)) {
                                error = JSON.parse(error);
                                error = JSON.stringify(error, undefined, 4);
                                error = syntaxHighlight(error);
                            }
                            $('#api-reponse-body').empty().append(error);
                            $('.response-status').removeClass('hide');
                            testAPIForm.find('.input-container :input').prop('disabled', false);
                        },
                        complete: function (data) {
                            testAPIForm.find('.input-container :input').prop('disabled', false);
                            // unblockUI();
                        }
                    });
                }

                $(document.body).on('click', '#api-post-button', function (e) {
                    e.preventDefault();
                    apiPost();
                    return;
                });

                $(document.body).on("keydown", function (e) {
//                    e.preventDefault();
                    if ((e.ctrlKey || e.metaKey) && (e.keyCode == 13 || e.keyCode == 10)) {
                        apiPost();
                    }
                });

                $(document.body).on('keydown', '.field-name', function (e) {
                    if (e.keyCode == 32)
                        e.preventDefault();
                });
                $(document.body).on('click', '#reset-api-post', function (e) {
                    console.log('reset');
                    $('a.api-name[data-value="' + selectedApiName + '"]').click();
                    $('#api-reponse-body').empty();
                });

                $(document.body).on('click', '#hide-list', function (e) {
                    var obj = $(this);
                    if (obj.hasClass('hideList')) {
                        $('#api-list-container').addClass('hide');
                        $('#api-test-container').removeClass('col-md-8').addClass('col-md-12')
                                .find('#input-fields-main-container').removeClass('col-md-5').addClass('col-md-4').end()
                                .find('#api-reponse-container').removeClass('col-md-7').addClass('col-md-8');
                        obj.removeClass('hideList').text('Show api list');
                    } else {
                        $('#api-list-container').removeClass('hide');
                        $('#api-test-container').removeClass('col-md-12').addClass('col-md-8')
                                .find('#input-fields-main-container').removeClass('col-md-4').addClass('col-md-5').end()
                                .find('#api-reponse-container').removeClass('col-md-8').addClass('col-md-7');
                        obj.addClass('hideList').text('Hide api list');
                    }

                });

                //Toggle fullscreen
                $("#panel-fullscreen").click(function (e) {
                    e.preventDefault();

                    var $this = $(this);

                    if ($this.children('i').hasClass('glyphicon-resize-full'))
                    {
                        $this.children('i').removeClass('glyphicon-resize-full');
                        $this.children('i').addClass('glyphicon-resize-small');
                    } else if ($this.children('i').hasClass('glyphicon-resize-small'))
                    {
                        $this.children('i').removeClass('glyphicon-resize-small');
                        $this.children('i').addClass('glyphicon-resize-full');
                    }
                    $(this).closest('.panel').toggleClass('panel-fullscreen');
                });
            });


            function syntaxHighlight(json) {
                json = json.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
                return json.replace(/("(\\u[a-zA-Z0-9]{4}|\\[^u]|[^\\"])*"(\s*:)?|\b(true|false|null)\b|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?)/g, function (match) {
                    var cls = 'number';
                    if (/^"/.test(match)) {
                        if (/:$/.test(match)) {
                            cls = 'key';
                        } else {
                            cls = 'string';
                        }
                    } else if (/true|false/.test(match)) {
                        cls = 'boolean';
                    } else if (/null/.test(match)) {
                        cls = 'null';
                    }
                    return '<span class="' + cls + '">' + match + '</span>';
                });
            }

            function IsJsonString(str) {
                try {
                    JSON.parse(str);
                } catch (e) {
                    return false;
                }
                return true;
            }

        </script>
    </body>
</html>
