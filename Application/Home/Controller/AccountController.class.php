<?php
/**
 * Created by PhpStorm.
 * User: Hon
 * Date: 2017/6/18 0018
 * Time: 12:22
 */

namespace Home\Controller;


use Think\Model;

class AccountController extends HomeController
{
    protected $result = ['status'=>0,'msg'=>'验证通过'];
    protected $members=[];
    protected $sub_members=[];

    public function oneCheck()
    {
        if(!$this->authCheck())
            return $this->ajaxReturn($this->result,'JSON');
        $recommend_name = I('recommend_name','');
        $account_name = I('account_name','');
        $account_pwd = I('account_pwd','');
        $account_balance = I('account_balance','');
        $wtNum = I('wtNum',1);
        if(!$recommend_name || !$account_name || !$account_pwd || !$account_balance)
            return $this->ajaxReturn($this->result,'JSON');
        if(!$this->checkRe($recommend_name,$wtNum))
            return $this->ajaxReturn($this->result,'JSON');
        $member = $this->getMember($account_name);
        if(!$member){
            $this->result['msg'] = '委托账户不是推荐账户的下级';
            return $this->ajaxReturn($this->result,'JSON');
        }
        if($member['coin'] < 4999.99){
            $this->result['msg'] = '委托账户余额不足5000';
            return $this->ajaxReturn($this->result,'JSON');
        }
//        if($member['type']== 1){
//            $this->result['msg'] = '代理账户不能委托';
//            return $this->ajaxReturn($this->result,'JSON');
//        }
        if(think_ucenter_md5($account_pwd, UC_AUTH_KEY) !== $member['password']){
            $this->result['msg'] = '委托用户密码不正确';
            return $this->ajaxReturn($this->result,'JSON');
        }
        $account_type = 1; //5000
//        if($member['coin'] >= 30000 && $member['coin'] < 80000) $account_type = 2;
//        if($member['coin'] >= 80000 && $member['coin'] < 120000) $account_type = 3;
//        if($member['coin'] >= 120000) $account_type = 4;
        if($member['coin'] >= 9999.99)  $account_type = 5; //10000
        if($member['coin'] >= 49999.99)  $account_type = 6;
        $this->result['status'] = 1;
        $this->result['account_type'] = $account_type;
        return $this->ajaxReturn($this->result,'JSON');
    }

    public function dataEtl()
    {
        if(!$this->authCheck())
            return $this->ajaxReturn($this->result,'JSON');
        $data = I('data','');
        $this->result['msg'] = '请求失败11';
        if(!$data)return $this->ajaxReturn($this->result,'JSON');
        if(!$this->isJson($data))
            return $this->ajaxReturn($this->result,'JSON');
        $data = json_decode($data,true);
        $members = M('members')->where(['is_test'=>0/*,'type'=>0*/])->select();
        $accData = [];
        $errData = [];
        $okData = [];
        foreach ($data as $da){
            $da['msg'] = '';
            $da['is_modify_pwd'] = 0;
            $flag = false;
            foreach ($members as $member){
                if(strtolower($da['account_name']) == strtolower($member['username'])){
                    if(think_ucenter_md5($da['account_pwd'], UC_AUTH_KEY) === $member['password']
                        && $member['coin'] >= 2000){
                        $account = [
                            'account_id' => $da['account_id'],
                            'account_name' => $da['account_name'],
                            'account_pwd' => $da['account_pwd'],
                            'recommend_name' => $da['recommend_name'],
                            'account_balance' => $member['coin'],
                            'deadline' => $da['deadline'],
                            'is_verified' => $da['is_verified'],
                            'verified_at' => $da['verified_at'],
                            'verified_user_id' => $da['verified_user_id'],
                            'verified_user' => $da['verified_user'],
                            'account_type' => $da['account_type'],
                            'created_at' => $da['created_at'],
                            'updated_at' => $da['updated_at']
                        ];
                        $accData[] = $account;
                        $flag = true;
                    }else{
                        $da['msg'] = '无法登录，用户密码已修改！';
                        $da['is_modify_pwd'] = 1;
                    }
                    break;
                }
            }
            if($flag){
                $okData[] = $da;
            }else{
                $errData[] = $da;
            }
        }
        $model = new Model();
        $model->startTrans();
        $model->query('DELETE FROM accounts');
        $re  = $model->table("accounts")->addAll($accData);
        if($re){
            $model->commit();
            $this->result['status'] = 1;
            $this->result['msg'] = '数据同步成功！';
        }else{
            $model->rollback();
            $this->result['msg'] = '数据同步失败！';
        }
        $data = ['ok_data' => $okData,'err_data' => $errData];
        $this->result['data'] = $data;
        return $this->ajaxReturn($this->result,'JSON');
    }

    /**
     * @return mixed
     */
    public function isJson($param)
    {
        json_decode($param);
        if (json_last_error()) {
            return false;
        }

        if(!is_array(json_decode($param,true))){
            return false;
        }
        return true;
    }
    /**
     * 授权验证
     * @return bool
     */
    private function authCheck(){
        $ss = 'yhylApp1';
        $sss = I('sss','');
        $pwd = md5(sha1($ss) .'yh!qQazXsw23)sxhgf}]:./kjh666');
        if($pwd !== $sss){
            $this->result['msg'] = '请求失败sss！';
            return false;
        }
        return true;
    }

    /**
     * @param $name
     * @return mixed|null
     */
    protected function getMember($name)
    {
        foreach ($this->sub_members as $member){
            if($name == $member['username']){
                return $member;
            }
        }
        return null;
    }


    /**
     * 检验推荐用户
     * @param $username
     * @param $wtNum
     * @return bool
     */
    private function checkRe($username,$wtNum)
    {

        $data = M('members')->where(['username'=>$username,'enable'=>1,'isDelete'=>0,'type'=>1,'is_test'=>0])->find();
        if(!$data){
            $this->result['msg'] = '推荐用户不符合验证规则：（代理 + 账户状态状态正常）';
            return false;
        }
        $data = M('members')->where([
            'parentId'=> $data['uid'],
            'coin' => ['egt','5000'],
            'enable'=>1,'isDelete'=>0,'type'=>0,'is_test'=>0
        ])->select();
        if(!$data){
            $this->result['msg'] = '推荐用户不符合验证规则：（代理 + 账户状态状态正常）';
            return false;
        }
        $this->sub_members = $data;
        $model = new Model();
        $accData = $model->table("accounts")
            ->where(array("recommend_name"=>$username,'is_verified'=>1))
            ->field("*")
            ->query('select %FIELD% from %TABLE% %WHERE%',true);
        if($accData){
            $this->members = $accData;
        }
//        if((count($data) - count($this->members)) < $wtNum+1){
//            $this->result['msg'] = '推荐用户的委托名额不足';
//            return false;
//        }
        return true;
    }
}