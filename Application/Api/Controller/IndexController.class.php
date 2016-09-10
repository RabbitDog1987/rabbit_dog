<?php
namespace Api\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
        echo "hello world";
    }

    public function createJson()
    {
    	$arr = array(
    		'name'  => 'lilei',
    		'age'  => 12,
			'job'  => 'php',
    		);
    	$json = json_encode($arr);
    	echo $json;
    	echo "<br>";
    	dump($json);
    }

    public function readJson()
    {
    	$personJson = '{"name":"lilei","age":12,"job":"php"}';
    	$personarray = json_decode($personJson,true);
    	$personObj = json_decode($personJson);
    	dump($personarray);
    	echo '<br>';
    	echo 'name:' . $personarray['name'];
    	echo "<hr>";
    	dump($personObj);
    	echo '<br>';
    	echo 'name:' . $personObj->name;	
    }

    public function createXML()
    {
    	//生成一个xml;
    	$str = '<?xml version="1.0" encoding="utf-8"?>';
    	$str .='<person>';
    	$str .='<name>tom</name>';
    	$str .='<age>12</age>';
    	$str .='<job>php</job>';
    	$str .='</person>';
    	//xml保存为文档
    	$rs = file_put_contents('./person.xml', $str);
    	echo $rs;
    }

	public function readXML()
	{
		//读取xml文件
		$xmlStr = file_get_contents('./person.xml');
		//dump($xmlStr);
		//解析xml字符串为一个对象
		$xmlObj = simplexml_load_string($xmlStr);
		dump($xmlObj);
		echo '<br>';
		echo $xmlObj->name;
	}     

	//测试request方法封装的curl函数
	public function testRequest()
	{
		$url = 'https://www.baidu.com/';
		$content = request($url);
		var_dump($content);
	}
	
	public function weather()
    {
        //获取参数
        $city = I('get.city');
        
        //生成url
        $url = 'http://api.map.baidu.com/telematics/v2/weather?location='.$city.'&ak=B8aced94da0b345579f481a1294c9094';

        //发送请求
        $contents = request($url,false);
       
        //将xml转化为对象
        $contentObj = simplexml_load_string($contents);

        //输出内容
         echo '当前查询城市为:'.$contentObj->currentCity.'<br />';
      echo '日期为:'.$contentObj->results->result[0]->date.'<br />';
      echo '<img src="'.$contentObj->results->result[0]->dayPictureUrl.'" /><br />';
      echo '<img src="'.$contentObj->results->result[0]->nightPictureUrl.'" /><br />';
      echo '天气:'.$contentObj->results->result[0]->weather.'<br />';
      echo '风力:'.$contentObj->results->result[0]->wind.'<br />';
      echo '温度区间:'.$contentObj->results->result[0]->temperature.'<br />';
    }

    public function getAreaByPhone($phone)
    {
      //接收传输过来的phone参数
      // $phone = I('get.phone');
      //1.url地址
      $url = 'http://cx.shouji.360.cn/phonearea.php?number='.$phone;
      //2.是否为post
      //3.发送请求
      $content = request($url,false);

      //4.处理返回值
      //数据返回为json格式，json转化为对象或者数组使用
      $contentObj = json_decode($content);
       dump($contentObj);
      echo '当前查询号码为:'.$phone.'<br />';
      echo '省份为:'.$contentObj->data->province.'<br />';
      echo '城市为:'.$contentObj->data->city.'<br />';
      echo '运营商为:'.$contentObj->data->sp.'<br />';
    }

    //在客户列表里调用电话号码归属地测试接口
    public function customer()
    {
      $phone = I('get.phone');
      $this->getAreaByPhone($phone);
    }

    public function express()
    {
      $type = 'yuantong';
      $postid = '200363780641';
      $url = 'https://www.kuaidi100.com/query?type='. $type . '&postid=' . $postid;
      $content = request($url);
      $content = json_decode($content);
      //$content = iconv("UTF_8", "GBK", $content);
      //dump($content);
      foreach($content->data as $value) {
        echo $value->time . '#####' . $value->context . '<br>';
      }
    }

    public function testsend()
    {
      $str = "Many PHP developers utilize email in their code. The only PHP function that supports this is the mail() function. However, it does not provide any assistance for making use of popular features such as HTML-based emails and attachments.";
      $rs = sendMail('面试通知', $str, '1479384346@qq.com');
      if($rs === true) {
        echo "邮件发送成功";
      } else {
        echo "邮件发送失败";
      }
    }

    public function testMySQL()
    {
      $info = M('user')->select();
      dump($info);
    }

    public function doDataToRedis()
    {
      ini_set("memory", "500M");
      set_time_limit(0);
      $data = M('mobile') -> select();
      //dump($data);
      $redis = new \Redis();
      $redis->connect('127.0.0.1', 6379);
      foreach ($data as $key => $value) {
        $redis->hMSet($value['mobile'], array('id' => $value['id'], 'mobile' =>$value['mobile'], 'province' => $value['province'], 'city' => $value['city'], 'sp' =>$value['sp'],));
        unset($value);
      }
    }

    public function zhuabao()
    {
      $data = file_get_contents('https://baidu.com/');
      echo $data;
    }

    public function phpin()
    {
      phpinfo();
    }

    public function doDataToMongodb()
    {
      ini_set('memory_limit','500M');
      set_time_limit(0);
      $data =M('mobile')->select();
      $connection = new \MongoClient("mongodb://root:root@localhost:27017");
      $db = $connection->admin;
      $i = 0;
      foreach ($data as $key => $value) {
        $rs = $db->mobile->insert(array('id'=>$value['id'],'mobile'=>$value['mobile'],'province'=>$value['province'],'city'=>$value['city'],'sp'=>$value['sp']));
          ++$i;
      }
      echo $i;
    }

    public function getAreaByMongodb()
    {
      G('begin');
      $phone = I('get.phone');
      $areaNum = substr($phone,0,7);
      if(empty($phone)) {
        $dataArray = array(
          'errorCode'=>1,
          'time'=>time(),
          );
        echo json_encode($dataArray);
      } else {
        $connection = new \MongoClient("mongodb://root:root@localhost:27017");
        $db = $connection->admin;
        $data = $db->mobile->find(array('mobile'=>$areaNum));
        //dump($data);die;
        foreach ($data as $key => $value) {
          $province=$value['province'];
          $city = $value['city'];
          $sp = $value['sp'];
        }
        $dataArray = array(
          'errorCode'=>0,
          'time'=>time(),
          'province'=>$province,
          'city'=>$city,
          'sp'=>$sp,
          );
        echo json_encode($dataArray);
      }
      G('end');
      echo '<br>';
      echo G('begin', 'end') . 's<br>';
      echo G('begin', 'end', m) . 'kb';
    }
}