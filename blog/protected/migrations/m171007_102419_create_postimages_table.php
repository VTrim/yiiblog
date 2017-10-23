<?php
class m171007_102419_create_postimages_table extends CDbMigration
{
	public function up()
	{
		$this->createTable('postimages', array(
			'id' => 'pk',
			'image' => 'string NOT NULL',
			'post_id' => 'integer'
		));
	}

	public function down()
	{
		$this->dropTable('postimages');
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
