<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2020
 */


/**
 * Renders the pagination in the list view
 *
 * Available data:
 * - action: Action to use for generating URLs
 * - fragment: Name of the subpanel that should be shown by default
 * - group: Parameter group if several lists are on one page
 * - pageParams: Associative list of page parameters
 * - page: Current pagination parameters
 * - pos: Drop-down direction (top/bottom)
 * - tabindex: Numerical index for tabbing through the fields and buttons
 * - total: Total number available items
 */


$pgroup = function (array $params, $group) {
    if ($group != null) {
        return [$group => ['page' => $params]];
    }

    return ['page' => $params];
};

$fragment = (array)$this->get('fragment', []);
$total = min($this->get('total', 0), 10000);
$pOffset = $pLimit = $this->get('page', []);
$params = $this->get('pageParams', []);
$group = $this->get('group');

$offset = max($this->get('page/offset', 0), 0);
$limit = max($this->get('page/limit', 25), 1);

$first = ($offset > 0 ? 0 : null);
$prev = ($offset - $limit >= 0 ? $offset - $limit : null);
$next = ($offset + $limit < $total ? $offset + $limit : null);
$last = (floor(($total - 1) / $limit) * $limit > $offset ? floor(($total - 1) / $limit) * $limit : null);

$pageCurrent = floor($offset / $limit) + 1;
$pageTotal = ($total != 0 ? ceil($total / $limit) : 1);


if ($this->get('action') === 'get') {
    if (isset($params['id'])) {
        $target = $this->config('admin/jqadm/url/get/target');
        $controller = $this->config('admin/jqadm/url/get/controller', 'Jqadm');
        $action = $this->config('admin/jqadm/url/get/action', 'get');
        $config = $this->config('admin/jqadm/url/get/config', []);
    } else {
        $target = $this->config('admin/jqadm/url/create/target');
        $controller = $this->config('admin/jqadm/url/create/controller', 'Jqadm');
        $action = $this->config('admin/jqadm/url/create/action', 'create');
        $config = $this->config('admin/jqadm/url/create/config', []);
    }
} else {
    $target = $this->config('admin/jqadm/url/search/target');
    $controller = $this->config('admin/jqadm/url/search/controller', 'Jqadm');
    $action = $this->config('admin/jqadm/url/search/action', 'search');
    $config = $this->config('admin/jqadm/url/search/config', []);
}


$enc = $this->encoder();

?>
<?php if ($total > $limit || $offset > 0 || $this->get('pos', 'top') === 'bottom') : ?>
    <nav class="list-page">
        <ul class="page-offset pagination">
            <li class="page-item ">
                <a class="page-link" tabindex="<?= $this->get('tabindex', 1); ?>"
                   href="#order_received"
                   aria-label="<?= $enc->attr($this->translate('admin', 'vendor-orders-first')); ?>"
                   onclick="getVendorOrders($(this).attr('value'))"
                >
                    <span class="fa fa-fast-backward" aria-hidden="true"></span>
                </a>
            </li>
            <li class="page-item ">
                <a class="page-link" tabindex="<?= $this->get('tabindex', 1); ?>"
                   href="#order_received"
                   aria-label="<?= $enc->attr($this->translate('admin', 'vendor-orders-previous')); ?>"
                   onclick="getVendorOrders($(this).attr('value'))"
                >
                    <span class="fa fa-step-backward" aria-hidden="true"></span>
                </a>
            </li>
            <li class="page-item disabled">
                <a class="page-link" tabindex="<?= $this->get('tabindex', 1); ?>" href="#">
                    Page
                    <span class="current_page"><!--js code here--></span>
                    of
                    <span class="last_page"><!--js code here--></span>
                </a>
            </li>
            <li class="page-item ">
                <a class="page-link" tabindex="<?= $this->get('tabindex', 1); ?>"
                   href="#order_received"
                   aria-label="<?= $enc->attr($this->translate('admin', 'vendor-orders-next')); ?>"
                   onclick="getVendorOrders($(this).attr('value'))"
                >
                    <span class="fa fa-step-forward" aria-hidden="true"></span>
                </a>
            </li>
            <li class="page-item ">
                <a class="page-link" tabindex="<?= $this->get('tabindex', 1); ?>"
                   href="#order_received"
                   aria-label="<?= $enc->attr($this->translate('admin', 'vendor-orders-last')); ?>"
                   onclick="getVendorOrders($(this).attr('value'))"
                >
                    <span class="fa fa-fast-forward" aria-hidden="true"></span>
                </a>
            </li>
        </ul>
    </nav>
<?php endif; ?>
