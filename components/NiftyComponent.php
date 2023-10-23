<?php
namespace app\components;

use Yii;
use yii\helpers\Url;
use yii\base\Component;
use yii\helpers\Html;
use yii\widgets\Menu;
use app\models\user\User;
use app\models\Esys;

class NiftyComponent extends Component{
	private $menuItems;

    public function __construct($config = [])
    {
        // ... initialization before configuration is applied

        parent::__construct($config);
    }


     public function init()
    {
        parent::init();

		if(!isset(Yii::$app->user->identity)){
			$this->menuItems[] = ['label' =>  Yii::$app->name , 'options' => ['class' => 'list-header']];
			$this->menuItems[] = ['label' => '<i class="fa fa-lock"></i><span class="menu-title">Iniciar sesión </span>', 'url' => ['/admin/user/login']];
			$this->menuItems[] = ['label' => '<i class="fa fa-lock"></i><span class="menu-title">¿Olvidaste tu contraseña?</span>', 'url' => ['/admin/user/request-password-reset']];

		}else{
			/*****************************
			* CRM
			*****************************/
				$crm = [];

				if(Yii::$app->user->can('padreTutorView'))
					$crm[] = ['label' => '<i class="fa fa-vcard"></i><span class="menu-title">Padres / Tutores</span>', 'url' => ['/crm/cliente/index']];

				$alumno = [];

				if(Yii::$app->user->can('alumnosView'))
					$alumno[] = ['label' => '<i class="fa fa-users"></i><span class="menu-title">Alumno</span>', 'url' => ['/alumnos/alumno/index']];


				$operacion = [];

				if(Yii::$app->user->can('listaView'))
					$operacion[] = ['label' => '<i class="fa fa-calendar-check-o"></i><span class="menu-title">Lista</span>', 'url' => ['/gestion/lista/index']];

				if(Yii::$app->user->can('cajaView'))
					$operacion[] = ['label' => '<i class="fa fa-check-circle"></i><span class="menu-title">Cobro / Caja</span>', 'url' => ['/gestion/caja/index']];

				if(Yii::$app->user->can('calendario'))
					$operacion[] = ['label' => '<i class="fa fa-calendar"></i><span class="menu-title">Agenda</span>', 'url' => ['/calendario/agenda/recordatorio']];

				if(Yii::$app->user->can('articuloView'))
				$operacion[] = ['label' => '<i class="fa fa-cart-plus"></i><span class="menu-title">Articulos</span>', 'url' => ['/gestion/articulo/index']];

				if(Yii::$app->user->can('articuloView'))
				$operacion[] = ['label' => '<i class="fa fa-edit"></i><span class="menu-title">Ciclo Escolar</span>', 'url' => ['/gestion/ciclo/index']];


/*
				$documento = [];

				if(Yii::$app->user->can('documentoView'))
					$documento[] = ['label' => '<i class="fa fa-book"></i><span class="menu-title">Documentos</span>', 'url' => ['/gestion/documento/index']];
*/

			/*****************************
			* Sucursal
			*****************************/

			/*
			$sucursal = [];

				if(Yii::$app->user->can('sucursalView'))
					$sucursal[] = ['label' => '<i class="fa fa-building"></i><span class="menu-title">Sucursal</span>', 'url' => ['/sucursales/sucursal/index']];
			*/


			/*****************************
			* Administración
			*****************************/
				$admin = [];

				if(Yii::$app->user->can('userView'))
					$admin[] = ['label' => '<i class="fa fa-users"></i><span class="menu-title">Usuarios internos</span>', 'url' => ['/admin/user/index']];


				$adminConfig = [];

				if(Yii::$app->user->can('perfilUserView'))
					$adminConfig[] = ['label' => '<span class="menu-title">Perfiles de usuarios</span>', 'url' => ['/admin/perfil/index']];

				if(Yii::$app->user->can('listaDesplegableView'))
					$adminConfig[] = ['label' => '<span class="menu-title">Listas desplegables</span>', 'url' => ['/admin/listas-desplegables/index']];

				if(Yii::$app->user->can('configuracionSitio'))
					$adminConfig[] = ['label' => '<span class="menu-title">Configuracion del sitio</span>', 'url' => ['/admin/configuracion/configuracion-update']];

				if(!empty($adminConfig))
					$admin[] = ['label' => '<i class="fa fa-cogs"></i><span class="menu-title">Configuraciones </span> <i class="arrow"></i>', 'url' => '#', 'items' => $adminConfig];


				$adminSistema = [];

				if(Yii::$app->user->can('historialAccesosUser'))
					$adminSistema[] = ['label' => '<span class="menu-title">Historial de accesos</span>', 'url' => ['/admin/historial-de-acceso/index']];

				if(!empty($adminSistema))
					$admin[] = ['label' => '<i class="fa fa-database"></i><span class="menu-title">Sistema</span> <i class="arrow"></i>', 'url' => '#', 'items' => $adminSistema];


			/*****************************
			* Menú Items
			*****************************/
				if(!empty($crm)){
					$this->menuItems[] = ['options' => ['class' => 'list-divider']];
					$this->menuItems[] = ['label' => 'Familia', 'options' => ['class' => 'list-header']];

					foreach ($crm as $key => $item) {
						$this->menuItems[] = $item;
					}
				}

				if(!empty($alumno)){
					$this->menuItems[] = ['options' => ['class' => 'list-divider']];
					$this->menuItems[] = ['label' => 'Alumnos', 'options' => ['class' => 'list-header']];

					foreach ($alumno as $key => $item) {
						$this->menuItems[] = $item;
					}
				}

				if(!empty($operacion)){
					$this->menuItems[] = ['options' => ['class' => 'list-divider']];
					$this->menuItems[] = ['label' => 'Gestión', 'options' => ['class' => 'list-header']];

					foreach ($operacion as $key => $item) {
						$this->menuItems[] = $item;
					}
				}

				/*if(!empty($documento)){
					$this->menuItems[] = ['options' => ['class' => 'list-divider']];
					$this->menuItems[] = ['label' => 'Documentos', 'options' => ['class' => 'list-header']];

					foreach ($documento as $key => $item) {
						$this->menuItems[] = $item;
					}
				}*/

				/*if(!empty($sucursal)){
					$this->menuItems[] = ['options' => ['class' => 'list-divider']];
					$this->menuItems[] = ['label' => 'Sucursal', 'options' => ['class' => 'list-header']];

					foreach ($sucursal as $key => $item) {
						$this->menuItems[] = $item;
					}
				}*/


				if(!empty($admin)){
					$this->menuItems[] = ['options' => ['class' => 'list-divider']];
					$this->menuItems[] = ['label' => 'Adminstración', 'options' => ['class' => 'list-header']];

					foreach ($admin as $key => $item) {
						$this->menuItems[] = $item;
					}
				}
		}
    }


