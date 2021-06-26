<div class="wrapper responsive">
<div class="profile-wrapper" itemprop="accountablePerson" itemscope="itemscope">
	<div class="profile" itemtype="http://schema.org/Person" itemscope="itemscope" itemprop="Person">
        <section class="profile-details">
            <div class="profile-primary" itemprop="name">
				<div class="profile-avatar">
					<div class="no-avatar"><?php echo $name{0} ?></div>
				</div>
				<div class="profile-name">
					<div class="profile-username"><?php echo $name ?></div>
					<div class="profile-realname"><?php echo $realname ?></div>
				</div>
			</div>
			<div class="profile-stats">
				<span class="profile-stat">
					<span class="profile-stat-property">User Role:</span>
					<span class="profile-stat-value"><?php echo ucwords($role) ?></span>
				</span>
			</div>
        </section>
        
		<div class="profile-nav">
			<a class="profile-nav-item<?php if ($mode == 'overview'){ echo ' active';} ?>" href="<?php echo site_url() ?>member/<?php echo $name ?>">Overview</a>
			<?php if ($role == 'admin' || $role == 'editor'): ?>
			<a class="profile-nav-item<?php if ($mode == 'blog-posts'){ echo ' active';} ?>" href="<?php echo site_url() ?>member/<?php echo $name ?>/blog-posts">Blog Posts</a>
			<?php endif; ?>
		</div>
		<div class="profile-main">
			<?php if ($mode === 'overview'): ?>
			<section>
				<div class="profile-bio" itemprop="description"><?php echo $bio ?></div>
			</section>
			<?php endif; ?>
			
			<?php if ($mode === 'topics'): ?>
			<section>
				<h2>Forum topics by this member</h2>
			</section>
			<?php endif; ?>
			
			<?php if ($mode === 'posts'): ?>
			<section>
				<h2>Forum posts by this member</h2>
			</section>
			<?php endif; ?>
			
			<?php if ($mode === 'blog-posts' && $role === 'admin' || $role === 'editor'): ?>
			<section>
				<h2 class="post-index profile-post-index">Blog posts by this author</h2>
				<?php if(!empty($posts)) {?>
				<ul class="post-list profile-post-list">
					<?php $i = 0; $len = count($posts);?>
					<?php foreach($posts as $p):?>
						<?php 
							if ($i == 0) {
								$class = 'item first';
							} 
							elseif ($i == $len - 1) {
								$class = 'item last';
							}
							else {
								$class = 'item';
							}
							$i++;		
						?>
					<li class="<?php echo $class;?>">
						<span class="profile-post-title"><a href="<?php echo $p->url?>"><?php echo $p->title ?></a></span>
						<span class="profile-post-tag"><span class="fa fa-tag"></span><span><?php echo $p->tag ?></span></span>
						<span class="profile-post-date"><i class="fa fa-fw fa-calendar-o"></i> <?php echo date('d F Y', $p->date)?></span>
						<div class="clearfix"></div>
					</li>
					<?php endforeach;?>
				</ul>
				<?php if (!empty($pagination['prev']) || !empty($pagination['next'])):?>
					<div class="pager">
						<?php if (!empty($pagination['prev'])):?>
							<span class="newer" >&laquo; <a href="?page=<?php echo $page-1?>" rel="prev">Newer</a></span>
						<?php endif;?>
						<?php if (!empty($pagination['next'])):?>
							<span class="older" ><a href="?page=<?php echo $page+1?>" rel="next">Older</a> &raquo;</span>
						<?php endif;?>
					</div>
				<?php endif;?>
				<?php } else { echo '<span>No posts found.</span>'; }?>
			</section>
			<?php endif; ?>
		</div>
		<div class="clearfix"></div>
	</div>
</div>
</div>