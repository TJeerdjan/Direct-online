import React, { useEffect } from "react";
import "@/App.css";
import { BrowserRouter, Routes, Route, useLocation, Navigate } from "react-router-dom";
import Header from "@/components/Header";
import Footer from "@/components/Footer";
import HomePage from "@/components/HomePage";
import PortfolioPage from "@/components/PortfolioPage";
import OverMijPage from "@/components/OverMijPage";
import ContactPage from "@/components/ContactPage";
import { Toaster } from "@/components/ui/toaster";

const ScrollToTop = () => {
  const { pathname } = useLocation();
  useEffect(() => {
    window.scrollTo(0, 0);
  }, [pathname]);
  return null;
};

// Admin redirect component - redirects to Direct-Online dashboard
const AdminRedirect = () => {
  useEffect(() => {
    // Redirect to Direct-Online dashboard
    window.location.href = "https://agency-dashboard-61.preview.emergentagent.com";
  }, []);
  
  return (
    <div className="min-h-screen bg-[#0A0A0A] flex items-center justify-center">
      <div className="text-center text-white">
        <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-teal-500 mx-auto mb-4"></div>
        <p className="text-xl mb-2">Doorsturen naar Direct-Online Dashboard...</p>
        <p className="text-gray-400">Je wordt automatisch doorgestuurd</p>
        <a 
          href="https://agency-dashboard-61.preview.emergentagent.com" 
          className="text-teal-500 hover:underline mt-4 inline-block"
        >
          Klik hier als je niet wordt doorgestuurd
        </a>
      </div>
    </div>
  );
};

function App() {
  return (
    <div className="App min-h-screen bg-[#0A0A0A]">
      <BrowserRouter>
        <ScrollToTop />
        <Header />
        <main>
          <Routes>
            <Route path="/" element={<HomePage />} />
            <Route path="/portfolio" element={<PortfolioPage />} />
            <Route path="/over-mij" element={<OverMijPage />} />
            <Route path="/contact" element={<ContactPage />} />
            {/* Admin now redirects to Direct-Online dashboard */}
            <Route path="/admin" element={<AdminRedirect />} />
            <Route path="/admin/*" element={<AdminRedirect />} />
          </Routes>
        </main>
        <Footer />
        <Toaster />
      </BrowserRouter>
    </div>
  );
}

export default App;
