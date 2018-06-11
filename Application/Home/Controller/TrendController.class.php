<?php
/**
 * Created by PhpStorm.
 * User: Hon <chenhong@fangstar.net>
 * Date: 2017/5/24 0024
 * Time: 21:38
 */

namespace Home\Controller;


class TrendController extends HomeController
{
    protected $mmcConfig = [1,3,12,41,42,43,37,40,35,36,5,34,14];
    protected $sscConfig = [38,39,6,16,15];

    public function chart()
    {
        $type = I('type') ? I('type') : 36;
        $type_name = I('type_name') ? I('type_name') : '';
        $issuecount = I('issuecount') ? I('issuecount') : 30;
        $mod = I('mod') ? I('mod') : 'five';
        $this->assign('type',$type);
        $this->assign('type_name',$type_name);
        $this->assign('issuecount',$issuecount);
        $this->assign('sscConfig',$this->sscConfig);
        //辅助位
        $assist = [
            'ns' => 5,
            'nts' => 60,
            'ntx' => 0,
            'ntd' => 9,
            'ntn' => 10
        ];
        if (in_array($type,$this->mmcConfig)) {
            $assist['ns'] = 5;
            $assist['nts'] = 60;
            $assist['ntx'] = 0;
            $assist['ntd'] = 9;
            $assist['ntn'] = 10;
        } else if(in_array($type,$this->sscConfig)) {
            $assist['ns'] = 5;
            $assist['nts'] = 66;
            $assist['ntx'] = 1;
            $assist['ntd'] = 11;
            $assist['ntn'] = 11;
        }else{
            if ($type == 10 || $type == 9) {
                $assist['ns'] = 3;
                $assist['nts'] = 40;
                $assist['ntx'] = 0;
                $assist['ntd'] = 9;
                $assist['ntn'] = 10;
            }
        }
        $this->assign('assist',$assist);
        $rs = M('data')->cache(true, 15, 'xcache')->where(array('type' => $type))->order('time desc')->limit($issuecount)->select();
        $rs = array_reverse($rs);
        $total = count($rs);
        $this->assign('rs',$rs);
        $this->assign('total',$total);
        $this->display();
    }

    public function chart11_5(){
        $type = I('type') ? I('type') : 38;
        $type_name = I('type_name') ? I('type_name') : '';
        $issuecount = I('issuecount') ? I('issuecount') : 30;
        $mod = I('mod') ? I('mod') : 'five';
        $this->assign('type',$type);
        $this->assign('type_name',$type_name);
        $this->assign('issuecount',$issuecount);
        //辅助位
        $assist = [
            'ns' => 5,
            'nts' => 60,
            'ntx' => 0,
            'ntd' => 9,
            'ntn' => 10
        ];
        if (in_array($type,$this->mmcConfig)) {
            $assist['ns'] = 5;
            $assist['nts'] = 60;
            $assist['ntx'] = 0;
            $assist['ntd'] = 9;
            $assist['ntn'] = 10;
        } else {
            if ($type == 10 || $type == 9) {
                $assist['ns'] = 3;
                $assist['nts'] = 40;
                $assist['ntx'] = 0;
                $assist['ntd'] = 9;
                $assist['ntn'] = 10;
            } else {
                if ($type == 6 || $type == 15 || $type == 16) {
                    $assist['ns'] = 5;
                    $assist['nts'] = 66;
                    $assist['ntx'] = 1;
                    $assist['ntd'] = 11;
                    $assist['ntn'] = 11;
                }
            }
        }
        $this->assign('assist',$assist);
        $rs = M('data')->cache(true, 15, 'xcache')->where(array('type' => $type))->order('time desc')->limit($issuecount)->select();
        $rs = array_reverse($rs);
        $total = count($rs);
        $this->assign('rs',$rs);
        $this->assign('total',$total);
        $this->display();
    }

    public function chart_yhlf(){
        $type = I('type') ? I('type') : 46;
        $type_name = I('type_name') ? str_replace('.html', '', I('type_name')) : '';
        $issuecount = I('issuecount') ? I('issuecount') : 30;
        $mod = I('mod') ? I('mod') : 'five';
        $this->assign('type',$type);
        $type_name1 = $type_name;
        
        $this->assign('type_name',$type_name);
        $this->assign('type_name1',$type_name1);
        $this->assign('issuecount',$issuecount);
        $this->assign('sscConfig',$this->sscConfig);
        //辅助位
        $assist = [
            'ns' => 1,
            'nts' => 8,
            'ntx' => 0,
            'ntd' => 1,
            'ntn' => 2
        ];

        $rs = M('data')
            // ->cache(true, 15, 'xcache')
            ->where(array('type' => $type))
            ->order('time desc')
            ->limit($issuecount)
            ->select();
        foreach ($rs as $k => $v) {
            
        }
        if (!empty($rs[0]['data'])) {
            $assist['ns'] = count(explode(',',$rs[0]['data']));
            $assist['nts'] = ($assist['ns'] + 1) * 10;
        }
        $this->assign('assist',$assist);
        $rs = array_reverse($rs);
        $total = count($rs);
        $this->assign('rs',$rs);
        $this->assign('total',$total);
        $this->display();
    }
}