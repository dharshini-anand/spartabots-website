<script>
var files_list_current_year = 0;
var files_list_current_month = 0;
var multiActionCheckedNumber = 0;

function switch_year(year) {
    files_list_current_year = year;
    $.get("/uploads/get", { year: year }, function(data) {
        //$("#file-list-wrapper").html(data);
        $('#file-list-wrapper-inner').fadeOut("normal", function(){
            $('#file-list-wrapper-inner').html(data);
            $('#file-list-wrapper-inner').fadeIn("normal");
        });
    });
    
    $('.year-tab').each(function(index) {
        $(this).removeClass('active');
        if ($(this).attr('id') === ('year-tab-' + year)) {
            $(this).addClass('active');
        }
    });
    
    $('.files-multi-action-checkbox').each(function() {
        var checkbox = $(this);
        if ((this).checked) {
            checkbox.prop('checked', false);
            checkbox.change();
        }
    });
}

function checkMultiActionUpdate(checkbox) {
    if (checkbox.checked) {
        multiActionCheckedNumber++;
    } else {
        multiActionCheckedNumber--;
    }
    
    if (multiActionCheckedNumber >= 1) {
        $('#files-list-multi-action').fadeIn("normal");
    } else {
        $('#files-list-multi-action').fadeOut("normal");
    }
}

function filesRunMultiAction(action_type) {
    if (action_type == 2) {
        if (confirm('Delete multiple files?')) {
            var allData = 'Multiple files deleted.';
            $('.files-multi-action-checkbox').each(function() {
                var checkbox = $(this);
                if ((this).checked) {
                    var year = checkbox.attr('data-year');
                    var month = checkbox.attr('data-month');
                    var file_name = checkbox.attr('data-file');
                    
                    $.post( "/uploads/delete", { year: year, month: month, file_name: file_name }, function(data) {
                        allData += (data + '<br/>');
                    });
                    checkbox.prop('checked', false);
                    checkbox.change();
                }
            });
            admin_alert('File deletion', allData);
            
            setTimeout(function(){
                switch_month(files_list_current_year, files_list_current_month);
            }, 1000);
        }
    }
    
}

function switch_month(year, month) {
    files_list_current_year = year;
    files_list_current_month = month;
    $.get( "/uploads/get", { year: year, month: month }, function(data) {
        //$("#file-list-wrapper").html(data);
        $('#file-list-wrapper-inner').fadeOut("normal", function(){
            $('#file-list-wrapper-inner').html(data);
            $('#file-list-wrapper-inner').fadeIn("normal");
        });
    });
    
    $('.year-tab').each(function(index) {
        $(this).removeClass('active');
        if ($(this).attr('id') === ('year-tab-' + year)) {
            $(this).addClass('active');
        }
    });
    
    $('.files-multi-action-checkbox').each(function() {
        var checkbox = $(this);
        if ((this).checked) {
            checkbox.prop('checked', false);
            checkbox.change();
        }
    });
}

function delete_file(year, month, file_name) {
    if (confirm('Delete file? <?php echo site_url() ?>uploads/' + year + '/' + month + '/' + file_name)) {
        $.post( "/uploads/delete", { year: year, month: month, file_name: file_name }, function(data) {
            admin_alert('File deletion', data);
            switch_month(year, month);
        });
    }
}

function file_select_change() {
    var fileSelect = document.getElementById('file-select');
    
    var files = fileSelect.files;
    var uploaded_files_list = '<h3><span>Files queued for upload (' + files.length + ')</span></h3><ul class="uploaded-files-list">';
    for (var i = 0; i < files.length; i++) {
        uploaded_files_list += '<li>' + files[i].name + '</li>';
    }
    uploaded_files_list += '</ul>';
    $('#file-upload-form-files-list').html(uploaded_files_list);
}

