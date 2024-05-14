# pharmacy-weblist
地域薬剤師会向け薬局の情報公開用WEBシステムです。
このプロジェクトは、薬局情報の管理および公開を目的としたPHPシステムです。ユーザーと管理者が薬局情報を管理できる機能を提供します。

## 特徴

- 登録された薬局情報の公開
- ユーザーが自身の薬局情報を編集可能
- 新規ユーザー登録申請
- 新規薬局登録申請
- 管理者によるユーザー承認
- 各アクションでのメール通知
- 管理者によるユーザーおよび薬局情報の編集
- 初期設定スクリプトによる対話式セットアップ
- SQLiteデータベースを使用

## システム要件

- Linuxサーバー
- Apache Webサーバー
- PHP 7.4

## インストール

1. このリポジトリをクローンします。

    ```sh
    git clone https://github.com/yourusername/pharmacy-management-system.git
    cd pharmacy-management-system
    ```

2. 必要なパッケージをインストールします。

    ```sh
    sudo apt update
    sudo apt install apache2 php7.4 php7.4-sqlite3
    ```

3. データベースをセットアップします。

    ```sh
    php setup.php
    ```

4. 初期設定を行います。ブラウザで `initial_setup.php` にアクセスし、指示に従って設定を完了します。

    ```sh
    http://your-server-address/initial_setup.php
    ```

## 使い方

### ユーザー

1. **ユーザー登録**

    - URL: `/user_register.php`
    - フォームにユーザー名、パスワード、メールアドレスを入力し、「Register」ボタンをクリックします。

2. **ユーザーログイン**

    - URL: `/user_login.php`
    - フォームにユーザー名、パスワードを入力し、「Login」ボタンをクリックします。

3. **薬局登録**

    - URL: `/pharmacy_register.php`
    - フォームに薬局名、住所、電話番号、メールアドレスを入力し、「Register」ボタンをクリックします。

4. **薬局情報の編集**

    - URL: `/pharmacy_edit.php?id={pharmacy_id}`
    - 編集したい情報を入力し、「Update」ボタンをクリックします。

### 管理者

1. **管理者ログイン**

    - URL: `/admin_login.php`
    - フォームにユーザー名、パスワードを入力し、「Login」ボタンをクリックします。

2. **管理ダッシュボード**

    - URL: `/admin_dashboard.php`
    - ユーザーの承認や薬局情報の編集を行います。

## ディレクトリ構造
<pre>
/project-root
|-- setup
|   |-- setup.php
|   |-- initial_setup.php
|   |-- setup_complete.php
|   |-- setup_message.php
|   |-- index.php
|   |-- setup.sql
|-- admin
|   |-- admin_login.php
|   |-- admin_dashboard.php
|   |-- admin_user_edit.php
|   |-- pharmacy_edit.php
|   |-- pharmacy_add.php
|   |-- meta_keys.php
|   |-- header.php
|-- pharmacy.db
|-- mail_functions.php
|-- functions.php
|-- mail_config.json
|-- pharmacy_list.php
|-- pharmacy_details.php
|-- user_register.php
|-- user_login.php
|-- pharmacy_register.php
|-- index.php (or other main files of your project)
</pre>

## ライセンス

このプロジェクトはMITライセンスの下でライセンスされています。詳細については`LICENSE`ファイルを参照してください。

## 貢献

貢献を歓迎します。プルリクエストを送信する前に、問題を立ててください。

---

これで、プロジェクトをセットアップし、使い始めるための基本的な情報が提供されました。質問やフィードバックがある場合は、お気軽にお知らせください。
