<div class="clear"></div>
<?php $var = explode('/', $_SERVER['REQUEST_URI']); ?>
<ul id="company_menu">
    <li class="resume<?= stristr($var[2], 'resume_') ? "_c" : "" ?>">
        <a href="/company_center/resume_apply"><i></i><span>����</span></a>
    </li>
    <li class="jobs<?= stristr($var[2], 'jobs_') ? "_c" : "" ?>">
        <a href="/company_center/jobs_release"><i></i><span>ְλ</span></a>
    </li>
    <li class="search<?= stristr($var[1], 'resume') ? "_c" : "" ?>">
        <a href="/resume"><i></i><span>�˲�</span></a>
    </li>
    <li class="my<?= stristr($var[2], 'my_') ? "_c" : "" ?>">
        <a href="/company_center/my_index"><i></i><span>��ҵ</span></a>
    </li>
</ul>