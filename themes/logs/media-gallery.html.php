<script>
function toggle_gallery_info(id) {
    $('#gallery-info-container').fadeIn('fast');
    $('[data-popout-info=' + id + ']').fadeIn('normal');
}

function close_all_gallery_info() {
    $('#gallery-info-container').fadeOut('fast');
    $('.gallery-popout-info').fadeOut('normal');
}
</script>
<div id="media-main">
	<div id="media-nav">
		<a id="media-nav-title" href="<? echo site_url() ?>media"><h1>Media</h1></a>
		<a href="<? echo site_url() ?>media/gallery" class="active">Gallery</a>
		<a href="<? echo site_url() ?>media/videos">Videos</a>
	</div>
	<div id="media-content-wrapper">
		<div id="media-nav-shadow"></div>
		<div id="media-content">
		<?php
		$dirs = array_reverse(array_filter(glob('images/gallery/*'), 'is_dir'));
		$dirs_count = count($dirs);
		
		if ($dirs_count == 0) {
			echo '<a class="card-shadow media-card media-card-red"><h1>No Galleries Found</h1></a>';
			echo '<div class="clearfix"></div>';
		} else {
			$dirs_list = '<div class="media-toc" style="margin-left:20px"><h3>Table of Contents</h3><ul>';
			foreach ($dirs as $dir) {
				$dirs_list .= '<li><a href="#'.str_replace(' ', '_', addslashes(basename($dir))).'">'.basename($dir).'</a></li>';
			}
			$dirs_list .= '</ul></div>';
			echo $dirs_list;
			
			foreach ($dirs as $dir) {
				$files = array_filter(glob($dir . '/*'), 'is_file');
				$files_count = count($files);
				
				echo '<section id="'.str_replace(' ', '_', addslashes(basename($dir))).'" class="gallery-list"><h2>'.basename($dir).' ('.$files_count.' photos)</h2>';
				if ($files_count == 0) {
					echo '<a class="card-shadow media-card fl media-card-red"><h1>Gallery is empty</h1></a>';
				} else {
					$j = 0;
					for ($i = 0; $i < $files_count; $i++) {
						$path = site_url() . $files[$i];
						$name = preg_replace("/\.[^$]*/", "", basename($files[$i]));
						echo '<a href="' . $path . '" class="card-shadow media-card media-card-dbl fl media-card-red" style="background: url(\''.$path.'\')no-repeat;background-size:cover;"><div class="label"><span>'.$name.'</span></div></a>';
						$j++;
						if ($j >= 2) {
							echo '<div class="clearfix"></div>';
							$j = 0;
						}
					}
				}
				echo '<div class="clearfix"></div></section>';
			}
		}
		?>
		</div>
	</div>
    <div class="clearfix"></div>
</div>