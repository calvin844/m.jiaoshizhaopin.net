
<div id="header">
    <a class="logo" href="/" title="��ʦ��Ƹ��"></a>
    <div class="login_box">
        <?php if (intval($_SESSION['uid']) > 0): ?>
            <a href="/personal_center" title="<?= $_SESSION['username'] ?>"><?= $_SESSION['username'] ?></a>
        <?php else: ?>
            <a href="/user/check_login?url=<?= $_SERVER['REQUEST_URI']; ?>" title="��½">��½</a>
            <i></i>
            <a href="/user/reg" title="ע��">ע��</a>
        <?php endif; ?>
    </div>
</div>