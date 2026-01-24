import React from 'react';
import { Button } from '../ui/button';
import { Badge } from '../ui/badge';
import { ArrowRight, CheckCircle2, Zap } from 'lucide-react';
import { heroData, CALENDLY_URL } from '../../data/mock';

const Hero = () => {
  const handleCTAClick = () => {
    window.open(CALENDLY_URL, '_blank');
  };

  return (
    <section className="relative min-h-[90vh] flex items-center overflow-hidden bg-slate-50">
      {/* Subtle background pattern */}
      <div className="absolute inset-0 bg-[radial-gradient(ellipse_at_top_right,_var(--tw-gradient-stops))] from-amber-50 via-transparent to-transparent opacity-60" />
      
      {/* Geometric accent */}
      <div className="absolute top-20 right-10 w-72 h-72 bg-amber-400/10 rounded-full blur-3xl" />
      <div className="absolute bottom-20 left-10 w-96 h-96 bg-teal-400/10 rounded-full blur-3xl" />
      
      <div className="container mx-auto px-6 lg:px-12 relative z-10">
        <div className="max-w-4xl">
          {/* Urgency badge */}
          <div className="inline-flex items-center gap-2 bg-amber-100 text-amber-800 px-4 py-2 rounded-full mb-8 animate-pulse">
            <Zap className="w-4 h-4" />
            <span className="text-sm font-semibold">Launch Actie — Beperkte Plekken</span>
          </div>
          
          {/* Main headline */}
          <h1 className="text-4xl md:text-5xl lg:text-6xl font-bold text-slate-900 leading-tight mb-6">
            {heroData.headline}
            <span className="block text-teal-600 mt-2">— voor een prijs die absurd lijkt.</span>
          </h1>
          
          {/* Subheadline */}
          <p className="text-xl md:text-2xl text-slate-600 mb-8 max-w-2xl">
            {heroData.subheadline}
          </p>
          
          {/* Price comparison */}
          <div className="flex items-center gap-4 mb-10">
            <span className="text-2xl text-slate-400 line-through">{heroData.priceOld}</span>
            <span className="text-5xl md:text-6xl font-black text-teal-600">{heroData.priceNew}</span>
            <span className="text-slate-500">eenmalig</span>
          </div>
          
          {/* CTA Button */}
          <Button 
            onClick={handleCTAClick}
            size="lg" 
            className="bg-amber-500 hover:bg-amber-600 text-white text-lg px-10 py-7 rounded-xl shadow-lg shadow-amber-500/30 transition-all duration-300 hover:scale-105 hover:shadow-xl hover:shadow-amber-500/40 group"
          >
            {heroData.ctaText}
            <ArrowRight className="ml-2 w-5 h-5 transition-transform group-hover:translate-x-1" />
          </Button>
          
          {/* Trust badges */}
          <div className="flex flex-wrap gap-3 mt-8">
            {heroData.badges.map((badge, index) => (
              <div key={index} className="flex items-center gap-2 text-slate-600">
                <CheckCircle2 className="w-5 h-5 text-teal-500" />
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