	/*********************************
	/ Navigation Bar - Elements Template
	/********************************/
		public function get_notification_dropdown(){
			if(!isset(Yii::$app->user->identity))
				return false;

			ob_start();
			?>

			<?php

			return ob_get_clean();
		}

		public function get_mega_dropdown(){
			if(!isset(Yii::$app->user->identity))
				return false;

			ob_start();
			?>
			<li class="mega-dropdown">
				<a href="#" class="mega-dropdown-toggle">
					<i class="fa fa-th-large fa-lg"></i>
				</a>
				<div class="dropdown-menu mega-dropdown-menu">
					<div class="clearfix">
			<?php /* ?>
						<div class="col-sm-12 col-md-3">

							<!--Mega menu widget-->
							<div class="text-center bg-purple pad-all">
								<h3 class="text-thin mar-no">Weekend shopping</h3>
								<div class="pad-ver box-inline">
									<span class="icon-wrap icon-wrap-lg icon-circle bg-trans-light">
										<i class="fa fa-shopping-cart fa-4x"></i>
									</span>
								</div>
								<p class="pad-btm">
									Members get <span class="text-lg text-bold">50%</span> more points. Lorem ipsum dolor sit amet!
								</p>
								<a href="#" class="btn btn-purple">Learn More...</a>
							</div>

						</div>
						<div class="col-sm-4 col-md-3">

							<!--Mega menu list-->
							<ul class="list-unstyled">
								<li class="dropdown-header">Pages</li>
								<li><a href="#">Profile</a></li>
								<li><a href="#">Search Result</a></li>
								<li><a href="#">FAQ</a></li>
								<li><a href="#">Sreen Lock</a></li>
								<li><a href="#" class="disabled">Disabled</a></li>
								<li class="divider"></li>
								<li class="dropdown-header">Icons</li>
								<li><a href="#"><span class="pull-right badge badge-purple">479</span> Font Awesome</a></li>
								<li><a href="#">Skycons</a></li>
							</ul>

						</div>
						<div class="col-sm-4 col-md-3">

							<!--Mega menu list-->
							<ul class="list-unstyled">
								<li class="dropdown-header">Mailbox</li>
								<li><a href="#"><span class="pull-right label label-danger">Hot</span>Indox</a></li>
								<li><a href="#">Read Message</a></li>
								<li><a href="#">Compose</a></li>
								<li class="divider"></li>
								<li class="dropdown-header">Featured</li>
								<li><a href="#">Smart navigation</a></li>
								<li><a href="#"><span class="pull-right badge badge-success">6</span>Exclusive plugins</a></li>
								<li><a href="#">Lot of themes</a></li>
								<li><a href="#">Transition effects</a></li>
							</ul>

						</div>
						<div class="col-sm-4 col-md-3">

							<!--Mega menu list-->
							<ul class="list-unstyled">
								<li class="dropdown-header">Components</li>
								<li><a href="#">Tables</a></li>
								<li><a href="#">Charts</a></li>
								<li><a href="#">Forms</a></li>
								<li class="divider"></li>
								<li>
									<form role="form" class="form">
										<div class="form-group">
											<label class="dropdown-header" for="megamenu-input">Newsletter</label>
											<input id="megamenu-input" type="email" placeholder="Enter email" class="form-control">
										</div>
										<button class="btn btn-primary btn-block" type="submit">Submit</button>
									</form>
								</li>
							</ul>
						</div>
			*/ ?>
					</div>
				</div>
			</li>
			<?php

			return ob_get_clean();
		}

