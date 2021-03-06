#!/bin/bash
. base.sh

#verifica quantidade minima de argumentos
[ $# -lt 5 ] && { printError "<b>Erro:</b> Preencha todos os campos."; exit 2; }

# atribuicao de variaveis
login=$1
senha=$2
grupo=$3
docs=$4
shift 4
users="$*"

checkLogin
[ $needjoin -eq 1 ] && sudo net ads join -U$login%$senha -W $domain &> /dev/null

# verifica se o grupo a ser criado ja existe
# retornando mensagem de erro e finalizando
# o script caso exista.
[ $(getent group $grupo) ] && { printError "<b>Erro:</b> Grupo <b>$grupo</b> ja existe."; exit 9; }

[ $docs -eq 0 ] && path="/rede/grupos" || path="/rede/grupos/DocsScaneados"

# adiciona o grupo
net rpc group add $grupo -S $servidor -W $domain -U$login%$senha &> .tmp$$

# se o retorno for OK, apresenta mensagem de sucesso
# caso contrario, exibe mensagem de erro na tela
[ $? -eq 0 ] && {
	printOK "Grupo <b>$grupo</b> criado com sucesso"
} || printError "Erro: $(cat .tmp$$)";
rm .tmp$$

# adicionar usuarios ao grupo
./grupos.sh -L $login -S $senha -G "$grupo" -U "$users"


# criacao de pasta e set de permissoes no servidor de arquivos.
# o servidor deve ter as chaves do usuario que executa o script


# criar pasta e setar permissoes
ssh root@10.100.10.40 "mkdir $path/${grupo^^};"

# permissoes especiais do DocsScaneados
[ $docs -eq 1 ] && ssh root@10.100.10.40 "setfacl -R -m g:docsscaneados:rwx,u:impressorastype:rwx $path/${grupo^^};"

# permissoes comuns a todos
ssh root@10.100.10.40 "setfacl -R -m o:---,g:admingrupos:rwx,g:$idgrupo:rwx $path/${grupo^^}; chmod -R 2770 $path/${grupo^^};"

#[ $? -ne 0 ] && idgrupo=$(ssh root@10.100.10.40 "getent group | grep ^${grupo}:x | cut -f3 -d:")

# loop para corrigir falha do AD
while [ ! $(ssh root@10.100.10.40 "getfacl $path/${grupo^^} | grep $grupo:rwx") ];
do
	ssh root@10.100.10.40 "setfacl -R -m o:---,g:admingrupos:rwx,g:$grupo:rwx $path/${grupo^^}; chmod -R 2770 $path/${grupo^^};"
done;


[ $? -eq 0 ] && {
        printOK "Compartilhamento <b>$path/${grupo^^}</b> criado com sucesso.<br>Todos os usuarios necessitam realizar novo login"
} || printError "Falha de comunicação";

