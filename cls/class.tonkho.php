<?php
class TonKho {
    const COLLECTION = 'tonkho';
    private $_mongo;
    private $_collection;

    public $id = '';

    public $id_sanpham = '';
    public $tensanpham = '';
    public $donvitinh = '';
    public $soluong = 0;
    public $erp_id = ''; //

    public $date_post = '';
    public $date_update = '';
    public $id_user = '';

    public function __construct(){
        $this->_mongo = DBConnect::init();
        $this->_collection = $this->_mongo->getCollection(TonKho::COLLECTION);
    }

    public function get_all_list(){
        return $this->_collection->find()->sort(array('date_update'=>-1));
    }

    public function get_one(){
        $query = array('_id' => new MongoId($this->id));
        return $this->_collection->findOne($query);
    }

    public function get_one_by_condition($condition){
        return $this->_collection->findOne($condition);
    }

    public function delete(){
        $query = array('_id' => new MongoId($this->id));
        return $this->_collection->remove($query);
    }

    public function insert(){
        $query = array(
            'id_sanpham' => $this->id_sanpham,
            'tensanpham' => $this->tensanpham,
            'donvitinh' => $this->donvitinh,
            'soluong' => intval($this->soluong),
            'erp_id' => $this->erp_id,
            'date_post' => new MongoDate(),
            'date_update' => new MongoDate(),
            'id_user' => new MongoId($this->id_user)
        );
        return $this->_collection->insert($query);
    }

    public function update_tonkho(){
        $query = array('$set' => array('soluong' => intval($this->soluong),  'date_update' => new MongoDate(), 'id_user' => new MongoId($this->id_user)));
        $condition = array('erp_id' => $this->erp_id, 'id_sanpham' => $this->id_sanpham);
        return $this->_collection->update($condition, $query);
    }

    public function inc_soluong(){
        $query = array('$inc' => array('soluong' => intval($this->soluong)));
        $condition = array('id_sanpham' => $this->id_sanpham, 'erp_id' => $this->erp_id);
        return $this->_collection->update($condition, $query);
    }

    public function inc_soluong_by_itemId_erpId($itemId, $erp_id){
        $query = array('$inc' => array('soluong' => $this->soluong));
        $condition = array('id_sanpham' => $itemId, 'erp_id' => $erp_id);
        return $this->_collection->update($condition, $query);
    }

    public function inc_soluong_by_itemId($itemId){
        $result = $this->get_one_by_condition(array('id_sanpham' => $itemId));
        $query = array('$inc' => array('soluong' => $this->soluong));
        $condition = array('id_sanpham' => $result['id_sanpham']);
        return $this->_collection->update($condition, $query);
    }
    public function sum_soluong_by_id_sanpham($id_sanpham){
        $condition = array(
            '$match' => array('id_sanpham' => $id_sanpham)
        );
        $query = array(
            '$group' => array(
                '_id' => '$id_sanpham',
                'sum_soluong' => array('$sum' => '$soluong')
            )
        );
        $result = $this->_collection->aggregate($condition, $query);
        if($result && isset($result['result'])) return $result['result'];
        return 0;
    }
    public function sum_soluong_by_id_sanpham_erp($id_sanpham, $erp_id){
        $condition = array(
            '$match' => array('id_sanpham' => $id_sanpham, 'erp_id' => $erp_id)
        );
        $query = array(
            '$group' => array(
                '_id' => array('$id_sanpham', '$erp_id'),
                'sum_soluong' => array('$sum' => '$soluong')
            )
        );
        $result = $this->_collection->aggregate($condition, $query);
        if($result && isset($result['result'])) return $result['result'];
        return 0;
    }



    public function check_exists(){
        $query = array('erp_id' => $this->erp_id, 'id_sanpham' => $this->id_sanpham);
        $field = array('_id' => true);
        $result = $this->_collection->findOne($query, $field);
        if(isset($result['_id']) && $result['_id']) return true;
        else return false;
    }
}
?>
