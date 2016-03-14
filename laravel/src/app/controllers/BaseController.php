<?php
use App\utils\LoggerFactory;

class BaseController extends Controller {

	/**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */
	protected function setupLayout()
	{
		if ( ! is_null($this->layout))
		{
			$this->layout = View::make($this->layout);
		}
	}
	public function LogQuery($sql){
		//$this -> LogMessage('Executing Query', $sql);
	}

	public function LogMessage($logName, $message){
		$logger = LoggerFactory::getInstance($logName);
		$logger->debug( $message );
	}

	public function LogException($logName, $exception){
		$logger = LoggerFactory::getInstance($logName);
		$logger->error("Error exception" , $exception );
	}

}
