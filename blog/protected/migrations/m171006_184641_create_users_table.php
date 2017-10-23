<?php
class m171006_184641_create_users_table extends CDbMigration
{
	public function up()
	{
		$this->createTable('users', array(
			'id' => 'pk',
			'username' => 'string NOT NULL',
			'email' => 'string NOT NULL',
			'status' => 'integer',
			'role' => 'string NOT NULL',
			'password' => 'string NOT NULL',
			'password_salt' => 'string NOT NULL',
			'datetime_registration' => 'datetime',
		));
	}

	public function down()
	{
		$this->dropTable('users');
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