<?php
class DanhMucCongTy {
	const COLLECTION = 'danhmuccongty';
	private $_mongo;
	private $_collection;

	public $id = '';
	public $erp_id = '';
	public $ten = '';
	public $diachi = '';

	public $addresslevelone = '';
	public $addressleveltwo = '';
	public $addresslevelthree = '';

	public $tenduong = '';

	public function __construct(){
		$this->_mongo = DBConnect::init();
		$this->_collection = $this->_mongo->getCollection(DanhMucCongTy::COLLECTION);
	}

	public function get_all_list(){
		return $this->_collection->find()->sort(array('ten'=>-1));
	}

	public function get_list_condition($condition){
		return $this->_collection->find($condition)->sort(array('ten'=>-1));
	}

	public function get_one_by_address(){
		$query = array(
			'addresslevelone' => $this->addresslevelone,
			'addressleveltwo' => $this->addressleveltwo,
			'addresslevelthree' => $this->addresslevelthree
		);
		return $this->_collection->findOne($query);
	}

	public function get_one_by_address1(){
		$query = array(
			'addresslevelone' => $this->addresslevelone,
			'addressleveltwo' => $this->addressleveltwo
		);
		return $this->_collection->findOne($query);
	}

	public function get_one_by_address2(){
		$query = array(
			'ten' => 'ANOVA'
		);
		return $this->_collection->findOne($query);
	}

	public function get_one(){
		$query = array('_id' => new MongoId($this->id));
		return $this->_collection->findOne($query);
	}

	public function insert(){
		$query = array(
			'erp_id' => $this->erp_id,
			'ten' => $this->ten,
			'diachi' => $this->diachi,
			'addresslevelone' => $this->addresslevelone,
			'addressleveltwo' => $this->addressleveltwo,
			'addresslevelthree' => $this->addresslevelthree,
			'tenduong' => $this->tenduong
		);
		return $this->_collection->insert($query);
	}

	public function edit(){
		$query = array('$set' => array(
			'erp_id' => $this->erp_id,
			'ten' => $this->ten,
			'diachi' => $this->diachi,
			'addresslevelone' => $this->addresslevelone,
			'addressleveltwo' => $this->addressleveltwo,
			'addresslevelthree' => $this->addresslevelthree,
			'tenduong' => $this->tenduong
		));
		$condition = array('_id' => new MongoId($this->id));
		return $this->_collection->update($condition, $query);
	}

	public function delete(){
		$query = array('_id' => new MongoId($this->id));
		return $this->_collection->remove($query);
	}

	public function check_exist_by_erpid($erp_id){
		$query = array('erp_id' => $erp_id);
        $field = array('_id' => true);
        $result = $this->_collection->findOne($query, $field);
        if(isset($result['_id']) && $result['_id']) return true;
        else return false;
	}
}
?>
