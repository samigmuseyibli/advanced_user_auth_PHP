<?php
class Captcha_model{
	var $bg_color = '#ffffff00';

    
    public function createCaptcha(){
		//echo 'aaa';
         $cap_characters   = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
         $cap_word_length  = 5;
         $cap_img_width    = 200;
         $cap_img_height   = 50;
         $cap_font_path    = './assets/captcha/aaa_aaa.ttf';
         $cap_font_size    = 27;
         $cap_text_color   = '#870dc3';
         $cap_grid_color   = '#ffb6b6';
         $cap_shadow_color = '#fff0f0'; 
         $cap_border_color = '#999'; 
        $str = '';//exit();
        for ($i = 0; $i < $cap_word_length; $i++){
            $str .= substr($cap_characters, mt_rand(0, strlen($cap_characters) -1), 1);
        }
        $word = $str;
		if(!$this->is_session_started()) {
            session_start();
        }
		//unset($_SESSION['captchaCode']);
        $_SESSION['captchaCode'] = $word;
		/* Determine angle and position */
		$length	= strlen($word);
		$angle	= ($length >= 6) ? rand(-($length-6), ($length-6)) : 0;
		$x_axis	= rand(6, (360/$length)-16);
		$y_axis = ($angle >= 0 ) ? rand($cap_img_height, $cap_img_width) : rand(6, $cap_img_height);

		/* Create image */
		if (function_exists('imagecreatetruecolor')){
			$im = imagecreatetruecolor($cap_img_width, $cap_img_height);
		}else{
			$im = imagecreate($cap_img_width, $cap_img_height);
		}
		//////$im = imagecreatefrompng($cap_image);

		/* Assign colors */
		$bgColorRgb = $this->hexToRgb($this->bg_color);
		$borderColorRgb = $this->hexToRgb($cap_border_color);
		$textColorRgb = $this->hexToRgb($cap_text_color);
		$gridColorRgb = $this->hexToRgb($cap_grid_color);
		$shadowColorRgb = $this->hexToRgb($cap_shadow_color);
		$bg_color		= imagecolorallocate ($im, $bgColorRgb[0], $bgColorRgb[1], $bgColorRgb[2]);
		$border_color	= imagecolorallocate ($im, $borderColorRgb[0], $borderColorRgb[1], $borderColorRgb[2]);
		$text_color		= imagecolorallocate ($im, $textColorRgb[0], $textColorRgb[1], $textColorRgb[2]);
		$grid_color		= imagecolorallocate($im, $gridColorRgb[0], $gridColorRgb[1], $gridColorRgb[2]);
		$shadow_color	= imagecolorallocate($im, $shadowColorRgb[0], $shadowColorRgb[1], $shadowColorRgb[2]);

		/* Create the rectangle */
		ImageFilledRectangle($im, 0, 0, $cap_img_width, $cap_img_height, $bg_color);
		$theta		= 1;
		$thetac		= 7;
		$radius		= 16;
		$circles	= 20;
		$points		= 32;

		for ($i = 0; $i < ($circles * $points) - 1; $i++){
			$theta = $theta + $thetac;
			$rad = $radius * ($i / $points );
			$x = ($rad * cos($theta)) + $x_axis;
			$y = ($rad * sin($theta)) + $y_axis;
			$theta = $theta + $thetac;
			$rad1 = $radius * (($i + 1) / $points);
			$x1 = ($rad1 * cos($theta)) + $x_axis;
			$y1 = ($rad1 * sin($theta )) + $y_axis;
			imageline($im, $x, $y, $x1, $y1, $grid_color);
			$theta = $theta - $thetac;
		}

		/* Write the text in image */
		$use_font = ($cap_font_path != '' AND file_exists($cap_font_path) AND function_exists('imagettftext')) ? TRUE : FALSE;

		$x = rand(0, $cap_img_width/($length/1.5));
		$y = $cap_font_size+2;

		for ($i = 0; $i < strlen($word); $i++)
		{
			if ($use_font == FALSE){
				$y = rand(0 , $cap_img_height/2);
				imagestring($im, $cap_font_size, $x, $y, substr($word, $i, 1), $text_color);
				$x += ($cap_font_size);
			}else{
				$y = rand($cap_img_height/2, $cap_img_height-3);
				imagettftext($im, $cap_font_size, $angle, $x, $y, $text_color, $cap_font_path, substr($word, $i, 1));
				$x += $cap_font_size;
			}
		}
		/* Create the image border */
		imagerectangle($im, 0, 0, $cap_img_width-1, $cap_img_height-1, $border_color);
		/* Showing the image */
		imagejpeg($im,NULL,90);
		//header('Content-Type: image/jpeg');
		imagedestroy($im);
    }
	
	public function hexToRgb($hex){
		$hex = str_replace("#", "", $hex);
		if(strlen($hex) == 3) {
		   $r = hexdec(substr($hex,0,1).substr($hex,0,1));
		   $g = hexdec(substr($hex,1,1).substr($hex,1,1));
		   $b = hexdec(substr($hex,2,1).substr($hex,2,1));
		} else {
		   $r = hexdec(substr($hex,0,2));
		   $g = hexdec(substr($hex,2,2));
		   $b = hexdec(substr($hex,4,2));
		}
		$rgb = array($r, $g, $b);
		return $rgb;
	}
	protected function is_session_started()
    {
        if ( php_sapi_name() !== 'cli' ) {
            if ( version_compare(phpversion(), '5.4.0', '>=') ) {
                return session_status() === PHP_SESSION_ACTIVE ? TRUE : FALSE;
            } else {
                return session_id() === '' ? FALSE : TRUE;
            }
        }
        return FALSE;
    }
	public function check($value){
		if(isset($_SESSION['captchaCode'])){
			if(strtolower($_SESSION['captchaCode']) == strtolower($value)){
				unset($_SESSION['captchaCode']);
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
}
?>