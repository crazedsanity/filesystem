<?php

namespace crazedsanity\core;
use crazedsanity\core\ToolBox;

function debug_backtrace($printItForMe=NULL,$removeHR=NULL) {
	if(is_null($printItForMe)) {
		if(defined('DEBUGPRINTOPT')) {
			$printItForMe = constant('DEBUGPRINTOPT');
		}
		elseif(isset($GLOBALS['DEBUGPRINTOPT'])) {
			$printItForMe = $GLOBALS['DEBUGPRINTOPT'];
		}
	}
	if(is_null($removeHR)) {
		if(defined('DEBUGREMOVEHR')) {
			$removeHR = constant('DEBUGREMOVEHR');
		}
		elseif(isset($GLOBALS['DEBUGREMOVEHR'])) {
			$removeHR = $GLOBALS['DEBUGREMOVEHR'];
		}
	}
		//create our own backtrace data.
		$stuff = \debug_backtrace();
		if(is_array($stuff)) {
			$i=0;
			foreach($stuff as $num=>$arr) {
				if($arr['function'] !== "debug_print_backtrace") {
					
					$fromClass = '';
					if(isset($arr['class']) && strlen($arr['class'])) {
					$fromClass = $arr['class'] .'::';
				}
				
				$args = '';
				foreach($arr['args'] as $argData) {
					$args = ToolBox::create_list($args, ToolBox::truncate_string(ToolBox::debug_print($argData, 0, 1, false), 600), ', ');
				}
				
				$fileDebug = "";
				if(isset($arr['file'])) {
					$fileDebug = " from file <u>". $arr['file'] ."</u>, line #". $arr['line'];
				}
				$tempArr[$num] = $fromClass . $arr['function'] .'('. $args .')'. $fileDebug;
				
			}
		}
		
		array_reverse($tempArr);
		$myData = null;
		foreach($tempArr as $num=>$func) {
			$myData = ToolBox::create_list($myData, "#". $i ." ". $func, "\n");
			$i++;
		}
	}
	else {
		//nothing available...
		$myData = $stuff;
	}
	
	$backTraceData = ToolBox::debug_print($myData, $printItForMe, $removeHR);
	return($backTraceData);
}//end cs_debug_backtrace()

function get_where_called() {
	$stuff = \debug_backtrace();
	$myData = $stuff[2];
	$fromClass = $myData['class'];
	if(!$fromClass) {
		$fromClass = '**GLOBAL**';
	}
	$retval = $fromClass .'::'. $myData['function'];
	return($retval);
}
