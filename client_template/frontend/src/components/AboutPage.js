// ================================================================
// ABOUT PAGE COMPONENT
// ================================================================
// 
// CUSTOMIZE THIS FOR EACH CLIENT:
// - Hero text and image
// - About content
// - Team section (if applicable)
// - Values/services
//
// ================================================================

import React from "react";
import { Link } from "react-router-dom";
import { useSite } from "../App";
import { ArrowRight, CheckCircle } from "lucide-react";

const AboutPage = () => {
  const { settings } = useSite();

  // CUSTOMIZE: Add client-specific services/skills
  const services = [
    "Grafisch Ontwerp",
    "Webdesign",
    "Branding",
    "Drukwerk",
  ];

  return (
    <div className="page-enter-active" style={{ paddingTop: '6rem' }}>
      {/* Hero Section */}
      <section className="section">
        <div className="container">
          <div style={{ 
            display: 'grid', 
            gridTemplateColumns: 'repeat(auto-fit, minmax(300px, 1fr))',
            gap: '4rem',
            alignItems: 'center'
          }}>
            <div>
              <h1 style={{ fontSize: '3rem', fontWeight: '700', marginBottom: '1.5rem' }}>
                Over <span className="text-primary">{settings.site_name}</span>
              </h1>
              <p style={{ fontSize: '1.125rem', color: 'var(--color-text-muted)', lineHeight: '1.8', marginBottom: '2rem' }}>
                {/* CUSTOMIZE: Client's story/introduction */}
                Wij zijn gepassioneerd over het creëren van unieke visuele 
                identiteiten die jouw merk onderscheiden van de rest. Met jarenlange 
                ervaring helpen wij ondernemers en bedrijven om hun verhaal te vertellen 
                door middel van design.
              </p>
              <Link to="/contact" className="btn btn-primary">
                Neem Contact Op <ArrowRight size={18} />
              </Link>
            </div>
            <div style={{ 
              aspectRatio: '4/3',
              background: 'var(--color-surface)',
              borderRadius: 'var(--radius-lg)',
              overflow: 'hidden'
            }}>
              {/* CUSTOMIZE: Add client's image */}
              <img 
                src="https://images.unsplash.com/photo-1600880292203-757bb62b4baf?w=800&q=80"
                alt="Over ons"
                style={{ width: '100%', height: '100%', objectFit: 'cover' }}
              />
            </div>
          </div>
        </div>
      </section>

      {/* Services/Skills */}
      <section className="section bg-surface">
        <div className="container">
          <div className="section-header">
            <h2 className="section-title">Wat Wij Doen</h2>
            <p className="section-subtitle">
              Onze diensten en expertise
            </p>
          </div>
          
          <div style={{ 
            display: 'grid', 
            gridTemplateColumns: 'repeat(auto-fit, minmax(250px, 1fr))',
            gap: '1.5rem',
            maxWidth: '800px',
            margin: '0 auto'
          }}>
            {services.map((service) => (
              <div 
                key={service}
                style={{
                  display: 'flex',
                  alignItems: 'center',
                  gap: '0.75rem',
                  padding: '1rem 1.5rem',
                  background: 'var(--color-background)',
                  borderRadius: 'var(--radius-md)',
                  border: '1px solid var(--color-border)'
                }}
              >
                <CheckCircle size={20} className="text-primary" />
                <span>{service}</span>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* Mission/Values */}
      <section className="section">
        <div className="container" style={{ maxWidth: '800px' }}>
          <div className="text-center">
            <h2 className="section-title">Onze Aanpak</h2>
            <p style={{ 
              fontSize: '1.25rem', 
              color: 'var(--color-text-muted)', 
              lineHeight: '1.8' 
            }}>
              {/* CUSTOMIZE: Client's mission/approach */}
              Wij geloven in een persoonlijke aanpak waarbij jouw wensen en doelen 
              centraal staan. Door nauw samen te werken zorgen we ervoor dat het 
              eindresultaat perfect aansluit bij jouw visie en doelgroep.
            </p>
          </div>
        </div>
      </section>

      {/* CTA */}
      <section className="section bg-surface">
        <div className="container">
          <div className="text-center">
            <h2 className="section-title">Klaar om te Starten?</h2>
            <p className="section-subtitle mb-8">
              Laten we samen jouw volgende project realiseren.
            </p>
            <Link to="/contact" className="btn btn-accent">
              Start Je Project <ArrowRight size={18} />
            </Link>
          </div>
        </div>
      </section>
    </div>
  );
};

export default AboutPage;
