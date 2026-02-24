// ================================================================
// PORTFOLIO DETAIL PAGE COMPONENT
// ================================================================

import React, { useEffect, useState } from "react";
import { useParams, Link } from "react-router-dom";
import { ArrowLeft } from "lucide-react";

const API_URL = process.env.REACT_APP_BACKEND_URL || "";

const PortfolioDetail = () => {
  const { slug } = useParams();
  const [item, setItem] = useState(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  useEffect(() => {
    const fetchItem = async () => {
      try {
        const response = await fetch(`${API_URL}/api/portfolio/${slug}`);
        if (!response.ok) {
          throw new Error("Project niet gevonden");
        }
        setItem(await response.json());
      } catch (err) {
        setError(err.message);
      }
      setLoading(false);
    };
    
    fetchItem();
  }, [slug]);

  if (loading) {
    return (
      <div className="section" style={{ paddingTop: '8rem' }}>
        <div className="container text-center">
          <div className="spinner" style={{ margin: '0 auto' }} />
        </div>
      </div>
    );
  }

  if (error || !item) {
    return (
      <div className="section" style={{ paddingTop: '8rem' }}>
        <div className="container text-center">
          <h1 className="section-title">Project Niet Gevonden</h1>
          <p className="text-muted mb-8">{error}</p>
          <Link to="/portfolio" className="btn btn-primary">
            <ArrowLeft size={18} /> Terug naar Portfolio
          </Link>
        </div>
      </div>
    );
  }

  return (
    <div className="page-enter-active" style={{ paddingTop: '6rem' }}>
      <section className="section">
        <div className="container" style={{ maxWidth: '900px' }}>
          {/* Back Button */}
          <Link 
            to="/portfolio" 
            className="btn btn-secondary mb-8"
            style={{ display: 'inline-flex' }}
          >
            <ArrowLeft size={18} /> Terug naar Portfolio
          </Link>

          {/* Main Image */}
          <div style={{ 
            borderRadius: 'var(--radius-lg)', 
            overflow: 'hidden',
            marginBottom: '2rem'
          }}>
            <img 
              src={item.image} 
              alt={item.title}
              style={{ width: '100%', display: 'block' }}
            />
          </div>

          {/* Content */}
          <div>
            <div style={{ marginBottom: '1rem' }}>
              <span className="text-primary" style={{ fontSize: '0.875rem', fontWeight: '500' }}>
                {item.category}
              </span>
            </div>
            
            <h1 style={{ fontSize: '2.5rem', fontWeight: '700', marginBottom: '1rem' }}>
              {item.title}
            </h1>
            
            {item.client && (
              <p className="text-muted" style={{ marginBottom: '2rem' }}>
                Klant: {item.client} {item.year && `• ${item.year}`}
              </p>
            )}
            
            <div style={{ 
              fontSize: '1.125rem', 
              lineHeight: '1.8',
              color: 'var(--color-text-muted)' 
            }}>
              <p>{item.description}</p>
            </div>

            {/* Tags */}
            {item.tags && item.tags.length > 0 && (
              <div style={{ marginTop: '2rem', display: 'flex', gap: '0.5rem', flexWrap: 'wrap' }}>
                {item.tags.map((tag) => (
                  <span
                    key={tag}
                    style={{
                      fontSize: '0.875rem',
                      padding: '0.5rem 1rem',
                      background: 'var(--color-surface)',
                      border: '1px solid var(--color-border)',
                      borderRadius: 'var(--radius-full)',
                      color: 'var(--color-text-muted)',
                    }}
                  >
                    {tag}
                  </span>
                ))}
              </div>
            )}

            {/* Additional Images */}
            {item.images && item.images.length > 1 && (
              <div style={{ marginTop: '3rem' }}>
                <h3 style={{ marginBottom: '1rem' }}>Meer Afbeeldingen</h3>
                <div style={{ 
                  display: 'grid', 
                  gridTemplateColumns: 'repeat(auto-fill, minmax(250px, 1fr))',
                  gap: '1rem'
                }}>
                  {item.images.slice(1).map((img, index) => (
                    <div key={index} style={{ 
                      borderRadius: 'var(--radius-md)', 
                      overflow: 'hidden' 
                    }}>
                      <img 
                        src={img} 
                        alt={`${item.title} ${index + 2}`}
                        style={{ width: '100%', display: 'block' }}
                      />
                    </div>
                  ))}
                </div>
              </div>
            )}
          </div>

          {/* CTA */}
          <div style={{ 
            marginTop: '4rem', 
            padding: '2rem',
            background: 'var(--color-surface)',
            borderRadius: 'var(--radius-lg)',
            textAlign: 'center'
          }}>
            <h3 style={{ marginBottom: '0.5rem' }}>Interesse in een vergelijkbaar project?</h3>
            <p className="text-muted mb-4">Neem contact met ons op voor een vrijblijvend gesprek.</p>
            <Link to="/contact" className="btn btn-primary">
              Neem Contact Op
            </Link>
          </div>
        </div>
      </section>
    </div>
  );
};

export default PortfolioDetail;
