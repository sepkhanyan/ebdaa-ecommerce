<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Metaways Infosystems GmbH, 2011
 * @copyright Aimeos (aimeos.org), 2015-2020
 * @package MShop
 * @subpackage Product
 */


namespace Aimeos\MShop\Product\Manager;


/**
 * Default product manager.
 *
 * @package MShop
 * @subpackage Product
 */
class EbdaaProductManager extends Standard {


    public $searchConfig = array(
        'product.id' => array(
            'code' => 'product.id',
            'internalcode' => 'mpro."id"',
            'label' => 'ID',
            'type' => 'integer',
            'internaltype' => \Aimeos\MW\DB\Statement\Base::PARAM_INT,
        ),
        'product.siteid' => array(
            'code' => 'product.siteid',
            'internalcode' => 'mpro."siteid"',
            'label' => 'Site ID',
            'type' => 'string',
            'internaltype' => \Aimeos\MW\DB\Statement\Base::PARAM_STR,
            'public' => false,
        ),
        'product.type' => array(
            'code' => 'product.type',
            'internalcode' => 'mpro."type"',
            'label' => 'Type',
            'type' => 'string',
            'internaltype' => \Aimeos\MW\DB\Statement\Base::PARAM_STR,
        ),
        'product.label' => array(
            'code' => 'product.label',
            'internalcode' => 'mpro."label"',
            'label' => 'Label',
            'type' => 'string',
            'internaltype' => \Aimeos\MW\DB\Statement\Base::PARAM_STR,
        ),
        'product.code' => array(
            'code' => 'product.code',
            'internalcode' => 'mpro."code"',
            'label' => 'SKU',
            'type' => 'string',
            'internaltype' => \Aimeos\MW\DB\Statement\Base::PARAM_STR,
        ),
        'product.url' => array(
            'code' => 'product.url',
            'internalcode' => 'mpro."url"',
            'label' => 'URL segment',
            'type' => 'string',
            'internaltype' => \Aimeos\MW\DB\Statement\Base::PARAM_STR,
        ),
        'product.basket_add_count' => array(
            'code' => 'product.basket_add_count',
            'internalcode' => 'mpro."basket_add_count"',
            'label' => 'URL segment',
            'type' => 'integer',
            'internaltype' => \Aimeos\MW\DB\Statement\Base::PARAM_INT,
        ),
        'product.sold_count' => array(
            'code' => 'product.sold_count',
            'internalcode' => 'mpro."sold_count"',
            'label' => 'URL segment',
            'type' => 'integer',
            'internaltype' => \Aimeos\MW\DB\Statement\Base::PARAM_INT,
        ),
        'product.dataset' => array(
            'code' => 'product.dataset',
            'internalcode' => 'mpro."dataset"',
            'label' => 'Data set',
            'type' => 'string',
            'internaltype' => \Aimeos\MW\DB\Statement\Base::PARAM_STR,
        ),
        'product.datestart' => array(
            'code' => 'product.datestart',
            'internalcode' => 'mpro."start"',
            'label' => 'Start date/time',
            'type' => 'datetime',
            'internaltype' => \Aimeos\MW\DB\Statement\Base::PARAM_STR,
        ),
        'product.dateend' => array(
            'code' => 'product.dateend',
            'internalcode' => 'mpro."end"',
            'label' => 'End date/time',
            'type' => 'datetime',
            'internaltype' => \Aimeos\MW\DB\Statement\Base::PARAM_STR,
        ),
        'product.status' => array(
            'code' => 'product.status',
            'internalcode' => 'mpro."status"',
            'label' => 'Status',
            'type' => 'integer',
            'internaltype' => \Aimeos\MW\DB\Statement\Base::PARAM_INT,
        ),
        'product.scale' => array(
            'code' => 'product.scale',
            'internalcode' => 'mpro."scale"',
            'label' => 'Quantity scale',
            'type' => 'float',
            'internaltype' => \Aimeos\MW\DB\Statement\Base::PARAM_FLOAT,
        ),
        'product.config' => array(
            'code' => 'product.config',
            'internalcode' => 'mpro."config"',
            'label' => 'Config',
            'type' => 'string',
            'internaltype' => \Aimeos\MW\DB\Statement\Base::PARAM_STR,
            'public' => false,
        ),
        'product.target' => array(
            'code' => 'product.target',
            'internalcode' => 'mpro."target"',
            'label' => 'URL target',
            'type' => 'string',
            'internaltype' => \Aimeos\MW\DB\Statement\Base::PARAM_STR,
            'public' => false,
        ),
        'product.ctime' => array(
            'code' => 'product.ctime',
            'internalcode' => 'mpro."ctime"',
            'label' => 'Create date/time',
            'type' => 'datetime',
            'internaltype' => \Aimeos\MW\DB\Statement\Base::PARAM_STR,
            'public' => false,
        ),
        'product.mtime' => array(
            'code' => 'product.mtime',
            'internalcode' => 'mpro."mtime"',
            'label' => 'Modify date/time',
            'type' => 'datetime',
            'internaltype' => \Aimeos\MW\DB\Statement\Base::PARAM_STR,
            'public' => false,
        ),
        'product.editor' => array(
            'code' => 'product.editor',
            'internalcode' => 'mpro."editor"',
            'label' => 'Editor',
            'type' => 'string',
            'internaltype' => \Aimeos\MW\DB\Statement\Base::PARAM_STR,
            'public' => false,
        ),
        'product:has' => array(
            'code' => 'product:has()',
            'internalcode' => ':site AND :key AND mproli."id"',
            'internaldeps' => ['LEFT JOIN "mshop_product_list" AS mproli ON ( mproli."parentid" = mpro."id" )'],
            'label' => 'Product has list item, parameter(<domain>[,<list type>[,<reference ID>)]]',
            'type' => 'null',
            'internaltype' => 'null',
            'public' => false,
        ),
        'product:prop' => array(
            'code' => 'product:prop()',
            'internalcode' => ':site AND :key AND mpropr."id"',
            'internaldeps' => ['LEFT JOIN "mshop_product_property" AS mpropr ON ( mpropr."parentid" = mpro."id" )'],
            'label' => 'Product has property item, parameter(<property type>[,<language code>[,<property value>]])',
            'type' => 'null',
            'internaltype' => 'null',
            'public' => false,
        ),
        // 'product.pos' => array(
        //     'code' => 'product.pos',
        //     'internalcode' => 'mpro."pos"',
        //     'label' => 'position',
        //     'type' => 'integer',
        //     'internaltype' => \Aimeos\MW\DB\Statement\Base::PARAM_INT,
        // ),
    );

