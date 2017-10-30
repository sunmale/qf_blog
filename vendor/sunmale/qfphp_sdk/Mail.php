<?php

namespace qf;
use qf\mail\PHPMailer;
use qf\mail\Exception;

class Mail
{
    //当前类对象
    private static $instances;
    //邮件发送基本配置
    private $_config = array(
        'qq' => [
            'email_host'             => '',          // smtp
            'email_account'         => '',    // 邮箱账号
            'email_password'         => '',   // 密码  注意: 163和QQ邮箱是授权码；不是登录的密码
            'email_secure'      => '',                    // 链接方式 如果使用QQ邮箱；需要把此项改为  ssl
            'email_port'             => '',              // 端口 如果使用QQ邮箱；需要把此项改为  465
            'email_username'        => '',             // 发件人
        ]
    );
    /**初始化邮件配置(用于自定义邮件参数)
     * @param null $options 自定义的邮件发送参数
     * @return mixed
     */
    public function init($options = null)
    {
        if (!empty($options)) {
            $this->_config = array_merge($this->_config, $options);
        }
        return $this;
    }
    /**
     * 邮件发送功能
     * @param $user //收件人的信息 （email,name）两个字段
     * @param $data //邮件内容 (title,html)两个字段
     * @param string $type //邮件发送的方式  默认是qq邮箱发送
     * @return bool
     */
    public function sendMail($user, $data, $type = 'qq')
    {
        try {
            $mail = new PHPMailer(true);
            $config = $this->_config[$type];
            //Server settings
            $mail->SMTPDebug = 0;                                 // Enable verbose debug output  开启调试模式 （默认 0 表示关闭调试模式）
            $mail->isSMTP();                                       // Set mailer to use SMTP   启用SMTP
            $mail->Host = $config['email_host'];                    // Specify main and backup SMTP servers      服务器地址
            $mail->SMTPAuth = true;                               // Enable SMTP authentication     开启SMTP验证
            $mail->Username = $config['email_account'];                 // SMTP username     SMTP 用户名（你要使用的邮件发送账号）
            $mail->Password = $config['email_password'];                           // SMTP password     SMTP 密码
            $mail->SMTPSecure = $config['email_secure'];                            // Enable TLS encryption, `ssl` also accepted   开启TLS 可选
            $mail->Port = $config['email_port'];                                    // TCP port to connect to     端口
            //Recipients
            $mail->setFrom($config['email_account'], $config['email_username']);       //来自
            $mail->addAddress($user['email'], $user['name']);     // Add a recipient     // 添加一个收件人
            // $mail->addAddress('429143652@qq.com');               // Name is optional     // 可以只传邮箱地址
            //$mail->addReplyTo('1982127547@qq.com', 'Information');          // 回复地址
            // $mail->addCC('cc@example.com');
            //  $mail->addBCC('bcc@example.com');
            //Attachments
            //  $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments       // 添加附件
            //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name       // 可以设定名字
            //Content
            $mail->isHTML(true);                                  // Set email format to HTML            // 设置邮件格式为HTML
            $mail->Subject = $data['title'];
            $mail->Body = $data['html'];
            $mail->AltBody = 'xxx';
            $res = $mail->send();
            return $res;
        } catch (Exception $e) {
            Log::error('出错位置:Mail.php,错误信息' . $mail->ErrorInfo);
            return false;
        }
    }
    /**
     * 获取当前类的对象
     * @return mixed
     * @throws \Exception
     */
    public static function getInstance()
    {
        $args = func_get_args();
        count($args) || $args = array(self::class);
        $key = md5(serialize($args));
        $className = array_shift($args);
        if (!class_exists($className)) {
            throw new \Exception("no class {$className}");
        }
        if (!isset(self::$instances[$key])) {
            $rc = new \ReflectionClass($className);
            self::$instances[$key] = $rc->newInstanceArgs($args);
        }
        return self::$instances[$key];
    }


}



