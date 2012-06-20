<?php

$this->startSetup();

$table = $this->getConnection()->newTable($this->getTable('osmm/project'))
							   ->addColumn('project_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array('identity' => true, 'primary' => true, 'unsigned'  => true, 'nullable' => false))
							   ->addColumn('project_name', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array('nullable' => false));
$this->getConnection()->createTable($table)
							   ->addColumn('project_status', Varien_Db_Ddl_Table::TYPE_TINYINT, 1, array('nullable' => false, 'default' => 1, 'unsigned' => true));
$this->getConnection()->createTable($table);

$table = $this->getConnection()->newTable($this->getTable('osmm/project_to_query'))
							   ->addColumn('project_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array('unsigned'  => true, 'nullable' => false))
							   ->addColumn('query_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array('unsigned'  => true, 'nullable' => false))
							   ->addIndex($this->getIdxName('osmm/project_to_query', array('project_id', 'query_id')), array('project_id', 'query_id'));
$this->getConnection()->createTable($table);

$table = $this->getConnection()->newTable($this->getTable('osmm/query'))
							   ->addColumn('query_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array('identity' => true, 'primary' => true, 'unsigned'  => true, 'nullable' => false))
							   ->addColumn('query_q', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array('nullable' => false))
							   ->addColumn('query_lang', Varien_Db_Ddl_Table::TYPE_CHAR, 2, array('nullable' => false))
							   ->addColumn('query_geocode', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array('nullable' => false))
                               ->addColumn('query_last_twitter', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array('nullable' => false))
                               ->addColumn('query_last_facebook', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array('nullable' => false));
$this->getConnection()->createTable($table);

$table = $this->getConnection()->newTable($this->getTable('osmm/search'))
							   ->addColumn('search_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array('identity' => true, 'primary' => true, 'unsigned'  => true, 'nullable' => false))
							   ->addColumn('query_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array('unsigned' => true, 'nullable' => false))
							   ->addColumn('search_outer_id', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array('nullable' => false))
							   ->addColumn('search_source', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array('nullable' => false))
							   ->addColumn('search_published', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array('unsigned' => true, 'nullable' => false))
							   ->addColumn('search_title', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array('nullable' => false))
							   ->addColumn('search_content', Varien_Db_Ddl_Table::TYPE_TEXT, null, array('nullable' => false))
							   ->addColumn('search_author_name', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array('nullable' => false))
							   ->addColumn('search_author_image', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array('nullable' => false));
$this->getConnection()->createTable($table);

$table = $this->getConnection()->newTable($this->getTable('osmm/search_index'))
							   ->addColumn('query_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array('unsigned'  => true, 'nullable' => false))
							   ->addColumn('index_date', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array('unsigned'  => true, 'nullable' => false))
							   ->addColumn('index_source', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array('nullable' => false))
							   ->addColumn('index_count', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array('unsigned'  => true, 'nullable' => false))
							   ->addIndex($this->getIdxName('osmm/search_index', array('query_id', 'index_date', 'index_source')), array('query_id', 'index_date', 'index_source'));
$this->getConnection()->createTable($table);

$table = $this->getConnection()->newTable($this->getTable('osmm/search_influencers'))
							   ->addColumn('query_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array('unsigned'  => true, 'nullable' => false))
							   ->addColumn('search_author_name', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array('nullable' => false))
							   ->addColumn('search_author_uri', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array('nullable' => false))
							   ->addColumn('cnt', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array('unsigned'  => true, 'nullable' => false))
                               ->addColumn('search_source', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array('unsigned'  => true, 'nullable' => false))
                               ->addIndex($this->getIdxName('osmm/search_influencers', array('query_id', 'search_author_name', 'search_source')), array('query_id', 'search_author_name', 'search_source'));
$this->getConnection()->createTable($table);

$this->endSetup();