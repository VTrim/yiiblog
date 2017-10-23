<?php

class RegistrationForm extends CFormModel
{
	public $username;
    public $email;
	public $password;
    public $repeat_password;
    public $captcha;

	public function rules()
	{
		return array(
			array('username, email, password, repeat_password, captcha', 'required'),
            array('username','unique', 'className' => 'User', 'attributeName' => 'username'),
            array('password', 'length', 'min'=>6),
            array('email', 'email'),
            array('email', 'unique', 'className' => 'User', 'attributeName' => 'email'),
            array('repeat_password', 'repeat_password'),
			array('captcha', 'captcha', 'allowEmpty'=>!CCaptcha::checkRequirements()),
		);
	}
    
    public function repeat_password()
    {
      if($this->password != $this->repeat_password)
      {
       $this->addError('repeat_password','Error repeat password');
      }
    }
    
}
