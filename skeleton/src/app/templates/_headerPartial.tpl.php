<?php $i18n = $this->app->i18n; ?>
    <header class="container">

        <?php if ($user->getUser()): ?>
            <div class="row">
                <div class="col-sm-8">
                    <ul class="nav">
                        <li class="nav-item">
                            <a class="nav-link disabled" href="#"><?php echo $i18n->trans('USER'); ?>: <strong><?php $view->printString($user->getUser()->getLogin()); ?></strong></a>
                        </li>
                        <?php if ($user->hasCredential('API')): ?>
                            <li class="nav-item btn-group">
                                <a class="nav-link dropdown-toggle nav-item" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <?php echo $i18n->trans('API'); ?>
                                </a>
                                <div class="dropdown-menu">
                                    <a class="nav-link" href="<?php echo $app->router->getUrl('getapidoc'); ?>"><?php echo $i18n->trans('APIDOC'); ?></a>
                                </div>
                            </li>
                        <?php endif; ?>
                        <?php if ($user->hasCredential('ADMIN')): ?>
                            <li class="nav-item btn-group">
                                <a class="nav-link dropdown-toggle nav-item" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <?php echo $i18n->trans('ADMIN'); ?>
                                </a>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item"
                                       href="<?php echo $app->router->getUrl('register'); ?>"><?php echo $i18n->trans('REGISTER'); ?></a>
                                    <a class="dropdown-item"
                                       href="<?php echo $app->router->getUrl('userCrudList'); ?>"><?php echo $i18n->trans('USERS'); ?></a>
                                </div>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo $app->router->getUrl('register'); ?>"><?php echo $i18n->trans('REGISTER'); ?></a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
                <div class="col-sm-4">
                    <ul class="nav">
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo $app->router->getUrl('logout'); ?>"><?php echo $i18n->trans('LOGOUT'); ?></a>
                        </li>
                    </ul>
                </div>
            </div>
        <?php  else: ?>
            <div class="row">
                <div class="col-sm-8"></div>
                <div class="col-sm-4">
                    <ul class="nav">
                        <li class="nav-item">
                            <a class="nav-link disabled" href="<?php echo $app->router->getUrl('login'); ?>"><?php echo $i18n->trans('LOGIN'); ?></a>
                        </li>
                    </ul>
                </div>
            </div>
        <?php endif ?>

    </header>
<?php
$flasher = new \app\core\Flasher($app);
$flashes = $flasher->getAll();
if (!empty($flashes)) {
    echo '<div class="container">';
    foreach ($flashes as $flash) {

        switch ($flash['lvl']) {
            case \App\Core\Flasher::LVL_ERROR:
                $class = 'alert-danger';
                break;
            case \App\Core\Flasher::LVL_ALERT:
                $class = 'alert-warning';
                break;
            case \App\Core\Flasher::LVL_NOTICE:
                $class = 'alert-success';
                break;
            default:
                $class = 'alert-secondary';
        }
        ?>
        <div class="alert <?php echo $class; ?> alert-dismissible fade show" role="alert">
            <strong></strong> <?php $view->printString($flash['msg']); ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <?php
    }
    echo '</div>';
}
