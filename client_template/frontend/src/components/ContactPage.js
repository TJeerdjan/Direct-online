// ================================================================
// CONTACT PAGE COMPONENT
// ================================================================

import React, { useState } from "react";
import { useSite } from "../App";
import { Mail, Phone, MapPin, Send, CheckCircle } from "lucide-react";

const API_URL = process.env.REACT_APP_BACKEND_URL || "";

const ContactPage = () => {
  const { settings } = useSite();
  const [formData, setFormData] = useState({
    name: "",
    email: "",
    message: "",
  });
  const [status, setStatus] = useState({ type: "", message: "" });
  const [submitting, setSubmitting] = useState(false);

  const handleSubmit = async (e) => {
    e.preventDefault();
    setSubmitting(true);
    setStatus({ type: "", message: "" });

    try {
      const response = await fetch(`${API_URL}/api/contact`, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(formData),
      });

      if (response.ok) {
        setStatus({ 
          type: "success", 
          message: "Bedankt voor je bericht! We nemen zo snel mogelijk contact met je op." 
        });
        setFormData({ name: "", email: "", message: "" });
      } else {
        throw new Error("Failed to send");
      }
    } catch (error) {
      setStatus({ 
        type: "error", 
        message: "Er ging iets mis. Probeer het later opnieuw." 
      });
    }
    
    setSubmitting(false);
  };

  const handleChange = (e) => {
    setFormData({ ...formData, [e.target.name]: e.target.value });
  };

  return (
    <div className="page-enter-active" style={{ paddingTop: '6rem' }}>
      <section className="section">
        <div className="container">
          <div className="section-header">
            <h1 className="section-title">Contact</h1>
            <p className="section-subtitle">
              Heb je een vraag of wil je samenwerken? Neem gerust contact op!
            </p>
          </div>

          <div style={{ 
            display: 'grid', 
            gridTemplateColumns: 'repeat(auto-fit, minmax(300px, 1fr))',
            gap: '4rem',
            maxWidth: '1000px',
            margin: '0 auto'
          }}>
            {/* Contact Form */}
            <div>
              <form onSubmit={handleSubmit} className="contact-form">
                {status.message && (
                  <div style={{
                    padding: '1rem',
                    borderRadius: 'var(--radius-md)',
                    marginBottom: '1.5rem',
                    background: status.type === 'success' 
                      ? 'rgba(20, 184, 166, 0.1)' 
                      : 'rgba(239, 68, 68, 0.1)',
                    border: `1px solid ${status.type === 'success' ? 'var(--color-primary)' : '#ef4444'}`,
                    display: 'flex',
                    alignItems: 'center',
                    gap: '0.75rem'
                  }}>
                    {status.type === 'success' && <CheckCircle size={20} className="text-primary" />}
                    <span style={{ color: status.type === 'success' ? 'var(--color-primary)' : '#ef4444' }}>
                      {status.message}
                    </span>
                  </div>
                )}

                <div className="form-group">
                  <label className="form-label" htmlFor="name">Naam</label>
                  <input
                    type="text"
                    id="name"
                    name="name"
                    value={formData.name}
                    onChange={handleChange}
                    className="form-input"
                    placeholder="Je naam"
                    required
                  />
                </div>

                <div className="form-group">
                  <label className="form-label" htmlFor="email">E-mail</label>
                  <input
                    type="email"
                    id="email"
                    name="email"
                    value={formData.email}
                    onChange={handleChange}
                    className="form-input"
                    placeholder="je@email.nl"
                    required
                  />
                </div>

                <div className="form-group">
                  <label className="form-label" htmlFor="message">Bericht</label>
                  <textarea
                    id="message"
                    name="message"
                    value={formData.message}
                    onChange={handleChange}
                    className="form-textarea"
                    placeholder="Vertel ons over je project..."
                    required
                  />
                </div>

                <button 
                  type="submit" 
                  className="btn btn-primary"
                  style={{ width: '100%' }}
                  disabled={submitting}
                >
                  {submitting ? (
                    <>Verzenden...</>
                  ) : (
                    <>Verstuur Bericht <Send size={18} /></>
                  )}
                </button>
              </form>
            </div>

            {/* Contact Info */}
            <div>
              <h3 style={{ marginBottom: '1.5rem', fontSize: '1.25rem' }}>
                Contactgegevens
              </h3>
              
              <div style={{ display: 'flex', flexDirection: 'column', gap: '1.5rem' }}>
                {/* CUSTOMIZE: Add client's contact details */}
                <div style={{ display: 'flex', alignItems: 'flex-start', gap: '1rem' }}>
                  <div style={{
                    width: '3rem',
                    height: '3rem',
                    borderRadius: 'var(--radius-md)',
                    background: 'var(--color-surface)',
                    display: 'flex',
                    alignItems: 'center',
                    justifyContent: 'center'
                  }}>
                    <Mail size={20} className="text-primary" />
                  </div>
                  <div>
                    <p className="text-muted" style={{ fontSize: '0.875rem', marginBottom: '0.25rem' }}>
                      E-mail
                    </p>
                    <a href="mailto:info@example.nl" style={{ color: 'var(--color-text)', textDecoration: 'none' }}>
                      info@example.nl
                    </a>
                  </div>
                </div>

                <div style={{ display: 'flex', alignItems: 'flex-start', gap: '1rem' }}>
                  <div style={{
                    width: '3rem',
                    height: '3rem',
                    borderRadius: 'var(--radius-md)',
                    background: 'var(--color-surface)',
                    display: 'flex',
                    alignItems: 'center',
                    justifyContent: 'center'
                  }}>
                    <Phone size={20} className="text-primary" />
                  </div>
                  <div>
                    <p className="text-muted" style={{ fontSize: '0.875rem', marginBottom: '0.25rem' }}>
                      Telefoon
                    </p>
                    <a href="tel:+31612345678" style={{ color: 'var(--color-text)', textDecoration: 'none' }}>
                      +31 6 12345678
                    </a>
                  </div>
                </div>

                <div style={{ display: 'flex', alignItems: 'flex-start', gap: '1rem' }}>
                  <div style={{
                    width: '3rem',
                    height: '3rem',
                    borderRadius: 'var(--radius-md)',
                    background: 'var(--color-surface)',
                    display: 'flex',
                    alignItems: 'center',
                    justifyContent: 'center'
                  }}>
                    <MapPin size={20} className="text-primary" />
                  </div>
                  <div>
                    <p className="text-muted" style={{ fontSize: '0.875rem', marginBottom: '0.25rem' }}>
                      Locatie
                    </p>
                    <p style={{ color: 'var(--color-text)' }}>
                      Amsterdam, Nederland
                    </p>
                  </div>
                </div>
              </div>

              {/* Social Links */}
              {settings.social && Object.keys(settings.social).length > 0 && (
                <div style={{ marginTop: '2rem' }}>
                  <h4 className="text-muted" style={{ fontSize: '0.875rem', marginBottom: '1rem' }}>
                    Volg Ons
                  </h4>
                  <div className="social-links">
                    {/* Social links rendered from settings */}
                  </div>
                </div>
              )}
            </div>
          </div>
        </div>
      </section>
    </div>
  );
};

export default ContactPage;
