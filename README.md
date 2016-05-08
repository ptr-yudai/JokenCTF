JokenCTF BETA
====

情報処理研究会用CTFのスコアサーバー

## Description

CTF(Capture The Flag)の参加者、問題、スコア等を管理するためのPHPサーバーです。

## VS. 

## Requirement

PHP5.xくらい

## Usage

## Install

CTF用にSQLのデータベースを作成します。データベースの名前は何でも良いですが、データベース名、ホスト名は/config/config.phpに設定してください。
ここではlocalhostにjoken_ctfを作成します。

```sql
CREATE DATABASE joken_ctf;
```

また、データベースにアクセスできる権限を持ったユーザーを作成します。
ここではctf_masterをdebug_passwordというパスワードで作成します。

```sql
CREATE USER 'ctf_master'@'localhost' IDENTIFIED BY 'debug_password';  
GRANT ALL PRIVILEGES ON joken_ctf.* TO 'ctf_master'@'localhost';
```

次に、アカウント(account)と問題(problem)のテーブルを作成します。

```sql
USE joken_ctf;

CREATE TABLE account(
    id INT NOT NULL AUTO_INCREMENT,
    user CHAR(64) NOT NULL,
    pass CHAR(128) NOT NULL,
    image MEDIUMBLOB,
    mime TINYINT NOT NULL,
    score INT NOT NULL,
    solved VARCHAR(512) NOT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE problem(
    id INT NOT NULL AUTO_INCREMENT,
    title CHAR(128) NOT NULL,
    problem TEXT NOT NULL,
    hint TEXT NOT NULL,
    note TEXT NOT NULL,
    flag CHAR(64) NOT NULL,
    score INT NOT NULL,
    category CHAR(64) NOT NULL,
    solved INT NOT NULL,
    last_user CHAR(64) NOT NULL,
    last_date DATETIME.
    PRIMARY KEY (id)
);
```

設定を/config/config.phpに記述し、PHPを動作できる環境に設置したら完了です。

## Screenshots


## Licence

MIT Lisence

## Author

[ptr-yudai](https://github.com/ptr-yudai)
