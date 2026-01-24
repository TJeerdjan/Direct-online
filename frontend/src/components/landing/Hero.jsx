import React from 'react';
import { Button } from '../ui/button';
import { ArrowRight, CheckCircle2, Zap } from 'lucide-react';
import { heroData, CALENDLY_URL } from '../../data/mock';

// Growth Arrow SVG Component - Full arrow with gradient
const GrowthArrow = () => (
  <svg 
    viewBox="0 0 300 300" 
    className="w-full h-full"
    fill="none"
    xmlns="http://www.w3.org/2000/svg"
  >
    <defs>
      {/* Gradient for arrow shaft */}
      <linearGradient id="shaftGradient" x1="0%" y1="100%" x2="100%" y2="0%">
        <stop offset="0%" stopColor="#14b8a6" stopOpacity="0.6" />
        <stop offset="50%" stopColor="#5eead4" stopOpacity="0.8" />
        <stop offset="100%" stopColor="#fbbf24" stopOpacity="0.9" />
      </linearGradient>
      {/* Glow filter */}
      <filter id="arrowGlow" x="-50%" y="-50%" width="200%" height="200%">
        <feGaussianBlur stdDeviation="4" result="coloredBlur"/>
        <feMerge>
          <feMergeNode in="coloredBlur"/>
          <feMergeNode in="SourceGraphic"/>
        </feMerge>
      </filter>
      {/* Drop shadow */}
      <filter id="shadow" x="-20%" y="-20%" width="140%" height="140%">
        <feDropShadow dx="2" dy="4" stdDeviation="6" floodOpacity="0.2"/>
      </filter>
    </defs>
    
    {/* Bar chart in background */}
    <rect x="40" y="220" width="35" height="50" rx="6" fill="#14b8a6" opacity="0.5" />
    <rect x="90" y="180" width="35" height="90" rx="6" fill="#14b8a6" opacity="0.6" />
    <rect x="140" y="130" width="35" height="140" rx="6" fill="#14b8a6" opacity="0.7" />
    
    {/* Main arrow shaft - thick curved path */}
    <path 
      d="M60 240 
         C80 220, 100 200, 120 180 
         C140 160, 160 140, 180 120
         C200 100, 220 80, 235 65"
      stroke="url(#shaftGradient)" 
      strokeWidth="28" 
      strokeLinecap="round"
      fill="none"
      filter="url(#shadow)"
      opacity="0.85"
    />
    
    {/* Arrow head - solid orange triangle pointing up-right */}
    <path 
      d="M220 30 L270 70 L230 90 L235 65 Z" 
      fill="#f59e0b"
      filter="url(#arrowGlow)"
    />
    
    {/* Decorative accent circles */}
    <circle cx="270" cy="45" r="8" fill="#f59e0b" opacity="0.7" />
    <circle cx="285" cy="70" r="5" fill="#14b8a6" opacity="0.6" />
    <circle cx="260" cy="25" r="4" fill="#fbbf24" opacity="0.5" />
  </svg>
);

const Hero = () => {
  const handleCTAClick = () => {
    window.open(CALENDLY_URL, '_blank');
  };

  return (
    <section className="relative min-h-[85vh] sm:min-h-[90vh] flex items-center overflow-hidden bg-slate-50">
      {/* Subtle background pattern */}
      <div className="absolute inset-0 bg-[radial-gradient(ellipse_at_top_right,_var(--tw-gradient-stops))] from-amber-50 via-transparent to-transparent opacity-60" />
      
      {/* Geometric accent - hidden on mobile for cleaner look */}
      <div className="hidden sm:block absolute top-20 right-10 w-48 md:w-72 h-48 md:h-72 bg-amber-400/10 rounded-full blur-3xl" />
      <div className="hidden sm:block absolute bottom-20 left-10 w-64 md:w-96 h-64 md:h-96 bg-teal-400/10 rounded-full blur-3xl" />
      
      {/* Growth Arrow - positioned to the right */}
      <div className="hidden lg:block absolute right-4 xl:right-16 2xl:right-24 top-1/2 -translate-y-1/2 w-72 xl:w-96 2xl:w-[450px] h-72 xl:h-96 2xl:h-[450px] opacity-80">
        <GrowthArrow />
      </div>
      
      <div className="container mx-auto px-4 sm:px-6 lg:px-12 relative z-10 py-8 sm:py-0">
        <div className="max-w-4xl">
          {/* Urgency badge */}
          <div className="inline-flex items-center gap-2 bg-amber-100 text-amber-800 px-3 sm:px-4 py-1.5 sm:py-2 rounded-full mb-6 sm:mb-8 animate-pulse">
            <Zap className="w-3.5 h-3.5 sm:w-4 sm:h-4" />
            <span className="text-xs sm:text-sm font-semibold">Launch Actie — Beperkte Plekken</span>
          </div>
          
          {/* Main headline */}
          <h1 className="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold text-slate-900 leading-tight mb-4 sm:mb-6">
            {heroData.headline}
            <span className="block text-teal-600 mt-2 text-2xl sm:text-3xl md:text-4xl lg:text-5xl">— voor een prijs die absurd lijkt.</span>
          </h1>
          
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
