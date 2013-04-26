<?
/**
* URL Encoder class
* This class facilitates encoding data to pass between two
* pages using the GET method employing encoding and tamper testing functions
* @package urlencoder
* @author Jason Browatzke <jay@omegapages.com>
* @link http://www.omegapages.com
*/
/**
* URL Encoder class
* @package get_crypt
* @author Jason Browatzke <jay@omegapages.com>
* @link http://www.omegapages.com
*/
class get_crypt{
  // Advanced options
/**
* Conversion Base
* Base is used as a conversion bases from a hexidecimal number to what ever number base you want (between 2 and 36)
* @var integer Conversion base 2 - 36 16=no change
*/
  var $base = 17;
/**
* Key length
* They Key length is used in determining the length of te key used for the tamper testing value
* @var integer Length of key (when added to keylen must be equalto or less than 32)
*/
  var $keyLen = 5;
/**
* Key offset
* They Key offset is used in determining the position of the beginning of the key used in the tamper testing value
* The key is generated using a md5 hash of the key supplied in the {@link get_crypt::get_crypt() CONSTRUCTOR}
* @var integer Offset of md5 string at whick the key is grabbed
*/
  var $keyOffset = 5;
/**
* @var bool Turns on or off debugging
*/
  var $debug = false;


/**
* @access private
*/
  var $key_key = '';
/**
* @access private
*/
  var $key = '';
/**
* @access private
*/
  var $string_data = '';
/**
* @access private
*/
  var $enc_data = '';
/**
* @access private
*/
  var $chop = 2;
/**
* @access private
*/
  var $out = '';
//*************************************************************************************//
//************************          Main functions          ***************************//
//*************************************************************************************//

/**
* Constructor
*
* Initializes our key and our chop values
* @uses get_crypt::set_key() Sets the key
* @uses get_crypt::set_chop() Sets the chop value
* @see get_crypt::set_key(), get_crypt::set_chop()
* @param string $key_key optional If left blank will use current date
* @return void
*/

