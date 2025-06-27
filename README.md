# 🚀 WebSocket Ratchet Project

Projeto Docker com PHP, MySQL, Composer e phpMyAdmin configurado para desenvolvimento de aplicações WebSocket usando Ratchet.

## 📋 Pré-requisitos

- Docker
- Docker Compose

## 🛠️ Tecnologias

- **PHP 8.2** com Apache
- **MySQL 8.0**
- **Composer** para gerenciamento de dependências
- **phpMyAdmin** para gerenciamento do banco de dados
- **Ratchet** para WebSockets

## 🚀 Como usar

### 1. Clone o repositório

```bash
git clone <seu-repositorio>
cd websocket-ratchet
```

### 2. Iniciar os containers

```bash
# Construir e iniciar todos os serviços
docker-compose up -d --build

# Ou apenas iniciar (se já foi construído)
docker-compose up -d
```

### 3. Instalar dependências do Composer

```bash
# Executar Composer dentro do container
docker-compose run --rm composer install
```

### 3. Inicializar o server websocket

```bash
# Entrar no container
docker compose exec php bash
```

```bash
# Dentro do container
php server.php
```

### 4. Acessar a aplicação

- **Aplicação PHP:** http://localhost:8080
- **phpMyAdmin:** http://localhost:8081
- **MySQL:** localhost:3306

## 📁 Estrutura do projeto

```
websocket-ratchet/
├── docker/
│   ├── php/
│   │   └── php.ini          # Configurações PHP
│   └── mysql/
│       └── init.sql         # Script de inicialização MySQL
├── src/          # Arquivo de teste
├── docker-compose.yml      # Configuração principal
├── docker-compose.override.yml # Configuração de desenvolvimento
├── Dockerfile              # Imagem PHP personalizada
└── README.md              # Este arquivo
```

## 🔧 Comandos úteis

### Gerenciar containers

```bash
# Iniciar serviços
docker-compose up -d

# Parar serviços
docker-compose down

# Ver logs
docker-compose logs -f

# Reconstruir containers
docker-compose up -d --build
```

### Composer

```bash
# Instalar dependências
docker-compose run --rm composer install

# Adicionar nova dependência
docker-compose run --rm composer require nome-do-pacote

# Atualizar dependências
docker-compose run --rm composer update
```

### MySQL

```bash
# Acessar MySQL via linha de comando
docker-compose exec mysql mysql -u root -p

# Backup do banco
docker-compose exec mysql mysqldump -u root -p websocket_ratchet > backup.sql
```

```bash
# Query a ser adicionada no banco de dados
CREATE TABLE `comments` (
    `comment_id` int NOT NULL AUTO_INCREMENT,
    `comment_subject` varchar(250) NOT NULL,
    `comment_text` text NOT NULL,
    `comment_status` int NOT NULL DEFAULT '0',
    PRIMARY KEY (`comment_id`)
) ENGINE=InnoDB;
```

## 🔍 Configurações

### Variáveis de ambiente

- `DB_HOST`: mysql
- `DB_PORT`: 3306
- `DB_DATABASE`: websocket_ratchet
- `DB_USERNAME`: root
- `DB_PASSWORD`: password

### Portas

- **PHP/Apache:** 8080
- **phpMyAdmin:** 8081
- **MySQL:** 3306

### Acesso ao phpMyAdmin

- **URL:** http://localhost:8081
- **Usuário:** root
- **Senha:** password
- **Servidor:** mysql

## 🐛 Solução de problemas

### Container não inicia

```bash
# Verificar logs
docker-compose logs

# Reconstruir sem cache
docker-compose build --no-cache
```

### Problemas de permissão

```bash
# Corrigir permissões
sudo chown -R $USER:$USER ./src
```

### MySQL não conecta

```bash
# Aguardar MySQL inicializar
docker-compose logs mysql

# Verificar se o container está rodando
docker-compose ps
```

### phpMyAdmin não acessa o MySQL

```bash
# Verificar se o MySQL está rodando
docker-compose ps mysql

# Verificar logs do phpMyAdmin
docker-compose logs phpmyadmin
```

## 📝 Próximos passos

1. Configure seu projeto PHP na pasta `src/`
2. Adicione suas dependências no `composer.json`
3. Configure seu banco de dados no `docker/mysql/init.sql`
4. Use o phpMyAdmin para gerenciar seu banco de dados
5. Desenvolva sua aplicação WebSocket com Ratchet!

## 🤝 Contribuição

1. Faça um fork do projeto
2. Crie uma branch para sua feature
3. Commit suas mudanças
4. Push para a branch
5. Abra um Pull Request

## 📄 Referências

https://www.youtube.com/watch?v=Ah96QVIoWNg&ab_channel=Webslesson

https://www.webslesson.info/2025/03/real-time-notification-system-in-php-using-ratchet-websocket.html

## 📄 Licença

Este projeto está sob a licença MIT.
