// ================================================================
// DIRECT-ONLINE CLIENT WEBSITE TEMPLATE - FRONTEND
// ================================================================
//
// Main App component with routing and layout
// Customize: colors, fonts, and component imports
//
// ================================================================

import React, { useEffect, useState, createContext, useContext } from "react";
import { BrowserRouter, Routes, Route, useLocation } from "react-router-dom";
import "./App.css";

// Import components
import Header from "./components/Header";
import Footer from "./components/Footer";
import HomePage from "./components/HomePage";
import PortfolioPage from "./components/PortfolioPage";
import PortfolioDetail from "./components/PortfolioDetail";
import AboutPage from "./components/AboutPage";
import ContactPage from "./components/ContactPage";
import AdminRedirect from "./components/AdminRedirect";

// ================================================================
// SITE CONTEXT - Provides settings to all components
// ================================================================

const SiteContext = createContext(null);

export const useSite = () => useContext(SiteContext);

const SiteProvider = ({ children }) => {
  const [settings, setSettings] = useState({
    site_name: "Loading...",
    tagline: "",
    logo: "",
    colors: { primary: "#14b8a6", accent: "#f59d0e" },
    social: {}
  });
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    const fetchSettings = async () => {
      try {
        const API_URL = process.env.REACT_APP_BACKEND_URL || "";
        const response = await fetch(`${API_URL}/api/settings`);
        if (response.ok) {
          const data = await response.json();
          setSettings(data);
          // Apply primary color as CSS variable
          document.documentElement.style.setProperty('--color-primary', data.colors?.primary || '#14b8a6');
          document.documentElement.style.setProperty('--color-accent', data.colors?.accent || '#f59d0e');
        }
      } catch (error) {
        console.error("Failed to fetch settings:", error);
      }
      setLoading(false);
    };
    fetchSettings();
  }, []);

  return (
    <SiteContext.Provider value={{ settings, loading }}>
      {children}
    </SiteContext.Provider>
  );
};

// ================================================================
// SCROLL TO TOP ON ROUTE CHANGE
// ================================================================

const ScrollToTop = () => {
  const { pathname } = useLocation();
  useEffect(() => {
    window.scrollTo(0, 0);
  }, [pathname]);
  return null;
};

// ================================================================
// MAIN APP
// ================================================================

function App() {
  return (
    <div className="App">
      <BrowserRouter>
        <SiteProvider>
          <ScrollToTop />
          <div className="min-h-screen flex flex-col bg-background">
            <Header />
            <main className="flex-1">
              <Routes>
                {/* Public Pages */}
                <Route path="/" element={<HomePage />} />
                <Route path="/portfolio" element={<PortfolioPage />} />
                <Route path="/portfolio/:slug" element={<PortfolioDetail />} />
                <Route path="/over-ons" element={<AboutPage />} />
                <Route path="/about" element={<AboutPage />} />
                <Route path="/contact" element={<ContactPage />} />
                
                {/* Admin - Redirects to Direct-Online Dashboard */}
                <Route path="/admin" element={<AdminRedirect />} />
                <Route path="/admin/*" element={<AdminRedirect />} />
              </Routes>
            </main>
            <Footer />
          </div>
        </SiteProvider>
      </BrowserRouter>
    </div>
  );
}

export default App;
