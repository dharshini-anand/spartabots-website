<script src="<?php echo site_url() ?>system/resources/run_selectplugin.js"></script>
<script>
$(document).ready(function() {
	$('.upload-area').on('dragover', function(e) {
		e.preventDefault();
		e.stopPropagation();
	});
	$('.upload-area').on('dragenter', function(e) {
		e.preventDefault();
		e.stopPropagation();
		
		$(this).attr('data-dragCount', parseInt($(this).attr('data-dragCount'))+1);
		$(this).addClass('dragHover');
	});
	$('.upload-area').on('dragleave', function(e) {
		e.preventDefault();
		e.stopPropagation();
		
		$(this).attr('data-dragCount', parseInt($(this).attr('data-dragCount'))-1);
		if (parseInt($(this).attr('data-dragCount')) === 0) {
			$(this).removeClass('dragHover');
		}
	});
	$('.upload-area').on('drop', function(e) {
		$(this).attr('data-dragCount', 0);
		$(this).removeClass('dragHover');
		if (e.originalEvent.dataTransfer.files && e.originalEvent.dataTransfer.files.length) {
            e.preventDefault();
            e.stopPropagation();
            
            var files = e.originalEvent.dataTransfer.files;
            handleFiles(files, $(this).find('.upload-list'));
        }
	});
	$('.upload-area-click').click(function(e) {
		$(this).closest('.upload-area').find('.upload-files').click();
	});
	
    $('.upload-files').change(function() {
        $upload_list = $(this).closest('.upload-area').find('.upload-list');
        handleFiles($(this)[0].files, $upload_list);
    });
});

function handleFiles(files, $upload_list) {
    if (files && files.length != 0) {
        for (var i = 0; i < files.length; i++) {
            readImage(files[i], $upload_list);
        }
    }
}

var upload_data = {};
var upload_item_id = 0;

/* http://stackoverflow.com/questions/12570834/how-to-preview-image-get-file-size-image-height-and-width-before-upload */
function readImage(file, $upload_list) {
    var reader = new FileReader();
    var image  = new Image();
	
	var $item = $('<div class="upload-list-item" data-id="'+(file.name + upload_item_id)+'"> \
                <div class="upload-list-item-thumbnail upload-list-item-thumbnail-loading" style=""> \
					<span class="ouro ouro3"> \
						<span class="left"><span class="anim"></span></span> \
						<span class="right"><span class="anim"></span></span> \
					</span> \
				</div> \
                <div class="upload-list-item-details"> \
                    <div class="upload-list-item-name"></div> \
                    <div class="upload-list-item-size"></div> \
					<div class="upload-list-item-remove" onclick="removeUploadItem($(this).closest(\'.upload-list-item\'))">Remove file</div> \
                </div>\
            </div>');
    $upload_list.append($item);
	
    reader.readAsDataURL(file);  
    reader.onload = function(_file) {
        image.src    = _file.target.result;              // url.createObjectURL(file);
        image.onload = function() {
            var w = this.width,
                h = this.height,
                t = file.type,                           // ext only: // file.type.split('/')[1],
                n = file.name,
                s = ~~(file.size/1024); /* KB */
            if ($upload_list.has('.upload-list-placeholder'))
                $upload_list.find('.upload-list-placeholder').remove();
				
			// Add file to upload data
			upload_data[file.name + upload_item_id] = file;
            
            var $upload_info = $upload_list.closest('.upload-module').find('.upload-info');
            $upload_info.find('.upload-info-size').find('.upload-info-value')
                    .html(parseInt($upload_info.find('.upload-info-size').find('.upload-info-value').html()) + s);
            $upload_info.find('.upload-info-numFiles').find('.upload-info-value')
                    .html(parseInt($upload_info.find('.upload-info-numFiles').find('.upload-info-value').html()) + 1);
			
			// Update item details
			$item.find('.upload-list-item-thumbnail').html('');
			$item.find('.upload-list-item-thumbnail').removeClass('upload-list-item-thumbnail-loading');
			$item.find('.upload-list-item-thumbnail').attr('style', 'background-image:url('+ this.src +')');
			$item.find('.upload-list-item-name').html(n);
			$item.find('.upload-list-item-size').html('<b>'+s+'</b> KB');
			
			// Append and increment id
			upload_item_id++;
        };
        image.onerror = function() {
			$item.find('.upload-list-item-thumbnail').removeClass('upload-list-item-thumbnail-loading');
			$item.find('.upload-list-item-thumbnail').html('Cannot upload: invalid file type: ' + file.type);
            window.setTimeout(function () {
				$item.remove();
			}, 1000);
        };
    };
}

