
<div id="header">
    <a class="logo" href="/" title="½ÌÊ¦ÕÐÆ¸Íø"></a>
    <div class="login_box">
        <?php if (intval($_SESSION['uid']) > 0): ?>
            <a href="/personal_center" title="<?= $_SESSION['username'] ?>"><?= $_SESSION['username'] ?></a>
        <?php else: ?>
            <a href="/user/check_login?url=<?= $_SERVER['REQUEST_URI']; ?>" title="µÇÂ½">µÇÂ½</a>
            <i></i>
            <a href="/user/reg" title="×¢²á">×¢²á</a>
        <?php endif; ?>
    </div>
</div>