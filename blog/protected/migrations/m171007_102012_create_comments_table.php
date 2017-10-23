<?php
class m171007_102012_create_comments_table extends CDbMigration
{
	public function up()
	{
		$this->createTable('comments', array(
			'id' => 'pk',
			'post_id' => 'integer',
			'content' => 'text NOT NULL',
			'user_id' => 'integer',
			'date_created' => 'date',
			'status' => 'string NOT NULL'
		));
	}

	public function down()
	{
		$this->dropTable('comments');
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
