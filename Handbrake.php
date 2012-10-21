 

<?php 
	$flag=true;
	$cmd= "./HandBrakeCLI ";
	//MAIN OPTIONS

	//Input File
	$input=htmlspecialchars($_POST['input']);
	$cmd = $cmd." -i ".$input." ";

	//Output File
	$name=str_replace(' ','_',htmlspecialchars($_POST['name']));
	if (strlen($name)==0) {
		$name='ouput';	
	} 
	$format=htmlspecialchars($_POST['format']);
	$cmd= $cmd." -o ".$name.".".$format." ";

	//PICTURE OPTIONS
	$width=htmlspecialchars($_POST['width']);
	$height=htmlspecialchars($_POST['height']);
	if (!is_numeric($width) || !is_numeric($height)) {
		$flag=false;
	}
	else {
		if ($width<1) {$width=1;}
		if ($width>1000) {$width=1000;}
		if ($height<1) {$height=1;}
		if ($height>1000) {$height=1000;}
	}
	if ($_POST['kar']!='on')	 {
		$cmd = $cmd." -w ".$width." -l ".$height." ";
	} else {
		if ($width>$height) {
			$cmd = $cmd." -w ".$width." ";
		}
		else {
			$cmd = $cmd." -l ".$height." ";
		}
		$cmd = $cmd." --keep-display-aspect ";
	}

	//VIDEO OPTIONS
	$cmd =$cmd." -e ".$_POST['encoder']." ";
	if ($_POST['fps']!='source') {
		$cmd =$cmd." -r ".$_POST['fps']." ";
	}

	if ($_POST['vq']=='cons') {
		$quality=htmlspecialchars($_POST['quality']);
		if (!is_numeric($quality)) {
			$flag=false;
		}
		else {
			if ($quality<1) {$quality=1;}
			if ($quality>51) {$quality=51;}
		}
		$cmd=$cmd." -q ".$quality." ";
	}
	if ($_POST['vq']=='avbr') {
		$vbr=htmlspecialchars($_POST['vbitrate']);
		if (!is_numeric($vbr)) {
			$flag=false;
		}
		else {
			if ($vbr<1) {$vbr=1;}
			if ($vbr>100000) {$vbr=100000;}
		}
		$cmd=$cmd." -b ".$vbr." ";
		if ($_POST['twopass']=='on') {
			$cmd = $cmd." -2 ";
			if ($_POST['turbo']=='on') {
				$cmd = $cmd." -T ";
			}
		}
	}
	if ($_POST['vq']=='targsize') {

		$size=htmlspecialchars($_POST['size']);
		if (!is_numeric($size)) {
			$flag=false;
		}
		else {
			if ($size<0) {$size=1;}
			if ($size>1000) {$size=1000;}
		}
		$cmd=$cmd." -S ".$size." ";
	}

	//AUDIO OPTIONS
	if ($_POST['samplingrate']!='auto') {
		$cmd = $cmd." -R ".$_POST['samplingrate']." ";
	}
	$cmd= $cmd." -B ".$_POST['abitrate']." ";
	
	if ($flag==false) {
		echo "One or more invalid inputs were given. Please reneter inputs.";
	} 
	else {
		shell_exec($cmd);
		echo "The requested video coversion has been <b> completed </b> and the output file has been saved as <b>".$name.".".$format."</b>.";
	}
	
?>
