<?php
$currentPage = basename($_SERVER['PHP_SELF'], '.php');
?>
<header class="header">
    <div class="container">
        <div class="header__inner">
            <a href="/" class="header__logo">
                <img src="https://customer-assets.emergentagent.com/job_quick-website-pro/artifacts/9zb1lmkl_71512929539.png" alt="direct-online">
            </a>
            
            <button class="mobile-toggle" aria-label="Menu">
                <span></span>
                <span></span>
                <span></span>
            </button>
            
            <nav class="header__nav">
                <a href="/" class="header__nav-link <?php echo $currentPage === 'index' ? 'header__nav-link--active' : ''; ?>">Home</a>
                <a href="/diensten.php" class="header__nav-link <?php echo $currentPage === 'diensten' ? 'header__nav-link--active' : ''; ?>">Diensten</a>
                <a href="/portfolio.php" class="header__nav-link <?php echo $currentPage === 'portfolio' ? 'header__nav-link--active' : ''; ?>">Portfolio</a>
                <a href="/over-ons.php" class="header__nav-link <?php echo $currentPage === 'over-ons' ? 'header__nav-link--active' : ''; ?>">Over ons</a>
                <a href="/contact.php" class="header__nav-link <?php echo $currentPage === 'contact' ? 'header__nav-link--active' : ''; ?>">Contact</a>
                <a href="https://calendly.com/direct-online-info/30min" target="_blank" class="header__cta">
                    Plan afspraak
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                </a>
            </nav>
        </div>
    </div>
</header>
