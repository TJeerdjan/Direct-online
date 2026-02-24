// ================================================================
// PORTFOLIO PAGE COMPONENT
// ================================================================

import React, { useEffect, useState } from "react";
import { Link } from "react-router-dom";

const API_URL = process.env.REACT_APP_BACKEND_URL || "";

const PortfolioPage = () => {
  const [portfolio, setPortfolio] = useState([]);
  const [categories, setCategories] = useState([]);
  const [activeCategory, setActiveCategory] = useState(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    const fetchData = async () => {
      try {
        const [portfolioRes, categoriesRes] = await Promise.all([
          fetch(`${API_URL}/api/portfolio`),
          fetch(`${API_URL}/api/portfolio/categories`),
        ]);
        
        if (portfolioRes.ok) {
          setPortfolio(await portfolioRes.json());
        }
        
        if (categoriesRes.ok) {
          const data = await categoriesRes.json();
          setCategories(data.categories || []);
        }
      } catch (error) {
        console.error("Failed to fetch portfolio:", error);
      }
      setLoading(false);
    };
    
    fetchData();
  }, []);

  const filteredPortfolio = activeCategory
    ? portfolio.filter((item) => item.category === activeCategory)
    : portfolio;

  if (loading) {
    return (
      <div className="section" style={{ paddingTop: '8rem' }}>
        <div className="container text-center">
          <div className="spinner" style={{ margin: '0 auto' }} />
        </div>
      </div>
    );
  }

  return (
    <div className="page-enter-active" style={{ paddingTop: '6rem' }}>
      <section className="section">
        <div className="container">
          <div className="section-header">
            <h1 className="section-title">Portfolio</h1>
            <p className="section-subtitle">
              Ontdek onze projecten en laat je inspireren
            </p>
          </div>

          {/* Category Filter */}
          {categories.length > 0 && (
            <div className="filter-tabs">
              <button
                className={`filter-tab ${!activeCategory ? "active" : ""}`}
                onClick={() => setActiveCategory(null)}
              >
                Alles
              </button>
              {categories.map((category) => (
                <button
                  key={category}
                  className={`filter-tab ${activeCategory === category ? "active" : ""}`}
                  onClick={() => setActiveCategory(category)}
                >
                  {category}
                </button>
              ))}
            </div>
          )}

          {/* Portfolio Grid */}
          {filteredPortfolio.length === 0 ? (
            <div className="text-center text-muted">
              <p>Nog geen projecten in deze categorie.</p>
            </div>
          ) : (
            <div className="portfolio-grid">
              {filteredPortfolio.map((item) => (
                <Link to={`/portfolio/${item.slug}`} key={item.id} className="card">
                  <div className="card-image">
                    <img src={item.image} alt={item.title} />
                  </div>
                  <div className="card-content">
                    <h3 className="card-title">{item.title}</h3>
                    <p className="card-description">
                      {item.description?.slice(0, 120)}...
                    </p>
                    <div className="card-meta">
                      <span className="text-primary">{item.category}</span>
                      {item.client && <span>• {item.client}</span>}
                      {item.year && <span>• {item.year}</span>}
                    </div>
                    {item.tags && item.tags.length > 0 && (
                      <div style={{ marginTop: '0.75rem', display: 'flex', gap: '0.5rem', flexWrap: 'wrap' }}>
                        {item.tags.slice(0, 3).map((tag) => (
                          <span
                            key={tag}
                            style={{
                              fontSize: '0.7rem',
                              padding: '0.25rem 0.5rem',
                              background: 'var(--color-background)',
                              borderRadius: 'var(--radius-full)',
                              color: 'var(--color-text-muted)',
                            }}
                          >
                            {tag}
                          </span>
                        ))}
                      </div>
                    )}
                  </div>
                </Link>
              ))}
            </div>
          )}
        </div>
      </section>
    </div>
  );
};

export default PortfolioPage;
