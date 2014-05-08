#!/bin/bash
. base.sh

login=$1
senha=$2
uf=$3
reset=$4

checkLogin

[ $needjoin -eq 1 ] && sudo net ads join -U$login%$senha -W $domain &> /dev/null


shift 4

ativar=$*

for i in $ativar
do
	php5 ativar.php $login $senha $uf $i
	[ $reset -eq 1 ] && /var/www/sis/ad/senhas.sh $login $senha $i
	echo $i >> upload/ativacao-$login-$data
done
