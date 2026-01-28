import React from 'react';
import { Button } from '../ui/button';
import { ArrowRight, CheckCircle2, Zap } from 'lucide-react';
import { heroData, CALENDLY_URL } from '../../data/mock';

// Blue geometric shape component
const BlueGeometric = () => (
  <svg 
    viewBox="0 0 400 400" 
    className="w-full h-full"
    fill="none"
    xmlns="http://www.w3.org/2000/svg"
  >
    {/* Main dark blue triangle */}
    <polygon 
      points="0,0 350,0 350,280 0,350" 
      fill="#2563eb"
      opacity="0.9"
    />
    {/* Medium blue overlay */}
    <polygon 
      points="0,50 300,0 380,200 50,350" 
      fill="#3b82f6"
      opacity="0.7"
    />
    {/* Light blue accent strip */}
    <polygon 
      points="0,300 400,180 400,250 0,380" 
      fill="#60a5fa"
      opacity="0.8"
    />
    {/* Darker accent */}
    <polygon 
      points="50,0 200,0 300,150 100,200" 
      fill="#1d4ed8"
      opacity="0.5"
    />
  </svg>
);

const Hero = () => {
  const handleCTAClick = () => {
    window.open(CALENDLY_URL, '_blank');
  };

  return (
    <section className="relative min-h-[85vh] sm:min-h-[90vh] flex items-center overflow-hidden bg-slate-50">
      {/* Blue geometric shape - top left */}
      <div className="absolute -top-10 -left-10 w-[300px] sm:w-[400px] md:w-[500px] lg:w-[600px] h-[300px] sm:h-[400px] md:h-[500px] lg:h-[600px] opacity-90 pointer-events-none">
        <BlueGeometric />
      </div>
      
      {/* Subtle background pattern */}
      <div className="absolute inset-0 bg-[radial-gradient(ellipse_at_top_right,_var(--tw-gradient-stops))] from-amber-50 via-transparent to-transparent opacity-60" />
      
      {/* Geometric accent - hidden on mobile for cleaner look */}
      <div className="hidden sm:block absolute top-20 right-10 w-48 md:w-72 h-48 md:h-72 bg-amber-400/10 rounded-full blur-3xl" />
      <div className="hidden sm:block absolute bottom-20 left-10 w-64 md:w-96 h-64 md:h-96 bg-teal-400/10 rounded-full blur-3xl" />
      
      <div className="container mx-auto px-4 sm:px-6 lg:px-12 relative z-10 py-8 sm:py-0">
        <div className="max-w-4xl">
          {/* Urgency badge */}
          <div className="inline-flex items-center gap-2 bg-amber-100 text-amber-800 px-3 sm:px-4 py-1.5 sm:py-2 rounded-full mb-6 sm:mb-8 animate-pulse">
            <Zap className="w-3.5 h-3.5 sm:w-4 sm:h-4" />
            <span className="text-xs sm:text-sm font-semibold">Launch Actie — Beperkte Plekken</span>
          </div>
          
          {/* Main headline */}
          <h1 className="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold text-slate-900 leading-tight mb-2 sm:mb-3">
            {heroData.headline}
          </h1>
          
          {/* Subtitle */}
          <p className="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-semibold text-slate-700 mb-2 sm:mb-3">
            {heroData.subtitle}
          </p>
          
          {/* Accent line */}
          <p className="text-2xl sm:text-3xl md:text-4xl lg:text-5xl text-teal-600 font-semibold mb-4 sm:mb-6">
            — voor een prijs die absurd lijkt.
          </p>
          
          {/* Subheadline */}
          <p className="text-lg sm:text-xl md:text-2xl text-slate-600 mb-6 sm:mb-8 max-w-2xl">
            {heroData.subheadline}
          </p>
          
          {/* Price comparison */}
          <div className="flex flex-wrap items-center gap-2 sm:gap-4 mb-8 sm:mb-10">
            <span className="text-xl sm:text-2xl text-slate-400 line-through">{heroData.priceOld}</span>
            <span className="text-4xl sm:text-5xl md:text-6xl font-black text-teal-600">{heroData.priceNew}</span>
            <span className="text-slate-500 text-sm sm:text-base">eenmalig</span>
          </div>
          
          {/* CTA Button */}
          <Button 
            onClick={handleCTAClick}
            size="lg" 
            className="w-full sm:w-auto bg-amber-500 hover:bg-amber-600 text-white text-base sm:text-lg px-8 sm:px-10 py-6 sm:py-7 rounded-xl shadow-lg shadow-amber-500/30 transition-all duration-300 hover:scale-105 hover:shadow-xl hover:shadow-amber-500/40 group"
          >
            {heroData.ctaText}
            <ArrowRight className="ml-2 w-5 h-5 transition-transform group-hover:translate-x-1" />
          </Button>
          
          {/* Trust badges */}
          <div className="flex flex-col sm:flex-row flex-wrap gap-2 sm:gap-3 mt-6 sm:mt-8">
            {heroData.badges.map((badge, index) => (
              <div key={index} className="flex items-center gap-2 text-slate-600">
                <CheckCircle2 className="w-4 h-4 sm:w-5 sm:h-5 text-teal-500 flex-shrink-0" />
                <span className="text-sm font-medium">{badge}</span>
              </div>
            ))}
          </div>
        </div>
      </div>
    </section>
  );
};

export default Hero;
