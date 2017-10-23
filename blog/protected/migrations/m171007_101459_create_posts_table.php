<?php
class m171007_101459_create_posts_table extends CDbMigration
{
	public function up()
	{
		$this->createTable('posts', array(
			'id' => 'pk',
			'title' => 'string NOT NULL',
			'content' => 'text NOT NULL',
			'category_id' => 'integer',
			'status' => 'string NOT NULL',
			'pub_date' => 'date'
		));
	}

	public function down()
	{
		$this->dropTable('posts');
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
