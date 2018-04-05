<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta name="yandex-verification" content="4b12934d0f79a0d4" />
    <meta name="google-site-verification" content="sAccp9ghfjf1mJS4LdndZdvmRP6S5N-dFntPmt22iVg" />
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta property="og:image" content="http://cybpoint.ru/web/images/logo.png">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <link rel="icon" href="http://cybpoint.ru/web/favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="Yii::getAlias('@web/favicon.ico')" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,600&amp;subset=cyrillic" rel="stylesheet"> 
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => Html::img('@web/images/logo.png', [
            'alt'=>Yii::$app->name, 
            'class'=>'headerLogo']),
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top'
        ],
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => [
            ['label' => 'Главная', 'url' => ['/site/index']],
            ['label' => 'Новости', 'url' => ['/news/index']],
            ['label' => 'Турниры', 'url' => ['/tourneys/index']],
                        
            Yii::$app->user->isGuest ? (
              '<i></i>'
            ) : (
              Yii::$app->user->identity->accessToken == 'admin' ? (
                ['label' => 'Панель управления', 'url' => ['/site/admin-panel']]
            ) : (
              (Yii::$app->user->identity->accessToken == 'newswriter' or Yii::$app->user->identity->accessToken == 'newsmoderator') ? (
                ['label' => 'Управление новостями', 'url' => ['/site/news-panel']]
            ) : (
              (Yii::$app->user->identity->accessToken == 'organisator' or Yii::$app->user->identity->accessToken == 'tourneysmoderator') ? (
              ['label' => 'Управление событиями', 'url' => ['/site/tourneys-panel']]):(
              '<li></li>'
              )
            )
            )
            ),
            
            Yii::$app->user->isGuest ? (
                ''
            ) : (
                '<li>'
                . Html::beginForm(['/site/logout'], 'post')
                . Html::submitButton(
                    'Выход (' . Yii::$app->user->identity->username . ')',
                    ['class' => 'btn btn-link logout']
                )
                . Html::endForm()
                . '</li>'
            ),
            
        ],
    ]); ?>
    <?
    NavBar::end();
    ?>
    <div class="container main template">
        <?= $content ?> 
    </div>

        
</div>
<!-- Top100 (Kraken) Counter -->
<script>
    (function (w, d, c) {
    (w[c] = w[c] || []).push(function() {
        var options = {
            project: 4509283,
        };
        try {
            w.top100Counter = new top100(options);
        } catch(e) { }
    });
    var n = d.getElementsByTagName("script")[0],
    s = d.createElement("script"),
    f = function () { n.parentNode.insertBefore(s, n); };
    s.type = "text/javascript";
    s.async = true;
    s.src =
    (d.location.protocol == "https:" ? "https:" : "http:") +
    "//st.top100.ru/top100/top100.js";

    if (w.opera == "[object Opera]") {
    d.addEventListener("DOMContentLoaded", f, false);
} else { f(); }
})(window, document, "_top100q");
</script>
<noscript>
  <img src="//counter.rambler.ru/top100.cnt?pid=4509283" alt="Топ-100" />
</noscript>
<!-- END Top100 (Kraken) Counter -->
<!-- Rating@Mail.ru counter -->
<script type="text/javascript">
var _tmr = window._tmr || (window._tmr = []);
_tmr.push({id: "2933246", type: "pageView", start: (new Date()).getTime()});
(function (d, w, id) {
  if (d.getElementById(id)) return;
  var ts = d.createElement("script"); ts.type = "text/javascript"; ts.async = true; ts.id = id;
  ts.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//top-fwz1.mail.ru/js/code.js";
  var f = function () {var s = d.getElementsByTagName("script")[0]; s.parentNode.insertBefore(ts, s);};
  if (w.opera == "[object Opera]") { d.addEventListener("DOMContentLoaded", f, false); } else { f(); }
})(document, window, "topmailru-code");
</script><noscript><div>
<img src="//top-fwz1.mail.ru/counter?id=2933246;js=na" style="border:0;position:absolute;left:-9999px;" alt="" />
</div></noscript>
<!-- //Rating@Mail.ru counter -->

<!-- Yandex.Metrika counter -->
<script type="text/javascript" >
    (function (d, w, c) {
        (w[c] = w[c] || []).push(function() {
            try {
                w.yaCounter45631875 = new Ya.Metrika({
                    id:45631875,
                    clickmap:true,
                    trackLinks:true,
                    accurateTrackBounce:true
                });
            } catch(e) { }
        });

        var n = d.getElementsByTagName("script")[0],
            s = d.createElement("script"),
            f = function () { n.parentNode.insertBefore(s, n); };
        s.type = "text/javascript";
        s.async = true;
        s.src = "https://mc.yandex.ru/metrika/watch.js";

        if (w.opera == "[object Opera]") {
            d.addEventListener("DOMContentLoaded", f, false);
        } else { f(); }
    })(document, window, "yandex_metrika_callbacks");
</script>
<noscript><div><img src="https://mc.yandex.ru/watch/45631875" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; CyberPoint <?= date('') ?></p>

        <p class="pull-right"><a href="http://cybpoint.ru/web/site/contact">Контакты</a></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
