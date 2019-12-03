<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
class Myproduct_model extends APS_Model
{
   public function __construct()
   {
      parent::__construct();
      $this->table         = "product";
      $this->table_product = "size";//bảng quan hệ sản phẩm
      $this->column_order  = array("$this->table.product_id","$this->table.product_id","$this->table.name","$this->table.content","$this->table.catalog","$this->table.thumbnail","","$this->table.maker_id","$this->table.price","$this->table.created","$this->table.total"); //thiết lập cột sắp xếp
      $this->column_search = array("$this->table.product_id","$this->table.name","$this->table.content","$this->table.catalog","$this->table.maker_id","$this->table.price","$this->table.created","$this->table.view","$this->table.total"); //thiết lập cột search
   }
   // Sử lý Datatable
   public function get_product_datatable($params)
   {  
      $this->db->from($this->table);
      $this->_filter($params);
      $this->search($params['search']);
      $this->db->limit($params['length'], $params['start']);
      $this->db->order_by($this->column_order[$params['columns']], $params['order']);
      $query = $this->db->get();
      // var_dump($this->db->last_query()); exit();
      return $query->result();
   }
   public function countALL($params){
      $this->_filter($params);
      $this->search($params['search']);
      $query = $this->db->get($this->table);
      return count($query->result());
   }
   public function _filter($params){
      if (!empty($params['catalog'])) {
         $this->db->where('catalog',$params['catalog']);
      }
      if (!empty($params['maker_id'])) {
         $this->db->where('maker_id',$params['maker_id']);
      }
      if (!empty($params['size'])) {
         $this->db->join($this->table_product, "$this->table_product.product_id = $this->table.product_id");
         $this->db->where('text_size',$params['size']);
      }
   }
   public function search($search){
      if (!empty($search)) {
         $dem = 0;
         foreach($this->column_search as $col){
            if ($dem < 1) {
               $this->db->like($col,$search);   
            } else {
               $this->db->or_like($col,$search);
            }
            $dem++;
         }
      }
   }
   // lấy size cho database
   public function list_size_datatable(){
      $this->db->select('text_size');
      $this->db->distinct();
      $query = $this->db->get($this->table_product);
      return $query->result();
   }
   // lấy ra size theo đi product
   public function get_size($id){
      $this->db->select('text_size')->from($this->table_product)->where("$this->table_product.product_id",$id);
      $query = $this->db->get();
      return $query->result();
   }
   //---------- thêm sản phẩm kèm size ------------
   public function set_products($data)
   {
      $this->db->insert($this->table, $data);
      $insert_id = $this->db->insert_id();
      return $insert_id;
   }
   public function set_size($data)
   {
      $this->db->insert($this->table_product, $data);
   }
   
   // ----------- get form update -----------
   // get json form update
   public function get_json($id){
      $this->db->where('product.product_id',$id);
      $query = $this->db->get($this->table);
      return $query->row();
   }
   public function get_size_come_product($id){
      $this->db->select('*')->from($this->table_product)->where("$this->table_product.product_id",$id);
      $query = $this->db->get();
      return $query->result();
   }
   public function update_products($data, $id)
   {
      $this->db->where("product_id",$id);
      return $this->db->update($this->table, $data);
   }
   public function delete_size($id){
      $this->db->where("product_id",$id);
      return $this->db->delete("size");
   }
   // ----------- delete sản phẩm ------------
   public function delete_product($id){
      $this->db->where("product_id",$id);
      $this->db->delete($this->table_product);
      $this->db->where("product_id",$id);
      return $this->db->delete($this->table);
   }
}



 ?>