		public function get_language_selector(){
			ob_start();
			?>
			<!--Language selector-->
			<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
			<li class="dropdown">
				<a id="demo-lang-switch" class="lang-selector dropdown-toggle" href="#" data-toggle="dropdown">
					<span class="lang-selected">
						<img class="lang-flag" src="img/flags/united-kingdom.png" alt="English">
					</span>
				</a>

				<!--Language selector menu-->
				<ul class="head-list dropdown-menu">
					<li>
						<!--English-->
						<a href="#" class="active">
							<img class="lang-flag" src="img/flags/united-kingdom.png" alt="English">
							<span class="lang-id">EN</span>
							<span class="lang-name">English</span>
						</a>
					</li>
					<li>
						<!--France-->
						<a href="#">
							<img class="lang-flag" src="img/flags/france.png" alt="France">
							<span class="lang-id">FR</span>
							<span class="lang-name">Fran&ccedil;ais</span>
						</a>
					</li>
					<li>
						<!--Germany-->
						<a href="#">
							<img class="lang-flag" src="img/flags/germany.png" alt="Germany">
							<span class="lang-id">DE</span>
							<span class="lang-name">Deutsch</span>
						</a>
					</li>
					<li>
						<!--Italy-->
						<a href="#">
							<img class="lang-flag" src="img/flags/italy.png" alt="Italy">
							<span class="lang-id">IT</span>
							<span class="lang-name">Italiano</span>
						</a>
					</li>
					<li>
						<!--Spain-->
						<a href="#">
							<img class="lang-flag" src="img/flags/spain.png" alt="Spain">
							<span class="lang-id">ES</span>
							<span class="lang-name">Espa&ntilde;ol</span>
						</a>
					</li>
				</ul>
			</li>
			<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
			<!--End language selector-->
			<?php

			return ob_get_clean();
		}