  function get_crypt($key_key = null){
    if ($key_key!==null){
      $this->key_key = $key_key;
    }else{
			if (function_exists('date_default_timezone_set')) date_default_timezone_set("America/New_York");
      $this->key_key = date('YmdH');
    }
    $this->set_key();
    $this->set_chop();
  }

/**
* Encoding function
*
* Accepts variables in an aray and returns encoded values in a string
* @uses get_crypt::debug() Sends debug information to the screen
* @uses get_crypt::make_url() Urlencodes the array into a string
* @uses get_crypt::obscufate_data() Encodes the data into a string
* @uses get_crypt::add_salt() Adds tamper testing values to the encoded string
* @see get_crypt::debug(), get_crypt::make_url(), get_crypt::obscufate_data(), get_crypt::add_salt()
* @param array $vars Array of named variables
* @return string
*/
  function encode($vars){
    $this->debug($vars,__LINE__,'Input array');
    $this->string_data = $this->make_url($vars);
    $this->debug($this->string_data,__LINE__,'Url encoded data');
    $this->enc_data = $this->obscufate_data();
    $this->debug($this->enc_data,__LINE__,'Encoded data');
    $this->out = $this->add_salt();
    $this->debug($this->out,__LINE__,'Salted data');
    return $this->out;
  }

/**
* Decoding function
*
* Accepts encoded string value and returns decoded array value
* @uses get_crypt::debug() Sends debug information to the screen
* @uses get_crypt::verify_salt() Tests to make sure no tampering has taken place
* @uses get_crypt::strip_salt() Removes the tamper testing values from the encoded string
* @uses get_crypt::deobscufate_data() Decodes stings into url encoded data
* @uses get_crypt::populate_var() Decodes url encoded data into a named array
* @see get_crypt::debug(), get_crypt::verify_salt(), get_crypt::strip_salt(), get_crypt::deobscufate_data(),get_crypt::populate_var()
* @param string $str String of encoded data
* @return array
*/
  function decode($str){
    $this->enc_data = $str;
    $this->debug($this->enc_data,__LINE__,'Encoded data');
    if ($this->verify_salt()===true){
      $this->enc_data = $this->strip_salt();
      $this->debug($this->enc_data,__LINE__,'Saltless data');
      $this->string_data = $this->deobscufate_data();
      $this->debug($this->string_data,__LINE__,'Url encoded data');
      $this->out = $this->populate_var();
      $this->debug($this->out,__LINE__,'Output array');
      return $this->out;
    }else{
      return false;
    }
  }

//*************************************************************************************//
//************************          Main functions          ***************************//
//*************************************************************************************//


/**
* Sets tamper testing key
*
* Accepts nothing and returns nothing called from {@link get_crypt::encode()}
* @uses get_crypt::debug() Sends debug information to the screen
* @see get_crypt::debug()
* @uses get_crypt::keylen
* @param void
* @return void
*/
  function set_key(){
    if ($this->keyLen + $this->keyOffset>32){
      $this->keyOffset = 5;
      $this->keyLen = 5;
    }
    $this->key = substr(md5($this->key_key),$this->keyOffset,$this->keyLen);
    $this->debug($this->key,__LINE__,'Key');
  }

/**
* Sets chop value
*
* Sets the chop value used internally for encoding and decoding purposes
* The chop value is derived from the length of the base converted value 255
* @uses get_crypt::debug() Sends debug information to the screen
* @see get_crypt::debug(), get_crypt::base, get_crypt::chop
* @param void
* @return void
*/
  function set_chop(){
    $this->chop = strlen(strval(base_convert(255,10,$this->base)));
    $this->debug("$this->chop",__LINE__,'Chop');

  }

/**
* Creates url encoded variable
*
* Accepts a named array of strings and returns a urlencoded version of the array
* called from {@link get_crypt::encode()}
* @see get_crypt::base
* @param array $vars array of named strings
* @return string
*/
  function make_url($vars){
    $str = '';
    foreach($vars as $name => $val){
      if ($str===''){
        $str .= "$name=".urlencode($val);
      }else{
         $str .= "&$name=".urlencode($val);
      }
    }
    return $str;
  }

/**
* Adds zeros infront of strings too short
*
* Prepends zeros to strings that are not as long as the chop value
* called from {@link get_crypt::obscufate_data()}
* @see get_crypt::obscufate_data(), get_crypt::set_chop()
* @param string $str
* @return string
*/
  function leading_zero($str){
    while (strlen($str)<$this->chop){
      $str = '0'.$str;
    }
    return $str;
  }

/**
* Encodes data using the base value
*
* Accepts a named array of strings and returns a urlencoded version of the array
* called from {@link get_crypt::encode()}
* @see get_crypt::base, get_crypt::encode()
* @uses get_crypt::base
* @param void
* @return string
*/
  function obscufate_data(){
    $len = strlen($this->string_data);
    $out = '';
    for($i=0;$i < $len;$i++){       // iterate through the string
      $out .= $this->leading_zero(strval(base_convert(dechex(ord($this->string_data{$i})),16,$this->base))); // convet the base of the hex value of the ordinal value of the char to base 17 encode as a string and append to the output string;
    }
    return $out;
  }

/**
* Adds tamper protection string to encoded data
*
* Accepts nothing and returns the final product of {@link get_crypt::encode()}
* called from {@link get_crypt::encode()}
* @see get_crypt::encode(), get_crypt::keylen
* @uses get_crypt::keylen
* @param void
* @return string
*/
  function add_salt(){
    $len = strlen($this->enc_data);
    $middle = round($len / 2);
    $this->debug($middle,__LINE__,'Original middle value');
    $len = floor($this->keyLen / 2);
    $diff = ($this->keyLen - ($len * 2));

    $start = substr($this->key,0,$len);
    $mid = substr($this->key,$len,$diff);
    $finish = substr($this->key,-$len);

    $front = substr($this->enc_data,0,$middle);
    $back = substr($this->enc_data,-$middle);

    $out = "$start$front$mid$back$finish";
    return $out;
  }

/**
* Tests tamper protection string to check for tampering
*
* Accepts nothing and returns true on success false on failure
* called from {@link get_crypt::decode()}
* @see get_crypt::decode(), get_crypt::keylen
* @uses get_crypt::keylen
* @param void
* @return bool
*/
  function verify_salt(){
    $middle = round((strlen($this->enc_data) - $this->keyLen) / 2);
    $this->debug($middle,__LINE__,'Verify middle value');

    $len = floor($this->keyLen / 2);
    $diff = ($this->keyLen - ($len * 2));

    $start = substr($this->key,0,$len);
    $mid = substr($this->key,$len,$diff);
    $finish = substr($this->key,($len * -1));


    $f = substr($this->enc_data,0,$len);
    $m = substr($this->enc_data,$middle + $len,$diff);
    $b = substr($this->enc_data,-$len);
    return ("$f$m$b" == $this->key);
  }

/**
* Removes tamper protection string from encoded data
*
* Accepts nothing and returns encoded string less the tamper protection string
* The {@link get_crypt::set_chop() chop}} value is VERY important here
* called from {@link get_crypt::decode()}
* @see get_crypt::decode(), get_crypt::set_chop(), get_crypt::keylen
* @uses get_crypt::keylen
* @param void
* @return string
*/
  function strip_salt(){
    $len = floor($this->keyLen / 2);
    $diff = $this->keyLen - (2 * $len);


    $this->enc_data = substr($this->enc_data,$len);
    $this->enc_data = substr($this->enc_data,0,-$len);

    $mid = round(strlen($this->enc_data) / 2) - $diff;
    $this->debug($mid,__LINE__,'Decode middle value');

    $mm = 0;
    if (fmod(strlen($this->enc_data)-$diff,$this->chop)!=0){
      $mm++;
    }


    $start = substr($this->enc_data,0,$mid);
    $finish = substr($this->enc_data,-($mid - $mm));
    $out = "$start$finish";

    return $out;
  }

/**
* Decodes encoded data
*
* Accepts nothing and returns url encoded string
* The {@link get_crypt::set_chop() chop}} value is VERY important here as well as the {@link get_crypt::base base} value
* called from {@link get_crypt::decode()}
* @see get_crypt::decode(), get_crypt::set_chop(), get_crypt::base
* @uses get_crypt::base Uses this value for conversion
* @uses get_crypt::chop Uses this value for item seperation
* @param void
* @return string
*/
  function deobscufate_data(){
    $tempdata = wordwrap($this->enc_data,$this->chop,':',1);
    $temparr = explode(':',$tempdata);
    $out = '';
    foreach($temparr as $val){
      $out.= chr(hexdec(base_convert($val,$this->base,16)));
    }
    return $out;
  }

/**
* Populates an array by decoding the url encoded data
*
* Accepts nothing and returns an array containing decoded values
* called from {@link get_crypt::decode()}
* @see get_crypt::decode()
* @param void
* @return array
*/
  function populate_var(){
    $arr = explode('&',$this->string_data);
    $out = array();
    foreach($arr as $val){
      @list($name,$value) = @explode('=',$val);
      $out[$name]=urldecode($value);
    }
    return $out;
  }

/**
* Output debug information
*
* Accepts the data the line number as well as a title
* called from {@link get_crypt::decode()}
* @param mixed $data String data or array data to output
* @param integer $line optional Line number that the debug info came from
* @param string $title optional Title for debug line
* @return void
*/
  function debug($data,$line = __LINE__,$title = ''){
    if ($this->debug){
      if (is_array($data)){
        echo "<b>Debug - line: <i>$line</i>:</b>&nbsp;<code><u>$title</u><hr /><pre>";
        print_r($data);
        echo "</pre><hr />";
      }else{
        echo "<B>Debug - line <i>$line</i>:</b> <code><u>$title</u> $data</code><br />\r\n";
      }
    }
  }


}

