<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
 use Snipe\BanBuilder\CensorWords;

class BadwordController extends Controller
{
  public function badword(){
   	$yourstring="fuck this shit";
	$censor = new CensorWords;
	$string = $censor->censorString($yourstring);
	//print_r($string);
	//echo $yourstring;
	echo $string['clean'];
	}
}
