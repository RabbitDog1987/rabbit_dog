<?php
function request($url,$https=true,$method='get',$data=null){
  //1.初始化url
  $ch = curl_init($url);
  //2.设置相关的参数
  //字符串不直接输出,进行一个变量的存储
  //CURLOPT_RETURNTRANSFER:将curl_exec()获取的信息以文件流的形式返回，而不是直接输出。
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  // curl_setopt($ch, CURLOPT_HEADER, 1);
  //判断是否为https请求
  if($https === true){
    //CURLOPT_SSL_VERIFYPEER:禁用后cURL将终止从服务端进行验证。使用CURLOPT_CAINFO选项设置证书使用CURLOPT_CAPATH选项设置证书目录 如果CURLOPT_SSL_VERIFYPEER(默认值为2)被启用，CURLOPT_SSL_VERIFYHOST需要被设置成TRUE否则设置为FALSE。
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
   //CURLOPT_SSL_VERIFYHOST 1 检查服务器SSL证书中是否存在一个公用名(common name)。译者注：公用名(Common Name)一般来讲就是填写你将要申请SSL证书的域名 (domain)或子域名(sub domain)。2 检查公用名是否存在，并且是否与提供的主机名匹配。 
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
  }
  //判断是否为post请求
  if($method == 'post'){
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
  }
  //3.发送请求
  $str = curl_exec($ch);
  // $hd = curl_getinfo($ch);
  //4.关闭连接
  curl_close($ch);
  //返回请求到的结果
  // return array('str'=>$str,'hd'=>$hd);
  return $str;
}

//邮件发送测试方法
function sendMail($subject,$msghtml,$sendAddress){
  //引入发送类phpmailer.php
  require './PHPMailer/class.phpmailer.php';
  //实列化对象
  $mail             = new PHPMailer();
  /*服务器相关信息*/
  $mail->IsSMTP();                        //设置使用SMTP服务器发送
  $mail->SMTPAuth   = true;               //开启SMTP认证
  $mail->Host       = 'smtp.163.com';         //设置 SMTP 服务器,自己注册邮箱服务器地址
  $mail->Username   = 'zhaoganghappy2012';      //发信人的邮箱用户名
  $mail->Password   = '0801074113.';          //发信人的邮箱密码
  /*内容信息*/
  $mail->IsHTML(true);               //指定邮件内容格式为：html
  $mail->CharSet    ="UTF-8";          //编码
  $mail->From       = 'zhaoganghappy2012@163.com';       //发件人完整的邮箱名称
  $mail->FromName   ="人事部";      //发信人署名
  $mail->Subject    = $subject;         //信的标题
  $mail->MsgHTML($msghtml);           //发信主体内容
  // $mail->AddAttachment("fish.jpg");      //附件
  /*发送邮件*/
  $mail->AddAddress($sendAddress);        //收件人地址
  //使用send函数进行发送
  if($mail->Send()) {
      //发送成功返回真
      return true;
     // echo '您的邮件已经发送成功！！！';
  } else {
     return  $mail->ErrorInfo;//如果发送失败，则返回错误提示
  }
}