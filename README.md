# module-security-zf2
modulo de autenticação e autorização conforme ACL com Doctrine 2 e ZF2 para sistema usuário e funcionário

# Instalação
Sete as suas configurações de conexão de banco de dados no arquivo config/autoload/doctrine_orm.local.php do jeito tradicional, similar ao exemplo abaixo:
<?php
return array(
  'doctrine' => array(
      'connection' => array(
          'orm_default' => array(
              'driverClass' => 'Doctrine\DBAL\Driver\PDOMysql\Driver',
              'params' => array(
                  'host' =>'localhost',
                  'port' => '3306',
                  'user' => 'root',
                  'password' =>'senha',
                  'dbname' => 'seubancodedados',
                  'driveOptions'=> array(
                      PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'"
                  )
              )
          )
      )
  )
);
