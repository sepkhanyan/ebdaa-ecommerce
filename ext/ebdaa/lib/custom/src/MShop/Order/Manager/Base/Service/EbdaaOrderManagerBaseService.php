<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Metaways Infosystems GmbH, 2011
 * @copyright Aimeos (aimeos.org), 2015-2020
 * @package MShop
 * @subpackage Order
 */


namespace Aimeos\MShop\Order\Manager\Base\Service;


/**
 * Default Manager Order service
 *
 * @package MShop
 * @subpackage Order
 */
class EbdaaOrderManagerBaseService extends Standard
{
    private $searchConfig = array(
        'order.base.service.id' => array(
            'code' => 'order.base.service.id',
            'internalcode' => 'mordbase."id"',
            'internaldeps' => array( 'LEFT JOIN "mshop_order_base_service" AS mordbase ON ( mordba."id" = mordbase."baseid" )' ),
            'label' => 'Service ID',
            'type' => 'integer',
            'internaltype' => \Aimeos\MW\DB\Statement\Base::PARAM_INT,
            'public' => false,
        ),
        'order.base.service.siteid' => array(
            'code' => 'order.base.service.siteid',
            'internalcode' => 'mordbase."siteid"',
            'label' => 'Service site ID',
            'type' => 'string',
            'internaltype' => \Aimeos\MW\DB\Statement\Base::PARAM_STR,
            'public' => false,
        ),
        'order.base.service.baseid' => array(
            'code' => 'order.base.service.baseid',
            'internalcode' => 'mordbase."baseid"',
            'label' => 'Order ID',
            'type' => 'integer',
            'internaltype' => \Aimeos\MW\DB\Statement\Base::PARAM_INT,
            'public' => false,
        ),
        'order.base.service.serviceid' => array(
            'code' => 'order.base.service.serviceid',
            'internalcode' => 'mordbase."servid"',
            'label' => 'Service original service ID',
            'type' => 'string',
            'internaltype' => \Aimeos\MW\DB\Statement\Base::PARAM_STR,
            'public' => false,
        ),
        'order.base.service.name' => array(
            'code' => 'order.base.service.name',
            'internalcode' => 'mordbase."name"',
            'label' => 'Service name',
            'type' => 'string',
            'internaltype' => \Aimeos\MW\DB\Statement\Base::PARAM_STR,
        ),
        'order.base.service.code' => array(
            'code' => 'order.base.service.code',
            'internalcode' => 'mordbase."code"',
            'label' => 'Service code',
            'type' => 'string',
            'internaltype' => \Aimeos\MW\DB\Statement\Base::PARAM_STR,
        ),
        'order.base.service.type' => array(
            'code' => 'order.base.service.type',
            'internalcode' => 'mordbase."type"',
            'label' => 'Service type',
            'type' => 'string',
            'internaltype' => \Aimeos\MW\DB\Statement\Base::PARAM_STR,
        ),
        'order.base.service.currencyid' => array(
            'code' => 'order.base.service.currencyid',
            'internalcode' => 'mordbase."currencyid"',
            'label' => 'Service currencyid code',
            'type' => 'string',
            'internaltype' => \Aimeos\MW\DB\Statement\Base::PARAM_STR,
        ),
        'order.base.service.price' => array(
            'code' => 'order.base.service.price',
            'internalcode' => 'mordbase."price"',
            'label' => 'Service price',
            'type' => 'decimal',
            'internaltype' => \Aimeos\MW\DB\Statement\Base::PARAM_STR,
        ),
        'order.base.service.costs' => array(
            'code' => 'order.base.service.costs',
            'internalcode' => 'mordbase."costs"',
            'label' => 'Service shipping',
            'type' => 'decimal',
            'internaltype' => \Aimeos\MW\DB\Statement\Base::PARAM_STR,
        ),
        'order.base.service.rebate' => array(
            'code' => 'order.base.service.rebate',
            'internalcode' => 'mordbase."rebate"',
            'label' => 'Service rebate',
            'type' => 'decimal',
            'internaltype' => \Aimeos\MW\DB\Statement\Base::PARAM_STR,
        ),
        'order.base.service.taxrates' => array(
            'code' => 'order.base.service.taxrates',
            'internalcode' => 'mordbase."taxrate"',
            'label' => 'Service taxrates',
            'type' => 'decimal',
            'internaltype' => \Aimeos\MW\DB\Statement\Base::PARAM_STR,
        ),
        'order.base.service.taxvalue' => array(
            'code' => 'order.base.service.taxvalue',
            'internalcode' => 'mordbase."tax"',
            'label' => 'Service tax value',
            'type' => 'decimal',
            'internaltype' => \Aimeos\MW\DB\Statement\Base::PARAM_STR,
        ),
        'order.base.service.taxflag' => array(
            'code' => 'order.base.service.taxflag',
            'internalcode' => 'mordbase."taxflag"',
            'label' => 'Service tax flag (0=net, 1=gross)',
            'type' => 'integer',
            'internaltype' => \Aimeos\MW\DB\Statement\Base::PARAM_INT,
        ),
        'order.base.service.mediaurl' => array(
            'code' => 'order.base.service.mediaurl',
            'internalcode' => 'mordbase."mediaurl"',
            'label' => 'Service media url',
            'type' => 'string',
            'internaltype' => \Aimeos\MW\DB\Statement\Base::PARAM_STR,
            'public' => false,
        ),
        'order.base.service.position' => array(
            'code' => 'order.base.service.position',
            'internalcode' => 'mordbase."pos"',
            'label' => 'Service position',
            'type' => 'integer',
            'internaltype' => \Aimeos\MW\DB\Statement\Base::PARAM_INT,
            'public' => false,
        ),
        'order.base.service.ctime' => array(
            'code' => 'order.base.service.ctime',
            'internalcode' => 'mordbase."ctime"',
            'label' => 'Service create date/time',
            'type' => 'datetime',
            'internaltype' => \Aimeos\MW\DB\Statement\Base::PARAM_STR,
            'public' => false,
        ),
        'order.base.service.mtime' => array(
            'code' => 'order.base.service.mtime',
            'internalcode' => 'mordbase."mtime"',
            'label' => 'Service modify date/time',
            'type' => 'datetime',
            'internaltype' => \Aimeos\MW\DB\Statement\Base::PARAM_STR,
            'public' => false,
        ),
        'order.base.service.editor' => array(
            'code' => 'order.base.service.editor',
            'internalcode' => 'mordbase."editor"',
            'label' => 'Service editor',
            'type' => 'string',
            'internaltype' => \Aimeos\MW\DB\Statement\Base::PARAM_STR,
            'public' => false,
        ),
        'order.base.service.delivery.type' => array(
            'code' => 'order.base.service.delivery.type',
            'internalcode' => 'mordbase."delivery_type"',
            'label' => 'Delivery Type',
            'type' => 'string',
            'internaltype' => \Aimeos\MW\DB\Statement\Base::PARAM_STR,
            'public' => false,
        ),
    );

