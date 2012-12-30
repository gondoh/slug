# slug プラグイン #
slugプラグインは、ブログ記事のURLにスラッグを設定できるbaserCMS専用のプラグインです。


## Installation ##

1. 圧縮ファイルを解凍後、BASERCMS/app/plugins/slug に配置します。
2. 管理システムのプラグイン管理に入って、表示されている slugプラグイン を有効化して下さい。
3. プラグインの有効化後、ブログ記事の投稿画面にアクセスすると、入力項目にスラッグ設定欄が追加されてます。


## Uses ##

* ブログのテンプレート内の `$blog->category($post)` を以下に書き換えます。  

	`echo $slug->category($post)`

* ブログのテンプレート内の `$blog->postContent($post)` を以下に書き換えます。  

	`echo $slug->postContent($post)`

* ウィジェット（/elements/widgets/blog_category_archives.php）内の `$blog->getCategoryList($category)` を以下に書き換えます。  

	`$slub->getCategoryList($category)`


## Uses Config ##

プラグイン設定画面では、以下の設定を行う事ができます。

* スラッグの構造を選択できます。
* ブログ内のURLの「archives」の省略を選択できます。
	* 例：http://YOUR_DOMAIN/index.php/news/archives/スラッグ  

		　→ http://YOUR_DOMAIN/index.php/news/スラッグ


## Thanks ##

- http://basercms.net
- http://doc.basercms.net/
- http://cakephp.jp
