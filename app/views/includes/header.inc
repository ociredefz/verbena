<header id="header">
    <hgroup id="menubar">
        <a href="<?! HTML::url(); ?>" id="logo"><?! HTML::image('logo-base.png') ?><?! Language::get('welcome.site-name') ?></a>
    </hgroup>
    <nav id="navbar" class="clearfix">
        <ul>
            <li><a href="<?! HTML::url('language/set/english'); ?>" <?! $active_english ?>><?! Language::get('welcome.language-label-eng') ?></a></li>
            <!--<li><a href="<?! HTML::url('language/set/italian'); ?>" <?! $active_italian ?>><?! Language::get('welcome.language-label-ita') ?></a></li>-->
        </ul>
    </nav>
</header>