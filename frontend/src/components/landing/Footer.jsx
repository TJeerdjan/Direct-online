import React from 'react';
import { footerData } from '../../data/mock';

const LOGO_URL = "https://customer-assets.emergentagent.com/job_quick-website-pro/artifacts/9zb1lmkl_71512929539.png";

const Footer = () => {
  return (
    <footer className="bg-slate-950 text-white py-8 sm:py-12">
      <div className="container mx-auto px-4 sm:px-6 lg:px-12">
        <div className="flex flex-col sm:flex-row items-center justify-between gap-6">
          {/* Logo & tagline */}
          <div className="flex flex-col sm:flex-row items-center gap-3 sm:gap-4 text-center sm:text-left">
            <div className="bg-white rounded-lg p-1.5">
              <img 
                src={LOGO_URL} 
                alt="direct-online" 
                className="h-8 sm:h-10 w-auto"
              />
            </div>
            <div>
              <span className="text-base sm:text-lg font-bold">{footerData.companyName}</span>
              <p className="text-xs sm:text-sm text-slate-400">{footerData.tagline}</p>
            </div>
          </div>
          
          {/* Links */}
          <div className="flex items-center gap-4 sm:gap-6">
            {footerData.links.map((link, index) => (
              <a 
                key={index} 
                href={link.href}
                className="text-slate-400 hover:text-white transition-colors text-xs sm:text-sm"
              >
                {link.label}
              </a>
            ))}
          </div>
        </div>
        
        <div className="border-t border-slate-800 mt-6 sm:mt-8 pt-6 sm:pt-8 text-center">
          <p className="text-slate-500 text-xs sm:text-sm">
            © {new Date().getFullYear()} {footerData.companyName}. Alle rechten voorbehouden.
          </p>
        </div>
      </div>
    </footer>
  );
};

export default Footer;
