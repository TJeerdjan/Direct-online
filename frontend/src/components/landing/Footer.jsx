import React from 'react';
import { Zap } from 'lucide-react';
import { footerData } from '../../data/mock';

const Footer = () => {
  return (
    <footer className="bg-slate-950 text-white py-12">
      <div className="container mx-auto px-6 lg:px-12">
        <div className="flex flex-col md:flex-row items-center justify-between gap-6">
          {/* Logo & tagline */}
          <div className="flex items-center gap-4">
            <div className="w-10 h-10 bg-teal-500 rounded-xl flex items-center justify-center">
              <Zap className="w-6 h-6 text-white" />
            </div>
            <div>
              <span className="text-lg font-bold">{footerData.companyName}</span>
              <p className="text-sm text-slate-400">{footerData.tagline}</p>
            </div>
          </div>
          
          {/* Links */}
          <div className="flex items-center gap-6">
            {footerData.links.map((link, index) => (
              <a 
                key={index} 
                href={link.href}
                className="text-slate-400 hover:text-white transition-colors text-sm"
              >
                {link.label}
              </a>
            ))}
          </div>
        </div>
        
        <div className="border-t border-slate-800 mt-8 pt-8 text-center">
          <p className="text-slate-500 text-sm">
            © {new Date().getFullYear()} {footerData.companyName}. Alle rechten voorbehouden.
          </p>
        </div>
      </div>
    </footer>
  );
};

export default Footer;
