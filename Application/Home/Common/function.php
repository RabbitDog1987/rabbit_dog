<?php 
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