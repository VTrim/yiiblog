<?php
class m171007_101053_create_categories_table extends CDbMigration
{
	public function up()
	{
		$this->createTable('categories', array(
			'id' => 'pk',
			'title' => 'string NOT NULL',
			'status' => 'string NOT NULL',
			'root' => 'integer UNSIGNED DEFAULT NULL',
			'lft' => 'integer UNSIGNED NOT NULL',
			'rgt' => 'integer UNSIGNED NOT NULL',
			'level' => 'integer UNSIGNED NOT NULL'
		));
	}

	public function down()
	{
		$this->dropTable('categories');
	}

	/*

	// Use safeUp/safeDown to do migration with transaction

	public function safeUp()
	{
	}

	public function safeDown()
	{
	}

	*/
}