    public function __construct(\Aimeos\MShop\Context\Item\Iface $context)
    {
        parent::__construct($context);
        $level = \Aimeos\MShop\Locale\Manager\Base::SITE_ALL;
        $level = $context->getConfig()->get( 'mshop/product/manager/sitemode', $level );
        $this->searchConfig['product:has']['function'] = function( &$source, array $params ) use ( $level ) {
            array_walk_recursive( $params, function( &$v ) {
                $v = trim( $v, '\'' );
            } );
            $keys = [];
            $params[1] = isset( $params[1] ) ? $params[1] : '';
            $params[2] = isset( $params[2] ) ? $params[2] : '';
            foreach( (array) $params[1] as $type ) {
                foreach( (array) $params[2] as $id ) {
                    $keys[] = $params[0] . '|' . ( $type ? $type . '|' : '' ) . $id;
                }
            }
            $sitestr = $this->getSiteString( 'mproli."siteid"', $level );
            $keystr = $this->toExpression( 'mproli."key"', $keys, $params[2] !== '' ? '==' : '=~' );
            $source = str_replace( [':site', ':key'], [$sitestr, $keystr], $source );
            return $params;
        };
        $this->searchConfig['product:prop']['function'] = function( &$source, array $params ) use ( $level ) {
            array_walk_recursive( $params, function( &$v ) {
                $v = trim( $v, '\'' );
            } );
            $keys = [];
            $params[1] = array_key_exists( 1, $params ) ? $params[1] : '';
            $params[2] = isset( $params[2] ) ? $params[2] : '';
            foreach( (array) $params[1] as $lang ) {
                foreach( (array) $params[2] as $id ) {
                    $keys[] = $params[0] . '|' . ( $lang ? $lang . '|' : '' ) . ( $id !== '' ?  md5( $id ) : '' );
                }
            }
            $sitestr = $this->getSiteString( 'mpropr."siteid"', $level );
            $keystr = $this->toExpression( 'mpropr."key"', $keys, $params[2] !== '' ? '==' : '=~' );
            $source = str_replace( [':site', ':key'], [$sitestr, $keystr], $source );
            return $params;
        };
    }


