<?php


	echo "Warning. This program will overwrite the current contents of the MyWind_XXXX databases\n";
	echo "This is a destructive process, and if there is anything custom there (there should not be) then this will overwrite them\n";
	
	$password = prompt_silent();

	$databases = [
		'MyWind_aaa',
		'MyWind_irs',
		'MyWind_northwind_data',
		'MyWind_northwind_model',
	];

	$commands = [];

	foreach($databases as $this_db){

		$commands[] = "mysqladmin -f -u root --password=$password drop $this_db";
		$commands[] = "mysqladmin -u root --password=$password create $this_db";
		$commands[] = "mysql -u root --password=$password $this_db < $this_db.sql";
	}


	foreach($commands as $this_command){
		$safe_print_command = str_replace($password,'*******',$this_command);
		echo "Running\n$safe_print_command\n";
		system($this_command);
	}




//from https://stackoverflow.com/a/1674175/144364
function prompt_silent($prompt = "Enter Password:") {
  if (preg_match('/^win/i', PHP_OS)) {
    $vbscript = sys_get_temp_dir() . 'prompt_password.vbs';
    file_put_contents(
      $vbscript, 'wscript.echo(InputBox("'
      . addslashes($prompt)
      . '", "", "password here"))');
    $command = "cscript //nologo " . escapeshellarg($vbscript);
    $password = rtrim(shell_exec($command));
    unlink($vbscript);
    return $password;
  } else {
    $command = "/usr/bin/env bash -c 'echo OK'";
    if (rtrim(shell_exec($command)) !== 'OK') {
      trigger_error("Can't invoke bash");
      return;
    }
    $command = "/usr/bin/env bash -c 'read -s -p \""
      . addslashes($prompt)
      . "\" mypassword && echo \$mypassword'";
    $password = rtrim(shell_exec($command));
    echo "\n";
    return $password;
  }
}
