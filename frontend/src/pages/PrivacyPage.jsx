import React from 'react';
import { ArrowLeft } from 'lucide-react';
import { Link } from 'react-router-dom';

const PrivacyPage = () => {
  return (
    <div className="min-h-screen bg-slate-50">
      {/* Header */}
      <header className="bg-slate-900 text-white py-6">
        <div className="container mx-auto px-4 sm:px-6 lg:px-12">
          <Link 
            to="/" 
            className="inline-flex items-center gap-2 text-slate-300 hover:text-white transition-colors"
          >
            <ArrowLeft className="w-4 h-4" />
            <span>Terug naar home</span>
          </Link>
        </div>
      </header>

      {/* Content */}
      <main className="container mx-auto px-4 sm:px-6 lg:px-12 py-12 sm:py-16">
        <div className="max-w-3xl mx-auto">
          <h1 className="text-3xl sm:text-4xl font-bold text-slate-900 mb-8">
            Privacybeleid
          </h1>
          
          <div className="prose prose-slate max-w-none">
            <p className="text-slate-600 mb-6">
              <strong>Laatst bijgewerkt:</strong> Januari 2025
            </p>

            <section className="mb-8">
              <h2 className="text-xl font-semibold text-slate-900 mb-4">
                1. Inleiding
              </h2>
              <p className="text-slate-600 mb-4">
                direct-online respecteert de privacy van alle bezoekers van haar website en draagt er zorg voor dat de persoonlijke informatie die u ons verschaft vertrouwelijk wordt behandeld. Dit privacybeleid is van toepassing op alle diensten van direct-online.
              </p>
            </section>

            <section className="mb-8">
              <h2 className="text-xl font-semibold text-slate-900 mb-4">
                2. Welke gegevens verzamelen wij?
              </h2>
              <p className="text-slate-600 mb-4">
                Wij kunnen de volgende persoonsgegevens verzamelen en verwerken:
              </p>
              <ul className="list-disc pl-6 text-slate-600 space-y-2 mb-4">
                <li>Naam en contactgegevens (e-mailadres, telefoonnummer)</li>
                <li>Bedrijfsnaam en functie</li>
                <li>Informatie die u verstrekt via ons contactformulier of Calendly</li>
                <li>Technische gegevens zoals IP-adres, browsertype en apparaatinformatie</li>
                <li>Gegevens over uw gebruik van onze website</li>
              </ul>
            </section>

            <section className="mb-8">
              <h2 className="text-xl font-semibold text-slate-900 mb-4">
                3. Waarvoor gebruiken wij uw gegevens?
              </h2>
              <p className="text-slate-600 mb-4">
                Wij gebruiken uw persoonsgegevens voor de volgende doeleinden:
              </p>
              <ul className="list-disc pl-6 text-slate-600 space-y-2 mb-4">
                <li>Het uitvoeren van onze dienstverlening</li>
                <li>Contact met u opnemen naar aanleiding van uw aanvraag</li>
                <li>Het plannen en uitvoeren van afspraken</li>
                <li>Het verbeteren van onze website en diensten</li>
                <li>Het voldoen aan wettelijke verplichtingen</li>
              </ul>
            </section>

            <section className="mb-8">
              <h2 className="text-xl font-semibold text-slate-900 mb-4">
                4. Hoe lang bewaren wij uw gegevens?
              </h2>
              <p className="text-slate-600 mb-4">
                Wij bewaren uw persoonsgegevens niet langer dan strikt noodzakelijk is om de doelen te realiseren waarvoor uw gegevens worden verzameld. Onze bewaartermijn voor klantgegevens is maximaal 7 jaar na het einde van de klantrelatie, in verband met wettelijke bewaarplichten.
              </p>
            </section>

            <section className="mb-8">
              <h2 className="text-xl font-semibold text-slate-900 mb-4">
                5. Delen van gegevens met derden
              </h2>
              <p className="text-slate-600 mb-4">
                Wij verkopen uw gegevens niet aan derden. Wij kunnen uw gegevens delen met:
              </p>
              <ul className="list-disc pl-6 text-slate-600 space-y-2 mb-4">
                <li>Dienstverleners die ons helpen bij het uitvoeren van onze diensten (zoals Calendly voor afsprakenplanning)</li>
                <li>Partijen die betrokken zijn bij het uitvoeren van de overeenkomst</li>
                <li>Overheidsinstanties wanneer wij hiertoe wettelijk verplicht zijn</li>
              </ul>
            </section>

            <section className="mb-8">
              <h2 className="text-xl font-semibold text-slate-900 mb-4">
                6. Cookies
              </h2>
              <p className="text-slate-600 mb-4">
                Onze website maakt gebruik van cookies om de gebruikerservaring te verbeteren. Cookies zijn kleine tekstbestanden die op uw apparaat worden opgeslagen. Wij gebruiken:
              </p>
              <ul className="list-disc pl-6 text-slate-600 space-y-2 mb-4">
                <li><strong>Functionele cookies:</strong> noodzakelijk voor het functioneren van de website</li>
                <li><strong>Analytische cookies:</strong> om het gebruik van de website te analyseren en te verbeteren</li>
              </ul>
              <p className="text-slate-600 mb-4">
                U kunt cookies uitschakelen via uw browserinstellingen, maar dit kan de functionaliteit van de website beperken.
              </p>
            </section>

            <section className="mb-8">
              <h2 className="text-xl font-semibold text-slate-900 mb-4">
                7. Beveiliging
              </h2>
              <p className="text-slate-600 mb-4">
                Wij nemen passende technische en organisatorische maatregelen om uw persoonsgegevens te beveiligen tegen verlies of onrechtmatige verwerking. Onze website maakt gebruik van een SSL-certificaat voor een beveiligde verbinding.
              </p>
            </section>

            <section className="mb-8">
              <h2 className="text-xl font-semibold text-slate-900 mb-4">
                8. Uw rechten
              </h2>
              <p className="text-slate-600 mb-4">
                U heeft de volgende rechten met betrekking tot uw persoonsgegevens:
              </p>
              <ul className="list-disc pl-6 text-slate-600 space-y-2 mb-4">
                <li><strong>Recht op inzage:</strong> u kunt opvragen welke gegevens wij van u verwerken</li>
                <li><strong>Recht op rectificatie:</strong> u kunt onjuiste gegevens laten corrigeren</li>
                <li><strong>Recht op verwijdering:</strong> u kunt verzoeken uw gegevens te verwijderen</li>
                <li><strong>Recht op beperking:</strong> u kunt de verwerking van uw gegevens laten beperken</li>
                <li><strong>Recht op overdraagbaarheid:</strong> u kunt uw gegevens opvragen in een gangbaar formaat</li>
                <li><strong>Recht van bezwaar:</strong> u kunt bezwaar maken tegen de verwerking van uw gegevens</li>
              </ul>
              <p className="text-slate-600 mb-4">
                Om gebruik te maken van deze rechten kunt u contact met ons opnemen via onderstaande contactgegevens.
              </p>
            </section>

            <section className="mb-8">
              <h2 className="text-xl font-semibold text-slate-900 mb-4">
                9. Contact
              </h2>
              <p className="text-slate-600 mb-4">
                Heeft u vragen over dit privacybeleid of over de verwerking van uw persoonsgegevens? Neem dan contact met ons op:
              </p>
              <div className="bg-slate-100 rounded-xl p-6 text-slate-600">
                <p><strong>direct-online</strong></p>
                <p>E-mail: info@direct-online.nl</p>
              </div>
            </section>

            <section className="mb-8">
              <h2 className="text-xl font-semibold text-slate-900 mb-4">
                10. Wijzigingen
              </h2>
              <p className="text-slate-600 mb-4">
                Wij behouden ons het recht voor om dit privacybeleid te wijzigen. Wijzigingen zullen op deze pagina worden gepubliceerd. Wij adviseren u daarom regelmatig deze pagina te raadplegen.
              </p>
            </section>

            <section className="mb-8">
              <h2 className="text-xl font-semibold text-slate-900 mb-4">
                11. Klachten
              </h2>
              <p className="text-slate-600 mb-4">
                Mocht u een klacht hebben over de verwerking van uw persoonsgegevens, dan kunt u contact met ons opnemen. U heeft ook het recht om een klacht in te dienen bij de Autoriteit Persoonsgegevens (www.autoriteitpersoonsgegevens.nl).
              </p>
            </section>
          </div>
        </div>
      </main>

      {/* Simple Footer */}
      <footer className="bg-slate-900 text-white py-8">
        <div className="container mx-auto px-4 sm:px-6 lg:px-12 text-center">
          <p className="text-slate-400 text-sm">
            © {new Date().getFullYear()} direct-online. Alle rechten voorbehouden.
          </p>
        </div>
      </footer>
    </div>
  );
};

export default PrivacyPage;
