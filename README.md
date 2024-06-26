# 薬局管理システム
地域薬剤師会向けの薬局情報の管理および公開を目的としたPHPシステムです。ユーザーと管理者が薬局情報を管理できる機能を提供します。
このシステムは、薬局情報の管理と公開を目的としたウェブアプリケーションです。管理者は薬局情報の追加・編集・削除を行い、一般利用者は薬局のリストを閲覧することができます。また、ユーザーは自身の薬局を登録し、管理者の承認を受けることができます。

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

## インストール方法

1. システムのファイルをWEBサーバーの公開ディレクトリに配置します。
2. ブラウザで配置ディレクトリの `/setup` にアクセスして初回セットアップを行います。
3. セットアップ完了後、自動的に `/admin/admin_login.php` にリダイレクトされます。

## 初回セットアップ

1. `/setup` にアクセスし、指示に従ってデータベース設定、管理者ユーザーの作成、メール設定を行います。
2. セットアップが完了すると、管理者ログインページ `/admin/admin_login.php` にリダイレクトされます。

## 管理者機能

1. 管理者ログインページ `/admin/admin_login.php` にアクセスし、ログインします。
2. 薬局の追加・編集・削除、ユーザーの承認管理が行えます。

## 一般利用者機能

1. 配置ディレクトリ `/` にアクセスし、公開された薬局リストを閲覧できます。
2. ユーザー登録を行い、管理者の承認を受けることで自身の薬局を登録することができます。

# ユーザーマニュアル

## システム概要

このシステムは、薬局情報の閲覧および自身の薬局情報の管理を行うことができます。

## ユーザー登録

1. ホームページにアクセスし、ユーザー登録ページに移動します。
2. 必要な情報を入力してユーザー登録を行います。
3. 登録後、管理者の承認を待ちます。承認されると、ログインして自身の薬局を登録・管理できます。

## 薬局リストの閲覧

1. ホームページにアクセスし、公開されている薬局リストを閲覧できます。
2. 薬局の詳細情報を確認するには、各薬局の「詳細を見る」ボタンをクリックします。

## 薬局の登録

1. 管理者に承認された後、ログインしてユーザープロフィールページにアクセスします。
2. 「新規薬局を登録」ボタンをクリックし、薬局情報を入力して登録します。

# 管理者マニュアル

## 管理者ログイン

1. `/admin/admin_login.php` にアクセスし、管理者アカウントでログインします。

## 薬局の管理

1. ログイン後、管理者ダッシュボードにアクセスします。
2. 薬局の追加、編集が行えます。

## ユーザーの承認管理

1. ログイン後、管理者ダッシュボードにアクセスします。
2. 承認待ちのユーザーを確認し、承認または拒否を行います。

# 開発者向けドキュメント

## 環境設定

このシステムは、以下の環境で動作するように設計されています。

- サーバー: Linux
- ウェブサーバー: Apache
- PHP: 7.4以上
- データベース: SQLite

## ディレクトリ構造
<pre>
/project-root
|-- setup
| |-- setup.php
| |-- initial_setup.php
| |-- setup_complete.php
| |-- setup_message.php
| |-- index.php
| |-- setup.sql
|-- admin
| |-- admin_login.php
| |-- admin_dashboard.php
| |-- admin_user_edit.php
| |-- pharmacy_edit.php
| |-- pharmacy_add.php
| |-- meta_keys.php
| |-- header.php
|-- pharmacy.db
|-- mail_functions.php
|-- functions.php
|-- mail_config.json
|-- index.php (renamed from pharmacy_list.php)
|-- pharmacy_details.php
|-- user_register.php
|-- user_login.php
|-- user_profile.php
|-- user_pharmacy_edit.php
|-- pharmacy_register.php
|-- logout.php
|-- vacuum.php
|-- .htaccess
</pre>

## セットアップスクリプト

- `setup/index.php`: セットアップのエントリーポイント。
- `setup/setup.php`: データベースおよびメール設定の初期化。
- `setup/initial_setup.php`: 管理者ユーザーの作成。

## セキュリティ

- `.htaccess` ファイルで `mail_config.json` および `pharmacy.db` へのアクセスを制限。

## メール設定

- `mail_config.json`: メール送信に必要な設定ファイル。

## セキュリティ

- `mail_config.json` と `pharmacy.db` へのアクセスを制限する `.htaccess` ファイルが含まれています。
- .htaccessはApacheでのみ動作することに注意してください。

## ライセンス

このプロジェクトはMITライセンスのもとで公開されています。詳細はLICENSEファイルを参照してください。

## 作者

このプログラムに関するお問い合わせはOZNET合同会社（info@oznet.co.jp）までご連絡ください。
設置代行やよりセキュリティの高いデータベース等を利用したサーバーのホスティング（月額5,500円）も承っております。
質問やフィードバックがある場合は、お気軽にお知らせください。