    public function getSearchAttributes( bool $withsub = true ) : array
    {
        /** mshop/order/manager/base/service/submanagers
         * List of manager names that can be instantiated by the order base service manager
         *
         * Managers provide a generic interface to the underlying storage.
         * Each manager has or can have sub-managers caring about particular
         * aspects. Each of these sub-managers can be instantiated by its
         * parent manager using the getSubManager() method.
         *
         * The search keys from sub-managers can be normally used in the
         * manager as well. It allows you to search for items of the manager
         * using the search keys of the sub-managers to further limit the
         * retrieved list of items.
         *
         * @param array List of sub-manager names
         * @since 2014.03
         * @category Developer
         */
        $path = 'mshop/order/manager/base/service/submanagers';

        return parent::getSearchAttributesBase( $this->searchConfig, $path, array( 'attribute' ), $withsub );
    }

    public function createItemBase(\Aimeos\MShop\Price\Item\Iface $price, array $values = [], array $attributes = [], ?\Aimeos\MShop\Service\Item\Iface $servItem = null): \Aimeos\MShop\Order\Item\Base\Service\Iface
    {
         parent::createItemBase($price, $values, $attributes, $servItem);

        return new \Aimeos\MShop\Order\Item\Base\Service\EbdaaOrderItemBaseService( $price, $values, $attributes, $servItem );
    }

