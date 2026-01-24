import React from 'react';
import { Zap, ArrowRight } from 'lucide-react';
import { Button } from '../ui/button';
import { CALENDLY_URL } from '../../data/mock';

const Header = () => {
  const handleCTAClick = () => {
    window.open(CALENDLY_URL, '_blank');
  };

  return (
    <header className="fixed top-0 left-0 right-0 z-50 bg-white/80 backdrop-blur-lg border-b border-slate-200/50">
      <div className="container mx-auto px-6 lg:px-12">
        <div className="flex items-center justify-between h-20">
          {/* Logo */}
          <div className="flex items-center gap-2">
            <div className="w-10 h-10 bg-teal-500 rounded-xl flex items-center justify-center">
              <Zap className="w-6 h-6 text-white" />
            </div>
            <span className="text-xl font-bold text-slate-900">direct-online</span>
          </div>
          
          {/* CTA */}
          <Button 
            onClick={handleCTAClick}
            className="bg-amber-500 hover:bg-amber-600 text-white font-semibold px-6 py-2 rounded-lg transition-all duration-300 hover:scale-105 group"
          >
            Plan afspraak
            <ArrowRight className="ml-2 w-4 h-4 transition-transform group-hover:translate-x-1" />
          </Button>
        </div>
      </div>
    </header>
  );
};

export default Header;
