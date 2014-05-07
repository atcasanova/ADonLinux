#!/bin/bash
. base

dos2unix $3
ano=$(date +"%Y")
sed -i '1d' $3
login=$1
senha=$2

checkLogin

[ $needjoin -eq 1 ] && sudo net ads join -U$login%$senha -W $domain &> /dev/null

while read line
do

	oIFS="$IFS"
	IFS=,
	read user first last UF group <<< "$line"
	IFS="$oIFS"

	php5 criar.php $user "${first~}" "${last~}" ${UF^^} $login $senha $ano
	./senhas $login $senha $username 2> /dev/null

	[ ${#group} -gt 0 ] && {
	        for group in $group
        	do
	                net rpc group addmem $group $user -S $servidor -U$login%$senha
                	printOK "Usuário <b>$user</b> adicionado ao grupo <b>$group</b>"
        	done
	}
done < $3

mv $3 upload/criacao-$login-$data
