<?php
class DonViTinh {
	const COLLECTION = 'donvitinh';
	private $_mongo;
	private $_collection;

	public $id = '';
	public $data_sync = '';
	public $date_post = '';

	public function __construct(){
		$this->_mongo = DBConnect::init();
		$this->_collection = $this->_mongo->getCollection(DonViTinh::COLLECTION);
	}

	public function get_all_list(){
		return $this->_collection->find()->sort(array('date_post'=>-1));
	}

	public function get_list_condition($condition){
		return $this->_collection->find($condition)->sort(array('ten'=>-1));
	}
	public function get_one(){
		$query = array('_id' => new MongoId($this->id));
		return $this->_collection->findOne($query);
	}

	public function get_one_by_id($id){
		$query = array('id' => $id);
		return $this->_collection->findOne($query);
	}

	public function sync(){
		return $this->_collection->insert($this->data_sync);
	}

	public function edit_sync(){
		$condition = array('_id' => new MongoId($this->id));
		$query = array('$set' => $this->data_sync);
		return $this->_collection->update($condition, $query);	
	}

	public function check_sync($id){
		$query = array('id' => $id);
		$field = array('_id' => true);
		$result = $this->_collection->findOne($query, $field);
		if(isset($result['_id']) && $result['_id']) return true;
		else return false;
	}

	public function delete(){
		$query = array('_id' => new MongoId($this->id));
		return $this->_collection->remove($query);
	}
}
?>