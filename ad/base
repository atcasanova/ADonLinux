#!/bin/bash
[[ $(sudo net ads testjoin) ]] || needjoin=1 && needjoin=0
data=$(date --rfc-3339=seconds | cut -f1-3 -d- | sed 's/ /-/g' | sed 's/:/-/g')

servidor=10.100.10.3
domain=INTRANET

printOK(){
	echo "<p style='color:#2CA823;'>$*</p>";
}

printError(){
	echo "<p style='color:#C93434;'>$*</p>"
}

printWarning(){
	echo "<p style='color:#D36F17;'>$*</p>";
}

checkLogin(){
	[[ ! $login || ! $senha ]] && { printError "Falha de autenticação"; exit 3; }
}