		public function get_user_dropdown(){
			if(!isset(Yii::$app->user->identity))
				return false;

			ob_start();
			?>
            <li id="dropdown-user" class="dropdown">
                <a href="#" data-toggle="dropdown" class="dropdown-toggle text-right">
                    <span class="pull-right">
                        <i class="demo-pli-male ic-user"></i>
                    </span>
                    <div class="username hidden-xs"><?= Yii::$app->user->identity->email ?></div>
                </a>

                <div class="dropdown-menu dropdown-menu-md dropdown-menu-right panel-default">

                    <!-- User dropdown menu -->
                    <ul class="head-list">
						<li>
                            <?= Html::a('<i class="demo-pli-male icon-lg icon-fw"></i> Mi perfil', ['/admin/user/mi-perfil']) ?>
						</li>
						<li>
                            <?= Html::a('<i class="demo-psi-lock-2 icon-lg icon-fw"></i> Cambiar contraseña', ['/admin/user/change-password']) ?>
						</li>
						<li>
                            <?= Html::a('<i class="fa fa-code icon-fw"></i> Acerca de . . .', ['/site/about']) ?>
						</li>
                    </ul>

                    <!-- Dropdown footer -->
                    <div class="pad-all text-right">
	                    <?= Html::a('<i class="fa fa-sign-out fa-fw"></i> Cerrar sesión', ['/admin/user/logout'], [
	                        	'class' => 'btn btn-primary',
	                        	'data-method' => 'post'
	                        	]) ?>
                    </div>
                </div>
			</li>
			<?php

			return ob_get_clean();
		}

