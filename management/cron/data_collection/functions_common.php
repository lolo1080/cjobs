<?
//Try to correct 50,000=>50000 and 50.000=>50000
function correct_salary_by_comma($amount)
{
	$pos = strpos($amount,",000");
	if ($pos !== false) return (int)$amount*1000;
	$pos = strpos($amount,".000");
	if ($pos !== false) return (int)$amount*1000;
	if (preg_match("~(\d+)[\.,]*(\d*)[\.,]*(\d*)~si", $amount, $matches)) {
		$c = $matches[1];
		if ($matches[2] != "") $c = $c*1000+$matches[2];
		if ($matches[3] != "") $c = (float)$c.'.'.$matches[3];
		return $c;
	}
 return (int)$amount;
}

//Try to understand job salary
function understand_job_salary($salary)
{
	if ($salary == "") return 0;

	$salary = stripslashes($salary);
	$salary = strtolower($salary);

	$pos1 = strpos($salary,"-");
	$pos2 = strpos($salary,"to");
//	$expr = (($pos1 !== false) || ($pos2 !== false)) ? "~\\\$(\d+[\.,]*\d*)(k)*?\s+[-to]*?\s+\\\$(\d+[\.,]*\d*)(k)*?~si" : "~\\\$(\d+[\.,]*\d*)(k)*?~si";
	$expr = (($pos1 !== false) || ($pos2 !== false)) ? "~(\d+[\.,]*\d*[\.,]*\d*)(k)*?\s*[\-to]+?\s*[\$]*(\d+[\.,]*\d*[\.,]*\d*)(k)*?~si" : "~(\d+[\.,]*\d*)(k)*?~si";
	if ( $c = preg_match($expr, $salary, $matches) ) {
		$pos = strpos($salary,"hour");
		$h = ($pos !== false) ? 8*22*12 : 1;
		$k = (isset($matches[2]) && ($matches[2] == "k")) ? 1000 : 1;
		if (($h == 1) && ($k == 1)) {
			$matches[1] = correct_salary_by_comma($matches[1]);
			if (isset($matches[3]) && ($matches[3] != "")) $matches[3] = correct_salary_by_comma($matches[3]);
		}
		if (!isset($matches[3]) || ($matches[3] == "")) $matches[3] = $matches[1];
		return ($matches[1]+round( abs($matches[3]-$matches[1])/2 ))*$h*$k;
	}
 return 0;
}

//Check month ago and chnage this value if need
function check_month_ago($n,$period_str,&$CurDt)
{
 global $ExitByDateLimit;
	if (!$ExitByDateLimit) return;
	$pos = strpos(strtolower($period_str), "month");
	if ($pos === false) return;
	if (check_int($n)) return;
	if ($n > $CurDt) $CurDt = $n;
}

//Check month ago and chnage this value if need
function check_month_ago_from_date($month,$day,$year,&$CurDt)
{
 global $ExitByDateLimit;
	if (!$ExitByDateLimit) return;
	if (check_int($month)) return;
	elseif (check_int($day)) return;
	elseif (check_int($year)) return;
	$sec = time() - mktime(0,0,0,$month,$day,$year);
	$n = floor($sec/60/60/24/30);
	if ($n > $CurDt) $CurDt = $n;
}

//Check month ago and chnage this value if need
function check_month_ago_by_month_day($month,$day,&$CurDt)
{
 global $ExitByDateLimit;
	$month3_array = array("jan"=>1,"feb"=>2,"mar"=>3,"apr"=>4,"may"=>5,"jun"=>6,"jul"=>7,"aug"=>8,"sep"=>9,"oct"=>10,"nov"=>11,"dec"=>12);
	if (!$ExitByDateLimit) return;
	if (!isset($month3_array[strtolower($month)])) return;
	$month = $month3_array[strtolower($month)];
	if (check_int($day)) return;
	$cur_month = date("n",time())."<br>";
	if ($cur_month < $month) $year = date("Y",time()) - 1;
	else $year = date("Y",time());
	$sec = time() - mktime(0,0,0,$month,$day,$year);
	$n = floor($sec/60/60/24/30);
	if ($n > $CurDt) $CurDt = $n;
}

//Check month ago and chnage this value if need
function check_month_ago_by_full_date($month,$day,$year,&$CurDt)
{
 global $ExitByDateLimit;
	$month3_array = array("jan"=>1,"feb"=>2,"mar"=>3,"apr"=>4,"may"=>5,"jun"=>6,"jul"=>7,"aug"=>8,"sep"=>9,"oct"=>10,"nov"=>11,"dec"=>12);
	if (!$ExitByDateLimit) return;
	if (!isset($month3_array[strtolower($month)])) return;
	$month = $month3_array[strtolower($month)];
	if (check_int($day)) return;
	if (check_int($year)) return;
	$sec = time() - mktime(0,0,0,$month,$day,$year);
	$n = floor($sec/60/60/24/30);
	if ($n > $CurDt) $CurDt = $n;
}

function correct_salary_for_sel2($amount)
{
	$amount = stripslashes($amount);
	$amount = strtolower($amount);
	$amount = str_replace(",", "", $amount);
	return (float)$amount;
}
//For date range in format $56,000.00 - $89,000.00
function understand_job_salary2($from_salary, $to_salary, $period)
{
	if ($from_salary == "") return 0;
	if ($to_salary == "") return 0;

	$from_salary = correct_salary_for_sel2($from_salary);
	$to_salary = correct_salary_for_sel2($to_salary);

	switch (strtolower($period)) {
		case "year": return round(($from_salary + $to_salary)/2);
		case "month": return round(($from_salary + $to_salary)*12/2);
		case "hour": return round(($from_salary + $to_salary)*8*22*12/2);
	}
	return 0;
}

//Try to understand job salary3
function understand_job_salary3($salary)
{
	if ($salary == "") return 0;

	$salary = stripslashes($salary);
	$salary = strtolower($salary);

	$pos1 = strpos($salary,"-");
	$pos2 = strpos($salary,"to");

	$expr = (($pos1 !== false) || ($pos2 !== false)) ? "~(\d+[\.,]*\d*[\.,]*\d*)(k)*?\s*[\-to]+?\s*[\$]*(\d+[\.,]*\d*[\.,]*\d*)(k)*?~si" : "~(\d+[\.,]*\d*)(k)*?~si";
	if ( $c = preg_match($expr, $salary, $matches) ) {
		if (isset($matches[1]) && ($matches[3] != "")) return ($matches[1]+round( abs($matches[3]-$matches[1])/2 ));
		elseif (isset($matches[1])) return $matches[1];
		elseif (isset($matches[3])) return $matches[3];
	}
 return 0;
}
?>
