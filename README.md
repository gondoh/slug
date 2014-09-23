# Slug プラグイン #
Slugプラグインは、ブログ記事のURLにスラッグを設定できるbaserCMS専用のプラグインです。


## Installation ##

1. 圧縮ファイルを解凍後、BASERCMS/app/plugins/Slug に配置します。
2. 管理システムのプラグイン管理に入って、表示されている Slugプラグイン を有効化して下さい。
3. プラグインの有効化後、ブログ記事の投稿画面にアクセスすると、入力項目にスラッグ設定欄が追加されてます。
4. インストール直後、「スラッグ設定管理メニュー」の「スラッグ設定データ作成」よりスラッグ設定用の初期データを作成して下さい。


## Uses ##

* ブログのテンプレート内の `$this->Blog->category($post)` を以下に書き換えます。  

	`echo $this->Slug->category($post)`

* ブログのテンプレート内の `$blog->postContent($post)` を以下に書き換えます。  

	`echo $this->Slug->postContent($post)`

* ウィジェット（/Elements/widgets/blog_category_archives.php）内の `$this->Blog->getCategoryList($category)` を以下に書き換えます。

	`$this->Slub->getCategoryList($category)`


## Uses Config ##

スラッグ設定画面では、ブログ別に以下の設定を行う事ができます。

* スラッグの構造を選択できます。
* ブログ内のURLの「archives」の省略を選択できます。
	* 例：http://YOUR_DOMAIN/index.php/news/archives/スラッグ  

		　→ http://YOUR_DOMAIN/index.php/news/スラッグ


## Thanks ##

- http://basercms.net
- http://doc.basercms.net/
- http://cakephp.jp