    public function searchItems(\Aimeos\MW\Criteria\Iface $search, array $ref = [], int &$total = null): \Aimeos\Map
    {
        $map = [];
        $context = $this->getContext();

        $dbm = $context->getDatabaseManager();
        $dbname = $this->getResourceName();
        $conn = $dbm->acquire( $dbname );

        try
        {
            $required = array( 'product' );

            /** mshop/product/manager/sitemode
             * Mode how items from levels below or above in the site tree are handled
             *
             * By default, only items from the current site are fetched from the
             * storage. If the ai-sites extension is installed, you can create a
             * tree of sites. Then, this setting allows you to define for the
             * whole product domain if items from parent sites are inherited,
             * sites from child sites are aggregated or both.
             *
             * Available constants for the site mode are:
             * * 0 = only items from the current site
             * * 1 = inherit items from parent sites
             * * 2 = aggregate items from child sites
             * * 3 = inherit and aggregate items at the same time
             *
             * You also need to set the mode in the locale manager
             * (mshop/locale/manager/standard/sitelevel) to one of the constants.
             * If you set it to the same value, it will work as described but you
             * can also use different modes. For example, if inheritance and
             * aggregation is configured the locale manager but only inheritance
             * in the domain manager because aggregating items makes no sense in
             * this domain, then items wil be only inherited. Thus, you have full
             * control over inheritance and aggregation in each domain.
             *
             * @param int Constant from Aimeos\MShop\Locale\Manager\Base class
             * @category Developer
             * @since 2018.01
             * @see mshop/locale/manager/standard/sitelevel
             */
            $level = \Aimeos\MShop\Locale\Manager\Base::SITE_ALL;
            $level = $context->getConfig()->get( 'mshop/product/manager/sitemode', $level );

            /** mshop/product/manager/standard/search/mysql
             * Retrieves the records matched by the given criteria in the database
             *
             * @see mshop/product/manager/standard/search/ansi
             */

            /** mshop/product/manager/standard/search/ansi
             * Retrieves the records matched by the given criteria in the database
             *
             * Fetches the records matched by the given criteria from the product
             * database. The records must be from one of the sites that are
             * configured via the context item. If the current site is part of
             * a tree of sites, the SELECT statement can retrieve all records
             * from the current site and the complete sub-tree of sites.
             *
             * As the records can normally be limited by criteria from sub-managers,
             * their tables must be joined in the SQL context. This is done by
             * using the "internaldeps" property from the definition of the ID
             * column of the sub-managers. These internal dependencies specify
             * the JOIN between the tables and the used columns for joining. The
             * ":joins" placeholder is then replaced by the JOIN strings from
             * the sub-managers.
             *
             * To limit the records matched, conditions can be added to the given
             * criteria object. It can contain comparisons like column names that
             * must match specific values which can be combined by AND, OR or NOT
             * operators. The resulting string of SQL conditions replaces the
             * ":cond" placeholder before the statement is sent to the database
             * server.
             *
             * If the records that are retrieved should be ordered by one or more
             * columns, the generated string of column / sort direction pairs
             * replaces the ":order" placeholder. In case no ordering is required,
             * the complete ORDER BY part including the "\/*-orderby*\/...\/*orderby-*\/"
             * markers is removed to speed up retrieving the records. Columns of
             * sub-managers can also be used for ordering the result set but then
             * no index can be used.
             *
             * The number of returned records can be limited and can start at any
             * number between the begining and the end of the result set. For that
             * the ":size" and ":start" placeholders are replaced by the
             * corresponding values from the criteria object. The default values
             * are 0 for the start and 100 for the size value.
             *
             * The SQL statement should conform to the ANSI standard to be
             * compatible with most relational database systems. This also
             * includes using double quotes for table and column names.
             *
             * @param string SQL statement for searching items
             * @since 2014.03
             * @category Developer
             * @see mshop/product/manager/standard/insert/ansi
             * @see mshop/product/manager/standard/update/ansi
             * @see mshop/product/manager/standard/newid/ansi
             * @see mshop/product/manager/standard/delete/ansi
             * @see mshop/product/manager/standard/count/ansi
             */
            $cfgPathSearch = 'mshop/product/manager/standard/search';

            /** mshop/product/manager/standard/count/mysql
             * Counts the number of records matched by the given criteria in the database
             *
             * @see mshop/product/manager/standard/count/ansi
             */

            /** mshop/product/manager/standard/count/ansi
             * Counts the number of records matched by the given criteria in the database
             *
             * Counts all records matched by the given criteria from the product
             * database. The records must be from one of the sites that are
             * configured via the context item. If the current site is part of
             * a tree of sites, the statement can count all records from the
             * current site and the complete sub-tree of sites.
             *
             * As the records can normally be limited by criteria from sub-managers,
             * their tables must be joined in the SQL context. This is done by
             * using the "internaldeps" property from the definition of the ID
             * column of the sub-managers. These internal dependencies specify
             * the JOIN between the tables and the used columns for joining. The
             * ":joins" placeholder is then replaced by the JOIN strings from
             * the sub-managers.
             *
             * To limit the records matched, conditions can be added to the given
             * criteria object. It can contain comparisons like column names that
             * must match specific values which can be combined by AND, OR or NOT
             * operators. The resulting string of SQL conditions replaces the
             * ":cond" placeholder before the statement is sent to the database
             * server.
             *
             * Both, the strings for ":joins" and for ":cond" are the same as for
             * the "search" SQL statement.
             *
             * Contrary to the "search" statement, it doesn't return any records
             * but instead the number of records that have been found. As counting
             * thousands of records can be a long running task, the maximum number
             * of counted records is limited for performance reasons.
             *
             * The SQL statement should conform to the ANSI standard to be
             * compatible with most relational database systems. This also
             * includes using double quotes for table and column names.
             *
             * @param string SQL statement for counting items
             * @since 2014.03
             * @category Developer
             * @see mshop/product/manager/standard/insert/ansi
             * @see mshop/product/manager/standard/update/ansi
             * @see mshop/product/manager/standard/newid/ansi
             * @see mshop/product/manager/standard/delete/ansi
             * @see mshop/product/manager/standard/search/ansi
             */
            $cfgPathCount = 'mshop/product/manager/standard/count';

            $results = $this->searchItemsBase( $conn, $search, $cfgPathSearch, $cfgPathCount, $required, $total, $level );

            while( ( $row = $results->fetch() ) !== null )
            {
                if( ( $row['product.config'] = json_decode( $config = $row['product.config'], true ) ) === null )
                {
                    $msg = sprintf( 'Invalid JSON as result of search for ID "%2$s" in "%1$s": %3$s', 'mshop_product.config', $row['product.id'], $config );
                    $this->getContext()->getLogger()->log( $msg, \Aimeos\MW\Logger\Base::WARN );
                }

                $map[$row['product.id']] = $row;
            }

            $dbm->release( $conn, $dbname );
        }
        catch( \Exception $e )
        {
            $dbm->release( $conn, $dbname );
            throw $e;
        }


        $propItems = []; $name = 'product/property';
        if( isset( $ref[$name] ) || in_array( $name, $ref, true ) )
        {
            $propTypes = isset( $ref[$name] ) && is_array( $ref[$name] ) ? $ref[$name] : null;
            $propItems = $this->getPropertyItems( array_keys( $map ), 'product', $propTypes );
        }

        if( isset( $ref['catalog'] ) || in_array( 'catalog', $ref, true ) )
        {
            $domains = isset( $ref['catalog'] ) && is_array( $ref['catalog'] ) ? $ref['catalog'] : [];

            foreach( $this->getDomainRefItems( array_keys( $map ), 'catalog', $domains ) as $prodId => $list ) {
                $map[$prodId]['catalog'] = $list;
            }
        }

        if( isset( $ref['supplier'] ) || in_array( 'supplier', $ref, true ) )
        {
            $domains = isset( $ref['supplier'] ) && is_array( $ref['supplier'] ) ? $ref['supplier'] : [];

            foreach( $this->getDomainRefItems( array_keys( $map ), 'supplier', $domains ) as $prodId => $list ) {
                $map[$prodId]['supplier'] = $list;
            }
        }

        if( isset( $ref['stock'] ) || in_array( 'stock', $ref, true ) )
        {
            $codes = array_column( $map, 'product.id', 'product.code' );

            foreach( $this->getStockItems( array_keys( $codes ), $ref ) as $stockId => $stockItem )
            {
                if( isset( $codes[$stockItem->getProductCode()] ) ) {
                    $map[$codes[$stockItem->getProductCode()]]['stock'][$stockId] = $stockItem;
                }
            }
        }
        return $this->buildItems( $map, $ref, 'product', $propItems );
    }

    public function createItemBase(array $values = [], array $listItems = [], array $refItems = [], array $propertyItems = []): \Aimeos\MShop\Common\Item\Iface
    {
         parent::createItemBase($values, $listItems, $refItems, $propertyItems);

         return new \Aimeos\MShop\Product\Item\EbdaaProductItem( $values, $listItems, $refItems, $propertyItems );
    }

   public function getSearchAttributes(bool $withsub = true): array
   {
       parent::getSearchAttributes($withsub);

       $path = 'mshop/product/manager/submanagers';

       return $this->getSearchAttributesBase( $this->searchConfig, $path, [], $withsub );
   }
}
