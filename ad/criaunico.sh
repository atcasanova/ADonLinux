#!/bin/bash
. base.sh
[ $# -lt 3 ] && { printError "Numero de argumentos insuficiente." ; exit 1; }

ano=$(date "+%Y")
login=$1
senha=$2
args=$(echo $3|tr '[A-Z]' '[a-z]')

checkLogin

[ $needjoin -eq 1 ] && sudo net ads join -U$login%$senha -W $domain &> /dev/null

oIFS="$IFS"
IFS=,
read username firstname lastname UF grupos <<< "$args"
IFS="$oIFS"

php5 criar.php $username "${firstname~}" "${lastname~}" ${UF^^} $login $senha $ano

./senhas.sh $login $senha $username 2> /dev/null

echo "$args" > upload/criacao-$login-$data

./grupos.sh -L $login -S $senha -G "$grupos" -U "$username"
