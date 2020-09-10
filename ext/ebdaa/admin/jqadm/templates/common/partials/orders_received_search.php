<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2020
 */

/**
 * Renders the table row with the search fields in the list view
 *
 * Available data:
 * - data: Associative list of keys (e.g. "product.id") and translated names (e.g. "ID")
 * - fields: List of columns that are currently shown
 * - filter: List of filter parameters
 * - group: Parameter group if several lists are on one page
 * - tabindex: Numerical index for tabbing through the fields and buttons
 */


$selected = function( $key, $code ) {
    return ( (string) $key === (string) $code ? 'selected="selected"' : '' );
};


$group = (array) $this->get( 'group', [] );
$filter = $this->get( 'filter', [] );
$fields = $this->get( 'fields', [] );
$idx = 0;

$enc = $this->encoder();
?>
<tr class="list-search">
    <td>
        <input class="form-control" type="text" tabindex="" name="invoice" value="">
    </td>
    <td>
        <input class="form-control" type="text" tabindex="" name="baseid" value="">
    </td>
    <td>
        <select class="form-control custom-select" name="statuspayment">
            <option value=""><?= $enc->attr( $this->translate( 'admin', 'All' ) ); ?></option>
            <?php foreach( $this->get('data',[])['order.statuspayment']['val'] as $val => $name ) : ?>
                <option value="<?= $enc->attr( $val ); ?>" >
                    <?= $enc->html( $name ); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </td>
    <td>
        <select class="form-control custom-select" name="statusdelivery">
            <option value=""><?= $enc->attr( $this->translate( 'admin', 'All' ) ); ?></option>
            <?php foreach( $this->get('data',[])['order.statusdelivery']['val'] as $val => $name ) : ?>
                <option value="<?= $enc->attr( $val ); ?>" >
                    <?= $enc->html( $name ); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </td>
    <td>
        <input class="form-control" type="date" tabindex="" name="cdate" value="">
    </td>
    <td>
        <input class="form-control" type="date" tabindex="" name="end_date" value="">
    </td>
    <td>
        <input class="form-control" type="text" tabindex="" name="sitecode" value="">
    </td>
    <td>
        <input class="form-control" type="text" tabindex="" name="lastname" value="">
    </td>

    <td class="actions">
        <span class="btn act-search fa orders_received" tabindex="<?= $this->get( 'tabindex' ); ?>"
              title="<?= $enc->attr( $this->translate( 'admin', 'Search' ) ); ?>"
              aria-label="<?= $enc->attr( $this->translate( 'admin', 'Search' ) ); ?>">
        </span>
        <a class="btn act-reset fa" href="#" tabindex="<?= $this->get( 'tabindex' ); ?>"
           title="<?= $enc->attr( $this->translate( 'admin', 'Reset' ) ); ?>"
           aria-label="<?= $enc->attr( $this->translate( 'admin', 'Reset' ) ); ?>"></a>
    </td>
</tr>
