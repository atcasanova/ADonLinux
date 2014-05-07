#!/bin/bash
. base

login=$1
senha=$2
shift 2
lista=$@

checkLogin

[ $needjoin -eq 1 ] && sudo net ads join -U$login%$senha -W $domain &> /dev/null

echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">"

[ $(echo $lista | grep -E "^([0-9]{3}\.){2}[0-9]{3}-[0-9]{2}$") ] && php5 buscarporcpf.php $login $senha $lista || php5 buscar.php $login $senha $lista
