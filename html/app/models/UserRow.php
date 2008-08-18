<?php

class UserRow extends Zend_Db_Table_Row_Abstract {

	public function adjustColumn( $column, $type = 'increase' )
	{
		switch($type){
			case 'increase':
				$newTotal = ($this->_data[$column] + 1);
				break;
			case 'decrease':
				$newTotal = ($this->_data[$column] - 1);
				break;
		}

		$table = $this->getTable();
		$where = $table->getAdapter()->quoteInto( 'id = ?', $this->id );
		$params[$column] = $newTotal;

		$table->update( $params, $where );
	}

	public function checkStatus()
	{
		$message = false;

		switch ($this->status)
		{
			case 'pending':
				$message = 'Your account has not been confirmed, please click the e-mail you received from us do you need it <a href="/user/sendconfirmation/'.$this->name.'">resending?</a>';
				break;
			case 'banned':
				$message = 'Your account has been banned, you need to get in touch with us to find out why';
				break;
			case 'suspended':
				$message = 'Your account has been suspended, you should of been mailed the reason';
				break;
			case 'admin':
			case 'active':
				break;
		}

		return $message;
	}

}
