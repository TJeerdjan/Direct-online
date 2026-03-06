<?php $pageTitle = 'Contact'; ?>
<?php include 'partials/header.php'; ?>

<main>
    <!-- Hero -->
    <section class="section" style="padding-top: 120px; background: var(--gray-100);">
        <div class="container">
            <div class="section-header">
                <p class="section-header__subtitle">Neem Contact Op</p>
                <h1>Laten we praten</h1>
                <p>Heb je een vraag of wil je direct aan de slag? Neem contact met ons op.</p>
            </div>
        </div>
    </section>
    
    <!-- Contact Section -->
    <section class="section">
        <div class="container">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 4rem;">
                <!-- Contact Form -->
                <div>
                    <h3 style="margin-bottom: 1.5rem;">Stuur een bericht</h3>
                    
                    <div id="formSuccess" class="form__message form__message--success">
                        Bedankt voor je bericht! We nemen zo snel mogelijk contact met je op.
                    </div>
                    <div id="formError" class="form__message form__message--error">
                        Er is iets misgegaan. Probeer het opnieuw.
                    </div>
                    
                    <form id="contactForm" class="form">
                        <input type="hidden" name="api_key" value="DEMO_API_KEY">
                        
                        <div class="form__group">
                            <label class="form__label" for="naam">Naam *</label>
                            <input type="text" id="naam" name="naam" class="form__input" required>
                        </div>
                        
                        <div class="form__group">
                            <label class="form__label" for="email">E-mail *</label>
                            <input type="email" id="email" name="email" class="form__input" required>
                        </div>
                        
                        <div class="form__group">
                            <label class="form__label" for="telefoon">Telefoonnummer</label>
                            <input type="tel" id="telefoon" name="telefoon" class="form__input">
                        </div>
                        
                        <div class="form__group">
                            <label class="form__label" for="bericht">Bericht *</label>
                            <textarea id="bericht" name="bericht" class="form__textarea" required></textarea>
                        </div>
                        
                        <button type="submit" class="btn btn--primary">
                            Verstuur bericht
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                        </button>
                    </form>
                </div>
                
                <!-- Contact Info -->
                <div>
                    <h3 style="margin-bottom: 1.5rem;">Of neem direct contact op</h3>
                    
                    <div style="display: flex; flex-direction: column; gap: 2rem;">
                        <div class="card">
                            <div style="display: flex; align-items: center; gap: 1rem;">
                                <div style="width: 50px; height: 50px; background: var(--primary); border-radius: var(--radius-lg); display: flex; align-items: center; justify-content: center;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                                </div>
                                <div>
                                    <p style="font-weight: 600; margin-bottom: 0.25rem;">E-mail</p>
                                    <a href="mailto:info@direct-online.nl" style="color: var(--primary);">info@direct-online.nl</a>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card">
                            <div style="display: flex; align-items: center; gap: 1rem;">
                                <div style="width: 50px; height: 50px; background: var(--primary); border-radius: var(--radius-lg); display: flex; align-items: center; justify-content: center;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                                </div>
                                <div>
                                    <p style="font-weight: 600; margin-bottom: 0.25rem;">Plan een afspraak</p>
                                    <a href="https://calendly.com/direct-online-info/30min" target="_blank" style="color: var(--primary);">Bekijk beschikbaarheid</a>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card">
                            <div style="display: flex; align-items: center; gap: 1rem;">
                                <div style="width: 50px; height: 50px; background: var(--primary); border-radius: var(--radius-lg); display: flex; align-items: center; justify-content: center;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                                </div>
                                <div>
                                    <p style="font-weight: 600; margin-bottom: 0.25rem;">Locatie</p>
                                    <p style="color: var(--gray-600); margin: 0;">Groningen en omgeving</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div style="margin-top: 3rem; padding: 2rem; background: var(--gray-100); border-radius: var(--radius-xl);">
                        <h4 style="margin-bottom: 1rem;">Liever direct praten?</h4>
                        <p style="color: var(--gray-600); margin-bottom: 1.5rem;">Plan een vrijblijvend gesprek van 30 minuten. We komen graag bij je langs!</p>
                        <a href="https://calendly.com/direct-online-info/30min" target="_blank" class="btn btn--primary">
                            Plan een afspraak
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- FAQ -->
    <section class="section section--gray">
        <div class="container">
            <div class="section-header">
                <h2>Veelgestelde vragen</h2>
            </div>
            
            <div style="max-width: 800px; margin: 0 auto;">
                <div class="faq__item">
                    <button class="faq__question">
                        Hoe snel kan ik online zijn?
                        <svg class="faq__icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
                    </button>
                    <div class="faq__answer">
                        <div class="faq__answer-inner">
                            Binnen 24 uur na ons eerste gesprek staat je website live en zijn je advertenties klaar om te draaien. Wij komen bij je langs, bespreken alles face-to-face, en gaan pas weg als alles staat.
                        </div>
                    </div>
                </div>
                
                <div class="faq__item">
                    <button class="faq__question">
                        Wat kost het?
                        <svg class="faq__icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
                    </button>
                    <div class="faq__answer">
                        <div class="faq__answer-inner">
                            Ons complete pakket kost €495 eenmalig. Dit is inclusief website, Google Ads setup, Meta Ads setup, copywriting, KPI's en de eerste maand rapportage & support. Geen verborgen kosten, geen contracten.
                        </div>
                    </div>
                </div>
                
                <div class="faq__item">
                    <button class="faq__question">
                        Moet ik vooraf betalen?
                        <svg class="faq__icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
                    </button>
                    <div class="faq__answer">
                        <div class="faq__answer-inner">
                            Nee! Je betaalt pas na oplevering, als je 100% tevreden bent. Niet tevreden? Dan betaal je niks. Zo simpel is het.
                        </div>
                    </div>
                </div>
                
                <div class="faq__item">
                    <button class="faq__question">
                        Komen jullie echt langs?
                        <svg class="faq__icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
                    </button>
                    <div class="faq__answer">
                        <div class="faq__answer-inner">
                            Ja, dat is juist het verschil! We komen bij je op kantoor om de eerste versie te bespreken en de advertenties samen te maken. Geen eindeloze mailwisselingen, maar directe feedback en persoonlijk contact.
                        </div>
                    </div>
                </div>
                
                <div class="faq__item">
                    <button class="faq__question">
                        Waarom zijn jullie zo goedkoop?
                        <svg class="faq__icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
                    </button>
                    <div class="faq__answer">
                        <div class="faq__answer-inner">
                            Door slimme workflows en de nieuwste AI-tools kunnen we in uren doen wat anderen weken kost. Geen dure kantoren of account managers. Directe lijnen, directe resultaten. Dit is een launch-actie om Groningse ondernemers op de kaart te zetten.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include 'partials/footer.php'; ?>
