<?php
class GridFS{
	private $_mongo;
	private $_gridfs;
	public $id = '';
	public $filename = '';
	public $filetype = '';
	public $tmpfilepath = '';
	public $caption = '';

	public function __construct(){
		$this->_mongo = DBConnect::init();
		$this->_gridfs = $this->_mongo->database->getGridFS();
	}
	public function insert_files(){
		$id = $this->_gridfs->storeFile($this->tmpfilepath, array('filename' => $this->filename, 'filetype' => $this->filetype, 'caption' => $this->caption));
		return $id;
	}
	public function get_one_file(){
		return $this->_gridfs->findOne(array('_id' => new MongoId($this->id)));
	}

	public function check_exists(){
		$fields = array('_id' => true);
		$condition = array('_id' => new MongoId($this->id));
		$result = $this->_gridfs->findOne($condition, $fields);
		if($result['_id']) return true;
		return false;
	}

	public function delete(){
		return $this->_gridfs->remove(array('_id' => new MongoId($this->id)));		
	}

}
?>