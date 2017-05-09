<?php
namespace Admin\Controller;

use Common\Controller\AdminbaseController;

class GoodsController extends AdminbaseController {
    
	protected $goods_model;
	
	function _initialize() {
		parent::_initialize();
		$this->goods_model =D("Common/Goods");
		$this->goods_type_model =D("Common/Goods_type");
	}
	
	// 后台商品管理列表
	public function index(){
		$count = $this->goods_model->count();
		$page = $this->page($count, 20);
		$goods_list = $this ->goods_model
							->alias('g')
							->join('left join bb_goods_type gt on g.goods_type=gt.id')
							->field('g.*,gt.type_name')
							->order('g.status desc')
							->limit($page->firstRow , $page->listRows)
						    ->select();
		$this->assign("goods_list",$goods_list);
		$this ->display();
	}
	
	// 添加商品
	public function add(){
		$goods_id = I("get.goods_id",0,'intval');
		$goods_info=$this->goods_model->where(array('goods_id'=>$goods_id))->find();
		$this->assign("goods_info",$goods_info);
		$this->display();
	}
	
	// 商品添加提交
	public function add_post(){
		if (IS_POST) {
			if(!empty($_POST['photos_alt']) && !empty($_POST['photos_url'])){
				foreach ($_POST['photos_url'] as $key=>$url){
					$photourl=sp_asset_relative_url($url);
					$_POST['smeta']['photo'][]=array("url"=>$photourl,"alt"=>$_POST['photos_alt'][$key]);
				}
			}
			$_POST['smeta']['thumb'] = sp_asset_relative_url($_POST['smeta']['thumb']);
			$goods_info=I("post.post");
			$goods_info['goods_price']=bb_yuan_to_fen($goods_info['goods_price']);
			$goods_info['smeta']=json_encode($_POST['smeta']);
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
		$goods_info['goods_price']=bb_fen_to_yuan($goods_info['goods_price']);
		$this->assign("goods_info",$goods_info);
		$this->assign("smeta",json_decode($goods_info['smeta'],true));
		$this->display();
	}
	
	// 商品编辑提交
	public function edit_post(){
		if (IS_POST) {
			if(!empty($_POST['photos_alt']) && !empty($_POST['photos_url'])){
				foreach ($_POST['photos_url'] as $key=>$url){
					$photourl=sp_asset_relative_url($url);
					$_POST['smeta']['photo'][]=array("url"=>$photourl,"alt"=>$_POST['photos_alt'][$key]);
				}
			}
			$_POST['smeta']['thumb'] = sp_asset_relative_url($_POST['smeta']['thumb']);
			$goods_info = I("post.post");
			$goods_info['smeta'] = json_encode($_POST['smeta']);
			$goods_info['goods_price']=bb_yuan_to_fen($goods_info['goods_price']);
			$result = $this->goods_model->save_data($goods_info);
			if ($result) {
				$this->success("编辑成功！");
			} else {
				$this->error("编辑失败！");
			}
			 
		}
	}
	public function delete_goods () {
		$status = I('get.status', 0);
		$goods_id = I('get.goods_id');
		$result = $this->goods_model->change_status($goods_id, $status);
		if ($result) {
				$this->success("操作成功！");
			} else {
				$this->error("操作失败！");
			}
	}

	public function index_type(){
		$goods_list = $this ->get_goods_type_list();
		$this->assign("goods_list",$goods_list);
		$this ->display();
	}

	public function add_type(){
		$this->display();
	}
	

	public function add_type_post(){
		if (IS_POST) {
			$type_info=I("post.post");
			$result=$this->goods_type_model->add_data($type_info);
			if ($result) {
				$this->success("添加成功！");
			} else {
				$this->error("添加失败！");
			}
			 
		}
	}

	public function edit_type(){
		$type_id = I("get.type_id",0,'intval');
		$type_info=$this->goods_type_model->get_info_by_id($type_id);
		$this->assign("type_info",$type_info);
		$this->display();
	}
	
	public function edit_type_post(){
		if (IS_POST) {
			$type_info = I("post.post");
			$result = $this->goods_type_model->save_data($type_info);
			if ($result) {
				$this->success("编辑成功！");
			} else {
				$this->error("编辑失败！");
			}
			 
		}
	}

}