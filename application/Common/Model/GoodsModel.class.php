<?php
namespace Common\Model;
use Common\Model\CommonModel;
class GoodsModel extends CommonModel{
	const SHOW_GOODS=0.;   //显示商品
	const HIDE_GOODS=1;   //下架商品

	const GOODS_INFO_ID='goods_info_id_';   //单条信息key前缀
	//自动验证
	// protected $_validate = array(
	// 		//array(验证字段,验证规则,错误提示,验证条件,附加规则,验证时间)
	// 		array('ad_name', 'require', '广告名称不能为空！', 1, 'regex', 3),
	// );
	
	// protected function _before_write(&$data) {
	// 	parent::_before_write($data);
	// }
	public function get_info_by_id ($goods_id) {
		
		$redis_key = self::GOODS_INFO_ID . $goods_id;
		$redis_key = md5($this->get_cache_flag_val() . $redis_key);
		$res = S($redis_key);
		if ($res === false) {
			$condition['id'] = $goods_id;
			$res = $this->where($condition)->find();
			if ($res !== false)
				S($redis_key, $res);
		}
		return $res;
	}

	/*
	 * 增加商品记录
	 */
	public function add_data($data)
	{
		$res = $this->add($data);
		return $res;
	}

	/*
	 * 更新商品信息
	*/
	public function save_data($data)
	{
		$redis_key = self::GOODS_INFO_ID . $data['id'];
		$redis_key = md5($this->get_cache_flag_val() . $redis_key);
		$condition['id'] = $data['id'];
		$res = $this->where($condition)->save($data);
		if ($res !== false) {
			S($redis_key, null);
		}
		return $res;

	}
	/*
	 * 改编商品状态
	*/
	public function change_status($goods_id, $status)
	{
		$redis_key = self::GOODS_INFO_ID . $goods_id;
		$redis_key = md5($this->get_cache_flag_val() . $redis_key);
		if($status != 0) 
			$status = 0;
		else
			$status = 1;
		$condition['id'] = $goods_id;
		$data = array('id'=>$goods_id, 'status'=>$status); 
		$res = $this->where($condition)->save($data);
		if ($res !== false) {
			S($redis_key, null);
		}
		return $res;
	}

}