/**
* Test class for {@link get_crypt GET_CRYPT}
*
* This tester class will iterate through the {@link get_crypt::base bases} of 2 through 36
* while iterating through {@link get_crypt::keyLen KeyLen's} from 5 to 27. This is done 100 times couting time and success ratio
* @subpackage test_crypt
* @uses get_crypt
* @link http://www.omegapages.com
*/
class test_crypt extends get_crypt{

/**
* @access private
*/
  var $test_vals = array();
/**
* @access private
*/
  var $testpart1 = '';
/**
* @access private
*/
  var $testpart2 = '';

/**
* This function counts the seconds between two times
*
* This function is called from {@link test_crypt::test_crypt() test_crypt::test_crypt} and is used for timing test operations
* @param float $start Start time in milliseconds
* @return float
*/
  function elapsed($start){
    $end = microtime();
    list($start2, $start1) = explode(" ", $start);
    list($end2, $end1) = explode(" ", $end);
    $diff1 = $end1 - $start1;
    $diff2 = $end2 - $start2;
    if ($diff2 < 0 ){
        $diff1 -= 1;
        $diff2 += 1.0;
    }
    return $diff2 + $diff1;
  }

/**
* This function returns random strings
*
* This function is called from {@link test_crypt::generate_vals() test_crypt::generate_vals} and is used for creating strings to pupolate an array
* @param bool $long Determine wether or not to return a long string
* @return string
*/
  function generate_string($long){
    ($long) ? $to = 10 : $to = 0;
    $out = '';
    for($i=0;$i<=mt_rand(1+$to,10 + $to);$i++){
      $out .= chr(97+mt_rand(0,26));
    }
    return $out;
  }

/**
* This function returns an array or random named strings
*
* This function is called from {@link test_crypt::test_bases() test_crypt::test_bases} and is used for populating an array with values
* @uses test_crypt::generate_string()
* @param bool $long Determine wether or not to return a long string
* @return string
*/
  function generate_vals(){
    $array = array();
    for($i=0;$i<=mt_rand(1,10);$i++){
      $str1 = $this->generate_string(false);
      $str2 = $this->generate_string(true);
      $array[$str1]=$str2;
    }
    $this->test_vals = $array;
  }

/**
* This function detemines if the encoded and decoded values are identical
*
* This function is called from {@link test_crypt::test_bases() test_crypt::test_bases} testing the input and output values for identicality
* @param void
* @return void
*/
  function comparevalues(){
    return ($this->test_vals===$this->testpart2);
  }

/**
* This function is the bulk of the testing here we iterate through the bases 2 through 36
*
* This function is called from {@link test_crypt::test_keys() test_crypt::test_keys} and tests the bases between 2 and 36 with the key provided
* @uses test_crypt::generate_vals()
* @uses get_crypt::set_chop()
* @uses get_crypt::encode()
* @uses get_crypt::decode()
* @see get_crypt::base, test_crypt::generate_vals(), get_crypt::set_chop(), get_crypt::encode(), get_crypt::decode()
* @param void
* @return void
*/
  function test_bases(){
    for($i=2;$i<=36;$i++){
      $this->base = $i;
      $this->set_chop();
      $this->generate_vals();
      $this->testpart1 = $this->encode($this->test_vals);
      $this->testpart2 = $this->decode($this->testpart1);
      if (!$this->comparevalues()){
        echo "Failed!";
      }
    }
  }

/**
* This function iterates through the {@link get_crypt::keyLen keyLen} between 5 and 27 (keeping {@link get_crypt::keyOffset keyOffset} at 5)
*
* This function is called from {@link test_crypt::test_crypt() test_crypt::test_crypt} and iterates through {@link get_crypt:keyLen keyLen} betwen 5 and 27
* @uses test_crypt::test_bases()
* @uses get_crypt::set_key()
* @see get_crypt::keyLen, test_crypt::test_bases(), get_crypt::set_key()
* @param void
* @return void
*/
  function test_keys(){
    for($i=5;$i<=27;$i++){
      $this->keyLen = $i;
      $this->set_key();
      $this->test_bases();
    }
  }

/**
* This is the constructor for this class
*
* This class initialisez the {@link get_crypt get_crypt} object and then starts iterating the values
* @uses get_crypt::get_crypt()
* @uses test_crypt::test_keys()
* @uses test_crypt::elapsed()
* @see get_crypt::debug, test_crypt::elapsed(), get_crypt::get_crypt()
* @param void
* @return void
*/
  function test_crypt(){
    set_time_limit(0);
    $this->get_crypt();
    $this->debug = true;
    $test_start = microtime();
    for($i=1;$i<=100;$i++){
      echo "Test $i: ";
      $start = microtime();
      $this->test_keys();
      echo "Successful";
      $time = round($this->elapsed($start),2);
      echo " - $time seconds\r\n";
    }

    $time = round($this->elapsed($test_start),2);
    echo "Total test time:$time seconds\r\n";

  }

}

//$test = new test_crypt();

?>