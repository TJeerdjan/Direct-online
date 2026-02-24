// ================================================================
// HOME PAGE COMPONENT
// ================================================================

import React, { useEffect, useState } from "react";
import { Link } from "react-router-dom";
import { useSite } from "../App";
import { ArrowRight, Star } from "lucide-react";

const API_URL = process.env.REACT_APP_BACKEND_URL || "";

const HomePage = () => {
  const { settings } = useSite();
  const [portfolio, setPortfolio] = useState([]);
  const [testimonials, setTestimonials] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    const fetchData = async () => {
      try {
        const [portfolioRes, testimonialsRes] = await Promise.all([
          fetch(`${API_URL}/api/portfolio`),
          fetch(`${API_URL}/api/testimonials`),
        ]);
        
        if (portfolioRes.ok) {
          const data = await portfolioRes.json();
          setPortfolio(data.slice(0, 6)); // Show max 6 items
        }
        
        if (testimonialsRes.ok) {
          const data = await testimonialsRes.json();
          setTestimonials(data.slice(0, 3)); // Show max 3
        }
      } catch (error) {
        console.error("Failed to fetch data:", error);
      }
      setLoading(false);
    };
    
    fetchData();
  }, []);

  return (
    <div className="page-enter-active">
      {/* Hero Section */}
      <section className="hero">
        <div className="hero-background" />
        <div className="hero-content">
          <h1 className="hero-title">
            {settings.tagline || (
              <>
                Welkom bij <span className="highlight">{settings.site_name}</span>
              </>
            )}
          </h1>
          <p className="hero-subtitle">
            {/* Customize this text per client */}
            Ontdek ons werk en laat je inspireren. Wij creëren unieke oplossingen 
            die jouw merk naar een hoger niveau tillen.
          </p>
          <div style={{ display: 'flex', gap: '1rem', justifyContent: 'center', flexWrap: 'wrap' }}>
            <Link to="/portfolio" className="btn btn-primary">
              Bekijk Portfolio <ArrowRight size={18} />
            </Link>
            <Link to="/contact" className="btn btn-secondary">
              Neem Contact Op
            </Link>
          </div>
        </div>
      </section>

      {/* Featured Portfolio */}
      {portfolio.length > 0 && (
        <section className="section">
          <div className="container">
            <div className="section-header">
              <h2 className="section-title">Uitgelicht Werk</h2>
              <p className="section-subtitle">
                Een selectie van onze recente projecten
              </p>
            </div>
            
            <div className="portfolio-grid">
              {portfolio.map((item) => (
                <Link to={`/portfolio/${item.slug}`} key={item.id} className="card">
                  <div className="card-image">
                    <img src={item.image} alt={item.title} />
                  </div>
                  <div className="card-content">
                    <h3 className="card-title">{item.title}</h3>
                    <p className="card-description">{item.description?.slice(0, 100)}...</p>
                    <div className="card-meta">
                      <span className="text-primary">{item.category}</span>
                      {item.client && <span>• {item.client}</span>}
                    </div>
                  </div>
                </Link>
              ))}
            </div>
            
            <div className="text-center mt-8">
              <Link to="/portfolio" className="btn btn-secondary">
                Bekijk Alle Projecten <ArrowRight size={18} />
              </Link>
            </div>
          </div>
        </section>
      )}

      {/* Testimonials */}
      {testimonials.length > 0 && (
        <section className="section bg-surface">
          <div className="container">
            <div className="section-header">
              <h2 className="section-title">Wat Klanten Zeggen</h2>
              <p className="section-subtitle">
                Ervaringen van tevreden klanten
              </p>
            </div>
            
            <div className="portfolio-grid">
              {testimonials.map((item) => (
                <div key={item.id} className="testimonial-card">
                  <div className="testimonial-stars">
                    {[...Array(item.rating || 5)].map((_, i) => (
                      <Star key={i} size={18} fill="currentColor" />
                    ))}
                  </div>
                  <blockquote className="testimonial-quote">
                    "{item.quote}"
                  </blockquote>
                  <div className="testimonial-author">
                    <div className="testimonial-avatar">
                      {item.client_name?.charAt(0)}
                    </div>
                    <div>
                      <p className="testimonial-name">{item.client_name}</p>
                      <p className="testimonial-title">
                        {item.client_title}
                        {item.client_company && ` @ ${item.client_company}`}
                      </p>
                    </div>
                  </div>
                </div>
              ))}
            </div>
          </div>
        </section>
      )}

      {/* CTA Section */}
      <section className="section">
        <div className="container">
          <div className="text-center">
            <h2 className="section-title">Klaar om te Beginnen?</h2>
            <p className="section-subtitle mb-8">
              Neem contact met ons op voor een vrijblijvend gesprek over jouw project.
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

export default HomePage;
