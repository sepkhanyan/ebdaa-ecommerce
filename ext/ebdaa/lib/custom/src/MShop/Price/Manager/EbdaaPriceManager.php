<?php

namespace Aimeos\MShop\Price\Manager;

class EbdaaPriceManager extends Standard
{
   public function createItemBase(array $values = [], array $listItems = [], array $refItems = [], array $propItems = []): \Aimeos\MShop\Common\Item\Iface
   {
       parent::createItemBase($values, $listItems, $refItems, $propItems);

       return new \Aimeos\MShop\Price\Item\Ebdaa( $values, $listItems, $refItems, $propItems );

   }


   public function saveItem(\Aimeos\MShop\Price\Item\Iface $item, bool $fetch = true): \Aimeos\MShop\Price\Item\Iface
   {
       if( !$item->isModified() )
       {
           $item = $this->savePropertyItems( $item, 'price', $fetch );
           return $this->saveListItems( $item, 'price', $fetch );
       }

       $context = $this->getContext();

       $dbm = $context->getDatabaseManager();
       $dbname = $this->getResourceName();
       $conn = $dbm->acquire( $dbname );

       try
       {
           $id = $item->getId();
           $date = date( 'Y-m-d H:i:s' );
           $columns = $this->getObject()->getSaveAttributes();

           if( $id === null )
           {
               /** mshop/price/manager/standard/insert/mysql
                * Inserts a new price record into the database table
                *
                * @see mshop/price/manager/standard/insert/ansi
                */

               /** mshop/price/manager/standard/insert/ansi
                * Inserts a new price record into the database table
                *
                * Items with no ID yet (i.e. the ID is NULL) will be created in
                * the database and the newly created ID retrieved afterwards
                * using the "newid" SQL statement.
                *
                * The SQL statement must be a string suitable for being used as
                * prepared statement. It must include question marks for binding
                * the values from the price item to the statement before they are
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
                * @see mshop/price/manager/standard/update/ansi
                * @see mshop/price/manager/standard/newid/ansi
                * @see mshop/price/manager/standard/delete/ansi
                * @see mshop/price/manager/standard/search/ansi
                * @see mshop/price/manager/standard/count/ansi
                */
               $path = 'mshop/price/manager/standard/insert';
               $sql = $this->addSqlColumns( array_keys( $columns ), $this->getSqlConfig( $path ) );
           }
           else
           {
               /** mshop/price/manager/standard/update/mysql
                * Updates an existing price record in the database
                *
                * @see mshop/price/manager/standard/update/ansi
                */

               /** mshop/price/manager/standard/update/ansi
                * Updates an existing price record in the database
                *
                * Items which already have an ID (i.e. the ID is not NULL) will
                * be updated in the database.
                *
                * The SQL statement must be a string suitable for being used as
                * prepared statement. It must include question marks for binding
                * the values from the price item to the statement before they are
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
                * @see mshop/price/manager/standard/insert/ansi
                * @see mshop/price/manager/standard/newid/ansi
                * @see mshop/price/manager/standard/delete/ansi
                * @see mshop/price/manager/standard/search/ansi
                * @see mshop/price/manager/standard/count/ansi
                */
               $path = 'mshop/price/manager/standard/update';
               $sql = $this->addSqlColumns( array_keys( $columns ), $this->getSqlConfig( $path ), false );
           }

           $idx = 1;
           $stmt = $this->getCachedStatement( $conn, $path, $sql );

           foreach( $columns as $name => $entry ) {
               $stmt->bind( $idx++, $item->get( $name ), $entry->getInternalType() );
           }

           $stmt->bind( $idx++, $item->getType() );
           $stmt->bind( $idx++, $item->getCurrencyId() );
           $stmt->bind( $idx++, $item->getDomain() );
           $stmt->bind( $idx++, $item->getLabel() );
           $stmt->bind( $idx++, $item->getQuantity(), \Aimeos\MW\DB\Statement\Base::PARAM_INT );
           $stmt->bind( $idx++, $item->getValue() );
           $stmt->bind( $idx++, $item->getCosts() );
           $stmt->bind( $idx++, $item->getRebate() );
           $stmt->bind( $idx++, json_encode( $item->getTaxrates(), JSON_FORCE_OBJECT ) );
           $stmt->bind( $idx++, $item->getStatus(), \Aimeos\MW\DB\Statement\Base::PARAM_INT );
           $stmt->bind( $idx++, $date ); //mtime
           $stmt->bind( $idx++, $context->getEditor() );
           $stmt->bind( $idx++, $item->getCostPrice() );
           $stmt->bind( $idx++, $item->getCommission() );
           $stmt->bind( $idx++, $context->getLocale()->getSiteId() );

           if( $id !== null ) {
               $stmt->bind( $idx++, $id, \Aimeos\MW\DB\Statement\Base::PARAM_INT );
           } else {
               $stmt->bind( $idx++, $date ); //ctime
           }

           $stmt->execute()->finish();

           if( $id === null )
           {
               /** mshop/price/manager/standard/newid/mysql
                * Retrieves the ID generated by the database when inserting a new record
                *
                * @see mshop/price/manager/standard/newid/ansi
                */

               /** mshop/price/manager/standard/newid/ansi
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
                *  SELECT currval('seq_mpri_id')
                * For SQL Server:
                *  SELECT SCOPE_IDENTITY()
                * For Oracle:
                *  SELECT "seq_mpri_id".CURRVAL FROM DUAL
                *
                * There's no way to retrive the new ID by a SQL statements that
                * fits for most database servers as they implement their own
                * specific way.
                *
                * @param string SQL statement for retrieving the last inserted record ID
                * @since 2014.03
                * @category Developer
                * @see mshop/price/manager/standard/insert/ansi
                * @see mshop/price/manager/standard/update/ansi
                * @see mshop/price/manager/standard/delete/ansi
                * @see mshop/price/manager/standard/search/ansi
                * @see mshop/price/manager/standard/count/ansi
                */
               $path = 'mshop/price/manager/standard/newid';
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

       $item = $this->savePropertyItems( $item, 'price', $fetch );
       return $this->saveListItems( $item, 'price', $fetch );
   }
}