$(document).ready(function() {
    $('#file-upload-form').submit(function(event) {
        event.preventDefault();
        var fileSelect = document.getElementById('file-select');
        
        $('#file-upload-button').val('Uploading...');
        
        // Get the selected files from the input.
        var files = fileSelect.files;
        
        var file_names = new Array();
        
        // Create a new FormData object.
        var formData = new FormData();
        
        // Loop through each of the selected files.
        for (var i = 0; i < files.length; i++) {
            var file = files[i];
            file_names.push(file.name);
            
            // Add the file to the request.
            formData.append('upload[]', file, file.name);
        }
        
        // Set up the request.
        var xhr = new XMLHttpRequest();
        
        // Open the connection.
        xhr.open('POST', '/uploads/upload', true);
        
        // Set up a handler for when the request finishes.
        xhr.onload = function () {
            if (xhr.status === 200) {
                // File(s) uploaded.
                $('#file-upload-button').val('Upload');
                
                var uploaded_files_title = (file_names.length == 1 ? 'File' : 'Files');
                var uploaded_files_title2 = (file_names.length == 1 ? 'file' : 'files');
                
                var uploaded_files_list = '<ul class="uploaded-files-list">';
                for (var i = 0; i < file_names.length; i++) {
                    uploaded_files_list += '<li>' + file_names[i] + '</li>';
                }
                uploaded_files_list += '</ul>';
                
                admin_alert(uploaded_files_title + ' uploaded', '<div>Succesfully uploaded the following ' + uploaded_files_title2 + '</div>' + uploaded_files_list);
                
                $('#file-upload-form')[0].reset();
                $('#file-upload-form-files-list').html('<h3><span>Files queued for upload (0)</span></h3>');
                switch_month(files_list_current_year, files_list_current_month);
            } else {
                admin_alert('File upload failed', 'Failed to upload file(s)');
            }
        };
        // Send the Data.
        xhr.send(formData);
    });

    $("#file-select").change(function() {
        var fileSelect = document.getElementById('file-select');
        var files = fileSelect.files;
        var uploaded_files_list = '<h3><span>Files queued for upload (' + files.length + ')</span></h3><ul class="uploaded-files-list">';
        for (var i = 0; i < files.length; i++) {
            uploaded_files_list += '<li>' + files[i].name + '</li>';
        }
        uploaded_files_list += '</ul>';
        $('#file-upload-form-files-list').html(uploaded_files_list);
    });
});
</script>

<noscript>
    <div class="error-message" style="margin: 10px;margin-top:0;">
        The file upload utility requires JavaScript in order to work.
    </div>
</noscript>

<section>
    <h2><?php echo $heading?></h2>
    <?php
    if (!empty($error)) {
        echo("Error: $error");
    }
    ?>

    <form id="file-upload-form" method="POST" enctype="multipart/form-data">
        <input type="button" value="Select files" onclick="$('#file-select').click();" />
        <input type="submit" id="file-upload-button" value="Upload" />
        <input type="file" id="file-select" name="upload[]" multiple required style="width: 1px; display:none" />
        <div id="file-upload-form-files-list">
            <h3><span>Files queued for upload (0)</span></h3>
        </div>
    </form>
</section>
<hr/>
<section>
    <form id="file-list-form" method="POST">
        <h2>Uploaded Files</h2>
        <div class="file-list-tabs" id="year-tabs">
            <?php
            foreach ($year_dirs as $year) {
                echo '<div id="year-tab-' . $year . '" class="year-tab" onclick="switch_year(' . $year . ')">' . $year . '</div>';
            }
            ?>
            <div class="clearfix"></div>
        </div>
        <div id="file-list-wrapper">
            <div id="file-list-wrapper-inner"></div>
        </div>
        <div class="clearfix"></div>
    </form>
    <div id="file-list-status">
    </div>
</section>

<script>
var d = new Date();
switch_month(d.getFullYear(), d.getMonth() + 1);
</script>

<div id="files-list-multi-action" style="display:none">
    <h4>With selected files:</h4>
    <select id="files-list-multi-action-select">
        <option value="2">Delete</option>
    </select>
    <input type="button" id="files-list-multi-action-button" onclick="filesRunMultiAction(document.getElementById('files-list-multi-action-select').options[document.getElementById('files-list-multi-action-select').selectedIndex].value)" value="Go" />
</div>