    //override this method update final_costs and delivery type
    public function saveItem(\Aimeos\MShop\Order\Item\Base\Service\Iface $item, bool $fetch = true): \Aimeos\MShop\Order\Item\Base\Service\Iface
    {
        if( !$item->isModified() ) {
            return $item;
        }

        $deliveryTypesArray = ['Mr Delivery', 'Qpost', 'Vendor', 'Syaanh'];
        $deliveryType = $item->getDeliveryType();

        if($deliveryType && !in_array($deliveryType, $deliveryTypesArray) ){
            return $item;
        }

        $context = $this->getContext();

        $dbm = $context->getDatabaseManager();
        $dbname = $this->getResourceName();
        $conn = $dbm->acquire( $dbname );

        try
        {
            $id = $item->getId();
            $price = $item->getPrice();
            $date = date( 'Y-m-d H:i:s' );
            $columns = $this->getObject()->getSaveAttributes();

            if( $id === null )
            {
                /** mshop/order/manager/base/service/standard/insert/mysql
                 * Inserts a new order record into the database table
                 *
                 * @see mshop/order/manager/base/service/standard/insert/ansi
                 */

                /** mshop/order/manager/base/service/standard/insert/ansi
                 * Inserts a new order record into the database table
                 *
                 * Items with no ID yet (i.e. the ID is NULL) will be created in
                 * the database and the newly created ID retrieved afterwards
                 * using the "newid" SQL statement.
                 *
                 * The SQL statement must be a string suitable for being used as
                 * prepared statement. It must include question marks for binding
                 * the values from the order item to the statement before they are
                 * sent to the database server. The number of question marks must
                 * be the same as the number of columns listed in the INSERT
                 * statement. The order of the columns must correspond to the
                 * order in the saveItems() method, so the correct values are
                 * bound to the columns.
                 *
                 * The SQL statement should conform to the ANSI standard to be
                 * compatible with most relational database systems. This also
                 * includes using double quotes for table and column names.
                 *
                 * @param string SQL statement for inserting records
                 * @since 2014.03
                 * @category Developer
                 * @see mshop/order/manager/base/service/standard/update/ansi
                 * @see mshop/order/manager/base/service/standard/newid/ansi
                 * @see mshop/order/manager/base/service/standard/delete/ansi
                 * @see mshop/order/manager/base/service/standard/search/ansi
                 * @see mshop/order/manager/base/service/standard/count/ansi
                 */
                $path = 'mshop/order/manager/base/service/standard/insert';
                $sql = $this->addSqlColumns( array_keys( $columns ), $this->getSqlConfig( $path ) );
            }
            else
            {
                /** mshop/order/manager/base/service/standard/update/mysql
                 * Updates an existing order record in the database
                 *
                 * @see mshop/order/manager/base/service/standard/update/ansi
                 */

                /** mshop/order/manager/base/service/standard/update/ansi
                 * Updates an existing order record in the database
                 *
                 * Items which already have an ID (i.e. the ID is not NULL) will
                 * be updated in the database.
                 *
                 * The SQL statement must be a string suitable for being used as
                 * prepared statement. It must include question marks for binding
                 * the values from the order item to the statement before they are
                 * sent to the database server. The order of the columns must
                 * correspond to the order in the saveItems() method, so the
                 * correct values are bound to the columns.
                 *
                 * The SQL statement should conform to the ANSI standard to be
                 * compatible with most relational database systems. This also
                 * includes using double quotes for table and column names.
                 *
                 * @param string SQL statement for updating records
                 * @since 2014.03
                 * @category Developer
                 * @see mshop/order/manager/base/service/standard/insert/ansi
                 * @see mshop/order/manager/base/service/standard/newid/ansi
                 * @see mshop/order/manager/base/service/standard/delete/ansi
                 * @see mshop/order/manager/base/service/standard/search/ansi
                 * @see mshop/order/manager/base/service/standard/count/ansi
                 */
                $path = 'mshop/order/manager/base/service/standard/update';
                $sql = $this->addSqlColumns( array_keys( $columns ), $this->getSqlConfig( $path ), false );
            }
            $idx = 1;
            $stmt = $this->getCachedStatement( $conn, $path, $sql );
            foreach( $columns as $name => $entry ) {
                $stmt->bind( $idx++, $item->get( $name ), $entry->getInternalType() );
            }

            $stmt->bind( $idx++, $item->getBaseId(), \Aimeos\MW\DB\Statement\Base::PARAM_INT );
            $stmt->bind( $idx++, $item->getServiceId() );
            $stmt->bind( $idx++, $item->getType() );
            $stmt->bind( $idx++, $item->getCode() );
            $stmt->bind( $idx++, $item->getName() );
            $stmt->bind( $idx++, $item->getMediaUrl() );
            $stmt->bind( $idx++, $price->getCurrencyId() );
            $stmt->bind( $idx++, $price->getValue() );
            $stmt->bind( $idx++, $price->getCosts() );
            $stmt->bind( $idx++, $price->getRebate() );
            $stmt->bind( $idx++, $price->getTaxValue() );
            $stmt->bind( $idx++, json_encode( $price->getTaxRates(), JSON_FORCE_OBJECT ) );
            $stmt->bind( $idx++, $price->getTaxFlag(), \Aimeos\MW\DB\Statement\Base::PARAM_INT );
            $stmt->bind( $idx++, (int) $item->getPosition(), \Aimeos\MW\DB\Statement\Base::PARAM_INT );
            $stmt->bind( $idx++, $date ); // mtime
            $stmt->bind( $idx++, $context->getEditor() );
            // final_costs and delivery_type only updated manually
            if($id !== null){
                $stmt->bind( $idx++, $item->getFinalCosts() );
                $stmt->bind( $idx++, $item->getDeliveryType() );
            }
            $stmt->bind( $idx++, $item->getSiteId() );

            if( $id !== null ) {
                $stmt->bind( $idx++, $id, \Aimeos\MW\DB\Statement\Base::PARAM_INT );
            } else {
                $stmt->bind( $idx++, $date ); // ctime
            }

            $stmt->execute()->finish();

            if( $id === null && $fetch === true )
            {
                /** mshop/order/manager/base/service/standard/newid/mysql
                 * Retrieves the ID generated by the database when inserting a new record
                 *
                 * @see mshop/order/manager/base/service/standard/newid/ansi
                 */

                /** mshop/order/manager/base/service/standard/newid/ansi
                 * Retrieves the ID generated by the database when inserting a new record
                 *
                 * As soon as a new record is inserted into the database table,
                 * the database server generates a new and unique identifier for
                 * that record. This ID can be used for retrieving, updating and
                 * deleting that specific record from the table again.
                 *
                 * For MySQL:
                 *  SELECT LAST_INSERT_ID()
                 * For PostgreSQL:
                 *  SELECT currval('seq_mord_id')
                 * For SQL Server:
                 *  SELECT SCOPE_IDENTITY()
                 * For Oracle:
                 *  SELECT "seq_mord_id".CURRVAL FROM DUAL
                 *
                 * There's no way to retrive the new ID by a SQL statements that
                 * fits for most database servers as they implement their own
                 * specific way.
                 *
                 * @param string SQL statement for retrieving the last inserted record ID
                 * @since 2014.03
                 * @category Developer
                 * @see mshop/order/manager/base/service/standard/insert/ansi
                 * @see mshop/order/manager/base/service/standard/update/ansi
                 * @see mshop/order/manager/base/service/standard/delete/ansi
                 * @see mshop/order/manager/base/service/standard/search/ansi
                 * @see mshop/order/manager/base/service/standard/count/ansi
                 */
                $path = 'mshop/order/manager/base/service/standard/newid';
                $id = $this->newId( $conn, $path );
            }

            $item->setId( $id );

            $dbm->release( $conn, $dbname );
        }
        catch( \Exception $e )
        {
            $dbm->release( $conn, $dbname );
            throw $e;
        }

        return $item;
    }
}
