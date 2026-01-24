import React, { useState } from 'react';
import { Zap, ArrowRight, Menu, X } from 'lucide-react';
import { Button } from '../ui/button';
import { CALENDLY_URL } from '../../data/mock';

const Header = () => {
  const [mobileMenuOpen, setMobileMenuOpen] = useState(false);

  const handleCTAClick = () => {
    window.open(CALENDLY_URL, '_blank');
  };

  return (
    <header className="fixed top-0 left-0 right-0 z-50 bg-white/80 backdrop-blur-lg border-b border-slate-200/50">
      <div className="container mx-auto px-4 sm:px-6 lg:px-12">
        <div className="flex items-center justify-between h-16 sm:h-20">
          {/* Logo */}
          <div className="flex items-center gap-2">
            <div className="w-8 h-8 sm:w-10 sm:h-10 bg-teal-500 rounded-lg sm:rounded-xl flex items-center justify-center">
              <Zap className="w-5 h-5 sm:w-6 sm:h-6 text-white" />
            </div>
            <span className="text-lg sm:text-xl font-bold text-slate-900">direct-online</span>
          </div>
          
          {/* Desktop CTA */}
          <Button 
            onClick={handleCTAClick}
            className="hidden sm:flex bg-amber-500 hover:bg-amber-600 text-white font-semibold px-4 md:px-6 py-2 rounded-lg transition-all duration-300 hover:scale-105 group"
          >
            <span className="hidden md:inline">Plan afspraak</span>
            <span className="md:hidden">Afspraak</span>
            <ArrowRight className="ml-2 w-4 h-4 transition-transform group-hover:translate-x-1" />
          </Button>

          {/* Mobile Menu Button */}
          <button 
            className="sm:hidden p-2 text-slate-700"
            onClick={() => setMobileMenuOpen(!mobileMenuOpen)}
          >
            {mobileMenuOpen ? <X className="w-6 h-6" /> : <Menu className="w-6 h-6" />}
          </button>
        </div>

        {/* Mobile Menu */}
        {mobileMenuOpen && (
          <div className="sm:hidden pb-4 border-t border-slate-200">
            <Button 
              onClick={handleCTAClick}
              className="w-full mt-4 bg-amber-500 hover:bg-amber-600 text-white font-semibold py-3 rounded-lg"
            >
              Plan afspraak
              <ArrowRight className="ml-2 w-4 h-4" />
            </Button>
          </div>
        )}
      </div>
    </header>
  );
};

export default Header;