		public function get_aside(){
			if(!isset(Yii::$app->user->identity))
				return false;

			ob_start();
			?>
				<!--Nav tabs-->
				<!--================================-->
				<ul class="nav nav-tabs nav-justified">
					<li class="active">
						<a href="#demo-asd-tab-1" data-toggle="tab">
							<i class="demo-pli-speech-bubble-7"></i>
						</a>
					</li>
					<li>
						<a href="#demo-asd-tab-2" data-toggle="tab">
							<i class="demo-pli-information icon-fw"></i> Report
						</a>
					</li>
					<li>
						<a href="#demo-asd-tab-3" data-toggle="tab">
							<i class="demo-pli-wrench icon-fw"></i> Settings
						</a>
					</li>
				</ul>
				<!--================================-->
				<!--End nav tabs-->



				<!-- Tabs Content -->
				<!--================================-->
				<div class="tab-content">

					<!--First tab (Contact list)-->
					<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
					<div class="tab-pane fade in active" id="demo-asd-tab-1">
						<p class="pad-hor mar-top text-semibold text-main">
							<span class="pull-right badge badge-warning">3</span> Family
						</p>

						<!--Family-->
						<div class="list-group bg-trans">
							<a href="#" class="list-group-item">
								<div class="media-left pos-rel">
								<!--
									<img class="img-circle img-xs" src="img/profile-photos/2.png" alt="Profile Picture">
								-->
									<i class="badge badge-success badge-stat badge-icon pull-left"></i>
								</div>
								<div class="media-body">
									<p class="mar-no">Stephen Tran</p>
									<small class="text-muted">Availabe</small>
								</div>
							</a>
							<a href="#" class="list-group-item">
								<div class="media-left pos-rel">
								<!--
									<img class="img-circle img-xs" src="img/profile-photos/7.png" alt="Profile Picture">
								-->
								</div>
								<div class="media-body">
									<p class="mar-no">Brittany Meyer</p>
									<small class="text-muted">I think so</small>
								</div>
							</a>
							<a href="#" class="list-group-item">
								<div class="media-left pos-rel">
								<!--
									<img class="img-circle img-xs" src="img/profile-photos/1.png" alt="Profile Picture">
								-->
									<i class="badge badge-info badge-stat badge-icon pull-left"></i>
								</div>
								<div class="media-body">
									<p class="mar-no">Jack George</p>
									<small class="text-muted">Last Seen 2 hours ago</small>
								</div>
							</a>
							<a href="#" class="list-group-item">
								<div class="media-left pos-rel">
								<!--
									<img class="img-circle img-xs" src="img/profile-photos/4.png" alt="Profile Picture">
								-->
								</div>
								<div class="media-body">
									<p class="mar-no">Donald Brown</p>
									<small class="text-muted">Lorem ipsum dolor sit amet.</small>
								</div>
							</a>
							<a href="#" class="list-group-item">
								<div class="media-left pos-rel">
								<!--
									<img class="img-circle img-xs" src="img/profile-photos/8.png" alt="Profile Picture">
								-->
									<i class="badge badge-warning badge-stat badge-icon pull-left"></i>
								</div>
								<div class="media-body">
									<p class="mar-no">Betty Murphy</p>
									<small class="text-muted">Idle</small>
								</div>
							</a>
							<a href="#" class="list-group-item">
								<div class="media-left pos-rel">
								<!--
									<img class="img-circle img-xs" src="img/profile-photos/9.png" alt="Profile Picture">
								-->
									<i class="badge badge-danger badge-stat badge-icon pull-left"></i>
								</div>
								<div class="media-body">
									<p class="mar-no">Samantha Reid</p>
									<small class="text-muted">Offline</small>
								</div>
							</a>
						</div>

						<hr>
						<p class="pad-hor text-semibold text-main">
							<span class="pull-right badge badge-success">Offline</span> Friends
						</p>

						<!--Works-->
						<div class="list-group bg-trans">
							<a href="#" class="list-group-item">
								<span class="badge badge-purple badge-icon badge-fw pull-left"></span> Joey K. Greyson
							</a>
							<a href="#" class="list-group-item">
								<span class="badge badge-info badge-icon badge-fw pull-left"></span> Andrea Branden
							</a>
							<a href="#" class="list-group-item">
								<span class="badge badge-success badge-icon badge-fw pull-left"></span> Johny Juan
							</a>
							<a href="#" class="list-group-item">
								<span class="badge badge-danger badge-icon badge-fw pull-left"></span> Susan Sun
							</a>
						</div>


						<hr>
						<p class="pad-hor mar-top text-semibold text-main">News</p>

						<div class="pad-hor">
							<p class="text-muted">Lorem ipsum dolor sit amet, consectetuer
								<a data-title="45%" class="add-tooltip text-semibold" href="#">adipiscing elit</a>, sed diam nonummy nibh. Lorem ipsum dolor sit amet.
							</p>
							<small class="text-muted"><em>Last Update : Des 12, 2014</em></small>
						</div>


					</div>
					<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
					<!--End first tab (Contact list)-->


					<!--Second tab (Custom layout)-->
					<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
					<div class="tab-pane fade" id="demo-asd-tab-2">

						<!--Monthly billing-->
						<div class="pad-all">
							<p class="text-semibold text-main">Billing &amp; reports</p>
							<p class="text-muted">Get <strong>$5.00</strong> off your next bill by making sure your full payment reaches us before August 5, 2016.</p>
						</div>
						<hr class="new-section-xs">
						<div class="pad-all">
							<span class="text-semibold text-main">Amount Due On</span>
							<p class="text-muted text-sm">August 17, 2016</p>
							<p class="text-2x text-thin text-main">$83.09</p>
							<button class="btn btn-block btn-success mar-top">Pay Now</button>
						</div>


						<hr>

						<p class="pad-hor text-semibold text-main">Additional Actions</p>

						<!--Simple Menu-->
						<div class="list-group bg-trans">
							<a href="#" class="list-group-item"><i class="demo-pli-information icon-lg icon-fw"></i> Service Information</a>
							<a href="#" class="list-group-item"><i class="demo-pli-mine icon-lg icon-fw"></i> Usage Profile</a>
							<a href="#" class="list-group-item"><span class="label label-info pull-right">New</span><i class="demo-pli-credit-card-2 icon-lg icon-fw"></i> Payment Options</a>
							<a href="#" class="list-group-item"><i class="demo-pli-support icon-lg icon-fw"></i> Message Center</a>
						</div>


						<hr>

						<div class="text-center">
							<div><i class="demo-pli-old-telephone icon-3x"></i></div>
							Questions?
							<p class="text-lg text-semibold text-main"> (415) 234-53454 </p>
							<small><em>We are here 24/7</em></small>
						</div>
					</div>
					<!--End second tab (Custom layout)-->
					<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->


					<!--Third tab (Settings)-->
					<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
					<div class="tab-pane fade" id="demo-asd-tab-3">
						<ul class="list-group bg-trans">
							<li class="pad-top list-header">
								<p class="text-semibold text-main mar-no">Account Settings</p>
							</li>
							<li class="list-group-item">
								<div class="pull-right">
									<input class="toggle-switch" id="demo-switch-1" type="checkbox" checked>
									<label for="demo-switch-1"></label>
								</div>
								<p class="mar-no">Show my personal status</p>
								<small class="text-muted">Lorem ipsum dolor sit amet, consectetuer adipiscing elit.</small>
							</li>
							<li class="list-group-item">
								<div class="pull-right">
									<input class="toggle-switch" id="demo-switch-2" type="checkbox" checked>
									<label for="demo-switch-2"></label>
								</div>
								<p class="mar-no">Show offline contact</p>
								<small class="text-muted">Aenean commodo ligula eget dolor. Aenean massa.</small>
							</li>
							<li class="list-group-item">
								<div class="pull-right">
									<input class="toggle-switch" id="demo-switch-3" type="checkbox">
									<label for="demo-switch-3"></label>
								</div>
								<p class="mar-no">Invisible mode </p>
								<small class="text-muted">Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. </small>
							</li>
						</ul>


						<hr>

						<ul class="list-group pad-btm bg-trans">
							<li class="list-header"><p class="text-semibold text-main mar-no">Public Settings</p></li>
							<li class="list-group-item">
								<div class="pull-right">
									<input class="toggle-switch" id="demo-switch-4" type="checkbox" checked>
									<label for="demo-switch-4"></label>
								</div>
								Online status
							</li>
							<li class="list-group-item">
								<div class="pull-right">
									<input class="toggle-switch" id="demo-switch-5" type="checkbox" checked>
									<label for="demo-switch-5"></label>
								</div>
								Show offline contact
							</li>
							<li class="list-group-item">
								<div class="pull-right">
									<input class="toggle-switch" id="demo-switch-6" type="checkbox" checked>
									<label for="demo-switch-6"></label>
								</div>
								Show my device icon
							</li>
						</ul>



						<hr>

						<p class="pad-hor text-semibold text-main mar-no">Task Progress</p>
						<div class="pad-all">
							<p>Upgrade Progress</p>
							<div class="progress progress-sm">
								<div class="progress-bar progress-bar-success" style="width: 15%;"><span class="sr-only">15%</span></div>
							</div>
							<small class="text-muted">15% Completed</small>
						</div>
						<div class="pad-hor">
							<p>Database</p>
							<div class="progress progress-sm">
								<div class="progress-bar progress-bar-danger" style="width: 75%;"><span class="sr-only">75%</span></div>
							</div>
							<small class="text-muted">17/23 Database</small>
						</div>

					</div>
					<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
					<!--Third tab (Settings)-->
				</div>
			<?php

			return ob_get_clean();
		}


