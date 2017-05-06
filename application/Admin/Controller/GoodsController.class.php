<?php
namespace Admin\Controller;

use Common\Controller\AdminbaseController;

class GoodsController extends AdminbaseController {
    
	protected $goods_model;
	
	function _initialize() {
		parent::_initialize();
		$this->goods_model =D("Common/Goods");
	}
	
	// 后台商品管理列表
	public function index(){
		$this->_lists(array("post_status"=>array('neq',3)));
		
		$this->display();
	}
	
	// 添加商品
	public function add(){
		$goods_list = $this->goods_model->select();
		$goods_id = I("get.goods_id",0,'intval');
		$goods_info=$this->goods_model->where(array('goods_id'=>$goods_id))->find();
		$this->assign("goods_list",$goods_list);
		$this->assign("goods_info",$goods_info);
		$this->display();
	}
	
	// 商品添加提交
	public function add_post(){
		if (IS_POST) {
			$goods_info=I("post.post");
			$result=$this->goods_model->add_data($goods_info);
			if ($result) {
				$this->success("添加成功！");
			} else {
				$this->error("添加失败！");
			}
			 
		}
	}
	// 编辑商品
	public function edit(){
		$goods_id = I("get.goods_id",0,'intval');
		$goods_info=$this->goods_model->get_info_by_id($goods_id);
		$this->assign("goods_info",$goods_info);
		$this->display();
	}
	
	// 商品编辑提交
	public function edit_post(){
		if (IS_POST) {
			$goods_info=I("post.post");
			$result=$this->goods_model->save_data($goods_info);
			if ($result) {
				$this->success("编辑成功！");
			} else {
				$this->error("编辑失败！");
			}
			 
		}
	}

	public function _lists ($where=array()) {

	}
}