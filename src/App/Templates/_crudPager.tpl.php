<?php
require_once __DIR__.'/../Helper/HtmlHelper.php';
$i18n = $this->app->i18n;
if ($filter['all_pages'] > 1) :
    $plusMinusPages = 10;
    $curPage = $filter['page']; ?>
    <div class="container">
        <nav aria-label="pager">
            <ul class="pagination justify-content-center">
                <?php if ($filter['page'] - $plusMinusPages > 1) { ?>
                    <li class="page-item"><a class="page-link" href="<?php
                        $filter['page'] = 1;
                        echo $app->router->getUrl($route_list, array('query' => $filter)); ?>">1</a></li>
                    <li class="page-item">
                        &nbsp;&nbsp;
                    </li>
                <?php } ?>

                <?php for ($page = 1; $filter['all_pages'] >= $page; $page++ ) {
                    if ($filter['page'] - $plusMinusPages > $page) {
                        continue;
                    }
                    if ($filter['page'] + $plusMinusPages < $page) {
                        continue;
                    }
                    ?>
                    <li class="page-item<?php echo ($page == $filter['page']) ?  ' active' : ''; ?>"><a class="page-link" href="<?php
                        $filter['page'] = $page;
                        echo $app->router->getUrl($route_list, array('query' => $filter)); ?>"><?php
                            echo $page; ?></a></li>
                <?php } ?>

                <?php if ($filter['page'] + $plusMinusPages < $filter['all_pages']) { ?>
                    <li class="page-item">
                        &nbsp;&nbsp;
                    </li>
                    <li class="page-item"><a class="page-link" href="<?php
                        $filter['page'] = $filter['all_pages'];
                        echo $app->router->getUrl($route_list, array('query' => $filter)); ?>"><?php echo $pager['all_pages']; ?></a></li>
                <?php } ?>
            </ul>
        </nav>
    </div>
<?php endif;