	/*********************************
	/ MAIN NAVIGATION - Elements Template
	/********************************/
		public function get_profile_widget(){
			if(!isset(Yii::$app->user->identity))
				return false;

			ob_start();
			?>
			<div id="mainnav-profile" class="mainnav-profile">
				<div class="profile-wrap text-center">
					<div class="pad-btm">
						<?= Html::img(User::getAvatar(), ["class" => "img-circle img-md img-border", "alt" => "IFNB"]) ?>
					</div>
					<a href="#profile-nav" class="box-block" data-toggle="collapse" aria-expanded="false">
						<span class="pull-right dropdown-toggle">
                            <i class="dropdown-caret"></i>
                        </span>
						<p class="mnp-name"><?= Yii::$app->user->identity->username ?></p>
						<span class="mnp-desc"><?= Yii::$app->user->identity->email ?></span>
					</a>
				</div>
				<div id="profile-nav" class="collapse list-group bg-trans">
					<?= Html::a('<i class="demo-pli-male icon-lg icon-fw"></i> Mi perfil', ['/admin/user/mi-perfil'], ['class' => 'list-group-item']) ?>
					<?= Html::a('<i class="demo-psi-lock-2 icon-lg icon-fw"></i> Cambiar contraseña', ['/admin/user/change-password'], ['class' => 'list-group-item']) ?>
					<?= Html::a('<i class="fa fa-sign-out fa-fw icon-lg"></i> Cerrar sesión', ['/admin/user/logout'], ['class' => 'list-group-item', 'data-method' => 'post']) ?>
				</div>
			</div>
			<?php

			return ob_get_clean();
		}

