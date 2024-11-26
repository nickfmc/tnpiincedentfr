<nav id="site-navigation" class="c-main-navigation" role="navigation" itemscope itemtype="https://schema.org/SiteNavigationElement">
    <?php
    gdt_nav_menu( 'main-menu', 'c-main-menu', array(
        // 'walker' => new Custom_Menu_Walker()
    ) ); // Adjust using Menus in WordPress Admin ?>

<div class="c-lang-select" role="navigation" aria-label="Sélecteur de langue">
    <a href="https://response-fr.tnpi.ca/" class="c-lang-select-btn c-lang-select--current" id="lang-fr" aria-label="Sélectionner le français">FR</a>

    <a href="https://response.tnpi.ca" class="c-lang-select-btn " id="lang-en" aria-label="Sélectionner l'anglais">EN</a>
</div>

</nav>
