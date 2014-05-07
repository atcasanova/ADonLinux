#!/bin/bash
. base
login=$1
senha=$2

shift 2

checkLogin
[ $needjoin -eq 1 ] && sudo net ads join -U$login%$senha -W $domain &> /dev/null

desativar=$*

for i in $desativar
do
	php5 desativar.php $login $senha $i
	echo $i >> upload/desativacao-$login-$data
done
