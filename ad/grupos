#!/bin/bash
. base

while getopts "L:S:G:U:" option
do
	case "$option" in
	L)	login="$OPTARG";;
	S)	senha="$OPTARG";;
	G)	GRPS="$OPTARG";;
	U)	USRS="$OPTARG";;
	esac
done

checkLogin
mostra=0;

[ $needjoin -eq 1 ] && sudo net ads join -U$login%$senha -W $domain &> /dev/null

for group in $GRPS
do
	for user in $USRS
	do
		net rpc group addmem $group $user -S $servidor -U$login%$senha &> .tmp$$
		[ $? -eq 0 ] && {
			printOK "Usuário <b>$user</b> adicionado ao grupo <b>$group</b>.";
			mostra=1;
			echo $user-$group >> upload/grupos-$login-$data;
			} || {
				[[ $(grep MEMBER_IN_GROUP .tmp$$) ]] && printWarning "Usuário <b>$user</b> já está no grupo <b>$group</b>";
				[[ $(grep "Could not lookup group name" .tmp$$) ]] && printError "Grupo <b>$group</b> inexistente";
				[[ $(grep NT_STATUS_NONE_MAPPED .tmp$$) ]] && printError "Usuário <b>$user</b> inexistente";
			}
		rm .tmp$$
	done
done

[ $mostra -eq 1 ] && printOK "É necessário fazer novo logon na estação"
