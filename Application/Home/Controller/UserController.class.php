<?php    
namespace Home\Controller;

use Think\Controller;

class UserController extends Controller{

  public function login()
  {
    echo "这是登录页面";
  }

  public function register()
  {
    if(IS_POST) {
        $userInfo = I('post.');
        $userInfo['salt'] = md5(time());
        $re = M('user')->add($userInfo);
        if($re) {
          $content = "<a href='http://www.king.com/index.php/Home/User/active/id/$re/salt/" . $userInfo['salt']. ">您好" . $userInfo['username'] . ",请点击链接进行用户的激活操作</a>";
          var_dump($content);die;
           /*$sendRs = sendMail("php49开发产品激活邮件","<a href=\"http://www.king.com/index.php/Home/User/active/id/$re/salt/$userInfo['salt']\">您好$userInfo['username'],请点击链接进行用户的激活操作</a>",$mail);*/
            $sendRs = sendMail("php49开发产品激活邮件",$content,$userInfo['mail']);
            if($sendRs === true){
              $this->success('注册成功,请到邮箱激活用户',U('User/login'),3);
            }else{
              $this->error('注册成功,发送邮件不成功',U('User/register'),3);
            }
        } else {
            $this->error('注册失败', U('register'), 3);
        }
    } else {
      $this->display();
    }
  }

  public function active(){
    $id = I('get.id');
    $salt = I('get.salt');
    //激活用户操作，修改active字段为1
    $rs = M("user")->where("id = $id AND salt = '$salt'")->setField('active',1);
    //判断是否修改成功
    if($rs){
      $this->success('激活用户成功,请您登陆~~~O(∩_∩)O~',U('User/login'),3);
    }else{
      $this->error('激活失败,非法激活',U('User/register'),3);
    }
  }
} 
?>