function removeUploadItem($item) {
	var item_id = $item.attr('data-id');
    var item_fsize = ~~(upload_data[item_id].size/1024);
    
    var $upload_info = $item.closest('.upload-module').find('.upload-info');
    $upload_info.find('.upload-info-size').find('.upload-info-value')
            .html(parseInt($upload_info.find('.upload-info-size').find('.upload-info-value').html()) - item_fsize);
    $upload_info.find('.upload-info-numFiles').find('.upload-info-value')
            .html(parseInt($upload_info.find('.upload-info-numFiles').find('.upload-info-value').html()) - 1);
    
	delete upload_data[item_id];
	console.log('remove: '+item_id);
	$item.fadeOut(200, function() { $(this).remove(); });
}

function sendData() {
	var form = new FormData();
	
	for (var key in upload_data) {
		form.append('upload[]', upload_data[key], upload_data[key].name);
	}
	
	var req = new XMLHttpRequest();
	req.open('POST', '/pathToPostData', true);
	req.onload = function(evt) {
		if (xhr.status === 200) {
			console.log(req.responseText);
		} else {
		}
	}
	req.send(form);
}
</script>
<style>#select-opts-container {color:rgb(51,51,51)}</style>
<div id="media-main">
	<div id="media-nav">
		<a id="media-nav-title" href="<? echo site_url() ?>media"><h1>Media</h1></a>
		<a href="<? echo site_url() ?>media/gallery">Gallery</a>
		<a href="<? echo site_url() ?>media/videos">Videos</a>
	</div>
	<div id="media-content-wrapper">
		<div id="media-nav-shadow">
		</div>
		<div id="media-content">
            <!--[if lte IE 9]>
            <div class="error-message">Sorry, file uploading will not work in versions of Internet Explorer less than 10.</div>
            <![endif]-->
            <form class="upload-form" method="POST" enctype="multipart/form-data" onsubmit="sendData();return false;">
                <h2 style="color:rgb(51,51,51);font-weight:300;border-bottom:1px solid #e3e3e3;padding-bottom:5px;margin-bottom:20px;">Upload Images to Gallery</h2>
				
                <div class="upload-module">
                    <div class="upload-info clearfix">
                        <div class="upload-info-item upload-info-size"><span class="upload-info-value">0</span><span> KB</span></div>
                        <div class="upload-info-item upload-info-numFiles"><span class="upload-info-value">0</span><span> Files</span></div>
                    </div>
                    <div class="upload-area" data-dragCount="0">
                        <input type="file" class="upload-files" name="upload[]" multiple accept="image/*" required style="width: 1px; display:none" />
                        <div class="upload-list">
                            <div class="upload-list-placeholder">Drag files here or click</div>
                        </div>
                        <div class="upload-area-click"></div>
                    </div>
                </div>
				
				<div>
					<select required id="catSelect" name="catSelect" style="display:block;float:left;color:rgb(51,51,51);margin-right:5px;">
						<option value="">Please select a category</option>
						<?php
						$dirs = array_reverse(array_filter(glob('images/gallery/*'), 'is_dir'));
						foreach ($dirs as $dir) {
							echo '<option value="'.basename($dir).'">'.basename($dir).'</option>';
						}
						?>
					</select>
					<input type="submit" id="upload-button" value="Upload" />
					<div class="clearfix"></div>
				</div>
            </form>
		</div>
	</div>
    <div class="clearfix"></div>
</div>