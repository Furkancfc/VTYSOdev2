<aside class="main-sidebar sidebar-dark-primary elevation-4">
	<div class="dropdown">
		<a href="./index.php?page=anasayfa" class="brand-link">
			<?php if ($_SESSION['login_type'] == 1): ?>
			<h3 class="text-center p-0 m-0"><b>Yönetici</b></h3>
			<?php elseif($_SESSION['login_type'] == 2): ?>
			<h3 class="text-center p-0 m-0"><b>Proje Yöneticisi</b></h3>
			<?php else: ?>
			<h3 class='text-center p-0 m-0'><b>Kullanıcı</b></h3>
			<?php endif; ?>

		</a>

	</div>
	<div class="sidebar pb-4 mb-4">
		<nav class="mt-2">
			<ul class="nav nav-pills nav-sidebar flex-column nav-flat" data-widget="treeview" role="menu"
				data-accordion="false">
				<li class="nav-item">
					<a href="./index.php?page=anasayfa" class="nav-link">
						<i class="nav-icon fas fa-tachometer-alt"></i>
						<p>
							Ana Sayfa
						</p>
					</a>
				</li>
				<li class="nav-item">
					<a href="./index.php?page=proje_listesi" class="nav-link nav-edit_project nav-view_project">
						<i class="nav-icon fas fa-layer-group"></i>
						<p>
							Projeler
						</p>
					</a>
					<!-- 					<ul class="nav nav-treeview">
						<?php if ($_SESSION['login_type'] != 3): ?>
						<li class="nav-item">
							<a href="./index.php?page=new_project" class="nav-link nav-new_project tree-item">
								<i class="fas fa-angle-right nav-icon"></i>
								<p>Ekle</p>
							</a>
						</li>
						<?php endif; ?>
						<li class="nav-item">
							<a href="./index.php?page=proje_listesi" class="nav-link nav-project_list tree-item">
								<i class="fas fa-angle-right nav-icon"></i>
								<p>Listele</p>
							</a>
						</li>
					</ul>
 -->
				</li>
				<li class="nav-item">
					<a href="./index.php?page=gorev_listesi" class="nav-link nav-task_list">
						<i class="fas fa-tasks nav-icon"></i>
						<p>Kullanıcı Görevleri</p>
					</a>
				</li>
				<?php if ($_SESSION['login_type'] == 1): ?>
				<li class="nav-item">
					<a href="./index.php?page=kullanici_listesi" class="nav-link nav-edit_user">
						<i class="nav-icon fas fa-users"></i>
						<p>
							Kullanıcılar
							<!-- <i class="right fas fa-angle-left"></i> -->
						</p>
					</a>
					<!-- <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="./index.php?page=new_user" class="nav-link nav-new_user tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Ekle</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="./index.php?page=user_list" class="nav-link nav-user_list tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Listele</p>
                </a>
              </li>
            </ul>
           -->
				</li>
				<?php endif; ?>
			</ul>
		</nav>
	</div>
</aside>
<script>
$(document).ready(function() {
	var page = '<?php echo isset($_GET['page']) ? $_GET['page'] : 'home' ?>';
	var s = '<?php echo isset($_GET['s']) ? $_GET['s'] : '' ?>';
	if (s != '')
		page = page + '_' + s;
	if ($('.nav-link.nav-' + page).length > 0) {
		$('.nav-link.nav-' + page).addClass('active')
		if ($('.nav-link.nav-' + page).hasClass('tree-item') == true) {
			$('.nav-link.nav-' + page).closest('.nav-treeview').siblings('a').addClass('active')
			$('.nav-link.nav-' + page).closest('.nav-treeview').parent().addClass('menu-open')
		}
		if ($('.nav-link.nav-' + page).hasClass('nav-is-tree') == true) {
			$('.nav-link.nav-' + page).parent().addClass('menu-open')
		}

	}

})
</script>