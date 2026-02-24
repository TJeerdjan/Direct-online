// ================================================================
// FOOTER COMPONENT
// ================================================================

import React from "react";
import { Link } from "react-router-dom";
import { useSite } from "../App";
import { Instagram, Linkedin, Facebook, Twitter } from "lucide-react";

const Footer = () => {
  const { settings } = useSite();
  const currentYear = new Date().getFullYear();

  const socialIcons = {
    instagram: Instagram,
    linkedin: Linkedin,
    facebook: Facebook,
    twitter: Twitter,
  };

  return (
    <footer className="footer">
      <div className="container">
        <div className="footer-inner">
          <p className="footer-copyright">
            © {currentYear} {settings.site_name}. Alle rechten voorbehouden.
          </p>

          <div className="footer-links">
            <Link to="/portfolio" className="footer-link">Portfolio</Link>
            <Link to="/over-ons" className="footer-link">Over Ons</Link>
            <Link to="/contact" className="footer-link">Contact</Link>
          </div>

          {settings.social && Object.keys(settings.social).length > 0 && (
            <div className="social-links">
              {Object.entries(settings.social).map(([platform, url]) => {
                if (!url) return null;
                const Icon = socialIcons[platform];
                if (!Icon) return null;
                
                return (
                  <a
                    key={platform}
                    href={url}
                    target="_blank"
                    rel="noopener noreferrer"
                    className="social-link"
                    aria-label={platform}
                  >
                    <Icon size={18} />
                  </a>
                );
              })}
            </div>
          )}
        </div>
        
        {/* Powered by Direct-Online */}
        <div className="text-center mt-8">
          <p className="text-muted" style={{ fontSize: '0.75rem' }}>
            Website beheerd via{" "}
            <a 
              href="https://direct-online.nl" 
              target="_blank" 
              rel="noopener noreferrer"
              style={{ color: 'var(--color-primary)' }}
            >
              Direct-Online
            </a>
          </p>
        </div>
      </div>
    </footer>
  );
};

export default Footer;
