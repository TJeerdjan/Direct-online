// ================================================================
// HEADER COMPONENT
// ================================================================

import React, { useState } from "react";
import { Link, useLocation } from "react-router-dom";
import { useSite } from "../App";
import { Menu, X } from "lucide-react";

const Header = () => {
  const { settings } = useSite();
  const location = useLocation();
  const [mobileMenuOpen, setMobileMenuOpen] = useState(false);

  const navLinks = [
    { path: "/", label: "Home" },
    { path: "/portfolio", label: "Portfolio" },
    { path: "/over-ons", label: "Over Ons" },
    { path: "/contact", label: "Contact" },
  ];

  const isActive = (path) => {
    if (path === "/") return location.pathname === "/";
    return location.pathname.startsWith(path);
  };

  return (
    <header className="header">
      <div className="header-inner">
        <Link to="/" className="logo">
          {settings.logo ? (
            <img src={settings.logo} alt={settings.site_name} />
          ) : (
            <span>{settings.site_name}</span>
          )}
        </Link>

        <nav className={`nav ${mobileMenuOpen ? "open" : ""}`}>
          {navLinks.map((link) => (
            <Link
              key={link.path}
              to={link.path}
              className={`nav-link ${isActive(link.path) ? "active" : ""}`}
              onClick={() => setMobileMenuOpen(false)}
            >
              {link.label}
            </Link>
          ))}
        </nav>

        <button
          className="mobile-menu-btn"
          onClick={() => setMobileMenuOpen(!mobileMenuOpen)}
          aria-label="Toggle menu"
        >
          {mobileMenuOpen ? <X size={24} /> : <Menu size={24} />}
        </button>
      </div>
    </header>
  );
};

export default Header;
