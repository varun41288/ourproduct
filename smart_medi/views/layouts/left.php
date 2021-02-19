<?php
use app\components\Helper;
?>
<aside class="main-sidebar">

    <section class="sidebar">

        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?= $directoryAsset ?>/img/user2-160x160.jpg" class="img-circle" alt="User Image"/>
            </div>
            <div class="pull-left info">
                <p><?php echo Yii::$app->user->identity->username; ?></p>

                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>

        <!-- search form -->
        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search..."/>
              <span class="input-group-btn">
                <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
            </div>
        </form>
        <!-- /.search form -->

        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu tree', 'data-widget'=> 'tree'],
                'items' => [
                ['label' => 'Dashboard', 'icon' => 'dashboard', 'url' => ['/site']],
                ['label' => 'Sales', 'icon' => ' fa-shopping-cart', 'url' => ['/invoices']],
				['label' => 'Products', 'icon' => ' fa-barcode', 'url' => ['/products']],
                ['label' => 'Purchases', 'icon' => ' fa-tags', 'url' => ['/purchases']],
                ['label' => 'Customers', 'icon' => ' fa-users', 'url' => ['/customers']],
                ['label' => 'Suppliers', 'icon' => ' fa-truck', 'url' => ['/suppliers']],
                ['label' => 'Job Sheets', 'icon' => ' fa-file-text-o', 'url' => ['/jobsheets'],'visible' => (Helper::modules_status('jobsheets')==true?1:0)],
                ['label' => 'Estimations', 'icon' => ' fa-file-text-o', 'url' => ['/estimations'],'visible' => (Helper::modules_status('estimations')==true?1:0)],
                ['label' => 'Reports', 'icon' => ' fa-file', 'url' => ['/reports']],
                //['label' => 'Job Sheets', 'icon' => ' fa-file-text-o', 'url' => ['/jobsheets']],
                //['label' => 'Delivery Challan', 'icon' => ' fa-file-text-o', 'url' => ['/delivery_challan']],
                //['label' => 'Shortcuts', 'icon' => ' fa-key', 'url' => ['/site/shortcuts']],
                
                ['label' => 'Settings', 'icon' => ' fa-gear', 'url' => ['/users/profile']],
                
                    [
						'label' => 'Users', 
						'visible' => (Yii::$app->user->id==1?1:0),
						//'options' => ['class' => 'header'],
						'icon' => 'users',
                        'url' => '#',
						 'items' => [
                            ['label' => 'Users', 'icon' => 'user', 'url' => ['/admin/user'],],
                            ['label' => 'Assignments', 'icon' => ' fa-bullhorn', 'url' => ['/admin'],],
                            ['label' => 'Routes', 'icon' => ' fa-sitemap', 'url' => ['/admin/route'],],
                            ['label' => 'Permissions', 'icon' => ' fa-shield', 'url' => ['/admin/permission'],],
                            ['label' => 'Role', 'icon' => ' fa-male', 'url' => ['/admin/role'],],
                            //~ [
                                //~ 'label' => 'Level One',
                                //~ 'icon' => 'circle-o',
                                //~ 'url' => '#',
                                //~ 'items' => [
                                    //~ ['label' => 'Level Two', 'icon' => 'circle-o', 'url' => '#',],
                                    //~ [
                                        //~ 'label' => 'Level Two',
                                        //~ 'icon' => 'circle-o',
                                        //~ 'url' => '#',
                                        //~ 'items' => [
                                            //~ ['label' => 'Level Three', 'icon' => 'circle-o', 'url' => '#',],
                                            //~ ['label' => 'Level Three', 'icon' => 'circle-o', 'url' => '#',],
                                        //~ ],
                                    //~ ],
                                //~ ],
                            //~ ],
                        ],
                    ],
                    ['label' => 'Modules', 'icon' => 'file-code-o', 'url' => ['/modules'],'visible' => (Yii::$app->user->id==1?1:0)],
                    ['label' => 'Gii', 'icon' => 'file-code-o', 'url' => ['/gii'],'visible' => (Yii::$app->user->id==1?1:0)],
					['label' => 'Database', 'icon' => ' fa-database', 'url' => ['/site/db'],'visible' => (Yii::$app->user->id==1?1:0)],
                    //['label' => 'Login', 'url' => ['site/login'], 'visible' => Yii::$app->user->isGuest],
                    
                ],
            ]
        ) ?>

    </section>

</aside>
