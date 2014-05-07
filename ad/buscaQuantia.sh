#!/bin/bash
. base

login=$1
senha=$2

checkLogin

[ $needjoin -eq 1 ] && sudo net ads join -U$login%$senha -W $domain &> /dev/null

echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">"

php5 buscarQuantia.php $login $senha
