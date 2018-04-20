<?php
/**
	*常用对称加密算法类
	*支持密钥：64/128/256bit（字节长度8/16/32）
	*支持算法：DES/AES（根据密钥长度自动匹配使用：DES:64bitAES:128/256bit）
	*支持模式：CBC/ECB/OFB/CFB
	*密文编码：base64字符串/十六进制字符串/二进制字符串流
	*填充方式:PKCS5Padding（DES）
*/
class Xcrypt{
	private $mcrypt;
	private $key;
	private $mode;
	private $iv;
	private $blocksize;
	/**
	   *构造函数
	   *
	   *@paramstring密钥
	   *@paramstring模式
	   *@paramstring向量（"off":不使用/"auto":自动/其他:指定值，长度同密钥）
	*/
	public function __construct($key, $mode='cbc', $iv="off"){
		switch(strlen($key)){
			case 8:
				$this->mcrypt = MCRYPT_DES;
				break;
			case 16:
				$this->mcrypt = MCRYPT_RIJNDAEL_128;
				break;
			case 32:
				$this->mcrypt = MCRYPT_RIJNDAEL_256;
				break;
			default:
				die("Keysizemustbe8/16/32");
		}

		$this->key=$key;

		switch(strtolower($mode)){
			case 'ofb':
				$this->mode = MCRYPT_MODE_OFB;
				if($iv == 'off') die('OFBmustgiveaIV');//OFB必须有向量
				break;
			case 'cfb':
				$this->mode = MCRYPT_MODE_CFB;
				if($iv == 'off') die('CFBmustgiveaIV');//CFB必须有向量
				break;
			case 'ecb':
				$this->mode = MCRYPT_MODE_ECB;
				$iv = 'off';//ECB不需要向量
				break;
			case 'cbc':
			default:
				$this->mode = MCRYPT_MODE_CBC;
		}

		switch(strtolower($iv)){
			case "off":
				$this->iv = null;
				break;
			case "auto":
				$source = PHP_OS == 'WINNT' ? MCRYPT_RAND : MCRYPT_DEV_RANDOM;
				$this->iv = mcrypt_create_iv(mcrypt_get_block_size($this->mcrypt, $this->mode), $source);
				break;
			default:
				$this->iv = $iv;
		}
	}
	/**
	   *获取向量值
	   *@paramstring向量值编码（base64/hex/bin）
	   *@returnstring向量值
	*/
	public function getIV($code = 'base64'){
		switch($code){
			case 'base64':
				$ret = base64_encode($this->iv);
				break;
			case 'hex':
				$ret = bin2hex($this->iv);
				break;
			case 'bin':
			default:
				$ret = $this->iv;
		}
		return $ret;
	}
	/**
	   *加密
	   *@paramstring明文
	   *@paramstring密文编码（base64/hex/bin）
	   *@returnstring密文
	*/
	public function encrypt($str, $code='base64'){
		if($this->mcrypt == MCRYPT_DES) $str = $this->_pkcs5Pad($str);
		if(isset($this->iv)){
			$result = mcrypt_encrypt($this->mcrypt, $this->key, $str, $this->mode, $this->iv);
		}else{
			@$result = mcrypt_encrypt($this->mcrypt, $this->key, $str, $this->mode);
		}
		switch($code){
			case 'base64':
				$ret = base64_encode($result);
				break;
			case 'hex':
				$ret = bin2hex($result);
				break;
			case 'bin':
			default:
				$ret = $result;
		}
		return $ret;
	}
	/**
	   *解密
	   *@paramstring密文
	   *@paramstring密文编码（base64/hex/bin）
	   *@returnstring明文
	*/
	public function decrypt($str, $code="base64"){
		$ret = false;
		switch($code){
			case 'base64':
				$str = base64_decode($str);
				break;
			case 'hex':
				$str = $this->_hex2bin($str);
				break;
			case 'bin':
			default:
		}
		if($str !== false){
			if(isset($this->iv)){
				$ret = mcrypt_decrypt($this->mcrypt, $this->key, $str, $this->mode, $this->iv);
			}else{
				@$ret = mcrypt_decrypt($this->mcrypt,$this->key,$str,$this->mode);
			}
			if($this->mcrypt == MCRYPT_DES) $ret = $this->_pkcs5Unpad($ret);
		}
		return $ret;
	}

	private function _pkcs5Pad($text){
		$this->blocksize = mcrypt_get_block_size($this->mcrypt,$this->mode);
		$pad= $this->blocksize - (strlen($text) % $this->blocksize);
		return $text.str_repeat(chr($pad),$pad);
	}

	private function _pkcs5Unpad($text){
		$pad = ord($text{strlen($text)-1});
		if($pad > strlen($text)) return false;
		if(strspn($text,chr($pad), strlen($text) - $pad) != $pad) return false;
		$ret = substr($text, 0, -1*$pad);
		return $ret;
	}

	private function _hex2bin($hex=false){
		$ret = $hex!==false && preg_match('/^[0-9a-fA-F]+$/i',$hex) ? pack("H*", $hex) : false;
		return $ret;
	}
}


header('Content-Type:text/html;Charset=utf-8;');

// 设置密码
$a = '123456';
//密钥
$key = '12345678123456781234567812345678';//256bit算法AES
//设置模式和IV
$m = new Xcrypt($key, 'cbc', 'auto');
//CBC模式才需要向量值
//获取向量值
echo '向量：';
echo $m->getIV();
echo '<br/>';
//加密
$b = $m->encrypt($a, 'base64');
echo '加密后：';
echo $b;
echo '<br/>';
//解密
$c = $m->decrypt($b, 'base64');
echo '解密后：';
echo $c; 
echo '<br/>';