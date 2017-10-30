<?php
class DonHang {
	const COLLECTION = 'donhang';
	private $_mongo;
	private $_collection;	
	public $id = '';
	public $data_sync = '';
	public $date_post = '';
	public $id_congty = '';
	public $tinhtrang = array(); //ten, noidung, date_post, thanhtoan, hinhanh, id_user

	public function __construct(){
		$this->_mongo = DBConnect::init();
		$this->_collection = $this->_mongo->getCollection(DonHang::COLLECTION);
	}

	public function get_all_list(){
		return $this->_collection->find()->sort(array('date_post'=>-1));
	}

	public function get_list_by_congty(){
		$query = array('id_congty' => new MongoId($this->id_congty));
		return $this->_collection->find($query)->sort(array('date_post'=>-1));	
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

	public function get_one_by_id_congty($id, $id_congty){
		$query = array('id' => $id, 'id_congty' => new MongoId($id_congty));
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

	public function push_congty(){
		$query = array('$set' => array('id_congty' => new MongoId($this->id_congty)));
		$condition = array('_id' => new MongoId($this->id));
		return $this->_collection->update($condition, $query);
	}

	public function push_tinhtrang(){
		$condition = array('_id' => new MongoId($this->id));
		$t = $this->get_one();
		if(!isset($t['tinhtrang'])) {
			$q = array('$set' => array('tinhtrang' => array()));
			$this->_collection->update($condition, $q);
		}
		$query = array('$push' => array('tinhtrang' => array('$each' => array($this->tinhtrang), '$position' => 0)));
		//$query = array('$push' => array('tinhtrang' => $this->tinhtrang));
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

	public function check_dmcongty($id_congty){
		$query = array('id_congty' => new MongoId($id_congty));
		$field = array('_id' => true);
		$result = $this->_collection->findOne($query, $field);
		if(isset($result['_id']) && $result['_id']) return true;
		else return false;
	}

	public function get_baocao($tungay, $denngay){
		$query = array(
			'ngaymua' => array('$gte' => new MongoDate($tungay)),
			'ngaymua' => array('$lte' => new MongoDate($denngay))
		);
		return $this->_collection->find($query)->sort(array('ngaymua' => -1));
	}

	public function get_baocao_condition($condition){
		return $this->_collection->find($condition)->sort(array('ngaymua' => -1));
	}
}

?>