#!/bin/bash
. base.sh
login=$1
senha=$2

shift 2

checkLogin
[ $needjoin -eq 1 ] && sudo net ads join -U$login%$senha -W $domain &> /dev/null

reset=$*
ano=$(date +"%Y")

for i in $reset
do
	nome=$(getent passwd $i | cut -f5 -d:)
	echo "<p style='color:#2CA823;'>"
	sudo net ads password $i@$domain Nova$ano -U$login@$domain%$senha &> .tmp$$
	[[ $(grep Preauthentication .tmp$$) ]] && printError "Falha de autenticacao";
	[[ $(grep Generic .tmp$$) ]] && printError "Usuario $i nao encontrado";
	[[ $(grep "@$domain completed" .tmp$$) ]] && printOK "Senha para <b>$nome</b> (<b>$i</b>) alterada para Nova$ano.";
	rm .tmp$$
	echo "</p>"
	php5 reset.php $login $senha $i $ano
	echo $i >> upload/reset-$login-$data
done
