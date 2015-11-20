# module-security-zf2
modulo de autenticação e autorização conforme ACL com Doctrine 2 e ZF2 para sistema com usuário funcionário, baseado na estrutura ZendSkeleton.

# Requerimentos
Zend Framework 2 instalado.
Doctrine 2 para Zend Framework 2 instalado.
Library de Cache Zend Framework 2.

# Instalação
Após clonar ou baixar o módulo security, adicione ele ao seu projeto na estrutura ZendSkeleton.
Sete as suas configurações de conexão de banco de dados no arquivo config/autoload/doctrine_orm.local.php do jeito tradicional.

Execute os scripts sql que estão dentro do diretório files/scripts_sql/, na ordem descrita abaixo:
grupo.sql
recurso_sistema.sql
permissao_acl.sql
usuario.sql
funcionario.sql

Este script já vem com dados para popular parcialmente sua estrutura de autenticação e autorização. Inclusive já vem com um usuário no grupo Super Administrador com as credencias:
login: erico.leandro
senha:1234

Adicionar a seguinte chave no final do array principal, no seu arquivo config/application.config.php 

'cache' => array(
        'adapter' => 'filesystem'
    )
    
# Personalizando recursos do módulo 

Após executar todo o processo logue no sistema com as credencias citadas acima e faça as alterações de login, senha, 
permissões e etc conforme sua necessidade. Você tambem é livre para criar grupos de usuários, usuários (funcionários), criar permissões de acesso para os grupos e etc.
As opções dos recursos a serem gerenciados no Controle de acesso de acesso, serão carregadas conforme você vá criando Controllers e actions na sua aplicação, desde que ela esteja no padrão ZendSkeleton.
Ao selecionar um grupo para gerenciar sua permissões, o módulo irá carregar as permissões nos recursos do sistema que aquele grupo possuí, previamente marcados dentre todos os recursos disponíveis no sistema. Ao configurar e salvar as permissões para o grupo, a aplicação irá salvar as configurações no banco de dados e reescrever esse dados na acl do sistema.

Caso você queira é possível adicionar Urls Desprotegidas da ACL, ou seja recurso que você quer conceder acesso a qualquer Grupo de usuário, para que você não necessite setar permissões de acesso para cada grupo de usuário. Basta adicionar este recurso no método: getRecursosDesprotegidosAcl(), presente no repository RecursoSistema, desde que esse recurso esteja no seguite padrão de string:
Nome_do_modulo\Controller\Seu_controllerController\sua_action







