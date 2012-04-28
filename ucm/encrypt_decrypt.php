<?php
define('SALT', 'youcuremedotcomactivationlink');   

function encrypt_userid($text)  
{      
	return trim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, SALT, $text, 
	MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, 
	MCRYPT_MODE_ECB), MCRYPT_RAND))));  
}   

function decrypt_userid($text)  
{      
	return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, SALT, base64_decode($text), 
	MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, 
	MCRYPT_MODE_ECB), MCRYPT_RAND)));  
}   

//$encryptedmessage = encrypt("your message");  
//echo decrypt($encryptedmessage); 
?>