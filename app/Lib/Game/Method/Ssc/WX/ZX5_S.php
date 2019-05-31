<?php namespace App\Lib\Game\Method\Ssc\WX;
use App\Lib\Game\Method\Ssc\Base;

class ZX5_S extends Base
{
    // 12345,12345,12345,12345,12345,12345,
    public $all_count =100000;
    public static $filterArr = array(0=>1,1=>1,2=>1,3=>1,4=>1,5=>1,6=>1,7=>1,8=>1,9=>1);

    //供测试用 生成随机投注
    public function randomCodes()
    {
        $rand=5;
        return implode('',(array)array_rand(self::$filterArr,$rand));
    }

    public function fromOld($codes)
    {
        //12111|23111|34111
        return implode(',',explode('|',$codes));
    }

    public function parse64($codes)
    {
        if(strpos($codes,'base64:')!==false){
            $ex=explode('base64:',$codes);
            $codes=$this->_parse64($ex[1],5);
            if(is_array($codes)){
                $codes=implode(',',$codes);
            }
        }
        return $codes;
    }

    public function encode64($codes)
    {
        return $this->_encode64(explode(',',$codes));
    }

    public function regexp($sCodes)
    {
//        if (!preg_match("/^(([0-9]{5}\,)*[0-9]{5})$/", $sCodes)) {
//            return false;
//        }

        //重复号码
        $temp =explode(",",$sCodes);
        $i = count(array_filter(array_unique($temp),function($val){
            if(!preg_match("/^[0-9]{5}$/",$val)) {
               return false;
            }
            return true;
        }));

        if($i != count($temp)) return false;

        return true;
    }

    public function count($sCodes)
    {
        return count(explode(",",$sCodes));
    }

    //判定中奖
    public function assertLevel($levelId, $sCodes, Array $numbers)
    {
        $str = implode('', $numbers);
        $exists = array_flip(explode(',', $sCodes));
        return intval(isset($exists[$str]));
    }

    //检查封锁
    public function tryLockScript($sCodes,$plan,$prizes,$lockvalue)
    {
        $aCodes = explode(',', $sCodes);
        $codes=[];
        foreach($aCodes as $code){
            $codes[$code]=1;
        }
        $codes="'".implode("','",array_keys($codes))."'";

        $script=
            <<<LUA

LUA;

        $max=$lockvalue-$prizes[1];
        $script.= <<<LUA

exists=cmd('exists','{$plan}')

if exists==0 and {$max}<0 then
    do return 0 end
end

ret=cmd('zrangebyscore','{$plan}',{$max},'+inf')
if (#ret==0) then
    do return 1 end
end

codes={{$codes}}
_codes={}

for _,str in pairs(ret) do
    for _,code in pairs(codes) do
        if code==str then
            do return 0 end
        end
    end
end


do return 1 end

LUA;

        return $script;
    }

    //写入封锁值
    public function lockScript($sCodes,$plan,$prizes)
    {
        $aCodes = explode(',', $sCodes);
        $codes="'".implode("','",$aCodes)."'";

        $script='';
        $script.= <<<LUA

codes={{$codes}}
for _,_code in pairs(codes) do
    cmd('zincrby','{$plan}',{$prizes[1]},_code)
end

LUA;

        return $script;
    }
}