		public function get_shortcut_buttons(){
			ob_start();
			/*
			?>
			<div id="mainnav-shortcut">
				<ul class="list-unstyled">
					<?php if(Yii::$app->user->can('flexzoneAdmin') || Yii::$app->user->can('cafeteriaAdmin')): ?>
					<li class="col-xs-4" data-content="Usuarios internos">
						<?= Html::a('<i class="fa fa-users"></i>', ['/admin/user/index'], ["id" => "shortcut-usuarios", "class" => "shortcut-grid"]) ?>
					</li>
					<?php endif?>

					<?php if(Yii::$app->user->can('flexzoneComprasGastos') || Yii::$app->user->can('flexzoneVentas') || Yii::$app->user->can('flexzoneFacturacion')): ?>
					<li class="col-xs-4" data-content="Clientes">
						<?= Html::a('<i class="fa fa-child"></i>', ['/flexzone/cliente/index'], ["id" => "shortcut-clientes", "class" => "shortcut-grid"]) ?>
					</li>
					<?php endif?>

					<?php if(Yii::$app->user->can('flexzoneVentas')): ?>
					<li class="col-xs-4" data-content="Nueva ventas">
						<?= Html::a('<i class="fa fa-shopping-cart"></i>', ['/flexzone/venta/create'], ["id" => "shortcut-ventas", "class" => "shortcut-grid"]) ?>
					</li>
					<?php endif?>

					<?php if(Yii::$app->user->can('flexzoneAccesos')): ?>
					<li class="col-xs-4" data-content="Comprobar membresía">
						<?= Html::a('<i class="fa fa-credit-card"></i>', ['/flexzone/venta/comprobar-membresia'], ["id" => "shortcut-comprobar-membresia", "class" => "shortcut-grid"]) ?>
					</li>
					<?php endif?>
				</ul>
			</div>
			<?php
			*/

			return ob_get_clean();
		}

		public function get_menu(){
            return  Menu::widget([
                'options'         => ['class' => 'list-group', 'id' => 'mainnav-menu'],
                'encodeLabels'    => false,
                'activateParents' => true,
                'activeCssClass'  => 'active-sub active',
                'items'           => $this->menuItems == null? ['label' => '']: $this->menuItems,
            ]);
		}

		public function get_widget(){
			if(!Yii::$app->user->can('recursosServidor'))
				return false;

			ob_start();
			?>
			<div class="mainnav-widget">
				<div class="show-small">
					<a href="#" data-toggle="menu-widget" data-target="#wg-server">
						<i class="fa fa-desktop"></i>
					</a>
				</div>

				<div id="wg-server" class="hide-small mainnav-widget-content">
					<ul class="list-group">
						<li class="list-header pad-no pad-ver">Estado del servido</li>
						<li class="mar-btm">
							<span class="label label-primary pull-right label-cpu-use"></span>
							<p>Uso de CPU</p>
							<div class="progress progress-sm">
								<div class="progress-bar progress-bar-cpu progress-bar-primary">
									<span class="sr-only label-cpu-use"></span>
								</div>
							</div>
						</li>
						<li class="mar-btm">
							<span class="label label-purple label-mem-use pull-right"></span>
							<p>Uso de Memoria</p>
							<div class="progress progress-sm">
								<div class="progress-bar progress-bar-mem progress-bar-purple">
									<span class="sr-only label-mem-use"></span>
								</div>
							</div>
						</li>
					</ul>
				</div>
			</div>

			<script>
				$(document).ready(function(){
					var avg_url	  = '<?= Yii::getAlias('@web') ?>',
						avg_interval = <?= Yii::$app->params['settings']['avg_interval'] ?>;

					nifty_avg(avg_url, avg_interval);
				});
			</script>
			<?php

			return ob_get_clean();
		}

}
