<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2016-2020
 */

$enc = $this->encoder();

$trans = array(
	'-1' => $this->translate( 'mshop/code', 'pay:-1' ),
	'0' => $this->translate( 'mshop/code', 'pay:0' ),
	'1' => $this->translate( 'mshop/code', 'pay:1' ),
	'2' => $this->translate( 'mshop/code', 'pay:2' ),
	'3' => $this->translate( 'mshop/code', 'pay:3' ),
	'4' => $this->translate( 'mshop/code', 'pay:4' ),
	'5' => $this->translate( 'mshop/code', 'pay:5' ),
	'6' => $this->translate( 'mshop/code', 'pay:6' ),
);

$deliveryStatusList = [
    '-1' => $this->translate('mshop/code', 'stat:-1'),
    '0' => $this->translate('mshop/code', 'stat:0'),
    '1' => $this->translate('mshop/code', 'stat:1'),
    '2' => $this->translate('mshop/code', 'stat:2'),
    '3' => $this->translate('mshop/code', 'stat:3'),
    '4' => $this->translate('mshop/code', 'stat:4'),
    '5' => $this->translate('mshop/code', 'stat:5'),
    '6' => $this->translate('mshop/code', 'stat:6'),
    '7' => $this->translate('mshop/code', 'stat:7'),
];

?>

<div class="order-countpaystatus card col-lg-6">
	<div id="order-countpaystatus-head" class="card-header header" role="tab"
		data-toggle="collapse" data-target="#order-countpaystatus-data"
		aria-expanded="true" aria-controls="order-countpaystatus-data">
		<div class="card-tools-left">
			<div class="btn btn-card-header act-show fa"></div>
		</div>
		<span class="item-label header-label">
			<?= $enc->html( $this->translate( 'admin', 'Orders by payment status' ) ); ?>
		</span>
	</div>
	<div id="order-countpaystatus-data" class="card-block collapse show content loading" role="tabpanel"
		aria-labelledby="order-countpaystatus-head" data-translation="<?= $enc->attr( $trans ); ?>">
	</div>
</div>

<div class="order-countpaystatus card col-lg-6">
    <div id="order-countdeliverystatus-head" class="card-header header" role="tab"
         data-toggle="collapse" data-target="#order-countdeliverystatus-data"
         aria-expanded="true" aria-controls="order-countdeliverystatus-data">
        <div class="card-tools-left">
            <div class="btn btn-card-header act-show fa"></div>
        </div>
        <span class="item-label header-label">
			<?= $enc->html( $this->translate( 'admin', 'Orders by Delivery status' ) ); ?>
		</span>
    </div>
    <div id="order-countdeliverystatus-data" class="card-block collapse show content loading" role="tabpanel"
         aria-labelledby="order-countdeliverystatus-head" data-translation="<?= $enc->attr( $deliveryStatusList ); ?>">
    </div>
</div>





<?= $this->get( 'orderpaymentstatusBody' ); ?>
